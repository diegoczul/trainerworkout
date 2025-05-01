<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Workouts;

class ExportWorkoutArticle extends Command
{
    protected $signature = 'workout:export-article {workout_id}';
    protected $description = 'Export workout to ChatGPT, generate article, and post to WordPress';


    // ✅ Hardcoded keys as you asked (real ones)
    private $chatGptKey = '***REMOVED***';
    private $wordpressUrl = 'https://trainer-workout.com/blog';
    private $wordpressUsername = 'root';
    private $wordpressAppPassword = '***REMOVED***'; // your APP password directly

    public function handle()
    {
        $workoutId = $this->argument('workout_id');
        $workout = Workouts::find($workoutId);

        if (!$workout) {
            $this->error('Workout not found');
            return 1;
        }

        $this->info('Generating PDF...');
        $pdfPath = $workout->getPrintPDF();
        if (!file_exists($pdfPath)) {
            $this->error('PDF not found: ' . $pdfPath);
            return 1;
        }

        $this->info('Generating workout screenshot...');
        $imagePath = $workout->getImageScreenshot();
        if (!file_exists($imagePath)) {
            $this->error('Image screenshot not found: ' . $imagePath);
            return 1;
        }

        $this->info('Sending workout to ChatGPT...');
        $chatGptPrompt = <<<EOT
You are a certified personal trainer writing a blog post. Write a detailed, engaging 500-word article about the following workout. Analyze why each exercise is chosen, and how it benefits a beginner, use h2 tags for the excersises. Speak like a friendly expert. Mention the benefits for posture, strength, and fitness goals. Include reasons why someone should add this workout to their routine.

End the article with a strong call to action encouraging readers to visit Trainer-Workout.com for free workouts, download the PDF, and view the full workout online.

Workout name: {$workout->name}
Workout description: {$workout->description}
EOT;

        $chatResponse = Http::withToken($this->chatGptKey)
            ->timeout(600)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a fitness blog writer.'],
                    ['role' => 'user', 'content' => $chatGptPrompt],
                ],
            ]);

        if (!$chatResponse->ok()) {
            $this->error('ChatGPT API error: ' . $chatResponse->body());
            return 1;
        }

        $articleContent = $chatResponse->json('choices.0.message.content');

        $this->info('Uploading files to WordPress...');

        $uploadPdf = Http::withBasicAuth($this->wordpressUsername, $this->wordpressAppPassword)
            ->attach('file', fopen($pdfPath, 'r'), basename($pdfPath))
            ->post($this->wordpressUrl . '/wp-json/wp/v2/media');
        if (!$uploadPdf->successful()) {
            $this->error('❌ Failed PDF upload: ' . $uploadPdf->body());
            return 1;
        }
        $pdfUrl = $uploadPdf->json('source_url');

        $uploadImage = Http::withBasicAuth($this->wordpressUsername, $this->wordpressAppPassword)
            ->attach('file', fopen($imagePath, 'r'), basename($imagePath))
            ->post($this->wordpressUrl . '/wp-json/wp/v2/media');
        if (!$uploadImage->successful()) {
            $this->error('❌ Failed image upload: ' . $uploadImage->body());
            return 1;
        }
        $imageUrl = $uploadImage->json('source_url');

        $this->info('✅ Uploaded assets.');

        // ➡️ Build final post content
        $finalContent = <<<HTML
<p><img src="{$imageUrl}" alt="Workout Preview" style="max-width:100%; height:auto;" /></p>

{$articleContent}

<p><a href="{$pdfUrl}" target="_blank">📄 Download the full workout PDF</a></p>

<p>🔥 Want more free workouts? <a href="https://trainer-workout.com" target="_blank">Visit Trainer-Workout.com</a> to access our full library!</p>

<p>✅ Add this workout to your routine today and start your transformation!</p>
HTML;

        $this->info('Posting article to WordPress...');

        $createPost = Http::withBasicAuth($this->wordpressUsername, $this->wordpressAppPassword)
            ->post($this->wordpressUrl . '/wp-json/wp/v2/posts', [
                'title' => $workout->name,
                'content' => $finalContent,
                'status' => 'publish',
            ]);

        if ($createPost->failed()) {
            $this->error('❌ Failed to create WordPress post');
            return 1;
        }

        $postUrl = $createPost->json('link') ?? 'unknown';
        $this->info("✅ Workout article posted: $postUrl");

        return 0;
    }
}
