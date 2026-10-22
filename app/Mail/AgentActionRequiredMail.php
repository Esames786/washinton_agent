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
    public $type;   // 'contract' | 'nda' | 'w9'

    public function __construct($userName, array $brand, string $type)
    {
        $this->userName = $userName;
        $this->brand    = $brand;
        // 'w9' used to be folded into 'contract', so the W-9 request went out as "Review your
        // Contract".
        $this->type     = in_array($type, ['nda', 'w9'], true) ? $type : 'contract';
    }

    public function build()
    {
        // Routed to the brand's own mailer (CrazyRays agents ← careers@crazyrays, SPF/DKIM valid).
        [$fromAddress, $fromName] = \App\Support\Brand::mailFrom($this->brand);
        $replyTo = $this->brand['email'] ?? $fromAddress;

        $subject = match ($this->type) {
            'nda'    => 'Action Required: Sign your NDA — ' . $fromName,
            'w9'     => 'Action Required: Complete your W-9 Form — ' . $fromName,
            default  => 'Action Required: Review your Contract — ' . $fromName,
        };

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
