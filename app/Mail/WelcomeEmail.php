<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $userEmail;

    public function __construct($userName, $userEmail)
    {
        $this->userName  = $userName;
        $this->userEmail = $userEmail;
    }

    public function build()
    {
        return $this
            ->from(config('mail.from.address', 'noreply@hellotransport.com'), 'Hello Transport')
            ->subject('Welcome to Hello Transport — Account Pending Approval')
            ->view('emails.welcome');
    }
}
