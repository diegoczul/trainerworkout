<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Workouts;

class ExportWorkoutArticle extends Command
{
    protected $signature = 'workout:export-article {workout_id?}';
    protected $description = 'Export workout to ChatGPT, generate article, and post to WordPress';



    // ✅ Hardcoded keys as you asked (real ones)
    private $chatGptKey = '***REMOVED***';
    private $wordpressUrl = 'https://trainer-workout.com/blog';
    private $wordpressUsername = 'root';
    private $wordpressAppPassword = '***REMOVED***'; // your APP password directly

    public function handle()
    {
        $workoutId = $this->argument('workout_id');

        if ($workoutId) {
            $workout = Workouts::find($workoutId);
        } else {
            $workout = Workouts::where('type', "public")
                ->whereNull('post_sent')
                ->orderBy('id', 'asc')
                ->first();
        }


        if (!$workout) {
            $this->info('No eligible workout found to post.');
            return 0;
        }

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
You are a certified personal trainer writing a blog post.

Write a detailed, engaging 500-word article about the following workout. Analyze why each exercise is chosen, how it benefits a beginner, and use h2 tags for the exercises. Speak like a friendly expert. Mention benefits for posture, strength, and fitness goals. End with a strong call to action encouraging readers to visit Trainer-Workout.com for more.

Also include at the end:
- A list of 5-7 relevant tags (keywords or exercises)
- One category that best represents the main muscle group focus (e.g., Chest, Back, Legs, Core, Full Body)

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



        preg_match('/Tags:\s*(.*)/i', $articleContent, $tagsMatch);
        preg_match('/Category:\s*(.*)/i', $articleContent, $categoryMatch);

        $tags = isset($tagsMatch[1]) ? array_map('trim', explode(',', $tagsMatch[1])) : [];
        $category = $categoryMatch[1] ?? 'General';

        // Remove tags/category section from content
        $articleContent = preg_replace('/Tags:\s*.*$/is', '', $articleContent);
        $articleContent = preg_replace('/Category:\s*.*$/is', '', $articleContent);

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
        $imageId = $uploadImage->json('id');


        $this->info('✅ Uploaded assets.');

        // ➡️ Build final post content
        $finalContent = <<<HTML
<p>
    <a href="https://trainer-workout.com/workout/{$workout->id}" target="_blank">
        <img src="{$imageUrl}" alt="Workout Preview" style="max-width:100%; height:auto;" />
    </a>
</p>

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
                'featured_media' => $imageId, // ✅ Set as featured image
            ]);
        $postId = $createPost->json('id');

        // 🔁 Create/find category
        $categoryResponse = Http::withBasicAuth($this->wordpressUsername, $this->wordpressAppPassword)
            ->get("{$this->wordpressUrl}/wp-json/wp/v2/categories", ['search' => $category]);
        $categoryId = collect($categoryResponse->json())->firstWhere('name', $category)['id'] ?? null;

        if (!$categoryId) {
            $newCat = Http::withBasicAuth($this->wordpressUsername, $this->wordpressAppPassword)
                ->post("{$this->wordpressUrl}/wp-json/wp/v2/categories", ['name' => $category]);
            $categoryId = $newCat->json('id');
        }

        // 🔁 Create/find tags
        $tagIds = [];
        foreach ($tags as $tag) {
            $tag = trim($tag);
            $existing = Http::withBasicAuth($this->wordpressUsername, $this->wordpressAppPassword)
                ->get("{$this->wordpressUrl}/wp-json/wp/v2/tags", ['search' => $tag]);
            $tagId = collect($existing->json())->firstWhere('name', $tag)['id'] ?? null;

            if (!$tagId) {
                $newTag = Http::withBasicAuth($this->wordpressUsername, $this->wordpressAppPassword)
                    ->post("{$this->wordpressUrl}/wp-json/wp/v2/tags", ['name' => $tag]);
                $tagId = $newTag->json('id');
            }

            if ($tagId) $tagIds[] = $tagId;
        }

        // 📝 Update post with category and tags
        Http::withBasicAuth($this->wordpressUsername, $this->wordpressAppPassword)
            ->post("{$this->wordpressUrl}/wp-json/wp/v2/posts/{$postId}", [
                'categories' => [$categoryId],
                'tags' => $tagIds,
            ]);


        if ($createPost->failed()) {
            $this->error('❌ Failed to create WordPress post');
            return 1;
        }

        $postUrl = $createPost->json('link') ?? 'unknown';
        $this->info("✅ Workout article posted: $postUrl");

        $workout->post_sent = now();
        $workout->save();

        return 0;
    }
}
