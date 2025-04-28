<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class UploadTestFile extends Command
{
    protected $signature = 'upload:test-file';
    protected $description = 'Upload a hardcoded test PDF to WordPress';

    public function handle()
    {
        $pdfPath = public_path('temp/chestbeginner_grid.pdf'); // <-- adjust path if needed
        $wordpressUrl = 'https://dev.trainer-workout.com/blog/wp-json/wp/v2';
        $wordpressUser = 'root'; // <-- your WP username
        $wordpressPassword = 'Cool**88'; // <-- your WP password

        if (!file_exists($pdfPath)) {
            $this->error('File not found: ' . $pdfPath);
            return 1;
        }

        $this->info('Uploading PDF directly...');

        $uploadResponse = Http::attach(
            'file',
            fopen($pdfPath, 'r'),
            basename($pdfPath)
        )
            ->withBasicAuth($wordpressUser, $wordpressPassword)
            ->post($wordpressUrl . '/media');

        if (!$uploadResponse->ok()) {
            $this->error('Failed to upload. Response:');
            $this->line($uploadResponse->body()); // <-- show exact WordPress error
            return 1;
        }

        $this->info('Uploaded successfully!');
        $this->info('PDF URL: ' . $uploadResponse->json('source_url'));

        return 0;
    }
}
