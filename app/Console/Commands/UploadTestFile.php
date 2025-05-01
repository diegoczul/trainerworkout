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
        $pdfPath = public_path('temp/sideabs_grid.pdf');
        $wordpressUrl = 'https://trainer-workout.com/blog/wp-json/wp/v2/media';
        $wordpressUser = 'root';
        $wordpressPassword = '***REMOVED***';

        if (!file_exists($pdfPath)) {
            $this->error('File not found: ' . $pdfPath);
            return 1;
        }

        $this->info('Uploading PDF to WordPress...');

        $response = Http::withBasicAuth($wordpressUser, $wordpressPassword)
            ->attach(
                'file',
                fopen($pdfPath, 'r'),
                basename($pdfPath)
            )
            ->withHeaders([
                'Content-Disposition' => 'attachment; filename="' . basename($pdfPath) . '"',
                'Content-Type' => 'application/pdf'
            ])
            ->post($wordpressUrl);
        $this->line('Raw JSON: ' . $response->body());


        if (!$response->successful()) {
            $this->error('Upload failed. Response:');
            $this->line($response->body());
            return 1;
        }

        $this->info('✅ Upload succeeded!');
        $this->info('📎 PDF URL: ' . $response->json('source_url'));

        return 0;
    }
}
