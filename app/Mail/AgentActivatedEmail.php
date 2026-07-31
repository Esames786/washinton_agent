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
        // Recruitment/agent email — routed to the brand's own mailer so a CrazyRays agent
        // is emailed from careers@crazyrays (SPF/DKIM valid), while a Hello agent stays on Hello.
        [$fromAddress, $fromName] = \App\Support\Brand::mailFrom($this->brand);
        $replyTo = $this->brand['email'] ?? $fromAddress;

        return $this
            ->mailer(\App\Support\Brand::mailer($this->brand))
            ->from($fromAddress, $fromName)
            ->replyTo($replyTo, $this->brand['name'])
            ->subject('Your ' . $this->brand['name'] . ' Agent Account is Now Active!')
            ->view('emails.agent_activated')
            ->with('brand', $this->brand);
    }
}
