<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomMail;
use App\EmailTemplate;
use App\AutoOrder;
use App\UsedAndNewCarDealers;
use Illuminate\Validation\ValidationException;

class SendCustomMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $emails;
    protected $content;
    protected $banner;

    public function __construct($emails, $content, $banner)
    {
        $this->emails = $emails;
        $this->content = $content;
        $this->banner = $banner;
    }

    public function handle()
    {
        foreach ($this->emails as $email) {
            try {
                Mail::to($email)->send(new CustomMail($this->content, $this->banner));
            } catch (Swift_RfcComplianceException $e) {
                continue;
            }
        }
    }
}