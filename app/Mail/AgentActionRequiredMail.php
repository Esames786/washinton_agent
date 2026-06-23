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
        $fromAddress = config('mail.from.address', 'support@hellotransport.com');
        $fromName    = $this->brand['name']  ?? config('mail.from.name', 'Hello Transport');
        $replyTo     = $this->brand['email'] ?? $fromAddress;

        $subject = $this->type === 'nda'
            ? 'Action Required: Sign your NDA — ' . $fromName
            : 'Action Required: Review your Contract — ' . $fromName;

        return $this->from($fromAddress, $fromName)
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
