<?php

namespace App\Mail;

use App\InstantQuote;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HelloTransportInstantQuoteMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public InstantQuote $quote) {}

    public function build()
    {
        return $this->subject('Your Hello Transport Quote - Regular & Premium Options')
            ->view('emails.hello_transport_instant_quote')
            ->with([
                'q'            => $this->quote,
                'pricing'      => $this->quote->pricing_payload ?? null,
                'platformCode' => 'hello-autohaul',
                'isAutohaul'   => true,
            ]);
    }
}
