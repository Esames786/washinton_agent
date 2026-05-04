<?php

namespace App\Http\Controllers;

use App\EmailAccount;
use App\EmailFolder;
use App\EmailMessage;
use App\EmailInlineUpload;
use App\EmailMessageAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;
use Webklex\PHPIMAP\ClientManager;

class MailboxSendController extends Controller
{
    private function currentUser()
    {
        return Auth::user();
    }

    private function mailboxOrFail(): EmailAccount
    {
        $u   = $this->currentUser();
        $acc = EmailAccount::where('user_id', $u->id)->where('status', 'active')->first();
        abort_if(!$acc, 403, 'No mailbox assigned.');
        return $acc;
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

    public function send(Request $request)
    {
        $acc = $this->mailboxOrFail();

        $request->validate([
            'to'              => 'required|string|max:5000',
            'cc'              => 'nullable|string|max:5000',
            'bcc'             => 'nullable|string|max:5000',
            'subject'         => 'required|string|max:190',
            'body'            => 'required|string',
            'attachments'     => 'nullable|array',
            'attachments.*'   => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,csv,txt,zip',
        ]);

        $lockKey = 'mail_send_' . md5(($acc->id ?? '0') . '|' . $request->subject . '|' . $request->to . '|' . $request->body);
        if (!Cache::add($lockKey, true, now()->addSeconds(15))) {
            return back()->with('Error!', 'Duplicate send blocked. Please wait a few seconds.');
        }

        $fromEmail = $acc->email;
        $user      = $this->currentUser();
        $fromName  = $user->name ?? 'Washington Agent';

        $to  = $this->parseEmails($request->to);
        $cc  = $this->parseEmails($request->cc);
        $bcc = $this->parseEmails($request->bcc);

        if (count($to) === 0) {
            return back()->with('Error!', 'Please provide at least one valid "To" email.');
        }

        config()->set('mail.mailers.mailbox_smtp', [
            'transport'  => 'smtp',
            'host'       => $acc->smtp_host ?: config('mailbox.smtp_host'),
            'port'       => (int) ($acc->smtp_port ?: config('mailbox.smtp_port')),
            'encryption' => $acc->smtp_encryption ?: config('mailbox.smtp_encryption'),
            'username'   => $acc->username ?: $acc->email,
            'password'   => Crypt::decryptString($acc->password_enc),
            'timeout'    => 60,
        ]);

        config()->set('mail.from.address', $fromEmail);
        config()->set('mail.from.name', $fromName);

        $attachmentFiles = array_values(array_filter((array) $request->file('attachments', [])));
        $browserPreviewHtml = $this->wrapHtmlBody($request->body);

        $prepared  = $this->prepareHtmlAndInlineImages($request->body, $user->id);
        $finalHtml = $this->wrapHtmlBody($prepared['html']);

        $symfonyEmail = (new Email())
            ->from(sprintf('%s <%s>', $fromName, $fromEmail))
            ->subject($request->subject);

        foreach ($to  as $e) $symfonyEmail->addTo($e);
        foreach ($cc  as $e) $symfonyEmail->addCc($e);
        foreach ($bcc as $e) $symfonyEmail->addBcc($e);

        foreach ($prepared['inline_images'] as $inline) {
            $embedName = 'img_' . Str::lower(Str::random(24));
            $part = (new DataPart(new File($inline['path']), $embedName, $inline['mime']))->asInline();
            $symfonyEmail->addPart($part);
            $finalHtml = str_replace($inline['placeholder'], 'cid:' . $embedName, $finalHtml);
        }

        $symfonyEmail->html($finalHtml);
        $symfonyEmail->text(trim(strip_tags($finalHtml)));

        foreach ($attachmentFiles as $file) {
            $symfonyEmail->attachFromPath($file->getRealPath(), $file->getClientOriginalName(), $file->getMimeType());
        }

        try {
            Mail::mailer('mailbox_smtp')->getSymfonyTransport()->send($symfonyEmail);
            $this->appendToSentBestEffort($acc, $symfonyEmail);
            $this->cacheSentMessageInstantly($acc, $request->subject, $fromEmail, $fromName, $to[0] ?? null, $browserPreviewHtml, count($attachmentFiles) > 0, $attachmentFiles);
            return back()->with('success', 'Email sent successfully!');
        } catch (\Throwable $e) {
            Log::error('MAILBOX SEND FAILED: ' . $e->getMessage());
            return back()->with('Error!', 'Send failed: ' . $e->getMessage());
        } finally {
            Cache::forget($lockKey);
        }
    }

    private function appendToSentBestEffort(EmailAccount $acc, Email $email): void
    {
        try {
            $client = $this->imapClient($acc);
            $map    = ['Sent' => ['Sent', 'Sent Items', 'INBOX.Sent', 'INBOX.Sent Items']];
            $sentName = 'Sent';
            foreach (($map['Sent']) as $name) {
                try { $client->getFolder($name); $sentName = $name; break; } catch (\Throwable $e) {}
            }
            $sentFolder = $client->getFolder($sentName);
            $sentFolder->appendMessage($email->toString(), ['Seen']);
        } catch (\Throwable $e) {}
    }

    private function cacheSentMessageInstantly(EmailAccount $acc, string $subject, ?string $fromEmail, ?string $fromName, ?string $toEmail, ?string $body, bool $hasAttachments, array $attachmentFiles): void
    {
        try {
            $folder = EmailFolder::firstOrCreate(
                ['email_account_id' => $acc->id, 'key' => 'Sent'],
                ['label' => 'Sent', 'imap_name' => 'Sent', 'cached_unread' => 0, 'cached_total' => 0]
            );

            $message = EmailMessage::create([
                'email_account_id' => $acc->id,
                'email_folder_id'  => $folder->id,
                'uid'              => time(),
                'message_id'       => null,
                'from_email'       => $fromEmail,
                'from_name'        => $fromName,
                'to_email'         => $toEmail,
                'subject'          => $subject ?: '(no subject)',
                'date_at'          => now(),
                'seen'             => true,
                'has_attachments'  => $hasAttachments,
                'snippet'          => Str::limit(trim(strip_tags($body ?? '')), 255),
                'body_html'        => $body ?: '',
            ]);

            $folder->cached_total  = EmailMessage::where('email_folder_id', $folder->id)->count();
            $folder->cached_unread = 0;
            $folder->last_synced_at = now();
            $folder->save();

            foreach ($attachmentFiles as $file) {
                $disk     = 'public';
                $dir      = 'mail-attachments/' . $acc->id . '/' . $message->id;
                $filename = Str::uuid()->toString() . '_' . $file->getClientOriginalName();
                $path     = $file->storeAs($dir, $filename, $disk);

                EmailMessageAttachment::create([
                    'email_message_id' => $message->id,
                    'attachment_index' => null,
                    'disk'             => $disk,
                    'path'             => $path,
                    'original_name'    => $file->getClientOriginalName(),
                    'mime_type'        => $file->getMimeType(),
                    'size'             => $file->getSize(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('cacheSentMessageInstantly failed: ' . $e->getMessage());
        }
    }

    private function parseEmails(?string $value): array
    {
        if (!$value) return [];
        $value  = str_replace(["\n", "\r", ";"], [",", "", ","], $value);
        $parts  = array_filter(array_map('trim', explode(',', $value)));
        $emails = [];
        foreach ($parts as $p) {
            if (preg_match('/<([^>]+)>/', $p, $m)) $p = trim($m[1]);
            if (filter_var($p, FILTER_VALIDATE_EMAIL)) $emails[] = strtolower($p);
        }
        return array_values(array_unique($emails));
    }

    private function wrapHtmlBody(string $body): string
    {
        $body = trim($body);
        if ($body !== strip_tags($body)) {
            return '<div style="font-family:Arial,sans-serif;font-size:14px;line-height:1.6;">' . $body . '</div>';
        }
        return '<div style="font-family:Arial,sans-serif;font-size:14px;line-height:1.6;">' . nl2br(e($body)) . '</div>';
    }

    private function prepareHtmlAndInlineImages(string $html, int $userId): array
    {
        $inlineImages = [];
        libxml_use_internal_errors(true);
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadHTML(mb_convert_encoding('<!DOCTYPE html><html><body>' . $html . '</body></html>', 'HTML-ENTITIES', 'UTF-8'));
        $images = $dom->getElementsByTagName('img');

        foreach ($images as $img) {
            $src = (string) $img->getAttribute('src');
            if (!$src) continue;

            if (str_starts_with($src, 'data:image')) {
                if (!preg_match('/^data:image\/(\w+);base64,/', $src, $type)) continue;
                $data = base64_decode(substr($src, strpos($src, ',') + 1));
                if ($data === false) continue;
                $ext      = strtolower($type[1]);
                $tempPath = storage_path('app/mail_inline_tmp');
                if (!is_dir($tempPath)) mkdir($tempPath, 0755, true);
                $filePath = $tempPath . '/' . Str::random(20) . '.' . $ext;
                file_put_contents($filePath, $data);
                $placeholder = '__INLINE_IMAGE_' . Str::random(24) . '__';
                $img->setAttribute('src', $placeholder);
                $inlineImages[] = ['placeholder' => $placeholder, 'path' => $filePath, 'mime' => 'image/' . $ext];
                continue;
            }

            $token = null;
            if (preg_match('/mail_inline_token=([a-zA-Z0-9]+)/', $src, $m)) $token = $m[1];
            if (!$token) continue;

            $upload = EmailInlineUpload::where('token', $token)->where('user_id', $userId)->first();
            if (!$upload) continue;
            $absolutePath = Storage::disk($upload->disk)->path($upload->path);
            if (!is_file($absolutePath)) continue;
            $placeholder = '__INLINE_IMAGE_' . Str::random(24) . '__';
            $img->setAttribute('src', $placeholder);
            $inlineImages[] = ['placeholder' => $placeholder, 'path' => $absolutePath, 'mime' => $upload->mime_type ?: 'image/png'];
        }

        $bodyNode  = $dom->getElementsByTagName('body')->item(0);
        $finalHtml = '';
        if ($bodyNode) foreach ($bodyNode->childNodes as $child) $finalHtml .= $dom->saveHTML($child);

        return ['html' => $finalHtml, 'inline_images' => $inlineImages];
    }
}
