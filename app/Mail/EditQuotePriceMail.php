<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EditQuotePriceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $price;
    public $order;
    public $linkv;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($price, $order, $linkv)
    {
        $this->price = $price;
        $this->order = $order;
        $this->linkv = $linkv;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('ShipA1 Shipping And Logistic Services')
            ->view('emails.editQuotePrice');
    }
}