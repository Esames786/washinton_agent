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
use Illuminate\Support\Str;
use Webklex\PHPIMAP\ClientManager;
use Webklex\PHPIMAP\IMAP;

/**
 * User mailbox — read inbox, view messages, download attachments.
 * Permission 163 = "View Mailbox".
 */
class MailboxController extends Controller
{
    use \App\Http\Controllers\Concerns\ResolvesMailboxAccount;

    private function currentUser()
    {
        return Auth::user();
    }

    private function mailboxOrNull(): ?EmailAccount
    {
        // #7: resolves to the supervisor's "view as" account when set, else own mailbox.
        return $this->resolveMailboxAccount();
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

        // #7: supervisor "view as" controls.
        $isSupervisor   = $this->isMailboxSupervisor();
        $switchAccounts = $isSupervisor ? $this->switchableMailboxAccounts() : collect();
        $viewingAsId    = $isSupervisor ? session('mailbox_view_as') : null;

        return view('main.mailbox.index', compact('mailbox', 'folders', 'isSupervisor', 'switchAccounts', 'viewingAsId'));
    }

    /**
     * #7: supervisor switches the mailbox they are viewing. Passing an empty account_id
     * returns them to their own mailbox. Non-supervisors are forbidden.
     */
    public function switchAccount(Request $request)
    {
        abort_unless($this->isMailboxSupervisor(), 403);

        $request->validate(['account_id' => 'nullable|integer']);
        $accountId = $request->input('account_id');

        if (!$accountId) {
            session()->forget('mailbox_view_as');
            return redirect()->route('mailbox.index')->with('success', 'Back to your own mailbox.');
        }

        $acc = EmailAccount::with('user')->where('id', $accountId)->where('status', 'active')->first();
        abort_if(!$acc, 404, 'Mailbox not found.');

        session(['mailbox_view_as' => $acc->id]);
        $owner = $acc->user->name ?? $acc->email;

        return redirect()->route('mailbox.index')->with('success', "Now viewing {$owner}'s mailbox ({$acc->email}).");
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
            $fromDisplay = $this->decodeHeader($m->from_name) ?: ($m->from_email ?: ($folder === 'Sent' ? 'Me' : '-'));
            return [
                'uid'             => $m->uid,
                'from'            => $fromDisplay,
                'from_email'      => $m->from_email,
                'subject'         => $this->decodeHeader($m->subject) ?: '(no subject)',
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
                'from'      => $this->decodeHeader($fromName) ?: $fromEmail,
                'from_email'=> $fromEmail,
                'to'        => $toEmail,
                'subject'   => $this->decodeHeader((string) ($m->getSubject() ?? '')) ?: '(no subject)',
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
            'from'       => $this->decodeHeader($cached->from_name) ?: ($cached->from_email ?: ($folder === 'Sent' ? 'Me' : '-')),
            'from_email' => $cached->from_email,
            'to'         => $cached->to_email ?: '-',
            'subject'    => $this->decodeHeader($cached->subject) ?: '(no subject)',
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
        if (!$folderRow) {
            Log::warning("Mailbox DL: folder not found — acc={$acc->id} key={$folder}");
            abort(404, 'Folder not found.');
        }

        $messageRow = EmailMessage::where('email_folder_id', $folderRow->id)->where('uid', $uid)->first();
        if (!$messageRow) {
            Log::warning("Mailbox DL: message not found in DB — acc={$acc->id} folder={$folder} uid={$uid}");
            abort(404, 'Message not found.');
        }

        $attachmentRow = EmailMessageAttachment::where('email_message_id', $messageRow->id)
            ->where(function ($q) use ($attachmentIndex) {
                $q->where('attachment_index', $attachmentIndex);
                if ($attachmentIndex === 0) {
                    $q->orWhereNull('attachment_index');
                }
            })->first();
        if (!$attachmentRow) {
            Log::warning("Mailbox DL: attachment row not found — msg_id={$messageRow->id} idx={$attachmentIndex}");
            abort(404, 'Attachment not found.');
        }

        Log::info("Mailbox DL: start — acc={$acc->id} uid={$uid} idx={$attachmentIndex} disk={$attachmentRow->disk} path={$attachmentRow->path}");

        // Serve from disk if already cached
        if ($attachmentRow->disk !== 'imap' && !empty($attachmentRow->path)) {
            $exists = Storage::disk($attachmentRow->disk)->exists($attachmentRow->path);
            Log::info("Mailbox DL: disk cache check — exists={$exists} path={$attachmentRow->path}");
            if (!$exists) abort(404, 'Cached file not found.');
            return Storage::disk($attachmentRow->disk)->download(
                $attachmentRow->path,
                $attachmentRow->original_name ?: ('attachment_' . $attachmentIndex)
            );
        }

        $mime     = $attachmentRow->mime_type ?: 'application/octet-stream';
        $filename = $attachmentRow->original_name ?: ('attachment_' . $attachmentIndex);

        if (!str_contains($filename, '.')) {
            $filename .= '.' . $this->extensionFromMime($mime);
        }

        // Live IMAP fetch
        try {
            $client     = $this->imapClient($acc);
            $realFolder = $this->resolveFolderName($client, $folder, $folderRow->imap_name);
        } catch (\Throwable $e) {
            Log::error("Mailbox DL: IMAP connect failed — acc={$acc->id}: " . $e->getMessage());
            abort(404, 'IMAP connection failed.');
        }

        Log::info("Mailbox DL: IMAP connected, fetching uid={$uid} from folder={$realFolder}");

        try {
            $imapFolder = $client->getFolder($realFolder);
            $message = $imapFolder->query()
                ->whereUid($uid)
                ->setFetchBody(true)
                ->get()
                ->first();
        } catch (\Throwable $e) {
            Log::error("Mailbox DL: IMAP query failed — acc={$acc->id} uid={$uid}: " . $e->getMessage());
            abort(404, 'IMAP query failed.');
        }

        if (!$message) {
            Log::warning("Mailbox DL: message not found in IMAP — acc={$acc->id} uid={$uid}");
            abort(404, 'IMAP message not found.');
        }

        $atts = collect($message->getAttachments())->values();
        Log::info("Mailbox DL: IMAP attachments found — count={$atts->count()} requested_idx={$attachmentIndex}");

        $att = $atts->get($attachmentIndex);

        if (!$att) {
            Log::warning("Mailbox DL: attachment not in IMAP — acc={$acc->id} uid={$uid} idx={$attachmentIndex} total={$atts->count()}");
            abort(404, 'Attachment not found in IMAP.');
        }

        try {
            if (method_exists($att, 'getName') && $att->getName()) {
                $filename = $att->getName();
            }
        } catch (\Throwable $e) {}

        if (!str_contains($filename, '.')) {
            $filename .= '.' . $this->extensionFromMime($mime);
        }

        try {
            if (method_exists($att, 'getMimeType') && $att->getMimeType()) {
                $mime = $att->getMimeType();
            }
        } catch (\Throwable $e) {}

        $content = null;
        try {
            if (method_exists($att, 'getContent')) {
                $content = $att->getContent();
            }
        } catch (\Throwable $e) {
            Log::warning("Mailbox DL: getContent() threw — acc={$acc->id} uid={$uid}: " . $e->getMessage());
        }

        // Fallback: save attachment to a temp dir and read the file
        if (($content === null || $content === '') && method_exists($att, 'save')) {
            try {
                $tmpBase = storage_path('app/tmp-mail-downloads');
                if (!is_dir($tmpBase)) {
                    mkdir($tmpBase, 0755, true);
                }
                $tmpDir = $tmpBase . '/' . uniqid('mail_att_', true);
                mkdir($tmpDir, 0755, true);

                $att->save($tmpDir);

                $saved = glob($tmpDir . '/*');
                if (!empty($saved) && is_file($saved[0])) {
                    $content = file_get_contents($saved[0]);
                    foreach ($saved as $f) { @unlink($f); }
                    @rmdir($tmpDir);
                }
            } catch (\Throwable $e) {
                Log::warning("Mailbox DL: save() fallback failed — acc={$acc->id} uid={$uid}: " . $e->getMessage());
            }
        }

        Log::info("Mailbox DL: content length=" . (is_string($content) ? strlen($content) : 'null/empty') . " filename={$filename}");

        if ($content === null || $content === '') {
            Log::warning("Mailbox DL: content empty after IMAP fetch — acc={$acc->id} uid={$uid} idx={$attachmentIndex}");
            abort(404, 'Attachment content not available.');
        }

        // Cache to disk so future downloads skip IMAP
        try {
            $ext  = pathinfo($filename, PATHINFO_EXTENSION) ?: 'bin';
            $dir  = 'mail-attachments/' . $acc->id . '/' . $messageRow->id;
            $file = Str::uuid()->toString() . '_ss.' . $ext;
            Storage::disk('public')->put($dir . '/' . $file, $content);
            $attachmentRow->update(['disk' => 'public', 'path' => $dir . '/' . $file]);
        } catch (\Throwable $e) {
            Log::warning("Mailbox download: disk cache failed — " . $e->getMessage());
        }

        return response($content, 200, [
            'Content-Type'        => $mime,
            'Content-Disposition' => 'attachment; filename="' . addslashes($filename) . '"',
            'Content-Length'      => (string) strlen($content),
        ]);
    }

    private function extensionFromMime(?string $mime): string
    {
        return match (strtolower((string) $mime)) {
            'image/gif'        => 'gif',
            'image/jpeg'       => 'jpg',
            'image/png'        => 'png',
            'image/webp'       => 'webp',
            'application/pdf'  => 'pdf',
            'application/zip'  => 'zip',
            'text/plain'       => 'txt',
            'text/html'        => 'html',
            default            => 'bin',
        };
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
     * #14: decode MIME-encoded-word headers (e.g. "=?utf-8?Q?=E2=80=94?=") so the
     * subject / sender name render as real text instead of the raw encoded string.
     * Safe on already-decoded values (returned unchanged).
     */
    private function decodeHeader(?string $value): string
    {
        if ($value === null || $value === '') return '';
        try {
            $decoded = mb_decode_mimeheader($value);
            return $decoded !== '' ? $decoded : $value;
        } catch (\Throwable $e) {
            return $value;
        }
    }

    /**
     * Allow access if user is admin (role=1) OR has permission 163.
     */
    private function authorizeMailbox(): void
    {
        $user = \Auth::user();
        if ((int) $user->role === 1) return;

        $ptype  = \App\user_setting::where('user_id', $user->id)->value('penal_type') ?? 1;
        // B6: accessForPanel() returns the same column value for panels 1-6 and reads the
        // link table for new dynamic panels (7+), so this works on every panel.
        $access = array_filter(explode(',', (string) $user->accessForPanel((int) $ptype)));
        if (!in_array('163', $access, true)) {
            abort(403, 'You do not have permission to access the mailbox.');
        }
    }
}
