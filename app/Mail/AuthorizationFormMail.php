<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AuthorizationFormMail extends Mailable
{
    use Queueable, SerializesModels;
    public $cID;
    public $cname;
    public $email;
    public $cphone;
    public $invoiceNo;
    public $invoiceAmount;
    public $origin;
    public $destination;
    public $vehicle;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($cID, $cname, $email, $cphone, $invoiceNo, $invoiceAmount, $origin, $destination, $vehicle)
    {
        $this->cID = $cID;
        $this->cname = $cname;
        $this->email = $email;
        $this->cphone = $cphone;
        $this->invoiceNo = $invoiceNo;
        $this->invoiceAmount = $invoiceAmount;
        $this->origin = $origin;
        $this->destination = $destination;
        $this->vehicle = $vehicle;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.authorization_form');
    }
}
