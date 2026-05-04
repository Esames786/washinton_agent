<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeliveredConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $autoorder ;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($autoorder )
    {
        $this->autoorder  = $autoorder ;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Shipment Status Update: Delivery Successfully Completed')
            ->view('emails.deliveredConfirmation');
    }
}
