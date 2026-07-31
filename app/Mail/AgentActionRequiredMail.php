<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AgentActionRequiredMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $brand;
    public $type;   // 'contract' | 'nda'

    public function __construct($userName, array $brand, string $type)
    {
        $this->userName = $userName;
        $this->brand    = $brand;
        $this->type     = $type === 'nda' ? 'nda' : 'contract';
    }

    public function build()
    {
        // Routed to the brand's own mailer (CrazyRays agents ← careers@crazyrays, SPF/DKIM valid).
        [$fromAddress, $fromName] = \App\Support\Brand::mailFrom($this->brand);
        $replyTo = $this->brand['email'] ?? $fromAddress;

        $subject = $this->type === 'nda'
            ? 'Action Required: Sign your NDA — ' . $fromName
            : 'Action Required: Review your Contract — ' . $fromName;

        return $this->mailer(\App\Support\Brand::mailer($this->brand))
            ->from($fromAddress, $fromName)
            ->replyTo($replyTo, $fromName)
            ->subject($subject)
            ->view('emails.agent_action_required')
            ->with([
                'brand'    => $this->brand,
                'type'     => $this->type,
                'userName' => $this->userName,
            ]);
    }
}
