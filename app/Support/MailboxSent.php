<?php

namespace App\Support;

use App\EmailMessage;
use Illuminate\Support\Str;

/**
 * Keeping the mailbox's Sent list and the mail server's Sent folder in step.
 *
 * Mail sent from the CRM is shown at once from a row the CRM writes itself, and a copy is saved to
 * the server's Sent folder. The sync then imports the folder from IMAP, skipping UIDs it has
 * already seen. It used to take that high-water mark from every row, and the CRM rows carried
 * uid = time() (~1.7 billion) while real UIDs are small, so after one send from the CRM nothing
 * else sent from that mailbox (webmail, phone, Outlook) was ever imported (client, 22 Oct 2026).
 *
 * Now:
 *  - CRM rows get a uid above 2^32. IMAP UIDs are 32-bit, so these can never collide with one and
 *    are left out of the high-water mark (so are the old time() rows, which have no message_id).
 *  - The mail, the server's copy and the CRM row share one Message-ID. When the sync meets the
 *    server's copy it takes over the CRM row instead of adding a second one.
 */
class MailboxSent
{
    /** First uid the CRM uses for its own rows: one past the largest possible IMAP UID. */
    public const LOCAL_UID_BASE = 4294967296;

    /** How far apart the CRM row and the server copy of an old (pre-Message-ID) send may be dated. */
    private const LEGACY_MATCH_MINUTES = 15;

    public static function newMessageId(string $fromEmail): string
    {
        $domain = Str::after($fromEmail, '@');

        return Str::lower(Str::random(32)) . '@' . ($domain !== '' && $domain !== $fromEmail ? $domain : 'localhost');
    }

    /** A uid for a CRM row; millisecond-based so two sends from one mailbox don't collide. */
    public static function localUid(): int
    {
        return self::LOCAL_UID_BASE + (int) floor(microtime(true) * 1000);
    }

    public static function isLocal(int $uid): bool
    {
        return $uid >= self::LOCAL_UID_BASE;
    }

    /** The highest UID the sync itself has imported into this folder (0 when none). */
    public static function lastImportedUid(int $folderId): int
    {
        return (int) EmailMessage::where('email_folder_id', $folderId)
            ->where('uid', '<', self::LOCAL_UID_BASE)
            ->whereNotNull('message_id')     // old CRM rows (uid = time()) have none; synced rows always do
            ->max('uid');
    }

    /**
     * Before the sync stores IMAP message $uid: if it is the server's copy of a mail the CRM
     * already listed, move the CRM row onto $uid so it is updated rather than duplicated.
     */
    public static function adoptCrmCopy(int $folderId, int $uid, ?string $messageId, ?string $subject, ?string $toEmail, ?string $dateAt): void
    {
        if (EmailMessage::where('email_folder_id', $folderId)->where('uid', $uid)->exists()) {
            return;
        }

        $messageId = trim((string) $messageId, " <>");
        $row = null;

        if ($messageId !== '') {
            $row = EmailMessage::where('email_folder_id', $folderId)
                ->where('uid', '>=', self::LOCAL_UID_BASE)
                ->where('message_id', $messageId)
                ->first();
        }

        // Sent before the CRM stored a Message-ID: same subject and recipient, sent within minutes.
        if (! $row && $dateAt && $toEmail) {
            try {
                $at = \Carbon\Carbon::parse($dateAt);
                $row = EmailMessage::where('email_folder_id', $folderId)
                    ->whereNull('message_id')
                    ->where('uid', '>=', 1000000000)
                    ->where('subject', (string) $subject)
                    ->where('to_email', $toEmail)
                    ->whereBetween('date_at', [
                        $at->copy()->subMinutes(self::LEGACY_MATCH_MINUTES),
                        $at->copy()->addMinutes(self::LEGACY_MATCH_MINUTES),
                    ])
                    ->orderBy('id')
                    ->first();
            } catch (\Throwable $e) {
                $row = null;
            }
        }

        if ($row) {
            $row->forceFill(['uid' => $uid])->save();
        }
    }
}
