<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\RingCentralUser;
use App\Services\RingCentralService;
use Exception;

class RingCentralBackfillRefreshExpiry extends Command
{
    protected $signature = 'ringcentral:backfill-refresh-expiry {--dry-run}';
    protected $description = 'Backfill refresh_token_expires_at from per-user token files';

    public function handle()
    {
        $dir = storage_path('ringcentral_tokens');
        if (!is_dir($dir)) {
            $this->warn("Tokens directory not found: {$dir}");
            return Command::SUCCESS;
        }

        $files = glob($dir . DIRECTORY_SEPARATOR . 'ringcentral_tokens_*.json') ?: [];
        if (empty($files)) {
            $this->info('No token files found to process.');
            return Command::SUCCESS;
        }

        $service = new RingCentralService();
        $users = RingCentralUser::whereNotNull('phone_number')->get();
        $usersByPhone = [];
        foreach ($users as $user) {
            $digits = preg_replace('/[^0-9]/', '', (string) $user->phone_number);
            if ($digits !== '' && !isset($usersByPhone[$digits])) {
                $usersByPhone[$digits] = $user;
            }
        }
        $updated = 0;
        $skipped = 0;
        $failed = 0;
        $dryRun = (bool) $this->option('dry-run');

        foreach ($files as $file) {
            try {
                if (!preg_match('/ringcentral_tokens_(\d+)\.json$/', $file, $matches)) {
                    $this->warn("Skipping unexpected file: {$file}");
                    $skipped++;
                    continue;
                }

                $phoneDigits = $matches[1];
                $contents = file_get_contents($file);
                $data = json_decode($contents, true);
                if (!is_array($data)) {
                    $this->warn("Invalid JSON for phone {$phoneDigits} in {$file}");
                    $skipped++;
                    continue;
                }

                $fileMtime = filemtime($file);
                $resolvedExpiry = $service->resolveRefreshTokenExpiresAt($data, $fileMtime);
                if (!$resolvedExpiry) {
                    $this->warn("No refresh expiry metadata for phone {$phoneDigits} in {$file}");
                    $skipped++;
                    continue;
                }

                $user = $usersByPhone[$phoneDigits] ?? null;
                if (!$user) {
                    $this->warn("R-Dialer user not found for phone {$phoneDigits}");
                    $skipped++;
                    continue;
                }

                $shouldUpdate = !$user->refresh_token_expires_at || $resolvedExpiry->gt($user->refresh_token_expires_at);
                if (!$shouldUpdate) {
                    $skipped++;
                    continue;
                }

                if ($dryRun) {
                    $this->info("[dry-run] Would update user_id {$user->user_id} refresh_token_expires_at to {$resolvedExpiry->toISOString()}");
                } else {
                    $user->update(['refresh_token_expires_at' => $resolvedExpiry]);
                    $this->info("Updated user_id {$user->user_id} refresh_token_expires_at to {$resolvedExpiry->toISOString()}");
                }

                $updated++;
            } catch (Exception $e) {
                $failed++;
                $this->error("Failed processing {$file}: {$e->getMessage()}");
            }
        }

        $this->info("Done. Updated: {$updated}, Skipped: {$skipped}, Failed: {$failed}");
        return Command::SUCCESS;
    }
}
