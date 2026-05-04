<?php

namespace App\Jobs;

use App\EmailAccount;
use App\EmailFolder;
use App\EmailMessage;
use App\EmailMessageAttachment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Webklex\PHPIMAP\ClientManager;

class SyncMailboxFolderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int    $emailAccountId,
        public string $folderKey,
        public int    $limit = 200
    ) {}

    public function handle(): void
    {
        $acc = EmailAccount::find($this->emailAccountId);
        if (!$acc || $acc->status !== 'active') return;

        $folder = EmailFolder::where('email_account_id', $acc->id)->where('key', $this->folderKey)->first();
        if (!$folder) return;

        $client   = $this->imapClient($acc);
        $imapName = $this->resolveFolderName($client, $folder->key, $folder->imap_name);
        $folder->imap_name = $imapName;

        $f       = $client->getFolder($imapName);
        $lastUid = (int) EmailMessage::where('email_folder_id', $folder->id)->max('uid');

        $msgs = $f->messages()->all()->setFetchBody(true)->setFetchFlags(true)->get()
            ->sortByDesc(fn($m) => (int) $m->getUid())
            ->take($this->limit);

        foreach ($msgs as $m) {
            $uid = (int) $m->getUid();
            if ($lastUid > 0 && $uid <= $lastUid) continue;

            [$fromEmail, $fromName] = $this->extractAddressData($m->getFrom());
            $toEmail   = $this->extractSingleEmail($m->getTo());
            $subject   = (string) ($m->getSubject() ?? '(no subject)');
            $dateAt    = now()->format('Y-m-d H:i:s');
            try { $dateAt = \Carbon\Carbon::parse((string) $m->getDate())->format('Y-m-d H:i:s'); } catch (\Throwable $e) {}

            $hasAttachments = false;
            $attachments    = collect();
            try {
                $attachments    = collect($m->getAttachments());
                $hasAttachments = $attachments->count() > 0;
            } catch (\Throwable $e) {}

            $messageId = null;
            try { $messageId = (string) ($m->getMessageId() ?? null); } catch (\Throwable $e) {}

            $emailMessage = EmailMessage::updateOrCreate(
                ['email_folder_id' => $folder->id, 'uid' => $uid],
                [
                    'email_account_id' => $acc->id,
                    'message_id'       => $messageId,
                    'from_email'       => $fromEmail,
                    'from_name'        => $fromName,
                    'to_email'         => $toEmail,
                    'subject'          => $subject,
                    'date_at'          => $dateAt,
                    'seen'             => $m->hasFlag('Seen'),
                    'has_attachments'  => $hasAttachments,
                ]
            );

            EmailMessageAttachment::where('email_message_id', $emailMessage->id)->delete();

            if ($hasAttachments) {
                foreach ($attachments as $index => $attachment) {
                    $originalName = 'attachment_' . $index;
                    $mimeType     = 'application/octet-stream';
                    $size         = 0;
                    try { $originalName = $attachment->getName() ?: $originalName; } catch (\Throwable $e) {}
                    try { $mimeType     = $attachment->getMimeType() ?: $mimeType; } catch (\Throwable $e) {}
                    try { $size         = (int) ($attachment->getSize() ?? 0); } catch (\Throwable $e) {}

                    EmailMessageAttachment::create([
                        'email_message_id' => $emailMessage->id,
                        'attachment_index' => (int) $index,
                        'disk'             => 'imap',
                        'path'             => '',
                        'original_name'    => $originalName,
                        'mime_type'        => $mimeType,
                        'size'             => $size,
                    ]);
                }
            }
        }

        try {
            $folder->cached_total  = (int) $f->messages()->all()->count();
            $folder->cached_unread = (int) $f->messages()->unseen()->count();
        } catch (\Throwable $e) {
            $folder->cached_total  = EmailMessage::where('email_folder_id', $folder->id)->count();
            $folder->cached_unread = EmailMessage::where('email_folder_id', $folder->id)->where('seen', false)->count();
        }

        $folder->last_synced_at = now();
        $folder->save();
    }

    private function imapClient(EmailAccount $acc)
    {
        $cm = new ClientManager();
        $client = $cm->make([
            'host'          => $acc->imap_host ?: config('mailbox.imap_host'),
            'port'          => (int) ($acc->imap_port ?: config('mailbox.imap_port')),
            'encryption'    => $acc->imap_encryption ?: config('mailbox.imap_encryption'),
            'validate_cert' => true,
            'username'      => $acc->username ?: $acc->email,
            'password'      => Crypt::decryptString($acc->password_enc),
            'protocol'      => 'imap',
        ]);
        $client->connect();
        return $client;
    }

    private function resolveFolderName($client, string $key, ?string $current): string
    {
        if ($current) { try { $client->getFolder($current); return $current; } catch (\Throwable $e) {} }
        $map = [
            'INBOX'  => ['INBOX'],
            'Sent'   => ['Sent', 'Sent Items', 'INBOX.Sent'],
            'Drafts' => ['Drafts', 'INBOX.Drafts'],
            'Junk'   => ['Junk', 'Spam', 'INBOX.Junk'],
            'Trash'  => ['Trash', 'Deleted Items', 'INBOX.Trash'],
        ];
        foreach (($map[$key] ?? [$key]) as $name) {
            try { $client->getFolder($name); return $name; } catch (\Throwable $e) {}
        }
        return $key;
    }

    private function extractAddressData($value): array
    {
        $email = null; $name = null;
        try {
            if (is_array($value) && count($value)) return $this->extractAddressData(array_values($value)[0]);
            if (is_object($value)) {
                $email = $value->mail ?? $value->email ?? null;
                $name  = $value->personal ?? $value->name ?? null;
                if ($email) return [$email, $name];
                if (method_exists($value, '__toString')) return $this->extractAddressData((string) $value);
            }
            if (is_string($value)) {
                $value = trim($value);
                if (preg_match('/^(.*)<([^>]+)>$/', $value, $m)) return [trim($m[2]), trim($m[1], "\"' ")];
                if (filter_var($value, FILTER_VALIDATE_EMAIL)) return [$value, null];
            }
        } catch (\Throwable $e) {}
        return [$email, $name];
    }

    private function extractSingleEmail($value): ?string
    {
        [$email] = $this->extractAddressData($value);
        return $email;
    }
}
