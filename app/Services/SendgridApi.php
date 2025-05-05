<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendgridApi
{
    protected $apiKey;
    protected $fields;

    public function __construct()
    {
        $this->apiKey = config('services.sendgrid.api_key');
        $this->loadFields();
    }

    protected function loadFields()
    {
        $this->fields = json_decode(file_get_contents(storage_path('app/sendgrid_fields.json')), true) ?? [
            'reserved_fields' => [],
            'custom_fields' => [],
        ];
    }

    public function fieldExists($name)
    {
        $custom = collect($this->fields['custom_fields'])->pluck('name')->toArray();
        $reserved = collect($this->fields['reserved_fields'])->pluck('name')->toArray();
        return in_array($name, $custom) || in_array($name, $reserved);
    }

    public function createField($name, $type = 'Text')
    {
        $response = Http::withToken($this->apiKey)
            ->post('https://api.sendgrid.com/v3/marketing/field_definitions', [
                'name' => $name,
                'field_type' => $type
            ]);

        if ($response->successful()) {
            Log::info("SendGrid: Created custom field $name");
            return true;
        }

        Log::error("SendGrid: Failed to create field $name", $response->json());
        return false;
    }

    public function syncUsers($users)
    {
        $reservedFields = array_map(fn($f) => $f['name'], $this->fields['reserved_fields'] ?? []);
        $customFields = array_map(fn($f) => $f['name'], $this->fields['custom_fields'] ?? []);
        $fieldTypeMap = collect($this->fields['custom_fields'] ?? [])
            ->mapWithKeys(fn($f) => [$f['name'] => $f['field_type']]);

        $contacts = [];

        foreach ($users as $user) {
            $contact = ['email' => $user->email];
            $userArray = $user->getAttributes();

            foreach ($userArray as $dbField => $value) {
                if ($dbField === 'birthday') continue;

                $sendgridField = $this->mapToSendgridField($dbField);
                if (!$sendgridField || ($value === null || $value === '')) continue;

                if ($value instanceof \Carbon\Carbon || in_array($sendgridField, $this->getDateFields())) {
                    try {
                        $parsed = \Carbon\Carbon::parse($value);
                        if ($parsed->year < 1000) continue;
                        $value = $parsed->toIso8601String();
                    } catch (\Exception $e) {
                        continue;
                    }
                }

                if (in_array($sendgridField, $reservedFields)) {
                    $contact[$sendgridField] = $value;
                } elseif (in_array($sendgridField, $customFields)) {
                    $type = $fieldTypeMap[$sendgridField] ?? 'Text';
                    $contact['custom_fields'][$sendgridField] = match ($type) {
                        'Number' => (float) $value,
                        default => (string) $value,
                    };
                }
            }

            $contacts[] = $contact;
        }

        if (empty($contacts)) return null;

        return Http::withToken($this->apiKey)
            ->put('https://api.sendgrid.com/v3/marketing/contacts', ['contacts' => $contacts]);
    }


    public function syncUser($user)
    {
        echo "\n🟡 [Stage 1] Loaded SendGrid fields:\n";
        // print_r($this->fields);

        // Extract names
        echo "\n🟡 [Stage 2] Extracting reserved and custom field names...\n";
        $reservedFields = array_map(fn($f) => $f['name'], $this->fields['reserved_fields'] ?? []);
        $customFields = array_map(fn($f) => $f['name'], $this->fields['custom_fields'] ?? []);
        // echo "Reserved Fields:\n";
        // print_r($reservedFields);
        // echo "Custom Fields:\n";
        // print_r($customFields);

        echo "\n🟡 [Stage 3] Preparing initial contact payload...\n";
        $contact = [
            'email' => $user->email,
        ];
        // print_r($contact);

        echo "\n🟡 [Stage 4] Mapping DB fields to SendGrid fields...\n";
        $userArray = $user->getAttributes();

        foreach ($userArray as $dbField => $value) {
            if ($dbField === 'birthday') {
                echo "⏭️ Skipping birthday field\n";
                continue;
            }
            // echo "\n🔹 Field: $dbField => ";
            // print_r($value);

            $sendgridField = $this->mapToSendgridField($dbField);
            // echo "🔸 Mapped to SendGrid field: ";
            // var_dump($sendgridField);

            if (!$sendgridField || ($value === null || $value === '')) {
                echo "⏭️ Skipping (empty or unmapped)\n";
                continue;
            }

            if ($value instanceof \Carbon\Carbon) {
                echo "📅 Converting Carbon instance to RFC3339 format...\n";
                try {
                    $parsed = \Carbon\Carbon::parse($value);
                    if ($parsed->year < 1000) {
                        echo "⚠️ Skipping invalid year for date field: $sendgridField => $value\n";
                        continue;
                    }
                    $value = $parsed->toIso8601String();
                } catch (\Exception $e) {
                    echo "⚠️ Failed to parse date: $value\n";
                    continue;
                }
            } elseif (in_array($sendgridField, $this->getDateFields())) {
                echo "📅 Forcing string date to RFC3339 format...\n";
                try {
                    $value = \Carbon\Carbon::parse($value)->toIso8601String();
                } catch (\Exception $e) {
                    echo "⚠️ Failed to parse date: $value\n";
                    continue;
                }
            }

            if (in_array($sendgridField, $reservedFields)) {
                echo "✅ Adding to reserved fields: $sendgridField => $value\n";
                $contact[$sendgridField] = $value;
            } else $fieldTypeMap = collect($this->fields['custom_fields'] ?? [])
                ->mapWithKeys(fn($f) => [$f['name'] => $f['field_type']]);

            if (in_array($sendgridField, $customFields)) {
                $type = $fieldTypeMap[$sendgridField] ?? 'Text';

                if ($type === 'Number') {
                    $value = (float) $value; // Ensure it's a number
                } elseif ($type === 'Text') {
                    $value = (string) $value;
                }

                echo "✅ Adding to custom fields: $sendgridField => ";
                var_dump($value);
                $contact['custom_fields'][$sendgridField] = $value;
            } else {
                echo "⚠️ Field $sendgridField not in reserved/custom, skipping\n";
            }
        }

        echo "\n✅ [Stage 5] Final Payload:\n";
        print_r([
            'user_id' => $user->id,
            'email' => $user->email,
            'payload' => $contact
        ]);

        Log::debug("Syncing user {$user->id} with payload", $contact);

        echo "\n🚀 [Stage 6] Sending payload to SendGrid...\n";

        $response = Http::withToken($this->apiKey)
            ->put('https://api.sendgrid.com/v3/marketing/contacts', [
                'contacts' => [$contact],
            ]);

        echo "\n📬 [Stage 7] Response:\n";
        if ($response) {
            print_r($response->json());
        } else {
            echo "❌ No response returned.\n";
        }

        exit();
        return $response;
    }


    protected function getDateFields()
    {
        return collect($this->fields['custom_fields'] ?? [])
            ->filter(fn($f) => $f['field_type'] === 'Date')
            ->pluck('name')
            ->toArray();
    }

    protected function mapToSendgridField($dbField)
    {
        $map = [
            'id' => 'id',
            'first_name' => 'first_name',
            'last_name' => 'last_name',
            'email' => 'email',
            'phone_number' => 'phone_number',
            'address' => 'address_line_1',
            'suite' => 'address_line_2',
            'city' => 'city',
            'province' => 'state_province_region',
            'postal_code' => 'postal_code',
            'country' => 'country',
            'created_at' => 'registered',
            'deleted_at' => 'deleted_at',
            'timezone' => 'timezone',
            'remember_token' => 'remember_token',
            'thumb' => 'thumb',
            'image' => 'image',
            'birthday' => 'birthday',
            'biography' => 'biography',
            'certifications' => 'certifications',
            'specialities' => 'specialities',
            'past_experience' => 'past_experience',
            'word' => 'word',
            'videoLink' => 'videoLink',
            'videoKey' => 'videoKey',
            'demoWeb' => 'demoWeb',
            'admin' => 'admin',
            'gender' => 'gender',
            'lastLoginApp' => 'lastLoginApp',
            'lastLogin' => 'lastLogin',
            'referral' => 'referral',
            'marketing' => 'marketing',
            'activated' => 'activated',
            'token' => 'token',
            'lang' => 'lang',
            'virtual' => 'virtual',
            'new_email' => 'new_email',
            'new_email_token' => 'new_email_token',
            'userType' => 'userType',
            'fbUsername' => 'fbUsername',
            'appInstalled' => 'appInstalled',
            'demoApp' => 'demoApp',
        ];

        return $map[$dbField] ?? null;
    }
}
