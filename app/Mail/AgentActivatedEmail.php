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
    public $brand;

    public function __construct($userName, $userEmail, ?array $brand = null)
    {
        $this->userName  = $userName;
        $this->userEmail = $userEmail;
        $this->brand     = $brand ?? \App\Support\Brand::byKey(config('brands.default', 'hellotransport'));
    }

    public function build()
    {
        return $this
            ->from(config('mail.from.address', 'noreply@hellotransport.com'), $this->brand['name'])
            ->subject('Your ' . $this->brand['name'] . ' Agent Account is Now Active!')
            ->view('emails.agent_activated')
            ->with('brand', $this->brand);
    }
}
