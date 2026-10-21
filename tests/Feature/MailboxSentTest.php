<?php

namespace Tests\Feature;

use App\EmailAccount;
use App\EmailFolder;
use App\EmailMessage;
use App\Support\MailboxSent;
use App\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Sent items (client, 22 Oct 2026: agents' mail "not showing in sent items"). The CRM's own Sent
 * rows must not stop the sync importing the server's Sent folder, and the server's copy of a CRM
 * send must replace the CRM row instead of doubling it. Runs against the local hello_devtest copy.
 */
class MailboxSentTest extends TestCase
{
    private array $users = [];
    private array $accounts = [];

    protected function tearDown(): void
    {
        // Query builder, not the models: accounts and messages soft-delete.
        $messages = DB::table('email_messages')->whereIn('email_account_id', $this->accounts)->pluck('id');
        DB::table('email_message_attachments')->whereIn('email_message_id', $messages)->delete();
        DB::table('email_messages')->whereIn('id', $messages)->delete();
        DB::table('email_folders')->whereIn('email_account_id', $this->accounts)->delete();
        DB::table('email_accounts')->whereIn('id', $this->accounts)->delete();
        DB::table('user_devices')->whereIn('user_id', $this->users)->delete();
        DB::table('user')->whereIn('id', $this->users)->delete();
        parent::tearDown();
    }

    /** An agent with a mailbox whose server can't be reached (127.0.0.1:1), and its Sent folder. */
    private function mailbox(): array
    {
        $u = User::findOrFail(373)->replicate();
        $u->forceFill([
            'email' => 'mailbox-test-' . uniqid() . '@example.test', 'role' => 2, 'status' => 1, 'deleted' => 0,
            'nda_required' => 0, 'ip_check_enabled' => 0, 'current_session_id' => null, 'device_policy' => 'off',
        ])->save();
        $this->users[] = $u->id;

        $local = 'mbtest' . uniqid();
        $acc = EmailAccount::create([
            'user_id' => $u->id, 'email' => "$local@example.test", 'local_part' => $local, 'domain' => 'example.test',
            'username' => "$local@example.test", 'password_enc' => Crypt::encryptString('x'), 'status' => 'active',
            'imap_host' => '127.0.0.1', 'imap_port' => 1, 'imap_encryption' => 'notls',
            'smtp_host' => '127.0.0.1', 'smtp_port' => 1, 'smtp_encryption' => null,
        ]);
        $this->accounts[] = $acc->id;
        $sent = EmailFolder::create(['email_account_id' => $acc->id, 'key' => 'Sent', 'label' => 'Sent', 'imap_name' => 'Sent']);

        return [$u->fresh(), $acc, $sent];
    }

    private function row(EmailFolder $f, int $uid, ?string $messageId, array $extra = []): EmailMessage
    {
        return EmailMessage::create(array_merge([
            'email_account_id' => $f->email_account_id, 'email_folder_id' => $f->id, 'uid' => $uid,
            'message_id' => $messageId, 'subject' => 'Quote', 'to_email' => 'customer@example.test',
            'date_at' => now(), 'seen' => true,
        ], $extra));
    }

    public function test_the_crms_own_rows_do_not_block_the_sync(): void
    {
        [, , $sent] = $this->mailbox();
        $this->row($sent, 7, 'synced-7@example.test');
        $this->row($sent, 12, '');                                  // synced, server gave no Message-ID
        $this->row($sent, 1761100000, null);                        // old CRM row: uid = time()
        $this->row($sent, MailboxSent::localUid(), 'crm@example.test');

        $this->assertSame(12, MailboxSent::lastImportedUid($sent->id));
    }

    public function test_the_server_copy_takes_over_the_crm_row_by_message_id(): void
    {
        [, , $sent] = $this->mailbox();
        $crm = $this->row($sent, MailboxSent::localUid(), 'abc123@example.test', ['body_html' => '<p>hi</p>']);

        MailboxSent::adoptCrmCopy($sent->id, 31, '<abc123@example.test>', 'Quote', 'customer@example.test', now()->toDateTimeString());

        $this->assertSame(31, (int) $crm->fresh()->uid);
        $this->assertSame('<p>hi</p>', $crm->fresh()->body_html);
        $this->assertSame(1, EmailMessage::where('email_folder_id', $sent->id)->count());
    }

    public function test_an_old_crm_row_is_matched_by_subject_recipient_and_time(): void
    {
        [, , $sent] = $this->mailbox();
        $old = $this->row($sent, 1761100000, null, ['date_at' => now()->subMinutes(3)]);
        $otherRecipient = $this->row($sent, 1761100001, null, ['to_email' => 'someone.else@example.test']);

        MailboxSent::adoptCrmCopy($sent->id, 40, 'server-generated@example.test', 'Quote', 'customer@example.test', now()->toDateTimeString());

        $this->assertSame(40, (int) $old->fresh()->uid);
        $this->assertSame(1761100001, (int) $otherRecipient->fresh()->uid);
    }

    public function test_nothing_is_taken_over_without_a_match_or_when_the_uid_is_already_stored(): void
    {
        [, , $sent] = $this->mailbox();
        $crm = $this->row($sent, MailboxSent::localUid(), 'mine@example.test');
        $this->row($sent, 50, 'already@example.test');

        MailboxSent::adoptCrmCopy($sent->id, 51, 'webmail-send@example.test', 'Other subject', 'x@example.test', now()->toDateTimeString());
        MailboxSent::adoptCrmCopy($sent->id, 50, 'mine@example.test', 'Quote', 'customer@example.test', now()->toDateTimeString());

        $this->assertTrue(MailboxSent::isLocal((int) $crm->fresh()->uid));
    }

    public function test_a_send_stores_a_crm_row_with_the_mails_own_message_id(): void
    {
        [$agent, $acc, $sent] = $this->mailbox();
        // Resolve the controller's mailer as an in-memory one first; it is reused when the
        // controller asks for it, so nothing is sent anywhere.
        config(['mail.mailers.mailbox_smtp' => ['transport' => 'array']]);
        $transport = Mail::mailer('mailbox_smtp')->getSymfonyTransport();

        $this->actingAs($agent)->post('/mailbox/send', [
            'to' => 'customer@example.test', 'subject' => 'Your quote', 'body' => '<p>Price inside</p>',
        ])->assertSessionHas('success');

        $row = EmailMessage::where('email_folder_id', $sent->id)->sole();
        $this->assertTrue(MailboxSent::isLocal((int) $row->uid));
        $this->assertSame('Your quote', $row->subject);

        $sentMail = $transport->messages()->sole()->getOriginalMessage();
        $this->assertSame($row->message_id, trim($sentMail->getHeaders()->get('Message-ID')->getBodyAsString(), '<>'));
        $this->assertSame(0, MailboxSent::lastImportedUid($sent->id));
    }
}
