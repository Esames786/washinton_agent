<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\RingCentralUser;
use App\RingCentralCallLog;
use App\Services\RingCentralService;

class SyncRingCentralHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ringcentral:sync-history
                            {--count=100 : Items per page}
                            {--all : Fetch all historical data}
                            {--months= : Limit to last N months}
                            {--phone= : Filter by phone number (defaults to user phone)}
                            {--only= : Comma-separated scopes: calls,messages,voicemails,recordings}
                            {--skip-repair : Skip local repair of missing call from/to numbers}
                            {--skip-voicemails : Skip voicemail sync}
                            {--skip-recordings : Skip recordings sync}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync R-Dialer call logs, messages, voicemails, and recordings into the local database. Use --all to fetch complete history. Use --only for targeted scopes.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $log = Log::channel('ringcentral_sync_history');
        $count = (int) $this->option('count');
        $count = max(1, min($count, 200));
        $fetchAll = $this->option('all');
        $months = $this->option('months');
        $phone = $this->option('phone');
        $skipVoicemails = (bool) $this->option('skip-voicemails');
        $skipRecordings = (bool) $this->option('skip-recordings');
        $onlyRaw = (string) $this->option('only');
        $scopes = $this->parseSyncScopes($onlyRaw);
        if ($scopes === null) {
            $this->error('Invalid --only value. Allowed: calls,messages,voicemails,recordings');
            $log->warning('R-Dialer sync aborted due to invalid --only option', [
                'only' => $onlyRaw,
            ]);
            return 1;
        }

        $syncCalls = in_array('calls', $scopes, true);
        $syncMessages = in_array('messages', $scopes, true);
        $syncVoicemails = in_array('voicemails', $scopes, true) && !$skipVoicemails;
        $syncRecordings = in_array('recordings', $scopes, true) && !$skipRecordings;

        if (!$syncCalls && !$syncMessages && !$syncVoicemails && !$syncRecordings) {
            $this->warn('Nothing to sync after applying --only/skip flags.');
            $log->info('R-Dialer sync skipped - no scopes selected', [
                'only' => $onlyRaw,
                'skip_voicemails' => $skipVoicemails,
                'skip_recordings' => $skipRecordings,
            ]);
            return 0;
        }

        $service = app(RingCentralService::class);

        $users = RingCentralUser::where('is_active', true)->get();
        if ($users->isEmpty()) {
            $this->info('No active R-Dialer users to sync.');
            $log->info('R-Dialer sync skipped - no active users');
            return 0;
        }

        $phoneDigitsFilter = $this->normalizePhoneDigits($phone);
        $users = $users->filter(function ($rcUser) use ($phoneDigitsFilter) {
            if ($phoneDigitsFilter === '') {
                return true;
            }
            $userDigits = $this->normalizePhoneDigits($rcUser->phone_number);
            if ($userDigits === '') {
                return false;
            }
            return $userDigits === $phoneDigitsFilter
                || str_ends_with($userDigits, $phoneDigitsFilter)
                || str_ends_with($phoneDigitsFilter, $userDigits);
        });

        if ($users->isEmpty()) {
            $this->info('No active R-Dialer users matched the phone filter.');
            $log->info('R-Dialer sync skipped - no users matched phone filter', [
                'phone_filter' => $phone,
            ]);
            return 0;
        }

        $usersByPhone = $users->groupBy(function ($rcUser) {
            $digits = $this->normalizePhoneDigits($rcUser->phone_number);
            return $digits !== '' ? $digits : 'no-phone';
        });

        $syncTargets = $usersByPhone->map(function ($group, $phoneKey) {
            return [
                'phone_key' => $phoneKey,
                'primary' => $group->first(),
                'ringcentral_user_ids' => $group->pluck('id')->values()->all(),
            ];
        })->values();

        if ($fetchAll || $months) {
            $msg = 'Syncing R-Dialer history';
            if ($months) {
                $msg .= ' for last ' . $months . ' month(s)';
            }
            $msg .= ' for ' . $syncTargets->count() . ' phone number(s) (this may take a while)...';
            $this->info($msg);
        } else {
            $this->info('Syncing recent R-Dialer history for ' . $syncTargets->count() . ' phone number(s)...');
        }

        $log->info('R-Dialer sync started', [
            'count' => $count,
            'fetch_all' => (bool) $fetchAll,
            'months' => $months !== null ? (int) $months : null,
            'phone_filter' => $phone,
            'scopes' => $scopes,
            'target_phones' => $syncTargets->count(),
        ]);

        foreach ($syncTargets as $syncTarget) {
            /** @var \App\RingCentralUser $rcUser */
            $rcUser = $syncTarget['primary'];
            $ringCentralUserIds = $syncTarget['ringcentral_user_ids'] ?? [$rcUser->id];
            $userId = $rcUser->user_id;
            $targetPhone = $rcUser->phone_number ?: 'n/a';

            try {
                $this->line('-> Syncing phone=' . $targetPhone . ' (auth_user_id=' . $userId . ')');

                $filters = ['count' => $count];
                if ($fetchAll || $months) {
                    $filters['fetchAll'] = true;
                }
                if ($months) {
                    $filters['monthsBack'] = (int) $months;
                }
                if ($phone) {
                    $filters['phoneNumber'] = $phone;
                } elseif (!empty($rcUser->phone_number)) {
                    $filters['phoneNumber'] = $rcUser->phone_number;
                }

                $callResult = $syncCalls ? $service->getCallHistory($userId, $filters) : ['success' => true, 'count' => 0];
                $msgResult = $syncMessages ? $service->getMessageHistory($userId, $filters) : ['success' => true, 'count' => 0];
                $vmResult = $syncVoicemails ? $service->getVoicemails($userId, $filters) : ['success' => true, 'count' => 0];
                $recResult = $syncRecordings ? $service->getCallRecordings($userId, $filters) : ['success' => true, 'count' => 0];

                $repaired = 0;
                if ($syncCalls && !$this->option('skip-repair')) {
                    $repaired = $this->repairMissingCallNumbers($rcUser, $ringCentralUserIds);
                }

                $callsSynced = (int) ($callResult['total_synced'] ?? $callResult['count'] ?? 0);
                $messagesSynced = (int) ($msgResult['total_synced'] ?? $msgResult['count'] ?? 0);
                $voicemailsSynced = (int) ($vmResult['total_synced'] ?? $vmResult['count'] ?? 0);
                $recordingsSynced = (int) ($recResult['total_synced'] ?? $recResult['count'] ?? 0);

                if ($syncCalls && !($callResult['success'] ?? false)) {
                    $this->line('  ! Calls sync warning: ' . ($callResult['message'] ?? 'Unknown error'));
                }
                if ($syncMessages && !($msgResult['success'] ?? false)) {
                    $this->line('  ! Messages sync warning: ' . ($msgResult['message'] ?? 'Unknown error'));
                }
                if ($syncVoicemails && !($vmResult['success'] ?? false)) {
                    $this->line('  ! Voicemails sync warning: ' . ($vmResult['message'] ?? 'Unknown error'));
                }
                if ($syncRecordings && !($recResult['success'] ?? false)) {
                    $this->line('  ! Recordings sync warning: ' . ($recResult['message'] ?? 'Unknown error'));
                }

                $this->line(
                    '  + Synced calls=' . $callsSynced .
                    ', messages=' . $messagesSynced .
                    ', voicemails=' . $voicemailsSynced .
                    ', recordings=' . $recordingsSynced .
                    ', repaired_call_rows=' . $repaired
                );

                $log->info('R-Dialer sync target completed', [
                    'phone_number' => $targetPhone,
                    'auth_user_id' => $userId,
                    'calls_synced' => $callsSynced,
                    'messages_synced' => $messagesSynced,
                    'voicemails_synced' => $voicemailsSynced,
                    'recordings_synced' => $recordingsSynced,
                    'repaired_call_rows' => $repaired,
                ]);

                if ($syncVoicemails) {
                    $this->line(
                        '  + Voicemail detail: api_total_fetched=' . (int) ($vmResult['api_total_fetched'] ?? 0) .
                        ', local_before=' . (int) ($vmResult['local_db_total_before_sync'] ?? 0) .
                        ', local_after=' . (int) ($vmResult['local_db_total_after_sync'] ?? 0)
                    );
                }
            } catch (\Exception $e) {
                $log->warning('R-Dialer sync failed', [
                    'phone_number' => $targetPhone,
                    'auth_user_id' => $userId,
                    'error' => $e->getMessage(),
                ]);
                $this->line('  x Failed: ' . $e->getMessage());
            }
        }

        $this->info('R-Dialer sync completed.');
        $log->info('R-Dialer sync completed');
        return 0;
    }

    private function normalizePhoneDigits($phoneNumber)
    {
        return preg_replace('/\D/', '', (string) $phoneNumber);
    }

    /**
     * Parse --only argument into normalized scope list.
     * Returns null when invalid scope is provided.
     *
     * @param string $onlyRaw
     * @return array<string>|null
     */
    private function parseSyncScopes(string $onlyRaw): ?array
    {
        $allowed = ['calls', 'messages', 'voicemails', 'recordings'];
        $aliases = [
            'call' => 'calls',
            'message' => 'messages',
            'voicemail' => 'voicemails',
            'recording' => 'recordings',
        ];

        if (trim($onlyRaw) === '') {
            return $allowed;
        }

        $parts = array_filter(array_map(function ($item) {
            return strtolower(trim((string) $item));
        }, explode(',', $onlyRaw)));

        if (empty($parts)) {
            return $allowed;
        }

        $normalized = [];
        foreach ($parts as $part) {
            $scope = $aliases[$part] ?? $part;
            if (!in_array($scope, $allowed, true)) {
                return null;
            }
            $normalized[$scope] = true;
        }

        return array_keys($normalized);
    }

    private function repairMissingCallNumbers(RingCentralUser $rcUser, array $ringCentralUserIds): int
    {
        $userPhone = (string) ($rcUser->phone_number ?? '');

        $rows = RingCentralCallLog::query()
            ->whereIn('ringcentral_user_id', $ringCentralUserIds)
            ->where(function ($q) {
                $q->whereNull('from_number')
                    ->orWhere('from_number', '')
                    ->orWhereNull('to_number')
                    ->orWhere('to_number', '');
            })
            ->get();

        $updated = 0;

        foreach ($rows as $row) {
            $direction = strtolower((string) ($row->direction ?? ''));
            $legacyPhone = (string) ($row->phone_number ?? '');

            $newFrom = (string) ($row->from_number ?? '');
            $newTo = (string) ($row->to_number ?? '');

            if ($newFrom === '' || $newTo === '') {
                if ($direction === 'outbound') {
                    $newFrom = $newFrom !== '' ? $newFrom : ($userPhone !== '' ? $userPhone : $legacyPhone);
                    $newTo = $newTo !== '' ? $newTo : ($legacyPhone !== '' ? $legacyPhone : $userPhone);
                } else {
                    $newFrom = $newFrom !== '' ? $newFrom : ($legacyPhone !== '' ? $legacyPhone : $userPhone);
                    $newTo = $newTo !== '' ? $newTo : ($userPhone !== '' ? $userPhone : $legacyPhone);
                }
            }

            if ($newFrom !== (string) ($row->from_number ?? '') || $newTo !== (string) ($row->to_number ?? '')) {
                $row->from_number = $newFrom !== '' ? $newFrom : null;
                $row->to_number = $newTo !== '' ? $newTo : null;
                $row->save();
                $updated++;
            }
        }

        return $updated;
    }
}
