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

    public function __construct($name,$code)
    {
        $this->name = $name;
        $this->code = $code;
        $this->mainTxt = ucfirst($name) ." your code is: " . $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // dd('asdasdasdasd');
        return $this->view('emails.send_code_email');
    }
}
