<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Users;
use App\Services\SendgridApi;
use Illuminate\Support\Facades\Http;

class SyncUsersToSendgrid extends Command
{
    protected $signature = 'sendgrid:sync-users';
    protected $description = 'Sync users to SendGrid';

    public function handle()
    {
        $api = new SendgridApi();
        $this->info("🔁 Starting SendGrid sync...");

        Users::whereNotNull('email')->chunk(100, function ($users, $page) use ($api) {
            $this->info("➡️ Syncing batch page $page with " . count($users) . " users");

            $retries = 0;
            $maxRetries = 3;

            do {
                $response = $api->syncUsers($users);

                if (!$response || !$response->successful()) {
                    $this->error("❌ Batch failed (attempt {$retries}): " . json_encode($response?->json()));
                    $retries++;
                    sleep(2);
                } else {
                    $jobId = $response->json('job_id');
                    $this->info("📬 Submitted job $jobId");

                    // Wait a few seconds before checking status
                    sleep(3);
                    $jobCheck = Http::withToken(config('services.sendgrid.api_key'))
                        ->get("https://api.sendgrid.com/v3/marketing/contacts/imports/{$jobId}");

                    if (!$jobCheck->successful()) {
                        $this->error("⚠️ Failed to check job $jobId: " . json_encode($jobCheck->json()));
                        break;
                    }

                    $status = $jobCheck->json('status');
                    $results = $jobCheck->json('results');
                    $errorsUrl = $jobCheck->json('results.errors_url');

                    $this->line("📦 Job $jobId status: $status");

                    if (!empty($results['errored_count'])) {
                        $this->warn("⚠️ {$results['errored_count']} contacts failed in job $jobId");
                        if ($errorsUrl) {
                            $this->warn("🔗 Error details: $errorsUrl");
                        }
                    } else {
                        $this->info("✅ Job $jobId completed successfully with no errors.");
                    }

                    break;
                }
            } while ($retries < $maxRetries);
        });

        $this->info('🎉 All users processed for SendGrid sync.');
    }
}
