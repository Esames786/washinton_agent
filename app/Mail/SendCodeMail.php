<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $code;
    public $mainTxt;
    public $name;
    public $brand;

    public function __construct($name, $code, ?array $brand = null)
    {
        $this->name = $name;
        $this->code = $code;
        $this->mainTxt = ucfirst($name) ." your code is: " . $code;
        $this->brand = $brand ?? \App\Support\Brand::byKey(config('brands.default', 'hellotransport'));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Send from the authorized mailbox (SPF/DKIM valid) but display the brand
        // name; route replies to the brand's own inbox.
        $fromAddress = config('mail.from.address', 'support@hellotransport.com');
        $fromName    = $this->brand['name']  ?? config('mail.from.name', 'Hello Transport');
        $replyTo     = $this->brand['email'] ?? $fromAddress;

        return $this->from($fromAddress, $fromName)
            ->replyTo($replyTo, $fromName)
            ->subject('Your ' . $fromName . ' Verification Code')
            ->view('emails.send_code_email')
            ->with('brand', $this->brand);
    }
}
