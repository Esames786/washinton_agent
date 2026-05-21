<?php

namespace App\Mail;

use App\CrApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CrApplicationReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public CrApplication $application) {}

    public function build()
    {
        $mail = $this
            ->from(config('mail.from.address', 'noreply@hellotransport.com'), 'Hello Transport')
            ->subject('New CrazyRays Application — ' . $this->application->campaign_label . ' | ' . $this->application->full_name)
            ->view('emails.cr_application_received');

        if ($this->application->resume_path && file_exists(storage_path('app/public/' . $this->application->resume_path))) {
            $mail->attach(storage_path('app/public/' . $this->application->resume_path), [
                'as'   => 'resume_' . $this->application->full_name . '.pdf',
                'mime' => 'application/octet-stream',
            ]);
        }

        return $mail;
    }
}
