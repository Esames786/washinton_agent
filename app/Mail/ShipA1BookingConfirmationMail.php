<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ShipA1BookingConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $autoorder;
    public $pricingData; // parsed tiers array or fixed prices

    /**
     * @param  \App\AutoOrder  $autoorder
     * @param  array           $pricingData  Shape:
     *   [
     *     'mode'   => 'fixed'|'slab',
     *     'tiers'  => [
     *       ['name'=>'Basic','window'=>'5–7 business days','badge'=>'Best Savings',
     *        'open'=>900.00,'enclosed'=>1250.00],
     *       ...
     *     ],
     *   ]
     */
    public function __construct($autoorder, array $pricingData)
    {
        $this->autoorder   = $autoorder;
        $this->pricingData = $pricingData;
    }

    public function build()
    {
        return $this
            ->subject('Your Transport Quote — All Pricing Options Unlocked | Order #' . $this->autoorder->id)
            ->view('emails.shipa1BookingConfirmation');
    }
}
