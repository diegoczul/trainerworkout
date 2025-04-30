<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log; // <-- important to log to storage/logs/laravel.log

class SendSlackNotification
{
    public function handle($eventName, $user, ...$params)
    {
        // 🛠️ DEBUG: Log raw inputs
        // Log::info('🔵 Event Name:', [$eventName]);
        // Log::info('🟢 User:', is_object($user) ? $user->toArray() : [$user]);
        // Log::info('🟡 Params:', $params);

        // Correct way to get email for your Users model
        if (is_object($user) && !empty($user->email)) {
            $userEmail = $user->email;
        } else {
            $userEmail = 'unknown@example.com';
        }



        $paramText = json_encode($params);

        $text = "[{$userEmail}] - {$eventName} {$paramText}";

        Http::post("***REMOVED***", [
            'text' => $text,
        ]);
    }
}
