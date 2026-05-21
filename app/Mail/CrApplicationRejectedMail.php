<?php

namespace App\Mail;

use App\CrApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CrApplicationRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public CrApplication $application) {}

    public function build()
    {
        return $this
            ->from('careers@crazyrayssolutions.com.pk', 'CrazyRays Solutions')
            ->subject('Update on Your Application — CrazyRays Solutions')
            ->view('emails.cr_application_rejected');
    }
}
