<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

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

    /**
     * Create a new message instance.
     *
     * @param mixed $sharing
     * @param mixed $invite
     * @param mixed $toUser
     * @param mixed $fromUser
     * @param string $comments
     * @param string|null $workoutScreenshot
     * @param string|null $workoutScreenshotPDF
     * @param string|null $workoutPDF
     * @param string $subject
     * @param bool $copyMe
     * @param bool $copyView
     * @param bool $copyPrint
     * @param string $lang
     */
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
     * @return $this
     */
    public function build()
    {
        $email = $this->subject($this->subject)
            ->from($this->fromUser->email, $this->fromUser->getCompleteName())
            ->replyTo($this->fromUser->email, $this->fromUser->getCompleteName())
            ->view("emails.{$this->lang}.sharedWorkout")
            ->with([
                'sharing' => $this->sharing,
                'invite' => $this->invite,
                'toUser' => $this->toUser,
                'fromUser' => $this->fromUser,
                'comments' => $this->comments,
            ]);

        // Attach files if needed
        if ($this->copyView) {
            if ($this->workoutScreenshot) {
                $email->attach($this->workoutScreenshot);
            }
            if ($this->workoutScreenshotPDF) {
                $email->attach($this->workoutScreenshotPDF);
            }
        }

        if ($this->copyPrint && $this->workoutPDF) {
            $email->attach($this->workoutPDF);
        }

        // Additional CC if "copyMe" is true
        if ($this->copyMe) {
            $email->cc($this->fromUser->email);
        }

        return $email;
    }
}