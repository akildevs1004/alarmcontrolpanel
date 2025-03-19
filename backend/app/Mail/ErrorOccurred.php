<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ErrorOccurred extends Mailable
{
    use Queueable, SerializesModels;

    public $errorDetails;

    /**
     * Create a new message instance.
     *
     * @param array $errorDetails
     * @return void
     */
    public function __construct($errorDetails)
    {
        $this->errorDetails = $errorDetails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $emailData = [
            'subject' =>  env('APP_ENV') . ' - Application Error Report - ' . date("Y-m-d H:i:s"),
            'body' =>  $this->errorDetails['exception_message'],
        ];

        //tanjore mail settings
        $emailData["to"] = "venuakil2@gmail.com";
        $response = Http::timeout(30)
            ->withoutVerifying()
            ->asForm()
            ->post("https://tanjore.hyderspark.com/xtremeguard_mail.php", $emailData);

        return $this->subject(env('APP_ENV') . 'Application Error Report - ' . date("Y-m-d H:i:s"))
            ->view('emails.error_report'); // Create this view
    }
}
