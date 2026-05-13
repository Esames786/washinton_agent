<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $userName,
        public string $userEmail
    ) {}

    public function build(): static
    {
        return $this
            ->subject('Welcome to Hello Transport — Account Pending Approval')
            ->view('emails.welcome');
    }
}
