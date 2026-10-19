<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Pipeline email 1: sent the moment someone applies on the Hello Transport website.
 *
 * Replaces WelcomeEmail for Hello applicants (client, 16 Oct 2026): that email said an
 * account had been created and showed a "Go to Login" button, but applying no longer
 * creates an account. WelcomeEmail stays for the CrazyRays flow, which still works that way.
 */
class HelloApplicationReceivedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $applicantName)
    {
    }

    public function build()
    {
        return $this
            ->from(config('mail.from.address', 'noreply@hellotransport.com'), 'Hello Transport')
            ->subject('We have received your application — Hello Transport')
            ->view('emails.hello_application_received');
    }
}
