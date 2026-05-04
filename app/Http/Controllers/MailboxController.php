<?php

namespace App\Http\Controllers;

use App\EmailAccount;
use App\EmailFolder;
use App\EmailMessage;
use App\EmailMessageAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Webklex\PHPIMAP\ClientManager;

/**
 * User mailbox — read inbox, view messages, download attachments.
 * Permission 163 = "View Mailbox".
 */
class MailboxController extends Controller
{
    private function currentUser()
    {
        return Auth::user();
    }

    private function mailboxOrNull(): ?EmailAccount
    {
        $u = $this->currentUser();
        return EmailAccount::where('user_id', $u->id)
            ->where('status', 'active')
            ->first();
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

    private function resolveFolderName($client, string $key, ?string $current = null): string
    {
        if ($current) {
            try { $client->getFolder($current); return $current; } catch (\Throwable $e) {}
        }
        $map = [
            'INBOX'  => ['INBOX'],
            'Sent'   => ['Sent', 'Sent Items', 'INBOX.Sent', 'INBOX.Sent Items'],
            'Drafts' => ['Drafts', 'INBOX.Drafts'],
            'Junk'   => ['Junk', 'Spam', 'INBOX.Junk', 'INBOX.Spam'],
            'Trash'  => ['Trash', 'Deleted Items', 'INBOX.Trash', 'INBOX.Deleted Items'],
        ];
        foreach (($map[$key] ?? [$key]) as $name) {
            try { $client->getFolder($name); return $name; } catch (\Throwable $e) {}
        }
        return $key;
    }

    public function index()
    {
        $this->authorizeMailbox();
        $mailbox = $this->mailboxOrNull();
        $folders = [
            'INBOX'  => 'Inbox',
            'Sent'   => 'Sent',
            'Drafts' => 'Drafts',
            'Junk'   => 'Junk',
            'Trash'  => 'Trash',
        ];
        return view('main.mailbox.index', compact('mailbox', 'folders'));
    }

    public function folders()
    {
        $acc = $this->mailboxOrNull();
        if (!$acc) return response()->json(['ok' => false, 'message' => 'No mailbox assigned.'], 403);

        $defaults = [
            ['key' => 'INBOX',  'label' => 'Inbox',  'imap_name' => 'INBOX'],
            ['key' => 'Sent',   'label' => 'Sent',   'imap_name' => 'Sent'],
            ['key' => 'Drafts', 'label' => 'Drafts', 'imap_name' => 'Drafts'],
            ['key' => 'Junk',   'label' => 'Junk',   'imap_name' => 'Junk'],
            ['key' => 'Trash',  'label' => 'Trash',  'imap_name' => 'Trash'],
        ];

        foreach ($defaults as $d) {
            EmailFolder::firstOrCreate(
                ['email_account_id' => $acc->id, 'key' => $d['key']],
                ['label' => $d['label'], 'imap_name' => $d['imap_name'], 'cached_unread' => 0, 'cached_total' => 0]
            );
        }

        $items = EmailFolder::where('email_account_id', $acc->id)
            ->orderByRaw("FIELD(`key`, 'INBOX','Sent','Drafts','Junk','Trash')")
            ->get()
            ->map(fn($f) => [
                'key'           => $f->key,
                'label'         => $f->label,
                'real'          => $f->imap_name,
                'unread'        => (int) $f->cached_unread,
                'total'         => (int) $f->cached_total,
                'last_synced_at'=> optional($f->last_synced_at)->format('Y-m-d H:i:s'),
            ]);

        return response()->json(['ok' => true, 'items' => $items]);
    }

    public function folder(Request $request, string $folder)
    {
        $acc = $this->mailboxOrNull();
        if (!$acc) return response()->json(['ok' => false, 'message' => 'No mailbox assigned.'], 403);

        $folderRow = EmailFolder::where('email_account_id', $acc->id)->where('key', $folder)->first();
        if (!$folderRow) return response()->json(['ok' => false, 'message' => 'Folder not found.'], 404);

        $limit  = max(1, min(100, (int) $request->get('limit', 50)));
        $page   = max(1, (int) $request->get('page', 1));
        $search = trim((string) $request->get('q', ''));

        $query = EmailMessage::where('email_account_id', $acc->id)
            ->where('email_folder_id', $folderRow->id);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('from_email', 'like', "%{$search}%")
                  ->orWhere('from_name', 'like', "%{$search}%");
            });
        }

        $messages = $query->orderByDesc('date_at')->paginate($limit, ['*'], 'page', $page);

        $items = $messages->getCollection()->map(function ($m) use ($folder) {
            $fromDisplay = $m->from_name ?: ($m->from_email ?: ($folder === 'Sent' ? 'Me' : '-'));
            return [
                'uid'             => $m->uid,
                'from'            => $fromDisplay,
                'from_email'      => $m->from_email,
                'subject'         => $m->subject ?: '(no subject)',
                'date'            => optional($m->date_at)->format('Y-m-d H:i') ?? '',
                'seen'            => (bool) $m->seen,
                'has_attachments' => (bool) $m->has_attachments,
                'snippet'         => $m->snippet ?: '',
                'folder'          => $folder,
            ];
        })->values();

        return response()->json([
            'ok'    => true,
            'folder'=> $folder,
            'page'  => $messages->currentPage(),
            'limit' => $messages->perPage(),
            'total' => $messages->total(),
            'items' => $items,
        ]);
    }

    public function message(string $folder, int $uid)
    {
        $acc = $this->mailboxOrNull();
        if (!$acc) return response()->json(['ok' => false, 'message' => 'No mailbox assigned.'], 403);

        $folderRow = EmailFolder::where('email_account_id', $acc->id)->where('key', $folder)->first();
        if (!$folderRow) return response()->json(['ok' => false, 'message' => 'Folder not found.'], 404);

        try {
            $client     = $this->imapClient($acc);
            $realFolder = $this->resolveFolderName($client, $folder, $folderRow->imap_name);
            $folderRow->imap_name = $realFolder;
            $folderRow->save();

            $f = $client->getFolder($realFolder);
            $m = $f->query()->whereUid($uid)->setFetchBody(true)->get()->first();

            if (!$m) {
                return $this->cachedMessageResponse($folderRow, $uid, $folder);
            }

            try { $m->setFlag('Seen'); } catch (\Throwable $e) {}

            EmailMessage::where('email_folder_id', $folderRow->id)->where('uid', $uid)->update(['seen' => true]);
            $folderRow->cached_unread = EmailMessage::where('email_folder_id', $folderRow->id)->where('seen', false)->count();
            $folderRow->save();

            [$fromEmail, $fromName] = $this->extractAddressData($m->getFrom());
            $toEmail = $this->extractSingleEmail($m->getTo());

            $cachedRow = EmailMessage::where('email_folder_id', $folderRow->id)->where('uid', $uid)->first();
            if ((!$fromEmail || $fromEmail === '-') && $cachedRow?->from_email) $fromEmail = $cachedRow->from_email;
            if ((!$fromName)  && $cachedRow?->from_name)  $fromName  = $cachedRow->from_name;
            if ((!$toEmail || $toEmail === '-') && $cachedRow?->to_email) $toEmail = $cachedRow->to_email;

            $html = null; $text = null;
            try { $html = $m->getHTMLBody(); } catch (\Throwable $e) {}
            try { $text = $m->getTextBody(); } catch (\Throwable $e) {}
            $bodyHtml = $html ?: nl2br(e($text ?: ''));
            if ($folder === 'Sent' && $cachedRow && !empty($cachedRow->body_html)) $bodyHtml = $cachedRow->body_html;

            $attachments = [];
            try {
                foreach ($m->getAttachments() as $idx => $att) {
                    $attachments[] = [
                        'index'        => $idx,
                        'name'         => $att->getName() ?: ('attachment_' . $idx),
                        'size'         => (int) ($att->getSize() ?? 0),
                        'mime'         => $att->getMimeType() ?: 'application/octet-stream',
                        'download_url' => route('mailbox.attachment', ['folder' => $folder, 'uid' => $uid, 'attachmentIndex' => $idx]),
                    ];
                }
            } catch (\Throwable $e) {}

            $localAtts = $cachedRow ? $this->localAttachmentsPayload($cachedRow->id, $folder, $uid) : [];
            if (empty($attachments) && !empty($localAtts)) $attachments = $localAtts;

            $msgDate = '';
            try { $msgDate = \Carbon\Carbon::parse((string) $m->getDate())->format('Y-m-d H:i'); } catch (\Throwable $e) {}

            return response()->json([
                'ok'        => true,
                'uid'       => $uid,
                'folder'    => $folder,
                'from'      => $fromName ?: $fromEmail,
                'from_email'=> $fromEmail,
                'to'        => $toEmail,
                'subject'   => (string) ($m->getSubject() ?? '(no subject)'),
                'date'      => $msgDate,
                'seen'      => true,
                'body_html' => $bodyHtml,
                'attachments'=> $attachments,
            ]);
        } catch (\Throwable $e) {
            return $this->cachedMessageResponse($folderRow, $uid, $folder);
        }
    }

    private function cachedMessageResponse(EmailFolder $folderRow, int $uid, string $folder)
    {
        $cached = EmailMessage::where('email_folder_id', $folderRow->id)->where('uid', $uid)->first();
        if (!$cached) return response()->json(['ok' => false, 'message' => 'Message not found.'], 404);

        return response()->json([
            'ok'         => true,
            'uid'        => $uid,
            'folder'     => $folder,
            'from'       => $cached->from_name ?: ($cached->from_email ?: ($folder === 'Sent' ? 'Me' : '-')),
            'from_email' => $cached->from_email,
            'to'         => $cached->to_email ?: '-',
            'subject'    => $cached->subject ?: '(no subject)',
            'date'       => optional($cached->date_at)->format('Y-m-d H:i') ?? '',
            'seen'       => (bool) $cached->seen,
            'body_html'  => $cached->body_html ?: nl2br(e($cached->snippet ?: 'No cached body available.')),
            'attachments'=> $this->localAttachmentsPayload($cached->id, $folder, $uid),
        ]);
    }

    public function downloadAttachment(string $folder, int $uid, int $attachmentIndex)
    {
        $acc = $this->mailboxOrNull();
        abort_if(!$acc, 403, 'No mailbox assigned.');

        $folderRow  = EmailFolder::where('email_account_id', $acc->id)->where('key', $folder)->first();
        abort_if(!$folderRow, 404, 'Folder not found.');

        $messageRow = EmailMessage::where('email_folder_id', $folderRow->id)->where('uid', $uid)->first();
        abort_if(!$messageRow, 404, 'Message not found.');

        $attachmentRow = EmailMessageAttachment::where('email_message_id', $messageRow->id)
            ->where('attachment_index', $attachmentIndex)->first();
        abort_if(!$attachmentRow, 404, 'Attachment not found.');

        if ($attachmentRow->disk !== 'imap' && !empty($attachmentRow->path)) {
            abort_unless(Storage::disk($attachmentRow->disk)->exists($attachmentRow->path), 404, 'File not found.');
            return Storage::disk($attachmentRow->disk)->download(
                $attachmentRow->path,
                $attachmentRow->original_name ?: ('attachment_' . $attachmentIndex)
            );
        }

        $client     = $this->imapClient($acc);
        $realFolder = $this->resolveFolderName($client, $folder, $folderRow->imap_name);
        $imapFolder = $client->getFolder($realFolder);
        $message    = $imapFolder->query()->whereUid($uid)->setFetchBody(true)->get()->first();
        abort_if(!$message, 404, 'IMAP message not found.');

        $atts    = collect($message->getAttachments())->values();
        $att     = $atts->get($attachmentIndex);
        abort_if(!$att, 404, 'Attachment not found.');

        $mime     = $attachmentRow->mime_type ?: 'application/octet-stream';
        $filename = $attachmentRow->original_name ?: ('attachment_' . $attachmentIndex);
        $content  = null;

        try { if (method_exists($att, 'getContent')) $content = $att->getContent(); } catch (\Throwable $e) {}

        abort_if(!$content, 404, 'Attachment content not available.');

        return response($content, 200, [
            'Content-Type'        => $mime,
            'Content-Disposition' => 'attachment; filename="' . addslashes($filename) . '"',
            'Content-Length'      => (string) strlen($content),
        ]);
    }

    private function localAttachmentsPayload(int $emailMessageId, string $folder, int $uid): array
    {
        return EmailMessageAttachment::where('email_message_id', $emailMessageId)
            ->get()
            ->map(function ($att) use ($folder, $uid) {
                return [
                    'index'        => (int) ($att->attachment_index ?? 0),
                    'name'         => $att->original_name ?: 'attachment',
                    'size'         => (int) ($att->size ?? 0),
                    'mime'         => $att->mime_type ?: 'application/octet-stream',
                    'download_url' => route('mailbox.attachment', [
                        'folder'          => $folder,
                        'uid'             => $uid,
                        'attachmentIndex' => $att->attachment_index ?? 0,
                    ]),
                ];
            })->filter()->values()->toArray();
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

    /**
     * Allow access if user is admin (role=1) OR has permission 163.
     */
    private function authorizeMailbox(): void
    {
        $user = \Auth::user();
        if ((int) $user->role === 1) return;

        $ptype  = \App\user_setting::where('user_id', $user->id)->value('penal_type') ?? 1;
        $column = match ((int) $ptype) {
            2       => 'emp_access_web',
            3       => 'emp_access_test',
            4       => 'panel_type_4',
            5       => 'panel_type_5',
            6       => 'panel_type_6',
            default => 'emp_access_phone',
        };

        $access = array_filter(explode(',', (string) ($user->$column ?? '')));
        if (!in_array('163', $access, true)) {
            abort(403, 'You do not have permission to access the mailbox.');
        }
    }
}
