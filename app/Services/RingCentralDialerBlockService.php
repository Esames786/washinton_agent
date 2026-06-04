<?php

namespace App\Services;

use App\RingCentralDialerBlockedNumber;
use App\RingCentralUser;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class RingCentralDialerBlockService
{
    public function normalizeNanpToE164($value): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $value);
        if (!$digits) {
            return null;
        }

        $candidates = [];
        $len = strlen($digits);

        if ($len >= 10) {
            $candidates[] = substr($digits, -10);
        }
        if ($len >= 11 && str_starts_with($digits, '1')) {
            $candidates[] = substr($digits, 1, 10);
            $candidates[] = substr($digits, -10);
        }

        $candidates = array_values(array_unique(array_filter($candidates, static function ($item) {
            return is_string($item) && strlen($item) === 10;
        })));

        foreach ($candidates as $local) {
            if (!preg_match('/^[2-9]\d{2}[2-9]\d{6}$/', $local)) {
                continue;
            }
            if (substr($local, 1, 2) === '11' || substr($local, 4, 2) === '11') {
                continue;
            }

            return '+1' . $local;
        }

        return null;
    }

    public function getBlockedMapForRingCentralUsers(array $ringCentralUserIds): array
    {
        $ringCentralUserIds = array_values(array_unique(array_filter(array_map('intval', $ringCentralUserIds))));
        if (empty($ringCentralUserIds)) {
            return [];
        }

        $rows = RingCentralDialerBlockedNumber::query()
            ->whereIn('ringcentral_user_id', $ringCentralUserIds)
            ->where('is_active', true)
            ->get(['ringcentral_user_id', 'normalized_e164', 'id']);

        $map = [];
        foreach ($rows as $row) {
            $rid = (int) $row->ringcentral_user_id;
            $num = (string) $row->normalized_e164;
            if ($num === '') {
                continue;
            }
            if (!isset($map[$rid])) {
                $map[$rid] = [];
            }
            $map[$rid][$num] = (int) $row->id;
        }

        return $map;
    }

    public function isBlockedForRingCentralUser(int $ringCentralUserId, ?string $phone): bool
    {
        $normalized = $this->normalizeNanpToE164($phone);
        if (!$normalized || $ringCentralUserId <= 0) {
            return false;
        }

        return RingCentralDialerBlockedNumber::query()
            ->where('ringcentral_user_id', $ringCentralUserId)
            ->where('normalized_e164', $normalized)
            ->where('is_active', true)
            ->exists();
    }

    public function upsertBlock(RingCentralUser $ringCentralUser, string $rawInput, ?string $reason, int $blockedByUserId): array
    {
        $normalized = $this->normalizeNanpToE164($rawInput);
        if (!$normalized) {
            return [
                'success' => false,
                'message' => 'Invalid phone number. Use +1XXXXXXXXXX or 10 digits.',
                'status' => 422,
            ];
        }

        $existing = RingCentralDialerBlockedNumber::query()
            ->where('ringcentral_user_id', $ringCentralUser->id)
            ->where('normalized_e164', $normalized)
            ->where('is_active', true)
            ->first();

        if ($existing) {
            return [
                'success' => true,
                'created' => false,
                'record' => $existing,
                'normalized_e164' => $normalized,
            ];
        }

        $inactive = RingCentralDialerBlockedNumber::query()
            ->where('ringcentral_user_id', $ringCentralUser->id)
            ->where('normalized_e164', $normalized)
            ->where('is_active', false)
            ->orderByDesc('id')
            ->first();

        if ($inactive) {
            // Keep one canonical historical row and remove older inactive duplicates
            RingCentralDialerBlockedNumber::query()
                ->where('ringcentral_user_id', $ringCentralUser->id)
                ->where('normalized_e164', $normalized)
                ->where('is_active', false)
                ->where('id', '!=', $inactive->id)
                ->delete();

            $inactive->raw_input = trim((string) $rawInput);
            $inactive->reason = trim((string) $reason) ?: null;
            $inactive->blocked_by_user_id = $blockedByUserId > 0 ? $blockedByUserId : null;
            $inactive->is_active = true;
            $inactive->save();

            return [
                'success' => true,
                'created' => false,
                'record' => $inactive,
                'normalized_e164' => $normalized,
            ];
        }

        try {
            $row = RingCentralDialerBlockedNumber::create([
                'ringcentral_user_id' => $ringCentralUser->id,
                'normalized_e164' => $normalized,
                'raw_input' => trim((string) $rawInput),
                'reason' => trim((string) $reason) ?: null,
                'blocked_by_user_id' => $blockedByUserId > 0 ? $blockedByUserId : null,
                'is_active' => true,
            ]);
        } catch (QueryException $e) {
            // Race-safe fallback for unique key (ringcentral_user_id, normalized_e164, is_active)
            // when two requests try to block the same number at the same time.
            $isDuplicateKey = ((int) $e->getCode() === 23000)
                || (stripos($e->getMessage(), 'Duplicate entry') !== false);
            if (!$isDuplicateKey) {
                throw $e;
            }

            $row = RingCentralDialerBlockedNumber::query()
                ->where('ringcentral_user_id', $ringCentralUser->id)
                ->where('normalized_e164', $normalized)
                ->where('is_active', true)
                ->orderByDesc('id')
                ->first();

            if (!$row) {
                throw $e;
            }

            return [
                'success' => true,
                'created' => false,
                'record' => $row,
                'normalized_e164' => $normalized,
            ];
        }

        return [
            'success' => true,
            'created' => true,
            'record' => $row,
            'normalized_e164' => $normalized,
        ];
    }

    public function unblockById(int $ringCentralUserId, int $id): bool
    {
        if ($ringCentralUserId <= 0 || $id <= 0) {
            return false;
        }

        $row = RingCentralDialerBlockedNumber::query()
            ->where('ringcentral_user_id', $ringCentralUserId)
            ->where('id', $id)
            ->where('is_active', true)
            ->first();

        if (!$row) {
            return false;
        }

        $row->is_active = false;
        $row->save();
        return true;
    }

    public function unblockByNumber(int $ringCentralUserId, string $rawInput): bool
    {
        $normalized = $this->normalizeNanpToE164($rawInput);
        if ($ringCentralUserId <= 0 || !$normalized) {
            return false;
        }

        $row = RingCentralDialerBlockedNumber::query()
            ->where('ringcentral_user_id', $ringCentralUserId)
            ->where('normalized_e164', $normalized)
            ->where('is_active', true)
            ->first();

        if (!$row) {
            return false;
        }

        $row->is_active = false;
        $row->save();
        return true;
    }

    public function listForRingCentralUser(int $ringCentralUserId, string $search = '')
    {
        $query = RingCentralDialerBlockedNumber::query()
            ->where('ringcentral_user_id', $ringCentralUserId)
            ->where('is_active', true)
            ->orderByDesc('id');

        $search = trim($search);
        if ($search !== '') {
            $query->where(function ($sub) use ($search) {
                $sub->where('normalized_e164', 'like', "%{$search}%")
                    ->orWhere('raw_input', 'like', "%{$search}%")
                    ->orWhere('reason', 'like', "%{$search}%");
            });
        }

        return $query->get();
    }

    public function syncFromRingCentralSnapshot(RingCentralUser $ringCentralUser, array $snapshot, ?int $actorUserId = null): array
    {
        $remoteRecords = data_get($snapshot, 'records', []);
        if (!is_array($remoteRecords)) {
            $remoteRecords = [];
        }

        $remoteBlockedByNormalized = [];
        foreach ($remoteRecords as $record) {
            $status = strtolower((string) data_get($record, 'status', ''));
            if ($status !== 'blocked') {
                continue;
            }
            $phone = (string) data_get($record, 'phoneNumber', '');
            $normalized = $this->normalizeNanpToE164($phone);
            if (!$normalized) {
                // Keep sync stable; skip non-NANP numbers since dialer logic is NANP-only.
                Log::info('RC_DIALER_BLOCK_SYNC skip non-NANP caller-blocking number.', [
                    'ringcentral_user_id' => (int) $ringCentralUser->id,
                    'phone' => $phone,
                    'rule_id' => (string) data_get($record, 'id', ''),
                ]);
                continue;
            }
            $remoteBlockedByNormalized[$normalized] = [
                'rule_id' => (string) data_get($record, 'id', ''),
                'raw_input' => $phone,
            ];
        }

        $created = 0;
        $updated = 0;
        $deactivated = 0;

        foreach ($remoteBlockedByNormalized as $normalized => $remote) {
            $row = RingCentralDialerBlockedNumber::query()
                ->where('ringcentral_user_id', (int) $ringCentralUser->id)
                ->where('normalized_e164', $normalized)
                ->orderByDesc('is_active')
                ->orderByDesc('id')
                ->first();

            if (!$row) {
                RingCentralDialerBlockedNumber::create([
                    'ringcentral_user_id' => (int) $ringCentralUser->id,
                    'normalized_e164' => $normalized,
                    'raw_input' => (string) ($remote['raw_input'] ?? $normalized),
                    'reason' => 'Synced from R-Dialer account settings',
                    'blocked_by_user_id' => $actorUserId && $actorUserId > 0 ? $actorUserId : null,
                    'ringcentral_rule_id' => (string) ($remote['rule_id'] ?? '') ?: null,
                    'ringcentral_sync_status' => 'synced',
                    'ringcentral_sync_error' => null,
                    'ringcentral_synced_at' => now(),
                    'is_active' => true,
                ]);
                $created++;
                continue;
            }

            $dirty = false;
            if (!$row->is_active) {
                $row->is_active = true;
                $dirty = true;
            }
            $incomingRuleId = (string) ($remote['rule_id'] ?? '');
            if ($incomingRuleId !== '' && (string) ($row->ringcentral_rule_id ?? '') !== $incomingRuleId) {
                $row->ringcentral_rule_id = $incomingRuleId;
                $dirty = true;
            }
            if ((string) ($row->ringcentral_sync_status ?? '') !== 'synced') {
                $row->ringcentral_sync_status = 'synced';
                $dirty = true;
            }
            if (!is_null($row->ringcentral_sync_error)) {
                $row->ringcentral_sync_error = null;
                $dirty = true;
            }
            $row->ringcentral_synced_at = now();
            $dirty = true;

            if ($dirty) {
                $row->save();
                $updated++;
            }
        }

        $activeRows = RingCentralDialerBlockedNumber::query()
            ->where('ringcentral_user_id', (int) $ringCentralUser->id)
            ->where('is_active', true)
            ->get();

        foreach ($activeRows as $row) {
            $normalized = (string) $row->normalized_e164;
            if (isset($remoteBlockedByNormalized[$normalized])) {
                continue;
            }

            $row->is_active = false;
            $row->ringcentral_sync_status = 'unsynced';
            $row->ringcentral_sync_error = null;
            $row->ringcentral_synced_at = now();
            $row->save();
            $deactivated++;
        }

        return [
            'created' => $created,
            'updated' => $updated,
            'deactivated' => $deactivated,
            'remote_count' => count($remoteBlockedByNormalized),
        ];
    }
}

