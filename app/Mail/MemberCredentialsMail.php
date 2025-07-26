<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class MemberCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;
    public $memberName;
    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $password, $memberName = "No name set")
    {
        $this->user = $user;
        $this->password = $password;
        $this->memberName = $memberName;
    }

    public function build()
    {
        return $this->subject(config('app.name') . ' - Account Credentials')
                    ->view('emails.member-credentials');
    }
}
