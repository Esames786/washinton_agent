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
        // Customer-facing order email — always sent from the Hello domain (default mailer),
        // since every order customer belongs to Hello Transport regardless of which agent
        // portal booked it.
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.send_order_email');
    }
}
