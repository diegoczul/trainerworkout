<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class NewEmailConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $lang;

    public function __construct($user,$lang)
    {
        $this->user = $user;
        $this->lang = $lang;
    }

    public function build()
    {
        return $this->view('emails.' . Config::get("app.whitelabel") . '.user.' . $this->lang . '.newEmailConfirmation')
            ->to($this->user->new_email)
            ->with(['user' => $this->user])
            ->subject(__('messages.TrainerWorkoutEmailConfirmation'));
    }


//    /**
//     * Create a new message instance.
//     */
//    public function __construct($user)
//    {
//        $this->user = $user;
//    }
//
//    /**
//     * Get the message envelope.
//     */
//    public function envelope(): Envelope
//    {
//        return new Envelope(
//            subject: 'New Email Confirmation Mail',
//        );
//    }
//
//    /**
//     * Get the message content definition.
//     */
//    public function content(): Content
//    {
//        return (new Content('emails.' . Config::get("app.whitelabel") . '.user.' . $this->lang . '.newEmailConfirmation'))->with(['user' => $this->user]);
//    }
//
//    /**
//     * Get the attachments for the message.
//     *
//     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
//     */
//    public function attachments(): array
//    {
//        return [];
//    }
//
//    public function boot($mail)
//    {
//        $mail->to($this->user->new_email);
//    }
}
