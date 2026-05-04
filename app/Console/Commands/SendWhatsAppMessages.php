<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Twilio\Rest\Client;

class SendWhatsAppMessages extends Command
{
    protected $signature = 'send:whatsapp-messages';
    protected $description = 'Send WhatsApp messages to all users';

    public function handle()
    {
        $accountSid = config('services.twilio.account_sid');
        $authToken = config('services.twilio.auth_token');
        $from = 'whatsapp:' . config('services.twilio.whatsapp_sandbox');
    
        $twilio = new Client($accountSid, $authToken);

        // Replace with your Twilio WhatsApp sandbox number
        $from = 'whatsapp:' . config('services.twilio.whatsapp_sandbox');

        // Example message
        $message = 'Hello from your Laravel app!';

        // Fetch user phone numbers from your database
        $userPhoneNumbers = \App\User::pluck('phone')->toArray();

        foreach ($userPhoneNumbers as $to) {
            // Replace + with empty space in phone numbers
            $to = 'whatsapp:' . str_replace('+', '', $to);

            // Send WhatsApp message
            $twilio->messages->create($to, [
                'from' => $from,
                'body' => $message,
            ]);

            $this->info("Message sent to $to");
        }

        $this->info('WhatsApp messages sent to all users.');
    }
}