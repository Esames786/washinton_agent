<?php

namespace App\Mail;

use App\CrApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CrApplicationApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public CrApplication $application,
        public string $portalUrl
    ) {}

    public function build()
    {
        return $this
            ->mailer('crazyrays')
            ->from('careers@crazyrayssolutions.com.pk', 'CrazyRays Solutions')
            ->subject('Your Application Has Been Approved — CrazyRays Solutions')
            ->view('emails.cr_application_approved');
    }
}
