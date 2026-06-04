<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RingCentralSchedulerHealth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ringcentral:scheduler-health
                            {--tail=5 : Number of latest non-empty log lines to show per file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show recent scheduler health for R-Dialer sync and cleanup tasks.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tail = max(1, (int) $this->option('tail'));

        $logs = [
            'sync' => storage_path('logs/ringcentral-sync.log'),
            'cleanup' => storage_path('logs/ringcentral-cleanup.log'),
        ];

        $this->line('R-Dialer Scheduler Health');
        $this->line('===========================');

        foreach ($logs as $name => $path) {
            $this->line('');
            $this->line(strtoupper($name) . ' LOG');
            $this->line('Path: ' . $path);

            if (!is_file($path)) {
                $this->warn('Status: no log file yet (task may not have run yet).');
                continue;
            }

            $lastModified = date('Y-m-d H:i:s', filemtime($path));
            $sizeKb = round(filesize($path) / 1024, 2);
            $this->info('Status: found');
            $this->line('Last modified: ' . $lastModified);
            $this->line('Size: ' . $sizeKb . ' KB');

            $lines = $this->tailNonEmptyLines($path, $tail);
            if (empty($lines)) {
                $this->warn('Recent output: (empty)');
                continue;
            }

            $this->line('Recent output:');
            foreach ($lines as $line) {
                $this->line(' - ' . $line);
            }
        }

        return 0;
    }

    /**
     * Return the latest non-empty lines from a file.
     *
     * @param string $path
     * @param int $tail
     * @return array<int, string>
     */
    private function tailNonEmptyLines(string $path, int $tail): array
    {
        $all = @file($path, FILE_IGNORE_NEW_LINES);
        if (!is_array($all)) {
            return [];
        }

        $all = array_values(array_filter($all, function ($line) {
            return trim((string) $line) !== '';
        }));

        if (empty($all)) {
            return [];
        }

        return array_slice($all, -$tail);
    }
}
