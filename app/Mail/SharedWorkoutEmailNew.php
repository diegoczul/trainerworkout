<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use SendGrid;
use SendGrid\Mail\Mail;

class SharedWorkoutEmailNew extends Mailable
{
    use Queueable, SerializesModels;

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

    /**
     * Build the message.
     *
     * @return bool
     */
    public function build()
    {
        //        $email = $this->subject($this->subject)
        //            ->to($this->toUser->email)
        //            ->view('emails.' . config('app.whitelabel') . '.user.' . $this->lang . '.sharedWorkout')
        //            ->with([
        //                'sharing' => $this->sharing,
        //                'invite' => $this->invite,
        //                'toUser' => $this->toUser,
        //                'fromUser' => $this->fromUser,
        //                'comments' => $this->comments,
        //            ]);
        //
        //        // Attach files if needed
        //        if ($this->copyView) {
        //            if ($this->workoutScreenshot) {
        //                $email->attach($this->workoutScreenshot);
        //            }
        //            if ($this->workoutScreenshotPDF) {
        //                $email->attach($this->workoutScreenshotPDF);
        //            }
        //        }
        //
        //        if ($this->copyPrint && $this->workoutPDF) {
        //            $email->attach($this->workoutPDF);
        //        }
        //
        //        // Additional CC if "copyMe" is true
        //        if ($this->copyMe) {
        //            $email->cc($this->fromUser->email);
        //        }
        //
        //        return $email;

        try {
            $email = new Mail();
            $email->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $email->setSubject($this->subject);
            $email->addTo($this->toUser->email);

            // CC the sender if "copyMe" is true
            if ($this->copyMe && $this->fromUser->email !== $this->toUser->email) {
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

            return true;
        } catch (\Exception $e) {
            Log::error("SendGrid Email Error: " . $e->getMessage());
            return false;
        }
    }
}
