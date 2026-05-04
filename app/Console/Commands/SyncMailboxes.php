<?php

namespace App\Console\Commands;

use App\Jobs\SyncMailboxFolderJob;
use App\EmailAccount;
use App\EmailFolder;
use Illuminate\Console\Command;

class SyncMailboxes extends Command
{
    protected $signature   = 'mailbox:sync {--account_id=} {--folder=}';
    protected $description = 'Queue mailbox sync jobs for all active email accounts';

    public function handle(): int
    {
        $accountId = $this->option('account_id');
        $folderKey = $this->option('folder');

        $query = EmailAccount::where('status', 'active');
        if ($accountId) {
            $query->where('id', $accountId);
        }

        $accounts = $query->get();

        if ($accounts->isEmpty()) {
            $this->info('No active email accounts found.');
            return 0;
        }

        foreach ($accounts as $acc) {
            $foldersQuery = EmailFolder::where('email_account_id', $acc->id);
            if ($folderKey) {
                $foldersQuery->where('key', $folderKey);
            }
            $folders = $foldersQuery->get();

            // Seed default folders if none exist yet
            if ($folders->isEmpty()) {
                $defaults = [
                    ['key' => 'INBOX',  'label' => 'Inbox',  'imap_name' => 'INBOX'],
                    ['key' => 'Sent',   'label' => 'Sent',   'imap_name' => 'Sent'],
                    ['key' => 'Drafts', 'label' => 'Drafts', 'imap_name' => 'Drafts'],
                    ['key' => 'Junk',   'label' => 'Junk',   'imap_name' => 'Junk'],
                    ['key' => 'Trash',  'label' => 'Trash',  'imap_name' => 'Trash'],
                ];
                foreach ($defaults as $d) {
                    EmailFolder::updateOrCreate(
                        ['email_account_id' => $acc->id, 'key' => $d['key']],
                        ['label' => $d['label'], 'imap_name' => $d['imap_name']]
                    );
                }
                $folders = EmailFolder::where('email_account_id', $acc->id)->get();
            }

            foreach ($folders as $folder) {
                dispatch(new SyncMailboxFolderJob($acc->id, $folder->key));
                $this->line("  Queued sync: account #{$acc->id} ({$acc->email}) → {$folder->key}");
            }
        }

        $this->info('Mailbox sync jobs dispatched for ' . $accounts->count() . ' account(s).');
        return 0;
    }
}
