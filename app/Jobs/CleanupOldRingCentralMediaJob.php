<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * R-Dialer retention: drop voicemail rows + audio files and recording/attachment files
 * older than the retention window. Invoked daily by `ringcentral:cleanup-old-media`
 * (App\Console\Commands\CleanupOldRingCentralMedia) via dispatchSync($days), which
 * expects the summary array returned from handle().
 *
 * Only files under the public disk's `ringcentral_attachments/` folder (recordings,
 * SMS attachments) and voicemail `audio_file_path`s are ever touched.
 */
class CleanupOldRingCentralMediaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public const MEDIA_DIR = 'ringcentral_attachments';

    /** @var int */
    protected $days;

    public function __construct($days = 30)
    {
        $this->days = max(1, (int) $days);
    }

    public function handle(): array
    {
        $cutoff = Carbon::now()->subDays($this->days);
        $disk   = Storage::disk('public');

        $result = [
            'retention_days'          => $this->days,
            'voicemails_deleted'      => 0,
            'voicemail_files_deleted' => 0,
            'recordings_cleaned'      => 0,
            'recording_files_deleted' => 0,
        ];

        // 1) Voicemails older than the window: remove audio file, then the row.
        DB::table('ringcentral_voicemails')
            ->where(function ($q) use ($cutoff) {
                $q->where('received_at', '<', $cutoff)
                  ->orWhere(function ($q2) use ($cutoff) {
                      $q2->whereNull('received_at')->where('created_at', '<', $cutoff);
                  });
            })
            ->orderBy('id')
            ->chunkById(200, function ($rows) use (&$result, $disk) {
                foreach ($rows as $row) {
                    $path = $this->normalizePublicPath($row->audio_file_path ?? '');
                    if ($path !== '' && $disk->exists($path)) {
                        try {
                            $disk->delete($path);
                            $result['voicemail_files_deleted']++;
                        } catch (\Throwable $e) {
                            Log::warning('R-Dialer cleanup: voicemail file delete failed', ['path' => $path, 'error' => $e->getMessage()]);
                        }
                    }
                    DB::table('ringcentral_voicemails')->where('id', $row->id)->delete();
                    $result['voicemails_deleted']++;
                }
            });

        // 2) Call recordings older than the window that were downloaded locally.
        DB::table('ringcentral_call_logs')
            ->whereNotNull('recording_url')
            ->where('recording_url', 'like', '%' . self::MEDIA_DIR . '%')
            ->where(function ($q) use ($cutoff) {
                $q->where('call_started_at', '<', $cutoff)
                  ->orWhere(function ($q2) use ($cutoff) {
                      $q2->whereNull('call_started_at')->where('created_at', '<', $cutoff);
                  });
            })
            ->orderBy('id')
            ->chunkById(200, function ($rows) use (&$result, $disk) {
                foreach ($rows as $row) {
                    $path = $this->normalizePublicPath($row->recording_url);
                    if ($path !== '' && $disk->exists($path)) {
                        try {
                            $disk->delete($path);
                            $result['recording_files_deleted']++;
                        } catch (\Throwable $e) {
                            Log::warning('R-Dialer cleanup: recording file delete failed', ['path' => $path, 'error' => $e->getMessage()]);
                        }
                    }
                    DB::table('ringcentral_call_logs')->where('id', $row->id)->update(['recording_url' => null]);
                    $result['recordings_cleaned']++;
                }
            });

        // 3) Orphan sweep: any file in the media folder older than the window.
        try {
            foreach ($disk->allFiles(self::MEDIA_DIR) as $file) {
                $mtime = $disk->lastModified($file);
                if ($mtime && $mtime < $cutoff->getTimestamp()) {
                    $disk->delete($file);
                    $result['recording_files_deleted']++;
                }
            }
        } catch (\Throwable $e) {
            Log::warning('R-Dialer cleanup: media folder sweep failed', ['error' => $e->getMessage()]);
        }

        Log::info('R-Dialer cleanup finished', $result);

        return $result;
    }

    /**
     * Reduce a stored value (relative path, /storage/... URL, or attachment proxy URL with
     * ?path=) to a path relative to the public disk. Returns '' when it is not a local
     * media file we own.
     */
    protected function normalizePublicPath($value): string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return '';
        }

        // Proxy URL: .../ringcentral/attachment?path=ringcentral_attachments/...
        $query = parse_url($value, PHP_URL_QUERY);
        if ($query) {
            parse_str($query, $qs);
            if (!empty($qs['path'])) {
                $value = $qs['path'];
            }
        }

        $path = parse_url($value, PHP_URL_PATH) ?: $value;
        $path = ltrim($path, '/');
        if (strpos($path, 'storage/') === 0) {
            $path = substr($path, strlen('storage/'));
        }

        // Safety: only ever touch our own media folder.
        if (strpos($path, '..') !== false || strpos($path, self::MEDIA_DIR . '/') !== 0) {
            return '';
        }

        return $path;
    }
}
