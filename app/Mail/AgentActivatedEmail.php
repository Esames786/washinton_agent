<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AgentActivatedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $userName,
        public string $userEmail
    ) {}

    public function build(): static
    {
        return $this
            ->subject('Your Hello Transport Agent Account is Now Active!')
            ->view('emails.agent_activated');
    }
}
