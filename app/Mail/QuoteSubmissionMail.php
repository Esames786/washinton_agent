<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuoteSubmissionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $recipientType; // 'customer' or 'company'

    public function __construct($order, $recipientType = 'customer')
    {
        $this->order = $order;
        $this->recipientType = $recipientType;
    }

    public function build()
    {
        $subject = $this->recipientType === 'customer'
            ? 'Thank You! Your Quote Request Has Been Received'
            : 'New Quote Submission Received';

        return $this->subject($subject)
            ->view('emails.quote_submission')
            ->with([
                'order' => $this->order,
                'recipientType' => $this->recipientType,
            ]);
    }
}
