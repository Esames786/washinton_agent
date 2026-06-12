<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomMail extends Mailable
{
    use Queueable, SerializesModels;

    public $content;
    public $banner;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($content, $banner, $fromAddress = null)
    {
        $this->content = $content;
        $this->banner = $banner;
        $this->fromAddress = $fromAddress;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {


        $email = $this
            ->subject('Hello Transport - Auto Transportation Services')
            ->view('emails.custom');

        if ($this->fromAddress) {
            $email->from($this->fromAddress, 'Hello Transport');
        }

        return $email;
    }
}
