<?php

namespace App\Console\Commands;

use App\Jobs\CleanupOldRingCentralMediaJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CleanupOldRingCentralMedia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ringcentral:cleanup-old-media
                            {--days=30 : Keep only the last N days of voicemail/recording data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete R-Dialer voicemail data and recording files older than the retention period.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $days = max(1, (int) $this->option('days'));

        try {
            $result = CleanupOldRingCentralMediaJob::dispatchSync($days);

            $this->info(
                'R-Dialer cleanup completed: ' .
                'retention_days=' . ($result['retention_days'] ?? $days) .
                ', voicemails_deleted=' . ($result['voicemails_deleted'] ?? 0) .
                ', voicemail_files_deleted=' . ($result['voicemail_files_deleted'] ?? 0) .
                ', recordings_cleaned=' . ($result['recordings_cleaned'] ?? 0) .
                ', recording_files_deleted=' . ($result['recording_files_deleted'] ?? 0)
            );

            return 0;
        } catch (\Throwable $e) {
            Log::error('R-Dialer cleanup command failed', [
                'days' => $days,
                'error' => $e->getMessage(),
            ]);

            $this->error('R-Dialer cleanup failed: ' . $e->getMessage());
            return 1;
        }
    }
}
