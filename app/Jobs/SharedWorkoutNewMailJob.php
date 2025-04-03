<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use SendGrid;
use SendGrid\Mail\Mail;

class SharedWorkoutNewMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $sharing;
    public $invite;
    public $toUser;
    public $fromUser;
    public $comments;
    public $workoutScreenshot;
    public $workoutScreenshotPDF;
    public $workoutPDF;
    public $subject;
    public $copyMe;
    public $copyView;
    public $copyPrint;
    public $lang;

    public function __construct(
        $sharing,
        $invite,
        $toUser,
        $fromUser,
        $comments,
        $workoutScreenshot,
        $workoutScreenshotPDF,
        $workoutPDF,
        $subject,
        $copyMe = true,
        $copyView = true,
        $copyPrint = true,
        $lang
    ) {
        $this->sharing = $sharing;
        $this->invite = $invite;
        $this->toUser = $toUser;
        $this->fromUser = $fromUser;
        $this->comments = $comments;
        $this->workoutScreenshot = $workoutScreenshot;
        $this->workoutScreenshotPDF = $workoutScreenshotPDF;
        $this->workoutPDF = $workoutPDF;
        $this->subject = $subject;
        $this->copyMe = $copyMe;
        $this->copyView = $copyView;
        $this->copyPrint = $copyPrint;
        $this->lang = $lang;
    }

    public function handle(): void
    {
        try {
            $email = new Mail();
            $email->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $email->setSubject($this->subject);
            $email->addTo($this->toUser->email);

            // CC the sender if "copyMe" is true
            if ($this->copyMe) {
                $email->addCc($this->fromUser->email);
            }

            // Email Body Content
            $body = view('emails.' . config('app.whitelabel') . '.user.' . $this->lang . '.sharedWorkout', [
                'sharing' => $this->sharing,
                'invite' => $this->invite,
                'toUser' => $this->toUser,
                'fromUser' => $this->fromUser,
                'comments' => $this->comments,
            ])->render();

            $email->addContent("text/html", $body);

            // Attachments
            if ($this->copyView) {
                if ($this->workoutScreenshot) {
                    $email->addAttachment(base64_encode(file_get_contents($this->workoutScreenshot)), "image/png", "workout_screenshot.png", "attachment");
                }
                if ($this->workoutScreenshotPDF) {
                    $email->addAttachment(base64_encode(file_get_contents($this->workoutScreenshotPDF)), "application/pdf", "workout_screenshot.pdf", "attachment");
                }
            }

            if ($this->copyPrint && $this->workoutPDF) {
                $email->addAttachment(base64_encode(file_get_contents($this->workoutPDF)), "application/pdf", "workout.pdf", "attachment");
            }

            // Send the email via SendGrid API
            $sendgrid = new SendGrid(env('SENDGRID_API_KEY'));
            $sendgrid->send($email);
        } catch (\Exception $exception) {
            $time = now('Asia/Kolkata')->format('d-m-Y H:i:s');
            Log::driver('email_exceptions_log')->error("[$time] : Email Exception : ",['error' => $exception->getMessage(), 'line' => $exception->getLine(),]);
        }
    }
}
