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
        return $this->view('emails.send_code_email')
            ->with('brand', $this->brand);
    }
}
