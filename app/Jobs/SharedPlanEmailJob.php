<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use SendGrid\Mail\Mail;
use SendGrid;
use Illuminate\Support\Facades\View;

class SharedPlanEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $sharing, $invite, $toUser, $fromUser, $comments, $copyMe;

    public function __construct($sharing, $invite, $toUser, $fromUser, $comments, $copyMe)
    {
        $this->sharing = $sharing;
        $this->invite = $invite;
        $this->toUser = $toUser;
        $this->fromUser = $fromUser;
        $this->comments = $comments;
        $this->copyMe = $copyMe;
    }

    public function handle(): void
    {
        try {
            \Log::info('📤 Sending shared plan email', [
                'to' => $this->toUser->email,
                'from' => $this->fromUser->email,
                'link' => $this->sharing->access_link,
            ]);

            $email = new \SendGrid\Mail\Mail();
            $email->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $email->setSubject("A plan has been shared with you");
            $email->addTo($this->toUser->email);

            if ($this->copyMe && $this->fromUser->email !== $this->toUser->email) {
                $email->addCc($this->fromUser->email);
            }

            $locale = $this->toUser->locale ?? app()->getLocale(); // fallback if not set
            $path = 'emails.' . config('app.whitelabel') . '.user.' . $locale . '.shared_plan';

            if (!View::exists($path)) {
                $path = 'emails.default.user.en.shared_plan'; // fallback template
            }

            $html = view($path, [
                'sharing' => $this->sharing,
                'invite' => $this->invite,
                'toUser' => $this->toUser,
                'fromUser' => $this->fromUser,
                'comments' => $this->comments,
            ])->render();


            $email->addContent("text/html", $html);

            $sendgrid = new \SendGrid(env('SENDGRID_API_KEY'));
            $response = $sendgrid->send($email);

            \Log::info('✅ SendGrid response', [
                'status' => $response->statusCode(),
                'body' => $response->body(),
                'headers' => $response->headers()
            ]);
        } catch (\Exception $e) {
            \Log::error('❌ SharedPlanEmailJob failed', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
            ]);
        }
    }
}
