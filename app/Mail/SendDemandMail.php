<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendDemandMail extends Mailable
{
    use Queueable, SerializesModels;
    public $link1;
    public $mainTxt;

    /**
     * Create a new message instance.
     *
     * @return void
     */
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
        return $this->view('emails.send_demand_email');
    }
}
