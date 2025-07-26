<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyNewEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $newEmail;
    public $memberName;

    public function __construct($token, $newEmail, $memberName = "Valued Member")
    {
        $this->token = $token;
        $this->newEmail = $newEmail;
        $this->memberName = $memberName;
    }

    public function build()
    {
        return $this->subject(config('app.name') . ' - Verify Your New Email')
            ->view('emails.verify-new-email');
    }
}
