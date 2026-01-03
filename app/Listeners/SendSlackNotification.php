<?php

namespace App\Listeners;

use Illuminate\Http\Request;
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
        $requestIP = $this->getClientIp(request()) ?? request()->getClientIp();
        if (env('APP_ENV') != 'production') { // Will send a yellow circle in Slack if not in production
            $text = "[{$userEmail}]($requestIP) - {$eventName} {$paramText}";
        } else {
            $text = "[{$userEmail}]($requestIP) - [:wrench:] {$eventName} {$paramText}";
        }

        Http::post(config('services.slack.webhook'), [
            'text' => $text,
        ]);
    }

    function getClientIp(Request $request)
    {
        $ipAddress = $request->header('X-Forwarded-For');

        if ($ipAddress) {
            // In case of multiple IPs, take the first one
            $ipAddress = explode(',', $ipAddress)[0];
        } else {
            $ipAddress = $request->ip(); // fallback to REMOTE_ADDR
        }

        return filter_var(trim($ipAddress), FILTER_VALIDATE_IP) ?: null;
    }
}
