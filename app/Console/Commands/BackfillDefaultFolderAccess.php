<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class BackfillDefaultFolderAccess extends Command
{
    /**
     * #18 (2026-07-03): grant existing agents the default New -> Delivered folder
     * access. Idempotent — safe to run multiple times.
     */
    protected $signature = 'agents:default-folder-access
                            {--role=2 : Which role id to backfill (2 = agent / Order Taker)}';

    protected $description = 'Backfill default New->Delivered folder access for existing agents (#18).';

    public function handle(): int
    {
        $role = (int) $this->option('role');
        $count = 0;

        User::where('role', $role)->chunkById(100, function ($users) use (&$count) {
            foreach ($users as $user) {
                $user->applyDefaultFolderAccess();
                $count++;
            }
        });

        $this->info("Default folder access applied to {$count} user(s) with role {$role}.");
        return self::SUCCESS;
    }
}
