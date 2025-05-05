<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class SyncSendgridFields extends Command
{
    protected $signature = 'sendgrid:fetch-fields';
    protected $description = 'Fetch field definitions from SendGrid, create missing ones, and save JSON';

    public function handle()
    {
        $apiKey = config('services.sendgrid.api_key');

        // 1. Fetch current SendGrid field definitions
        $response = Http::withToken($apiKey)
            ->get('https://api.sendgrid.com/v3/marketing/field_definitions');

        if (!$response->successful()) {
            $this->error('Failed to fetch field definitions from SendGrid');
            return 1;
        }

        $fieldData = $response->json();
        $customFields = collect($fieldData['custom_fields'] ?? []);
        $reservedFields = collect($fieldData['reserved_fields'] ?? []);
        $existingFields = $customFields->pluck('name')->merge($reservedFields->pluck('name'))->toArray();

        // 2. Map local DB fields to SendGrid reserved fields
        $fieldMap = [
            'firstName'     => 'first_name',
            'lastName'      => 'last_name',
            'email'         => 'email',
            'phone'         => 'phone_number',
            'address'       => 'address_line_1',
            'suite'         => 'address_line_2',
            'city'          => 'city',
            'province'      => 'state_province_region',
            'postalCode'    => 'postal_code',
            'country'       => 'country',
            'created_at'    => 'registered',
        ];

        $excluded = [
            'password',
            'remember_token',
            'token',
            'stripeCheckoutToken',
            'typeOfCreditCard',
            'fourLastDigits'
        ];

        $columnTypes = [
            'id' => 'Number',
            'firstName' => 'Text',
            'lastName' => 'Text',
            'email' => 'Text',
            'address' => 'Text',
            'phone' => 'Text',
            'street' => 'Text',
            'suite' => 'Text',
            'city' => 'Text',
            'province' => 'Text',
            'country' => 'Text',
            'userType' => 'Text',
            'password' => 'Text',
            'fbUsername' => 'Text',
            'appInstalled' => 'Number',
            'demoApp' => 'Number',
            'created_at' => 'Date',
            'deleted_at' => 'Date',
            'updated_at' => 'Date',
            'timezone' => 'Text',
            'remember_token' => 'Text',
            'thumb' => 'Text',
            'image' => 'Text',
            'birthday' => 'Date',
            'biography' => 'Text',
            'certifications' => 'Text',
            'specialities' => 'Text',
            'past_experience' => 'Text',
            'word' => 'Text',
            'videoLink' => 'Text',
            'videoKey' => 'Text',
            'demoWeb' => 'Date',
            'admin' => 'Number',
            'stripeCheckoutToken' => 'Text',
            'typeOfCreditCard' => 'Text',
            'fourLastDigits' => 'Text',
            'gender' => 'Text',
            'lastLoginApp' => 'Date',
            'lastLogin' => 'Date',
            'referral' => 'Text',
            'postalCode' => 'Text',
            'marketing' => 'Text',
            'activated' => 'Date',
            'token' => 'Text',
            'lang' => 'Text',
            'virtual' => 'Number',
            'new_email' => 'Text',
            'new_email_token' => 'Text',
        ];


        // 3. Load DB columns with correct casing
        $actualColumns = array_flip(Schema::getColumnListing('users'));

        foreach ($columnTypes as $column => $sendgridType) {
            if (in_array($column, $excluded)) {
                $this->line("⏭️ Skipping excluded field '$column'");
                continue;
            }

            $sendgridField = $fieldMap[$column] ?? $column;

            if (in_array($sendgridField, $existingFields)) {
                $this->line("✔️  Field '$sendgridField' already exists");
                continue;
            }

            $createResp = Http::withToken($apiKey)->post(
                'https://api.sendgrid.com/v3/marketing/field_definitions',
                ['name' => $sendgridField, 'field_type' => $sendgridType]
            );

            if ($createResp->successful()) {
                $this->info("➕ Created field '$sendgridField' as type $sendgridType");
            } else {
                $this->error("❌ Failed to create field '$sendgridField': " . json_encode($createResp->json()));
            }
        }


        // 4. Save updated field list
        $finalResp = Http::withToken($apiKey)
            ->get('https://api.sendgrid.com/v3/marketing/field_definitions');
        $this->line('🧪 Final SendGrid response: ' . substr($finalResp->body(), 0, 300));


        if ($finalResp->successful()) {
            $data = $finalResp->json();

            if ($data && is_array($data)) {
                Storage::put('sendgrid_fields.json', json_encode($data, JSON_PRETTY_PRINT));
                $this->info('✅ Final field definitions saved to storage/sendgrid_fields.json');
            } else {
                $this->error('❌ Response from SendGrid was empty or invalid.');
            }
            $this->info('✅ Final field definitions saved to storage/sendgrid_fields.json');
        } else {
            $this->warn('⚠️ Could not re-fetch updated field list.');
        }

        return 0;
    }
}
