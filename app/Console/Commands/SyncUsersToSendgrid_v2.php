<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Users;
use App\Services\SendgridApi;
use Illuminate\Support\Facades\Http;

class SyncUsersToSendgrid_v2 extends Command
{
    protected $signature = 'sendgrid:sync-users_v2';
    protected $description = 'Sync users to SendGrid';

    public function handle()
    {
        $api = new SendgridApi();
        $this->info("🔁 Starting SendGrid sync...");

        // Step 1: Load local emails
        $localEmails = Users::whereNotNull('email')->pluck('email')->map('strtolower')->toArray();
        $this->info("📦 Found " . count($localEmails) . " local users");

        // Step 2: Fetch existing SendGrid emails
        $this->info("📥 Fetching SendGrid contacts...");
        $allSendgridEmails = [];
        $pageToken = null;

        do {
            $url = "https://api.sendgrid.com/v3/marketing/contacts";
            if ($pageToken) {
                $url .= "?page_token={$pageToken}";
            }

            $res = Http::withToken(config('services.sendgrid.api_key'))->get($url);

            if (!$res->successful()) {
                $this->error("❌ Failed to fetch SendGrid contacts");
                return;
            }

            foreach ($res->json('result') ?? [] as $contact) {
                $email = strtolower($contact['email'] ?? '');
                if ($email) $allSendgridEmails[] = $email;
            }

            $pageToken = $res->json('next_page_token') ?? null;
        } while ($pageToken);

        $this->info("📤 Found " . count($allSendgridEmails) . " SendGrid contacts");

        // Step 3: Delete contacts that don’t exist locally
        $toDelete = array_diff($allSendgridEmails, $localEmails);
        if (count($toDelete)) {
            $this->warn("🗑 Deleting " . count($toDelete) . " stale SendGrid contacts...");

            collect($toDelete)->chunk(50)->each(function ($chunk) {
                $contactIds = collect();

                foreach ($chunk as $email) {
                    $search = Http::withToken(config('services.sendgrid.api_key'))
                        ->get("https://api.sendgrid.com/v3/marketing/contacts/search/emails", [
                            'emails' => $email
                        ]);

                    if ($search->successful()) {
                        $id = $search->json("result.{$email}.id");
                        if ($id) $contactIds->push($id);
                    }
                }

                if ($contactIds->isNotEmpty()) {
                    $delete = Http::withToken(config('services.sendgrid.api_key'))
                        ->delete("https://api.sendgrid.com/v3/marketing/contacts", [
                            'ids' => $contactIds->toArray()
                        ]);

                    if ($delete->successful()) {
                        $this->info("✅ Deleted " . $contactIds->count() . " contacts.");
                    } else {
                        $this->error("❌ Failed delete batch: " . json_encode($delete->json()));
                    }
                }
            });
        } else {
            $this->info("✅ No stale contacts to delete.");
        }

        // Step 4: Sync local users
        $this->info("📡 Syncing users to SendGrid...");

        $jobs = [];

        $allJobs = collect();

        Users::whereNotNull('email')->chunk(100, function ($usersChunk) use ($api, &$allJobs) {
            $response = $api->syncUsers($usersChunk);

            if ($response && $response->successful()) {
                $jobId = $response->json('job_id');
                echo "📬 Submitted job $jobId\n";
                $allJobs->push([
                    'id' => $jobId,
                    'emails' => $usersChunk->pluck('email')->toArray(),
                ]);
            } else {
                echo "❌ Sync failed: " . json_encode($response?->json()) . "\n";
            }

            // Check every 10 jobs
            if ($allJobs->count() % 10 === 0) {
                $this->info("⏳ Monitoring last 10 jobs...");
                $this->monitorJobs($allJobs->splice(0, 10)->all()); // Monitor and remove those 10
            }
        });

        // Catch any remaining jobs after final chunk
        if ($allJobs->isNotEmpty()) {
            $this->info("⏳ Monitoring remaining " . $allJobs->count() . " jobs...");
            $this->monitorJobs($allJobs->all());
        }


        // Step 5: Monitor jobs
        $problemEmails = [];

        foreach ($jobs as $job) {
            $jobId = $job['id'];
            $emails = $job['emails'];
            $status = null;
            $maxWait = 60; // seconds
            $elapsed = 0;

            $this->line("⏳ Waiting on job $jobId...");

            while ($elapsed < $maxWait) {
                sleep(5);
                $elapsed += 5;

                $res = Http::withToken(config('services.sendgrid.api_key'))
                    ->get("https://api.sendgrid.com/v3/marketing/contacts/imports/{$jobId}");

                if (!$res->successful()) {
                    $this->error("⚠️ Failed to check job $jobId: " . json_encode($res->json()));
                    break;
                }

                $status = $res->json('status');
                $this->line("📦 Job $jobId status: $status");

                if (in_array($status, ['completed', 'errored'])) {
                    $results = $res->json('results');
                    $erroredCount = $results['errored_count'] ?? 0;
                    $errorUrl = $results['errors_url'] ?? null;

                    if ($erroredCount > 0) {
                        $this->warn("⚠️ $erroredCount errors in job $jobId");

                        if ($errorUrl) {
                            $this->warn("🔗 Error details: $errorUrl");

                            // Optional: download and parse CSV from errorUrl
                            $problemEmails = array_merge($problemEmails, $emails);
                        }
                    } else {
                        $this->info("✅ Job $jobId completed with no errors.");
                    }
                    break;
                }
            }

            if ($elapsed >= $maxWait) {
                $this->warn("⌛ Timeout waiting for job $jobId. Please verify manually.");
                $problemEmails = array_merge($problemEmails, $emails);
            }
        }

        // Step 6: Summary
        $this->info('🎉 Sync complete.');

        if (count($problemEmails)) {
            $this->warn("🧾 Emails to check manually (" . count($problemEmails) . "):");
            foreach (array_unique($problemEmails) as $email) {
                $this->line(" - " . $email);
            }
        } else {
            $this->info("✅ All batches completed successfully.");
        }
    }

    protected function monitorJobs(array $jobs)
    {
        $problemEmails = [];

        foreach ($jobs as $job) {
            $jobId = $job['id'];
            $emails = $job['emails'];
            $status = null;
            $maxWait = 60;
            $elapsed = 0;

            echo "⏳ Waiting on job $jobId...\n";

            while ($elapsed < $maxWait) {
                sleep(5);
                $elapsed += 5;

                $res = Http::withToken(config('services.sendgrid.api_key'))
                    ->get("https://api.sendgrid.com/v3/marketing/contacts/imports/{$jobId}");

                if (!$res->successful()) {
                    echo "⚠️ Failed to check job $jobId: " . json_encode($res->json()) . "\n";
                    break;
                }

                $status = $res->json('status');
                echo "📦 Job $jobId status: $status\n";

                if (in_array($status, ['completed', 'errored'])) {
                    $results = $res->json('results');
                    $erroredCount = $results['errored_count'] ?? 0;
                    $errorUrl = $results['errors_url'] ?? null;

                    if ($erroredCount > 0) {
                        echo "⚠️ $erroredCount errors in job $jobId\n";
                        if ($errorUrl) {
                            echo "🔗 Error details: $errorUrl\n";
                            $problemEmails = array_merge($problemEmails, $emails);
                        }
                    } else {
                        echo "✅ Job $jobId completed with no errors.\n";
                    }
                    break;
                }
            }

            if ($elapsed >= $maxWait) {
                echo "⌛ Timeout waiting for job $jobId.\n";
                $problemEmails = array_merge($problemEmails, $emails);
            }
        }

        if (!empty($problemEmails)) {
            echo "🧾 Emails to check manually (" . count($problemEmails) . "):\n";
            foreach (array_unique($problemEmails) as $email) {
                echo " - $email\n";
            }
        } else {
            echo "✅ All monitored jobs finished cleanly.\n";
        }
    }
}
