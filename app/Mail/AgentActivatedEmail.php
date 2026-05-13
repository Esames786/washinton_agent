<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AgentActivatedEmail extends Mailable
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
            ->subject('Your Hello Transport Agent Account is Now Active!')
            ->view('emails.agent_activated');
    }
}
