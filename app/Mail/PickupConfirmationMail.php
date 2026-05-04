<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PickupConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $autoorder;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($autoorder)
    {
        $this->autoorder = $autoorder;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Shipment Status Update: Pickup Successfully Completed')
            ->view('emails.pickupConfirmation');
    }
}
