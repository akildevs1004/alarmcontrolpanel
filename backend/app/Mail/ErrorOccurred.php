<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

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
        return $this->subject('Application Error Report - ' . date("Y-m-d H:i:s"))
            ->view('emails.error_report'); // Create this view
    }
}
