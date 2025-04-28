<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Workouts;

class ExportWorkoutArticle extends Command
{
    protected $signature = 'workout:export-article {workout_id}';
    protected $description = 'Export workout to ChatGPT, generate article, and post to WordPress';

    private $chatGptKey = '***REMOVED***';
    private $wordpressUrl = 'https://dev.trainer-workout.com/blog/'; // Replace with your Wordpress URL
    private $wordpressUser = 'root';
    private $wordpressPassword = 'Cool**88';

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

        $this->info('Reading workout data and sending to ChatGPT...');

        $chatGptPrompt = "Write a detailed 500-word blog article about the following workout. Make it engaging and include reasons why someone would enjoy this workout. Title it: {$workout->name}.\nWorkout description: {$workout->description}. Also mention that a downloadable PDF is available.";

        $chatResponse = Http::withToken($this->chatGptKey)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a fitness blog writer.'],
                    ['role' => 'user', 'content' => $chatGptPrompt],
                ],
            ]);

        if (!$chatResponse->ok()) {
            $this->error('ChatGPT API error');
            return 1;
        }

        $articleContent = $chatResponse->json('choices.0.message.content');

        $this->info('Uploading PDF to WordPress...');

        $uploadPdf = Http::attach(
            'file',
            fopen($pdfPath, 'r'),
            basename($pdfPath)
        )->withBasicAuth($this->wordpressUser, $this->wordpressPassword)
            ->post($this->wordpressUrl . '/wp-json/wp/v2/media');

        if (!$uploadPdf->ok()) {
            $this->error('Failed to upload PDF to WordPress');
            return 1;
        }

        $pdfUrl = $uploadPdf->json('source_url');

        $this->info('Posting article to WordPress...');

        $fullArticle = $articleContent . "\n\n<p><a href='{$pdfUrl}' target='_blank'>Download the workout PDF</a></p>";
        $fullArticle .= "\n<p>View this workout online: <a href='https://trainer-workout.com/workout/{$workout->id}' target='_blank'>Trainer Workout Link</a></p>";

        $createPost = Http::withBasicAuth($this->wordpressUser, $this->wordpressPassword)
            ->post($this->wordpressUrl . '/wp-json/wp/v2/posts', [
                'title' => $workout->name,
                'content' => $fullArticle,
                'status' => 'publish',
            ]);

        if (!$createPost->ok()) {
            $this->error('Failed to create WordPress post');
            return 1;
        }

        $this->info('Workout exported and posted successfully!');
        return 0;
    }
}
