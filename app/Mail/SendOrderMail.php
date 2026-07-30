<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderMail extends Mailable
{
	use Queueable, SerializesModels;
    public $link1;
    public $mainTxt;

    public function __construct($link1)
    {
        $this->link1 = $link1;
        $this->mainTxt = "Your Link is: " . $link1;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Brand the sender name (florida = Crazy Rays Solutions) instead of the default
        // MAIL_FROM_NAME, so the order-link email doesn't show "Hello Transport".
        $brand = \App\Support\Brand::current();
        return $this->from(config('mail.from.address'), ($brand['name'] ?? config('mail.from.name')))
                    ->view('emails.send_order_email');
    }
}
