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
        // Authorized From address (SPF/DKIM valid), brand display name, brand Reply-To.
        $fromAddress = config('mail.from.address', 'support@hellotransport.com');
        $replyTo     = $this->brand['email'] ?? $fromAddress;

        return $this
            ->from($fromAddress, $this->brand['name'])
            ->replyTo($replyTo, $this->brand['name'])
            ->subject('Your ' . $this->brand['name'] . ' Agent Account is Now Active!')
            ->view('emails.agent_activated')
            ->with('brand', $this->brand);
    }
}
