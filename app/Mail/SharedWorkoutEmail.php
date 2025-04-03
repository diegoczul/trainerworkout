<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use SendGrid;
use SendGrid\Mail\Mail;

class SharedWorkoutEmail extends Mailable
{
    public function __construct(
        $sharing,
        $invite,
        $toUser,
        $fromUser,
        $comments,
        $workoutScreeshot,
        $workoutScreeshotPDF,
        $workoutPDF,
        $subject,
        $copyMe,
        $copyView,
        $copyPrint,
        $lang
    ) {
        // Initialize the properties
        $this->sharing = $sharing;
        $this->invite = $invite;
        $this->toUser = $toUser;
        $this->fromUser = $fromUser;
        $this->comments = $comments;
        $this->workoutScreeshot = $workoutScreeshot;
        $this->workoutScreeshotPDF = $workoutScreeshotPDF;
        $this->workoutPDF = $workoutPDF;
        $this->subject = $subject;
        $this->copyMe = $copyMe;
        $this->copyView = $copyView;
        $this->copyPrint = $copyPrint;
        $this->lang = $lang;
    }

    public function build()
    {
//        $this->view('emails.' . config('app.whitelabel') . '.user.' . $this->lang . '.sharedWorkout')
//            ->subject($this->subject)
//            ->to($this->toUser->email)
//            ->with([
//                'sharing' => $this->sharing,
//                'invite' => $this->invite,
//                'toUser' => $this->toUser,
//                'fromUser' => $this->fromUser,
//                'comments' => $this->comments,
//                'workoutScreeshot' => $this->workoutScreeshot,
//                'workoutScreeshotPDF' => $this->workoutScreeshotPDF,
//                'workoutPDF' => $this->workoutPDF,
//                'copyMe' => $this->copyMe,
//                'copyView' => $this->copyView,
//                'copyPrint' => $this->copyPrint,
//            ]);

        try {
            $email = new Mail();
            $email->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $email->setSubject($this->subject);
            $email->addTo($this->toUser->email);

            // Render email template
            $content = View::make('emails.' . config('app.whitelabel') . '.user.' . $this->lang . '.sharedWorkout', [
                'sharing' => $this->sharing,
                'invite' => $this->invite,
                'toUser' => $this->toUser,
                'fromUser' => $this->fromUser,
                'comments' => $this->comments,
                'workoutScreeshot' => $this->workoutScreeshot,
                'workoutScreeshotPDF' => $this->workoutScreeshotPDF,
                'workoutPDF' => $this->workoutPDF,
                'copyMe' => $this->copyMe,
                'copyView' => $this->copyView,
                'copyPrint' => $this->copyPrint,
            ])->render();

            $email->addContent("text/html", $content);

            $sendgrid = new SendGrid(env('SENDGRID_API_KEY'));
            $response = $sendgrid->send($email);

            return $response->statusCode();
        } catch (\Exception $exception) {
            $time = now('Asia/Kolkata')->format('d-m-Y H:i:s');
            Log::error("[$time] SendGrid Email Error: " . $exception->getMessage());
            return 'Error: ' . $exception->getMessage();
        }
    }

//    public function failed($exception)
//    {
//        $time = now('Asia/Kolkata')->format('d-m-Y H:i:s');
//        Log::driver('email_exceptions_log')->error("[$time] : Email Exception : ",['error' => $exception->getMessage(),'line' => $exception->getLine(),]);
//    }
}
