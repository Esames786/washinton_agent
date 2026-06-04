<?php

namespace App\Http\Controllers;

use App\RingCentralCallLog;
use App\RingCentralDialerBlockedNumber;
use App\RingCentralMessage;
use App\RingCentralUser;
use App\RingCentralVoicemail;
use App\Services\RingCentralDialerBlockService;
use App\Services\RingCentralService;
use App\Support\VoicemailOnlyLog;
use App\Models\WebPhoneInstance;
use App\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RingCentralApiController extends Controller
{
    private $ringCentralService;
    private $dialerBlockService;
    
    /**
     * Maximum number of simultaneous WebPhone instances allowed per subscription
     * This can be set dynamically based on subscription packages in the future
     * For now, set manually for testing: 2, 5, 10, etc.
     */
    private static $maxInstancesAllowed = 2;

    public function __construct(RingCentralService $ringCentralService, RingCentralDialerBlockService $dialerBlockService)
    {
        $this->ringCentralService = $ringCentralService;
        $this->dialerBlockService = $dialerBlockService;
    }
    
    /**
     * Set the maximum number of instances allowed for testing/configuration
     * 
     * @param int $maxInstances Maximum instances to allow
     */
    public static function setMaxInstances($maxInstances)
    {
        self::$maxInstancesAllowed = max(1, (int) $maxInstances);
    }
    
    /**
     * Get the current maximum number of instances allowed
     * 
     * @return int
     */
    public static function getMaxInstances()
    {
        return self::$maxInstancesAllowed;
    }


    /**
     * Send SMS message
     */
    public function sendSMS(Request $request)
    {
        try {
            $request->validate([
                'from_number' => 'required|string',
                'to_number' => 'nullable|string|required_without:to_numbers',
                'to_numbers' => 'nullable|array|required_without:to_number|max:10',
                'to_numbers.*' => 'required|string',
                'create_group_text' => 'nullable|boolean',
                'group_name' => 'nullable|string|max:120',
                'message' => 'nullable|string',
                'attachments' => 'nullable|array',
                'attachments.*' => 'file|max:150000', // max 1.5MB per file, adjust as needed
                'forwarded_attachments' => 'nullable',
            ]);

            $rawRecipients = [];
            if ($request->filled('to_number')) {
                $rawRecipients[] = (string) $request->input('to_number');
            }
            $toNumbersInput = $request->input('to_numbers', []);
            if (is_array($toNumbersInput)) {
                foreach ($toNumbersInput as $num) {
                    $rawRecipients[] = (string) $num;
                }
            }

            $normalizedRecipients = [];
            $invalidRecipients = [];
            foreach ($rawRecipients as $rawRecipient) {
                $normalized = $this->normalizeNanpPhoneNumber($rawRecipient);
                if (!$normalized) {
                    if (trim((string) $rawRecipient) !== '') {
                        $invalidRecipients[] = (string) $rawRecipient;
                    }
                    continue;
                }
                if (!in_array($normalized, $normalizedRecipients, true)) {
                    $normalizedRecipients[] = $normalized;
                }
            }

            if (!empty($invalidRecipients)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid phone number. Use +1XXXXXXXXXX or 10 digits.',
                    'errors' => [
                        'to_numbers' => ['Invalid phone number(s): ' . implode(', ', $invalidRecipients)],
                    ],
                ], 422);
            }
            if (empty($normalizedRecipients)) {
                return response()->json([
                    'success' => false,
                    'message' => 'At least one valid phone number is required.',
                    'errors' => [
                        'to_numbers' => ['At least one valid phone number is required.'],
                    ],
                ], 422);
            }
            if (count($normalizedRecipients) > 10) {
                return response()->json([
                    'success' => false,
                    'message' => 'A maximum of 10 recipients is allowed.',
                    'errors' => [
                        'to_numbers' => ['A maximum of 10 recipients is allowed.'],
                    ],
                ], 422);
            }

            $createGroupText = filter_var($request->input('create_group_text', false), FILTER_VALIDATE_BOOLEAN);
            $groupName = trim((string) $request->input('group_name', ''));

            $normalizedFrom = $this->normalizeNanpPhoneNumber($request->from_number);
            if (!$normalizedFrom) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid "Text from" number. Please reconnect RingCentral.',
                    'errors' => [
                        'from_number' => ['Invalid "Text from" number.'],
                    ],
                ], 422);
            }

            $ringCentralUser = RingCentralUser::where('user_id', Auth::id())->first();
            if (!$ringCentralUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not connected to R-Portal',
                ], 404);
            }

            $forwardedAttachments = $request->input('forwarded_attachments', []);
            if (is_string($forwardedAttachments)) {
                $decodedForwarded = json_decode($forwardedAttachments, true);
                $forwardedAttachments = is_array($decodedForwarded) ? $decodedForwarded : [];
            }
            if (!is_array($forwardedAttachments)) {
                $forwardedAttachments = [];
            }

            $forwardedAttachments = array_values(array_filter(array_map(function ($att) {
                if (!is_array($att)) return null;
                return [
                    'fileName' => isset($att['fileName']) ? (string) $att['fileName'] : (isset($att['filename']) ? (string) $att['filename'] : null),
                    'contentType' => isset($att['contentType']) ? (string) $att['contentType'] : null,
                    'size' => isset($att['size']) ? (int) $att['size'] : null,
                    'path' => isset($att['path']) ? (string) $att['path'] : (isset($att['stored_path']) ? (string) $att['stored_path'] : ''),
                    'uri' => isset($att['uri']) ? (string) $att['uri'] : (isset($att['contentUri']) ? (string) $att['contentUri'] : ''),
                ];
            }, $forwardedAttachments), function ($att) {
                if (!$att) return false;
                return !empty($att['path']) || !empty($att['uri']);
            }));

            if (!$request->filled('message') && !$request->hasFile('attachments') && empty($forwardedAttachments)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Message text or attachment is required',
                ], 422);
            }


            $attachments = $request->file('attachments', []);
            if (is_array($attachments)) {
                foreach ($attachments as $idx => $file) {
                }
            }

            $attachmentsArray = is_array($attachments) ? $attachments : [$attachments];
            if ($createGroupText || count($normalizedRecipients) === 1) {
                $blockedRecipients = [];
                foreach ($normalizedRecipients as $recipient) {
                    $blockCheck = $this->resolveDialerBlockDecision($ringCentralUser, $recipient);
                    if (!empty($blockCheck['blocked'])) {
                        $reasonParts = [];
                        if (!empty($blockCheck['remote_blocked'])) $reasonParts[] = 'R-Dialer remote block';
                        if (!empty($blockCheck['local_blocked'])) $reasonParts[] = 'local dialer block';
                        $reason = empty($reasonParts) ? 'blocked number rule' : implode(' + ', $reasonParts);
                        $blockedRecipients[] = [
                            'to_number' => $recipient,
                            'reason' => $reason,
                        ];
                    }
                }

                if (!empty($blockedRecipients)) {
                    $firstBlocked = $blockedRecipients[0];
                    return response()->json([
                        'success' => false,
                        'message' => 'Blocked number: outbound SMS is disabled by ' . $firstBlocked['reason'] . '.',
                        'blocked' => true,
                        'to_number' => $firstBlocked['to_number'],
                        'blocked_recipients' => $blockedRecipients,
                    ], 422);
                }

                $result = $this->ringCentralService->sendSMS(
                    Auth::id(),
                    count($normalizedRecipients) > 1 ? $normalizedRecipients : $normalizedRecipients[0],
                    $normalizedFrom,
                    $request->message,
                    $attachmentsArray,
                    $forwardedAttachments,
                    [
                        'create_group_text' => $createGroupText,
                        'group_name' => $groupName,
                    ]
                );

                return response()->json($result);
            }

            $results = [];
            $sent = 0;
            $failed = 0;
            foreach ($normalizedRecipients as $recipient) {
                $blockCheck = $this->resolveDialerBlockDecision($ringCentralUser, $recipient);
                if (!empty($blockCheck['blocked'])) {
                    $reasonParts = [];
                    if (!empty($blockCheck['remote_blocked'])) $reasonParts[] = 'R-Dialer remote block';
                    if (!empty($blockCheck['local_blocked'])) $reasonParts[] = 'local dialer block';
                    $reason = empty($reasonParts) ? 'blocked number rule' : implode(' + ', $reasonParts);

                    $failed++;
                    $results[] = [
                        'to_number' => $recipient,
                        'success' => false,
                        'blocked' => true,
                        'message' => 'Blocked number: outbound SMS is disabled by ' . $reason . '.',
                    ];
                    continue;
                }

                $sendResult = $this->ringCentralService->sendSMS(
                    Auth::id(),
                    $recipient,
                    $normalizedFrom,
                    $request->message,
                    $attachmentsArray,
                    $forwardedAttachments,
                    [
                        'create_group_text' => false,
                        'group_name' => null,
                    ]
                );

                if (!empty($sendResult['success'])) {
                    $sent++;
                } else {
                    $failed++;
                }

                $results[] = [
                    'to_number' => $recipient,
                    'success' => !empty($sendResult['success']),
                    'message' => $sendResult['message'] ?? ($sendResult['success'] ? 'SMS sent successfully' : 'Failed to send SMS'),
                    'message_id' => $sendResult['message_id'] ?? null,
                ];
            }

            return response()->json([
                'success' => $failed === 0,
                'message' => $failed === 0
                    ? 'SMS sent successfully to all recipients.'
                    : "SMS sent to {$sent} recipient(s), failed for {$failed}.",
                'total' => count($normalizedRecipients),
                'sent' => $sent,
                'failed' => $failed,
                'results' => $results,
            ], $failed === 0 ? 200 : 207);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Please check SMS details and try again.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::warning('R-Dialer SMS request failed.', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Unable to send SMS. Please try again.',
            ], 500);
        }
    }

    private function normalizeNanpPhoneNumber($value)
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

    public function listBlockedNumbers(Request $request)
    {
        $ringCentralUser = RingCentralUser::where('user_id', Auth::id())->first();
        if (!$ringCentralUser) {
            return response()->json([
                'success' => false,
                'message' => 'Not connected to R-Portal',
            ], 404);
        }

        $q = trim((string) $request->query('q', ''));
        $snapshot = $this->ringCentralService->getCallerBlockingSnapshot((int) Auth::id());
        if (empty($snapshot['success'])) {
            return response()->json([
                'success' => false,
                'message' => (string) ($snapshot['message'] ?? 'Unable to load R-Dialer blocked numbers.'),
                'settings' => [
                    'local_block_enabled' => $this->isLocalDialerBlockingEnabled($ringCentralUser),
                    'local_block_supported' => $this->supportsLocalBlockToggle(),
                ],
            ], 422);
        }

        $remoteRows = [];
        foreach ((array) data_get($snapshot, 'records', []) as $record) {
            $status = strtolower((string) data_get($record, 'status', ''));
            if ($status !== 'blocked') {
                continue;
            }
            $phone = (string) data_get($record, 'phoneNumber', '');
            if ($phone === '') {
                continue;
            }
            $normalized = $this->dialerBlockService->normalizeNanpToE164($phone) ?: $phone;
            if ($q !== '') {
                $haystack = strtolower($normalized . ' ' . $phone);
                if (!str_contains($haystack, strtolower($q))) {
                    continue;
                }
            }
            $remoteRows[] = [
                'id' => (string) (data_get($record, 'id', '') ?: $normalized),
                'normalized_e164' => (string) $normalized,
                'raw_input' => (string) $phone,
                'reason' => 'R-Dialer  account-level block',
                'blocked_by_user_id' => null,
                'ringcentral_rule_id' => (string) (data_get($record, 'id', '') ?: null),
                'ringcentral_sync_status' => 'synced',
                'ringcentral_sync_error' => '',
                'ringcentral_synced_at' => now()->toIso8601String(),
                'created_at' => null,
                'source' => 'remote',
            ];
        }

        return response()->json([
            'success' => true,
            'data' => array_values($remoteRows),
            'settings' => [
                'local_block_enabled' => $this->isLocalDialerBlockingEnabled($ringCentralUser),
                'local_block_supported' => $this->supportsLocalBlockToggle(),
            ],
            'remote_settings' => data_get($snapshot, 'settings', []),
        ]);
    }

    public function addBlockedNumber(Request $request)
    {
        $ringCentralUser = RingCentralUser::where('user_id', Auth::id())->first();
        if (!$ringCentralUser) {
            return response()->json([
                'success' => false,
                'message' => 'Not connected to R-Portal',
            ], 404);
        }

        $request->validate([
            'number' => 'required|string',
            'reason' => 'nullable|string|max:1000',
        ]);

        $normalized = $this->dialerBlockService->normalizeNanpToE164((string) $request->input('number'));
        if (!$normalized) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid phone number. Use +1XXXXXXXXXX or 10 digits.',
            ], 422);
        }

        $syncResult = $this->ringCentralService->upsertExtensionBlockedCallerRule(
            (int) Auth::id(),
            $normalized,
            null
        );

        if (empty($syncResult['success'])) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to apply R-Dialer account-level block: ' . (string) ($syncResult['message'] ?? 'Unknown error'),
            ], 422);
        }

        if ($this->isLocalDialerBlockingEnabled($ringCentralUser)) {
            $localResult = $this->dialerBlockService->upsertBlock(
                $ringCentralUser,
                $normalized,
                $request->input('reason'),
                (int) Auth::id()
            );
            if (!empty($localResult['success']) && !empty($localResult['record'])) {
                /** @var RingCentralDialerBlockedNumber $localRow */
                $localRow = $localResult['record'];
                $localRow->ringcentral_rule_id = (string) ($syncResult['rule_id'] ?? $localRow->ringcentral_rule_id);
                $localRow->ringcentral_sync_status = 'synced';
                $localRow->ringcentral_sync_error = null;
                $localRow->ringcentral_synced_at = now();
                $localRow->save();
            }
        }

        return response()->json([
            'success' => true,
            'created' => true,
            'data' => [
                'id' => (string) ($syncResult['rule_id'] ?? $normalized),
                'normalized_e164' => $normalized,
                'ringcentral_rule_id' => (string) ($syncResult['rule_id'] ?? ''),
                'ringcentral_sync_status' => 'synced',
                'ringcentral_synced_at' => now()->toIso8601String(),
            ],
        ]);
    }

    public function removeBlockedNumber(Request $request, $id)
    {
        $ringCentralUser = RingCentralUser::where('user_id', Auth::id())->first();
        if (!$ringCentralUser) {
            return response()->json([
                'success' => false,
                'message' => 'Not connected to R-Portal',
            ], 404);
        }

        $row = null;
        $normalized = null;
        if ((int) $id > 0) {
            $row = RingCentralDialerBlockedNumber::query()
                ->where('ringcentral_user_id', (int) $ringCentralUser->id)
                ->where('id', (int) $id)
                ->first();
            if ($row) {
                $normalized = (string) $row->normalized_e164;
            }
        }
        if (!$normalized) {
            $number = trim((string) $request->query('number', ''));
            $normalized = $this->dialerBlockService->normalizeNanpToE164($number);
        }
        if (!$normalized) {
            return response()->json([
                'success' => false,
                'message' => 'Blocked number not found or invalid phone number.',
            ], 422);
        }

        if (!$row) {
            $row = RingCentralDialerBlockedNumber::query()
                ->where('ringcentral_user_id', (int) $ringCentralUser->id)
                ->where('normalized_e164', $normalized)
                ->orderByDesc('is_active')
                ->orderByDesc('id')
                ->first();
        }

        $syncResult = $this->ringCentralService->removeExtensionBlockedCallerRule(
            (int) Auth::id(),
            $normalized,
            $row ? ($row->ringcentral_rule_id ?: null) : null
        );

        if (empty($syncResult['success'])) {
            $row->ringcentral_sync_status = 'failed';
            $row->ringcentral_sync_error = (string) ($syncResult['message'] ?? 'Failed to remove R-Dialer block rule.');
            $row->save();
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove R-Dialer account-level block: ' . $row->ringcentral_sync_error,
            ], 422);
        }

        RingCentralDialerBlockedNumber::query()
            ->where('ringcentral_user_id', (int) $ringCentralUser->id)
            ->where('normalized_e164', $normalized)
            ->where('is_active', true)
            ->update([
                'is_active' => false,
                'ringcentral_sync_status' => 'unsynced',
                'ringcentral_sync_error' => null,
                'ringcentral_synced_at' => now(),
                'updated_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Number unblocked successfully',
        ]);
    }

    public function checkBlockedNumber(Request $request)
    {
        $ringCentralUser = RingCentralUser::where('user_id', Auth::id())->first();
        if (!$ringCentralUser) {
            Log::info('RC_BLOCK_CHECK no R-Dialer user', [
                'user_id' => Auth::id(),
                'input_number' => (string) $request->input('number', ''),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Not connected to R-Portal',
            ], 404);
        }

        $request->validate([
            'number' => 'required|string',
        ]);

        $normalized = $this->dialerBlockService->normalizeNanpToE164($request->input('number'));
        if (!$normalized) {
            Log::info('RC_BLOCK_CHECK invalid number', [
                'user_id' => Auth::id(),
                'ringcentral_user_id' => (int) $ringCentralUser->id,
                'input_number' => (string) $request->input('number', ''),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid phone number',
                'blocked' => false,
            ], 422);
        }

        $blockDecision = $this->resolveDialerBlockDecision($ringCentralUser, $normalized);
        Log::info('RC_BLOCK_CHECK result', [
            'user_id' => Auth::id(),
            'ringcentral_user_id' => (int) $ringCentralUser->id,
            'input_number' => (string) $request->input('number', ''),
            'normalized_e164' => $normalized,
            'blocked' => (bool) ($blockDecision['blocked'] ?? false),
            'remote_blocked' => (bool) ($blockDecision['remote_blocked'] ?? false),
            'local_blocked' => (bool) ($blockDecision['local_blocked'] ?? false),
            'local_block_enabled' => (bool) ($blockDecision['local_block_enabled'] ?? false),
        ]);

        return response()->json([
            'success' => true,
            'blocked' => (bool) ($blockDecision['blocked'] ?? false),
            'remote_blocked' => (bool) ($blockDecision['remote_blocked'] ?? false),
            'local_blocked' => (bool) ($blockDecision['local_blocked'] ?? false),
            'local_block_enabled' => (bool) ($blockDecision['local_block_enabled'] ?? false),
            'normalized_e164' => $normalized,
        ]);
    }

    public function getBlockedNumberSettings(Request $request)
    {
        $ringCentralUser = RingCentralUser::where('user_id', Auth::id())->first();
        if (!$ringCentralUser) {
            return response()->json([
                'success' => false,
                'message' => 'Not connected to R-Portal',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'settings' => [
                'local_block_enabled' => $this->isLocalDialerBlockingEnabled($ringCentralUser),
                'local_block_supported' => $this->supportsLocalBlockToggle(),
            ],
        ]);
    }

    public function updateBlockedNumberSettings(Request $request)
    {
        $ringCentralUser = RingCentralUser::where('user_id', Auth::id())->first();
        if (!$ringCentralUser) {
            return response()->json([
                'success' => false,
                'message' => 'Not connected to R-Portal',
            ], 404);
        }

        $request->validate([
            'local_block_enabled' => 'required|boolean',
        ]);

        if (!$this->supportsLocalBlockToggle()) {
            return response()->json([
                'success' => false,
                'message' => 'Local dialer block toggle is not available until database migration is applied.',
            ], 422);
        }

        $ringCentralUser->dialer_local_block_enabled = (bool) $request->boolean('local_block_enabled');
        $ringCentralUser->save();

        return response()->json([
            'success' => true,
            'settings' => [
                'local_block_enabled' => (bool) $ringCentralUser->dialer_local_block_enabled,
                'local_block_supported' => true,
            ],
        ]);
    }

    public function debugBlockedNumbers(Request $request)
    {
        $ringCentralUser = RingCentralUser::where('user_id', Auth::id())->first();
        if (!$ringCentralUser) {
            return response()->json([
                'success' => false,
                'message' => 'Not connected to R-Portal',
            ], 404);
        }

        $numberInput = trim((string) $request->query('number', ''));
        $normalized = $numberInput !== ''
            ? $this->dialerBlockService->normalizeNanpToE164($numberInput)
            : null;

        if ($numberInput !== '' && !$normalized) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid phone number filter.',
            ], 422);
        }

        $localQuery = RingCentralDialerBlockedNumber::query()
            ->where('ringcentral_user_id', (int) $ringCentralUser->id)
            ->orderByDesc('id');
        if ($normalized) {
            $localQuery->where('normalized_e164', $normalized);
        }
        $localRows = $localQuery->limit(100)->get();

        $remote = $this->ringCentralService->getBlockedCallerRulesDiagnostics(
            (int) Auth::id(),
            $normalized
        );

        return response()->json([
            'success' => !empty($remote['success']),
            'filter' => [
                'input' => $numberInput !== '' ? $numberInput : null,
                'normalized_e164' => $normalized,
            ],
            'local' => $localRows->map(function (RingCentralDialerBlockedNumber $row) {
                return [
                    'id' => (int) $row->id,
                    'normalized_e164' => (string) $row->normalized_e164,
                    'raw_input' => (string) ($row->raw_input ?? ''),
                    'is_active' => (bool) $row->is_active,
                    'reason' => (string) ($row->reason ?? ''),
                    'ringcentral_rule_id' => $row->ringcentral_rule_id ? (string) $row->ringcentral_rule_id : null,
                    'ringcentral_sync_status' => (string) ($row->ringcentral_sync_status ?? ''),
                    'ringcentral_sync_error' => (string) ($row->ringcentral_sync_error ?? ''),
                    'ringcentral_synced_at' => optional($row->ringcentral_synced_at)->toIso8601String(),
                    'updated_at' => optional($row->updated_at)->toIso8601String(),
                    'created_at' => optional($row->created_at)->toIso8601String(),
                ];
            })->values(),
            'remote' => $remote,
        ]);
    }

    private function getBlockedMapForSharedRingCentralUsers(?string $phoneNumber, ?int $fallbackRingCentralUserId = null): array
    {
        $sharedUserIds = $this->getSharedRingCentralUserIdsByPhone($phoneNumber);
        if (empty($sharedUserIds) && $fallbackRingCentralUserId) {
            $sharedUserIds = [$fallbackRingCentralUserId];
        }
        if (empty($sharedUserIds)) {
            return [];
        }

        $eligibleUserIds = RingCentralUser::query()
            ->whereIn('id', $sharedUserIds);
        if ($this->supportsLocalBlockToggle()) {
            $eligibleUserIds = $eligibleUserIds->where('dialer_local_block_enabled', true)
                ->pluck('id')
                ->map(static fn ($id) => (int) $id)
                ->all();
        } else {
            $eligibleUserIds = [];
        }

        if (empty($eligibleUserIds)) {
            return [];
        }

        return $this->dialerBlockService->getBlockedMapForRingCentralUsers($eligibleUserIds);
    }

    private function isPhoneBlockedByRingCentralUserMap(array $blockedMap, ?int $ringCentralUserId, ?string $phone): bool
    {
        $normalized = $this->dialerBlockService->normalizeNanpToE164($phone);
        if (!$normalized) {
            return false;
        }

        if ($ringCentralUserId && isset($blockedMap[$ringCentralUserId][$normalized])) {
            return true;
        }

        foreach ($blockedMap as $byUser) {
            if (isset($byUser[$normalized])) {
                return true;
            }
        }

        return false;
    }

    private function isLocalDialerBlockingEnabled(RingCentralUser $ringCentralUser): bool
    {
        if (!$this->supportsLocalBlockToggle()) {
            return false;
        }
        return (bool) ($ringCentralUser->dialer_local_block_enabled ?? false);
    }

    private function supportsLocalBlockToggle(): bool
    {
        static $supports = null;
        if ($supports === null) {
            $supports = Schema::hasColumn('ringcentral_users', 'dialer_local_block_enabled');
        }
        return (bool) $supports;
    }

    /**
     * Read a DB datetime column as UTC and serialize as ISO-8601.
     *
     * R-Dialer message/call timestamps are persisted as UTC-like values in DB.
     * Serializing via Eloquent date casts can apply app timezone and shift clock time.
     * This helper preserves the original DB clock value as UTC for frontend local conversion.
     */
    private function toUtcIsoFromDatabaseValue(\Illuminate\Database\Eloquent\Model $model, string $column): ?string
    {
        $raw = $model->getRawOriginal($column);
        if ($raw === null || $raw === '') {
            return null;
        }

        try {
            return Carbon::createFromFormat('Y-m-d H:i:s', (string) $raw, 'UTC')->toIso8601String();
        } catch (\Exception $e) {
            try {
                return Carbon::parse((string) $raw, 'UTC')->toIso8601String();
            } catch (\Exception $_) {
                return optional($model->{$column})->toIso8601String();
            }
        }
    }

    /**
     * Resolve first available DB datetime column and return UTC ISO-8601.
     */
    private function toUtcIsoFromDatabaseColumns(\Illuminate\Database\Eloquent\Model $model, array $columns): ?string
    {
        foreach ($columns as $column) {
            if (!is_string($column) || $column === '') {
                continue;
            }

            $iso = $this->toUtcIsoFromDatabaseValue($model, $column);
            if ($iso !== null && $iso !== '') {
                return $iso;
            }
        }

        return null;
    }

    private function resolveDialerBlockDecision(RingCentralUser $ringCentralUser, string $normalizedE164): array
    {
        $localEnabled = $this->isLocalDialerBlockingEnabled($ringCentralUser);
        $localBlocked = $localEnabled
            ? $this->dialerBlockService->isBlockedForRingCentralUser((int) $ringCentralUser->id, $normalizedE164)
            : false;

        $remote = $this->ringCentralService->isNumberBlockedInRingCentral((int) $ringCentralUser->user_id, $normalizedE164, true);
        $remoteBlocked = !empty($remote['success']) && !empty($remote['blocked']);

        return [
            // Local enforcement only. Remote block is handled by R-Dialer network behavior.
            'blocked' => (bool) $localBlocked,
            'remote_blocked' => (bool) $remoteBlocked,
            'local_blocked' => (bool) $localBlocked,
            'local_block_enabled' => (bool) $localEnabled,
        ];
    }

    private function resolveCounterpartyForCallRow(RingCentralCallLog $call, ?string $userPhone = null): ?string
    {
        $direction = strtolower((string) ($call->direction ?? ''));
        $from = $call->from_number ?: null;
        $to = $call->to_number ?: null;
        $legacy = $call->phone_number ?: null;

        if ($direction === 'outbound') {
            return $to ?: $legacy ?: $from ?: $userPhone;
        }

        return $from ?: $legacy ?: $to ?: $userPhone;
    }

    private function resolveCounterpartyForMessageRow(RingCentralMessage $msg, ?string $userPhone = null): ?string
    {
        $direction = strtolower((string) ($msg->direction ?? ''));
        $from = $msg->from_number ?: null;
        $to = $msg->to_number ?: null;

        if ($direction === 'outbound') {
            return $to ?: $from ?: $userPhone;
        }

        return $from ?: $to ?: $userPhone;
    }

    /**
     * Create a conference via telephony/conference
     */
    public function createConference(Request $request)
    {
        try {

            // Call the service method to create the conference
            $result = $this->ringCentralService->createTelephonyConference(Auth::id());

            // Check if the conference creation was successful
            $status = (!empty($result['success'])) ? 200 : 500;
            return response()->json($result, $status);

        } catch (\Exception $e) {
            // Log the error and return failure response
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bring a party into an active telephony session
     */
    public function bringInParty(Request $request, $sessionId)
    {
        try {
                // Validation for bring-in: need party_id and session_id
                $rules = [
                    'party_id' => 'required|string',
                    'session_id' => 'required_without:session_id|string',
                    'from_number' => 'sometimes|string|nullable',
                    'play_prompt' => 'sometimes|boolean',
                    'device_id' => 'sometimes|string|nullable',
                    'extension_id' => 'sometimes|string|nullable',
                    'line_id' => 'sometimes|string|nullable',
                    'country_id' => 'sometimes|string|nullable',
                ];

                $request->validate($rules);

                if (!$request->filled('session_id') && $request->filled('session_id')) {
                    $request->merge(['session_id' => $request->session_id]);
                }

                // Log the bring-in request details

            // Call the service to bring in the party into the conference
            $result = $this->ringCentralService->bringInParty(
                Auth::id(),
                $sessionId,
                $request->all()
            );

            // Return the result based on success or failure
            $status = !empty($result['status_code'])
                ? (int) $result['status_code']
                : ((!empty($result['success'])) ? 200 : 500);
            return response()->json($result, $status);

        } catch (\Exception $e) {
            // Log the error and return failure response
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * List telephony sessions for call-control operations.
     */
    public function callControlSessions(Request $request)
    {
        try {
            $result = $this->ringCentralService->getTelephonySessions(Auth::id(), $request->all());
            $status = !empty($result['success']) ? 200 : ((int) ($result['status_code'] ?? 500));
            return response()->json($result, $status);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Transfer a telephony party using call-control APIs.
     */
    public function callControlTransfer(Request $request, $sessionId, $partyId)
    {
        try {
            $request->validate([
                'phone_number' => 'required|string',
            ]);

            $result = $this->ringCentralService->transferTelephonyParty(
                Auth::id(),
                $sessionId,
                $partyId,
                $request->all()
            );

            $status = !empty($result['success']) ? 200 : ((int) ($result['status_code'] ?? 500));
            return response()->json($result, $status);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove/disconnect a telephony party from the session.
     */
    public function callControlRemoveParty(Request $request, $sessionId, $partyId)
    {
        try {
            Log::info('CALL-CONTROL REMOVE-PARTY REQUEST', [
                'user_id' => Auth::id(),
                'session_id' => (string) $sessionId,
                'party_id' => (string) $partyId,
                'conference_session_id' => (string) $request->input('conference_session_id', ''),
                'participant_number' => (string) $request->input('participant_number', ''),
                'requested_party_id' => (string) $request->input('requested_party_id', ''),
            ]);

            $result = $this->ringCentralService->removeTelephonyParty(
                Auth::id(),
                $sessionId,
                $partyId,
                $request->all()
            );

            Log::info('CALL-CONTROL REMOVE-PARTY RESPONSE', [
                'user_id' => Auth::id(),
                'session_id' => (string) $sessionId,
                'party_id' => (string) $partyId,
                'success' => (bool) ($result['success'] ?? false),
                'status_code' => (int) ($result['status_code'] ?? 500),
                'error_code' => (string) ($result['errorCode'] ?? ''),
                'message' => (string) ($result['message'] ?? ''),
                'selected_party_id' => (string) ($result['selected_party_id'] ?? ''),
                'selected_session_id' => (string) ($result['selected_session_id'] ?? ''),
                'source' => (string) ($result['source'] ?? ''),
            ]);

            $status = !empty($result['success']) ? 200 : ((int) ($result['status_code'] ?? 500));
            return response()->json($result, $status);
        } catch (\Exception $e) {
            Log::error('CALL-CONTROL REMOVE-PARTY CONTROLLER ERROR', [
                'user_id' => Auth::id(),
                'session_id' => (string) $sessionId,
                'party_id' => (string) $partyId,
                'message' => $e->getMessage(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Merge two active telephony calls into a conference.
     */
    public function callControlMerge(Request $request)
    {
        try {
            $request->validate([
                'primary_session_id' => 'required|string',
                'primary_party_id' => 'required|string',
                'secondary_session_id' => 'required|string',
                'secondary_party_id' => 'required|string',
            ]);

            $result = $this->ringCentralService->mergeTelephonySessions(Auth::id(), $request->all());
            $status = !empty($result['success']) ? 200 : ((int) ($result['status_code'] ?? 500));
            return response()->json($result, $status);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Pick up a call to web device using call-control APIs.
     */
    public function callControlSwitchToWeb(Request $request, $sessionId, $partyId)
    {
        try {
            $result = $this->ringCentralService->switchTelephonyPartyToWeb(
                Auth::id(),
                $sessionId,
                $partyId,
                $request->all()
            );

            $status = !empty($result['success']) ? 200 : ((int) ($result['status_code'] ?? 500));
            return response()->json($result, $status);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get call history
     */
    public function getCallHistory(Request $request)
    {
        $direction = $request->get('direction');
        $perPage = min(max((int) $request->get('per_page', 25), 1), 100);
        $before = $request->get('before'); // cursor: ISO8601|id
        $refresh = $request->boolean('refresh');
        $q = trim((string) $request->get('q', ''));

        $ringCentralUser = RingCentralUser::where('user_id', Auth::id())->first();
        if (!$ringCentralUser) {
            return response()->json([
                'success' => false,
                'message' => 'Not connected to R-Portal',
            ], 404);
        }

        $syncResult = null;
        if ($refresh) {
            try {
                $filters = ['count' => $request->get('count', 100)];
                if ($direction) $filters['direction'] = $direction;
                if (!empty($ringCentralUser->phone_number)) {
                    $filters['phoneNumber'] = $ringCentralUser->phone_number;
                }
                $syncResult = $this->ringCentralService->getCallHistory(Auth::id(), $filters);
            } catch (\Exception $e) {
            }
        }

        $sharedUserIds = $this->getSharedRingCentralUserIdsByPhone($ringCentralUser->phone_number);
        if (empty($sharedUserIds)) {
            $sharedUserIds = [$ringCentralUser->id];
        }
        $blockedMap = $this->getBlockedMapForSharedRingCentralUsers($ringCentralUser->phone_number, (int) $ringCentralUser->id);
        $query = RingCentralCallLog::whereIn('ringcentral_user_id', $sharedUserIds);
        if (!empty($direction)) {
            $query->where('direction', $direction);
        }
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('phone_number', 'like', "%{$q}%")
                    ->orWhere('from_number', 'like', "%{$q}%")
                    ->orWhere('to_number', 'like', "%{$q}%")
                    ->orWhere('call_id', 'like', "%{$q}%")
                    ->orWhere('status', 'like', "%{$q}%");
            });
        }

        // Cursor logic: decode before cursor if present
        if ($before) {
            try {
                [$timeStr, $id] = explode('|', $before, 2) + [null, null];
                if ($timeStr) {
                    $time = Carbon::parse($timeStr);
                    $query->where(function ($qwhere) use ($time, $id) {
                        $qwhere->where('call_started_at', '<', $time->toDateTimeString());
                        if ($id) {
                            $qwhere->orWhere(function ($q2) use ($time, $id) {
                                $q2->where('call_started_at', '=', $time->toDateTimeString())
                                    ->where('id', '<', (int) $id);
                            });
                        }
                    });
                }
            } catch (\Exception $e) {
                // ignore malformed cursor
            }
        }

        $items = $query->orderBy('call_started_at', 'desc')
            ->orderBy('id', 'desc')
            ->limit($perPage + 1)
            ->get();

        $hasMore = $items->count() > $perPage;
        if ($hasMore) $items = $items->slice(0, $perPage);

        $items = $items->values()->filter(function (RingCentralCallLog $call) use ($ringCentralUser, $blockedMap) {
            $counterparty = $this->resolveCounterpartyForCallRow($call, $ringCentralUser->phone_number);
            return !$this->isPhoneBlockedByRingCentralUserMap($blockedMap, (int) $call->ringcentral_user_id, $counterparty);
        })->values();

        $userPhone = $ringCentralUser->phone_number;
        $data = $items->map(function ($call) use ($userPhone) {
            $directionRaw = $call->direction ?: 'Unknown';
            $directionKey = strtolower((string) ($call->direction ?? ''));

            $fromPhone = $call->from_number ?: null;
            $toPhone = $call->to_number ?: null;

            if (!$fromPhone || !$toPhone) {
                $legacyPhone = $call->phone_number ?: null;
                if ($directionKey === 'outbound') {
                    $fromPhone = $fromPhone ?: ($userPhone ?: $legacyPhone ?: 'Unknown');
                    $toPhone = $toPhone ?: ($legacyPhone ?: $userPhone ?: 'Unknown');
                } else {
                    $fromPhone = $fromPhone ?: ($legacyPhone ?: $userPhone ?: 'Unknown');
                    $toPhone = $toPhone ?: ($userPhone ?: $legacyPhone ?: 'Unknown');
                }
            }

            return [
                'call_id' => $call->call_id,
                'from' => $fromPhone,
                'to' => $toPhone,
                'direction' => $directionRaw,
                'duration_seconds' => (int) $call->duration_seconds,
                'status' => $this->normalizeCallStatusLabel($call->status),
                'start_time' => $this->toUtcIsoFromDatabaseValue($call, 'call_started_at'),
                'end_time' => $this->toUtcIsoFromDatabaseValue($call, 'call_ended_at'),
            ];
        })->values();

        $nextCursor = null;
        if ($items->count()) {
            $last = $items->last();
            $cursorTime = $this->toUtcIsoFromDatabaseValue($last, 'call_started_at');
            if ($cursorTime) {
                $nextCursor = $cursorTime . '|' . $last->id;
            }
        }

        $summary = $this->buildCallsSummary($ringCentralUser->id, $ringCentralUser->phone_number, $blockedMap);

        return response()->json([
            'success' => true,
            'data' => $data,
            'pagination' => [
                'per_page' => $perPage,
                'has_more' => $hasMore,
                'next_cursor' => $nextCursor,
            ],
            'summary' => $summary,
        ]);
    }

    /**
     * Get calls summary (missed totals, unseen counts)
     */
    public function getCallsSummary(Request $request)
    {
        $refresh = $request->boolean('refresh');
        $ringCentralUser = RingCentralUser::where('user_id', Auth::id())->first();
        if (!$ringCentralUser) {
            return response()->json([
                'success' => false,
                'message' => 'Not connected to R-Portal',
            ], 404);
        }

        $syncResult = null;
        if ($refresh) {
            try {
                $syncResult = $this->ringCentralService->getCallHistory(Auth::id(), ['count' => $request->get('count', 100)]);
            } catch (\Exception $e) {
            }
        }

        $blockedMap = $this->getBlockedMapForSharedRingCentralUsers($ringCentralUser->phone_number, (int) $ringCentralUser->id);
        $summary = $this->buildCallsSummary($ringCentralUser->id, $ringCentralUser->phone_number, $blockedMap);

        return response()->json([
            'success' => true,
            'summary' => $summary,
        ]);
    }

    /**
     * Mark missed calls as seen for the current user
     */
    public function markCallsSeen(Request $request)
    {
        $ringCentralUser = RingCentralUser::where('user_id', Auth::id())->first();
        if (!$ringCentralUser) {
            return response()->json([
                'success' => false,
                'message' => 'Not connected to R-Portal',
            ], 404);
        }

        $affected = RingCentralCallLog::where('ringcentral_user_id', $ringCentralUser->id)
            ->missed()
            ->where(function ($q) {
                $q->whereNull('is_seen')->orWhere('is_seen', false);
            })
            ->update([
                'is_seen' => true,
                'seen_at' => Carbon::now(),
            ]);

        return response()->json([
            'success' => true,
            'updated' => $affected,
            'summary' => $this->buildCallsSummary($ringCentralUser->id, $ringCentralUser->phone_number),
        ]);
    }

    private function buildCallsSummary($ringCentralUserId, $phoneNumber = null, ?array $blockedMap = null)
    {
        $sharedUserIds = $this->getSharedRingCentralUserIdsByPhone($phoneNumber);
        if (empty($sharedUserIds)) {
            $sharedUserIds = [$ringCentralUserId];
        }
        $blockedMap = $blockedMap ?? $this->dialerBlockService->getBlockedMapForRingCentralUsers($sharedUserIds);

        $allCalls = RingCentralCallLog::whereIn('ringcentral_user_id', $sharedUserIds)->get();
        $nonBlockedCalls = $allCalls->filter(function (RingCentralCallLog $call) use ($phoneNumber, $blockedMap) {
            $counterparty = $this->resolveCounterpartyForCallRow($call, $phoneNumber);
            return !$this->isPhoneBlockedByRingCentralUserMap($blockedMap, (int) $call->ringcentral_user_id, $counterparty);
        });

        $totalCalls = $nonBlockedCalls->count();
        $missedTotal = $nonBlockedCalls->filter(function (RingCentralCallLog $call) {
            return RingCentralCallLog::isMissedStatus($call->status);
        })->count();

        $missedUnseen = $nonBlockedCalls->filter(function (RingCentralCallLog $call) use ($ringCentralUserId) {
            $isMine = (int) $call->ringcentral_user_id === (int) $ringCentralUserId;
            $isMissed = RingCentralCallLog::isMissedStatus($call->status);
            $isSeen = !is_null($call->is_seen) && (bool) $call->is_seen;
            return $isMine && $isMissed && !$isSeen;
        })->count();

        return [
            'total_calls' => $totalCalls,
            'missed_total' => $missedTotal,
            'missed_unseen' => $missedUnseen,
        ];
    }

    private function buildVoicemailSummary($ringCentralUserId, $phoneNumber = null, ?array $blockedMap = null)
    {
        $sharedUserIds = $this->getSharedRingCentralUserIdsByPhone($phoneNumber);
        if (empty($sharedUserIds)) {
            $sharedUserIds = [$ringCentralUserId];
        }
        $blockedMap = $blockedMap ?? $this->dialerBlockService->getBlockedMapForRingCentralUsers($sharedUserIds);

        $allVoicemails = RingCentralVoicemail::whereIn('ringcentral_user_id', $sharedUserIds)->get();
        $nonBlocked = $allVoicemails->filter(function (RingCentralVoicemail $vm) use ($blockedMap) {
            $phone = $vm->caller_number ?: $vm->phone_number;
            return !$this->isPhoneBlockedByRingCentralUserMap($blockedMap, (int) $vm->ringcentral_user_id, $phone);
        });

        $totalVoicemails = $nonBlocked->count();
        $unreadVoicemails = $nonBlocked->filter(function (RingCentralVoicemail $vm) {
            return strtolower((string) ($vm->message_status ?? '')) === 'unread';
        })->count();

        return [
            'total_available' => $totalVoicemails,
            'unread_total' => $unreadVoicemails,
        ];
    }

    /**
     * Get message history
     */
    public function getMessageHistory(Request $request)
    {
        $direction = $request->get('direction');
        $perPage = min(max((int) $request->get('per_page', 50), 1), 200);
        $before = $request->get('before');
        $refresh = $request->boolean('refresh');
        $q = trim((string) $request->get('q', ''));

        $ringCentralUser = RingCentralUser::where('user_id', Auth::id())->first();
        if (!$ringCentralUser) {
            return response()->json([
                'success' => false,
                'message' => 'Not connected to R-Portal',
            ], 404);
        }

        $fallbackToLocal = false;
        $fallbackMessage = null;
        if ($refresh) {
            try {
                $filters = ['count' => $request->get('count', 100)];
                if ($direction) $filters['direction'] = $direction;
                if (!empty($ringCentralUser->phone_number)) {
                    $filters['phoneNumber'] = $ringCentralUser->phone_number;
                }
                $this->ringCentralService->getMessageHistory(Auth::id(), $filters);
            } catch (\Exception $e) {
                if (str_contains($e->getMessage(), 'Maximum execution time') || str_contains($e->getMessage(), 'timed out') || str_contains($e->getMessage(), 'cURL error')) {
                    $fallbackToLocal = true;
                    $fallbackMessage = "Can't get new from server. Retry in a min.";
                } else {
                    $fallbackToLocal = true;
                    $fallbackMessage = "Can't get new from server. Retry in a min.";
                }
            }
        }

        $sharedUserIds = $this->getSharedRingCentralUserIdsByPhone($ringCentralUser->phone_number);
        if (empty($sharedUserIds)) {
            $sharedUserIds = [$ringCentralUser->id];
        }
        $blockedMap = $this->getBlockedMapForSharedRingCentralUsers($ringCentralUser->phone_number, (int) $ringCentralUser->id);
        $query = RingCentralMessage::whereIn('ringcentral_user_id', $sharedUserIds);
        $phoneDigits = $ringCentralUser->phone_number
            ? preg_replace('/\D/', '', $ringCentralUser->phone_number)
            : null;
        if ($phoneDigits) {
            $query->where(function ($sub) use ($phoneDigits) {
                $sub->where('from_number', 'like', "%{$phoneDigits}%")
                    ->orWhere('to_number', 'like', "%{$phoneDigits}%");
            });
        }
        if (!empty($direction)) {
            $query->whereRaw('LOWER(direction) = ?', [strtolower((string) $direction)]);
        }
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('message_body', 'like', "%{$q}%")
                    ->orWhere('from_name', 'like', "%{$q}%")
                    ->orWhere('to_name', 'like', "%{$q}%")
                    ->orWhere('from_number', 'like', "%{$q}%")
                    ->orWhere('to_number', 'like', "%{$q}%");
            });
        }

        $summaryRows = (clone $query)->get();
        $summaryRows = $summaryRows->filter(function (RingCentralMessage $msg) use ($ringCentralUser, $blockedMap) {
            $counterparty = $this->resolveCounterpartyForMessageRow($msg, $ringCentralUser->phone_number);
            return !$this->isPhoneBlockedByRingCentralUserMap($blockedMap, (int) $msg->ringcentral_user_id, $counterparty);
        })->values();

        $filteredTotalBeforeCursor = $summaryRows->count();
        $filteredUnreadBeforeCursor = $summaryRows->filter(function (RingCentralMessage $msg) {
            return strtolower((string) ($msg->status ?? '')) === 'unread';
        })->count();

        // Cursor logic for messages
        if ($before) {
            try {
                [$timeStr, $id] = explode('|', $before, 2) + [null, null];
                if ($timeStr) {
                    $time = Carbon::parse($timeStr);
                    $query->where(function ($qwhere) use ($time, $id) {
                        $qwhere->where('sent_at', '<', $time->toDateTimeString());
                        if ($id) {
                            $qwhere->orWhere(function ($q2) use ($time, $id) {
                                $q2->where('sent_at', '=', $time->toDateTimeString())
                                    ->where('id', '<', (int) $id);
                            });
                        }
                    });
                }
            } catch (\Exception $e) {
                // ignore malformed cursor
            }
        }


        $items = $query->orderBy('sent_at', 'desc')
            ->orderBy('id', 'desc')
            ->limit($perPage + 1)
            ->get();


        $hasMore = $items->count() > $perPage;
        if ($hasMore) $items = $items->slice(0, $perPage);
        $items = $items->values()->filter(function (RingCentralMessage $msg) use ($ringCentralUser, $blockedMap) {
            $counterparty = $this->resolveCounterpartyForMessageRow($msg, $ringCentralUser->phone_number);
            return !$this->isPhoneBlockedByRingCentralUserMap($blockedMap, (int) $msg->ringcentral_user_id, $counterparty);
        })->values();

        $userPhone = $ringCentralUser->phone_number;
        $userDigits = preg_replace('/\D+/', '', (string) $userPhone);
        $messageAnomalies = [];
        $data = $items->map(function ($msg) use ($userPhone, $ringCentralUser, $userDigits, &$messageAnomalies) {
            $direction = $msg->direction ?: 'inbound';
            $fromPhone = $msg->from_number ?: 'Unknown';
            $toPhone = $msg->to_number ?: 'Unknown';
            $toRecipients = collect(preg_split('/\s*[,;]\s*/', (string) $toPhone))
                ->map(function ($value) { return trim((string) $value); })
                ->filter(function ($value) { return $value !== ''; })
                ->values();
            if ($toRecipients->isEmpty() && trim((string) $toPhone) !== '') {
                $toRecipients = collect([trim((string) $toPhone)]);
            }

            $fromDigits = preg_replace('/\D+/', '', (string) $fromPhone);
            $toDigitsAll = $toRecipients
                ->map(function ($value) { return preg_replace('/\D+/', '', (string) $value); })
                ->filter(function ($value) { return $value !== ''; })
                ->values()
                ->all();
            $toCounterparty = collect($toDigitsAll)->first(function ($digits) use ($userDigits) {
                return !$userDigits || $digits !== $userDigits;
            });

            $isInbound = str_contains(strtolower((string) $direction), 'in');
            $isOutbound = str_contains(strtolower((string) $direction), 'out');
            $counterpartyDigits = null;
            if ($isInbound) {
                $counterpartyDigits = $fromDigits ?: ($toCounterparty ?: null);
            } elseif ($isOutbound) {
                $counterpartyDigits = $toCounterparty ?: (($fromDigits && $fromDigits !== $userDigits) ? $fromDigits : null);
            } else {
                $counterpartyDigits = $toCounterparty ?: (($fromDigits && $fromDigits !== $userDigits) ? $fromDigits : null);
            }

            $hasUnknownFrom = strtolower((string) $fromPhone) === 'unknown';
            $hasUnknownTo = strtolower((string) $toPhone) === 'unknown';
            if (($hasUnknownFrom || $hasUnknownTo || !$counterpartyDigits) && count($messageAnomalies) < 60) {
                $messageAnomalies[] = [
                    'db_row_id' => (int) ($msg->id ?? 0),
                    'message_id' => (string) ($msg->message_id ?? ''),
                    'direction' => (string) $direction,
                    'from_raw' => (string) ($msg->from_number ?? ''),
                    'to_raw' => (string) ($msg->to_number ?? ''),
                    'from_digits_last4' => $fromDigits ? substr($fromDigits, -4) : null,
                    'to_digits_last4_list' => array_values(array_map(function ($digits) {
                        return substr((string) $digits, -4);
                    }, $toDigitsAll)),
                    'counterparty_last4' => $counterpartyDigits ? substr((string) $counterpartyDigits, -4) : null,
                    'user_last4' => $userDigits ? substr((string) $userDigits, -4) : null,
                    'has_unknown_from' => $hasUnknownFrom,
                    'has_unknown_to' => $hasUnknownTo,
                    'created_at_utc' => $this->toUtcIsoFromDatabaseValue($msg, 'sent_at'),
                ];
            }

            $fromName = $msg->from_name ?: null;
            $toName = $msg->to_name ?: null;
            $readStatus = $msg->status ?? 'unread';
            $attachments = is_array($msg->attachments) ? $msg->attachments : null;
            if ($attachments) {
                $attachments = array_map(function ($att) {
                    if (!is_array($att)) return $att;

                    $resolvedPath = null;
                    $explicitPath = $att['path'] ?? $att['stored_path'] ?? null;
                    if (is_string($explicitPath) && $explicitPath !== '') {
                        $resolvedPath = ltrim($explicitPath, '/');
                    }

                    $localPath = $att['local_path'] ?? null;
                    if (!$resolvedPath && is_string($localPath) && $localPath !== '') {
                        if (str_starts_with($localPath, 'storage/')) {
                            $resolvedPath = ltrim(substr($localPath, strlen('storage/')), '/');
                        } elseif (str_starts_with($localPath, 'ringcentral_attachments/')) {
                            $resolvedPath = ltrim($localPath, '/');
                        }
                    }

                    $urlPath = $att['url'] ?? null;
                    if (!$resolvedPath && is_string($urlPath) && $urlPath !== '' && str_starts_with($urlPath, 'ringcentral_attachments/')) {
                        $resolvedPath = ltrim($urlPath, '/');
                    }

                    if ($resolvedPath && str_starts_with($resolvedPath, 'ringcentral_attachments/')) {
                        $proxyUrl = route('ringcentral.api.attachment', ['path' => $resolvedPath]);
                        $att['path'] = $resolvedPath;
                        $att['url'] = $proxyUrl;
                        $att['local_path'] = $proxyUrl;
                        $att['download_url'] = route('ringcentral.api.attachment', ['path' => $resolvedPath, 'download' => 1]);
                        return $att;
                    }

                    $uri = $att['uri'] ?? $att['contentUri'] ?? null;
                    $existingUrl = isset($att['url']) ? (string) $att['url'] : '';
                    if ($uri && ($existingUrl === '' || !str_contains($existingUrl, '/api/r/attachment'))) {
                        $proxyUrl = route('ringcentral.api.attachment', ['uri' => base64_encode($uri)]);
                        $att['url'] = $proxyUrl;
                        $att['local_path'] = $proxyUrl;
                        $att['download_url'] = route('ringcentral.api.attachment', ['uri' => base64_encode($uri), 'download' => 1]);
                    }
                    return $att;
                }, $attachments);
            }
            
            return [
                'id' => $msg->message_id,
                'message_id' => $msg->message_id,
                'direction' => $direction,
                'creationTime' => $this->toUtcIsoFromDatabaseValue($msg, 'sent_at'),
                'text' => $msg->message_body,
                'subject' => $msg->message_body,
                'body' => $msg->message_body,
                'readStatus' => $readStatus,
                'attachments' => $attachments,
                'from' => [
                    'phoneNumber' => $fromPhone,
                    'name' => $fromName,
                ],
                'to' => $toRecipients->map(function ($phone) use ($toName) {
                    return [
                        'phoneNumber' => $phone,
                        'name' => $toName,
                    ];
                })->all(),
            ];
        })->values();

        if (!empty($messageAnomalies)) {
            Log::warning('[RINGCENTRAL_API] Message history payload anomalies', [
                'user_id' => (int) Auth::id(),
                'ringcentral_user_id' => (int) $ringCentralUser->id,
                'phone_number_last4' => $userDigits ? substr((string) $userDigits, -4) : null,
                'scope' => 'messages',
                'refresh' => (bool) $refresh,
                'before_cursor' => $before ? (string) $before : null,
                'per_page' => (int) $perPage,
                'query' => $q !== '' ? $q : null,
                'anomaly_count' => count($messageAnomalies),
                'items_returned' => (int) $items->count(),
                'sample' => array_slice($messageAnomalies, 0, 25),
            ]);
        }

        // Temporary diagnostic logging for the reported 0355 thread-label issue.
        if ($userDigits && substr((string) $userDigits, -4) === '0355') {
            $sampleRows = collect($data)->take(25)->map(function ($row) use ($userDigits) {
                $fromRaw = (string) data_get($row, 'from.phoneNumber', '');
                $toRawList = collect((array) data_get($row, 'to', []))
                    ->map(function ($entry) {
                        return (string) data_get($entry, 'phoneNumber', '');
                    })
                    ->filter(function ($v) { return trim($v) !== ''; })
                    ->values();
                $fromDigits = preg_replace('/\D+/', '', $fromRaw);
                $toDigitsList = $toRawList->map(function ($v) {
                    return preg_replace('/\D+/', '', (string) $v);
                })->filter()->values();
                $peer = $toDigitsList->first(function ($digits) use ($userDigits) {
                    return $digits !== $userDigits;
                });
                if (!$peer && $fromDigits && $fromDigits !== $userDigits) {
                    $peer = $fromDigits;
                }
                return [
                    'id' => (string) (data_get($row, 'id', '') ?: data_get($row, 'message_id', '')),
                    'direction' => (string) data_get($row, 'direction', ''),
                    'from_last4' => $fromDigits ? substr($fromDigits, -4) : null,
                    'to_last4_list' => $toDigitsList->map(function ($digits) {
                        return substr((string) $digits, -4);
                    })->values()->all(),
                    'peer_last4' => $peer ? substr((string) $peer, -4) : null,
                    'created_at' => (string) data_get($row, 'creationTime', ''),
                ];
            })->values()->all();

            Log::info('[RINGCENTRAL_API] Message history response sample for 0355', [
                'user_id' => (int) Auth::id(),
                'ringcentral_user_id' => (int) $ringCentralUser->id,
                'phone_number_last4' => '0355',
                'scope' => 'messages',
                'refresh' => (bool) $refresh,
                'before_cursor' => $before ? (string) $before : null,
                'per_page' => (int) $perPage,
                'items_returned' => (int) count($sampleRows),
                'sample' => $sampleRows,
            ]);
        }

        $nextCursor = null;
        if ($items->count()) {
            $last = $items->last();
            // Try to extract a timestamp and id for the next cursor
            try {
                if ($last instanceof \Illuminate\Database\Eloquent\Model) {
                    $nextCursor = $this->toUtcIsoFromDatabaseValue($last, 'sent_at') . '|' . $last->id;
                } elseif (isset($last['creationTime'])) {
                    $timeStr = $last['creationTime'];
                    $nextCursor = $timeStr . '|' . ($last['id'] ?? '');
                }
            } catch (\Exception $e) { /* ignore */ }
        }

        return response()->json([
            'success' => true,
            'data' => $data,
            'pagination' => [
                'per_page' => $perPage,
                'has_more' => $hasMore,
                'next_cursor' => $nextCursor,
            ],
            'summary' => [
                'total_available' => $filteredTotalBeforeCursor,
                'unread_total' => $filteredUnreadBeforeCursor,
            ],
            'fallback_to_local' => $fallbackToLocal,
            'fallback_message' => $fallbackMessage,
        ], 200);
    }

    public function getAttachment(Request $request)
    {
        $path = (string) $request->query('path', '');
        $uri = (string) $request->query('uri', '');
        $download = filter_var($request->query('download', false), FILTER_VALIDATE_BOOLEAN);
        $path = ltrim($path, '/');
        if ($path === '' && $uri === '') {
            return response()->json(['success' => false, 'message' => 'Missing attachment path or uri.'], 404);
        }

        if ($path !== '') {
            $userId = Auth::id();
            $prefix = 'ringcentral_attachments/' . $userId . '/';
            if (!str_starts_with($path, $prefix)) {
                return response()->json(['success' => false, 'message' => 'Unauthorized attachment access.'], 403);
            }

            if (!Storage::disk('public')->exists($path)) {
                return response()->json(['success' => false, 'message' => 'Attachment not found.'], 404);
            }

            return $this->streamStoredAttachment($path, $download);
        }

        $decoded = base64_decode($uri, true);
        if ($decoded !== false && str_contains($decoded, '/restapi/')) {
            $uri = $decoded;
        }
        $uri = trim($uri);

        $ringCentralUser = RingCentralUser::where('user_id', Auth::id())->first();
        if (!$ringCentralUser) {
            return response()->json(['success' => false, 'message' => 'Not connected to R-Portal'], 404);
        }

        $base = rtrim(config('services.ringcentral.server'), '/');
        if (str_starts_with($uri, 'http')) {
            if (!str_starts_with($uri, $base)) {
                return response()->json(['success' => false, 'message' => 'Unauthorized attachment uri.'], 403);
            }
            $downloadUrl = $uri;
        } elseif (str_starts_with($uri, '/restapi/')) {
            $downloadUrl = $base . $uri;
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid attachment uri.'], 400);
        }

        try {
            $cachePath = $this->buildAttachmentCachePath($ringCentralUser->user_id, $uri, $downloadUrl);
            if (Storage::disk('public')->exists($cachePath)) {
                return $this->streamStoredAttachment($cachePath, $download);
            }

            $accessToken = decrypt($ringCentralUser->access_token);
            $client = new \GuzzleHttp\Client(['timeout' => 30]);
            $response = $client->get($downloadUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept' => '*/*',
                ],
            ]);

            if ($response->getStatusCode() !== 200) {
                return response()->json(['success' => false, 'message' => 'Attachment not found.'], 404);
            }

            $body = (string) $response->getBody();
            if ($body === '') {
                return response()->json(['success' => false, 'message' => 'Attachment not found.'], 404);
            }

            Storage::disk('public')->put($cachePath, $body);

            return $this->streamStoredAttachment($cachePath, $download);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to fetch attachment.'], 500);
        }
    }

    private function streamStoredAttachment(string $path, bool $download = false)
    {
        $mime = Storage::disk('public')->mimeType($path) ?: 'application/octet-stream';
        $filename = basename($path) ?: 'attachment';
        $stream = Storage::disk('public')->readStream($path);

        return response()->stream(function () use ($stream) {
            if (is_resource($stream)) {
                fpassthru($stream);
            }
        }, 200, [
            'Content-Type' => $mime,
            'Content-Disposition' => ($download ? 'attachment' : 'inline') . '; filename="' . $filename . '"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function buildAttachmentCachePath(int $userId, string $uri, string $downloadUrl): string
    {
        $pathPart = parse_url($downloadUrl, PHP_URL_PATH);
        $baseName = $pathPart ? basename($pathPart) : 'attachment';
        $safeBaseName = preg_replace('/[^A-Za-z0-9._-]/', '_', (string) $baseName);
        if (!$safeBaseName) {
            $safeBaseName = 'attachment';
        }

        $hash = substr(sha1($uri), 0, 20);
        return 'ringcentral_attachments/' . $userId . '/uri_' . $hash . '_' . $safeBaseName;
    }

    /**
     * List SMS templates for the current R-Dialer number
     */
    public function listSmsTemplates(Request $request)
    {
        $ringCentralUser = RingCentralUser::where('user_id', Auth::id())->first();
        if (!$ringCentralUser) {
            return response()->json([
                'success' => false,
                'message' => 'Not connected to R-Portal',
            ], 404);
        }

        $phone = $ringCentralUser->phone_number;

        $templates = Template::query()
            ->where('status', 2)
            ->where('phone_number', $phone)
            ->orderBy('name')
            ->get(['id', 'name', 'description', 'template_image', 'phone_number']);

        return response()->json([
            'success' => true,
            'templates' => $templates,
        ]);
    }

    /**
     * Create a new SMS template
     */
    public function createSmsTemplate(Request $request)
    {
        $ringCentralUser = RingCentralUser::where('user_id', Auth::id())->first();
        if (!$ringCentralUser) {
            return response()->json([
                'success' => false,
                'message' => 'Not connected to R-Portal',
            ], 404);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $template = Template::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'status' => 2,
            'notification_type' => 1,
            'template_image' => null,
            'phone_number' => $ringCentralUser->phone_number,
        ]);

        return response()->json([
            'success' => true,
            'template' => $template,
        ]);
    }

    /**
     * Mark messages as read
     */
    public function markMessagesRead(Request $request)
    {
        $request->validate([
            'message_ids' => 'required|array',
            'message_ids.*' => 'string',
        ]);

        $messageIds = array_values(array_filter($request->input('message_ids', []), function ($id) {
            return $id !== null && $id !== '';
        }));

        if (empty($messageIds)) {
            return response()->json([
                'success' => true,
                'updated_ids' => [],
                'failed' => [],
            ]);
        }

        $result = $this->ringCentralService->markMessagesRead(Auth::id(), $messageIds);

        $status = !empty($result['success']) ? 200 : 500;
        return response()->json($result, $status);
    }

    public function markVoicemailsStatus(Request $request)
    {
        $request->validate([
            'voicemail_ids' => 'required|array',
            'voicemail_ids.*' => 'string',
            'status' => 'required|string|in:read,listened,unread',
        ]);

        $ringCentralUser = RingCentralUser::where('user_id', Auth::id())->first();
        if (!$ringCentralUser) {
            return response()->json([
                'success' => false,
                'message' => 'Not connected to R-Portal',
            ], 404);
        }

        $voicemailIds = array_values(array_filter($request->input('voicemail_ids', []), function ($id) {
            return $id !== null && $id !== '';
        }));

        if (empty($voicemailIds)) {
            return response()->json([
                'success' => true,
                'updated' => 0,
                'status' => strtolower((string) $request->input('status', 'read')),
                'summary' => $this->buildVoicemailSummary($ringCentralUser->id, $ringCentralUser->phone_number),
            ]);
        }

        $requestedStatus = strtolower((string) $request->input('status', 'read'));
        $targetStatus = in_array($requestedStatus, ['read', 'listened'], true) ? 'listened' : 'unread';

        $sharedUserIds = $this->getSharedRingCentralUserIdsByPhone($ringCentralUser->phone_number);
        if (empty($sharedUserIds)) {
            $sharedUserIds = [$ringCentralUser->id];
        }

        $updated = RingCentralVoicemail::query()
            ->whereIn('ringcentral_user_id', $sharedUserIds)
            ->whereIn('voicemail_id', $voicemailIds)
            ->update([
                'message_status' => $targetStatus,
                'updated_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'updated' => $updated,
            'status' => $targetStatus,
            'updated_ids' => $voicemailIds,
            'summary' => $this->buildVoicemailSummary($ringCentralUser->id, $ringCentralUser->phone_number),
        ]);
    }

    private function getSharedRingCentralUserIdsByPhone($phoneNumber)
    {
        $digits = preg_replace('/\D/', '', (string) $phoneNumber);
        if ($digits === '') {
            return [];
        }

        $users = RingCentralUser::whereNotNull('phone_number')->get();

        $matching = $users->filter(function ($user) use ($digits) {
            $userDigits = preg_replace('/\D/', '', (string) $user->phone_number);
            if ($userDigits === '') {
                return false;
            }
            return $userDigits === $digits
                || str_ends_with($userDigits, $digits)
                || str_ends_with($digits, $userDigits);
        })->pluck('id')->values()->all();

        return !empty($matching) ? $matching : [];
    }

    private function ringCentralTokenRefreshWindowSeconds(): int
    {
        $seconds = (int) config('services.ringcentral.token_refresh_window_seconds', 300);
        return max(60, $seconds);
    }

    /**
     * Resolve a recording URL by call/log identifier.
     */
    public function getRecording($recordingId)
    {
        try {
            $ringCentralUser = RingCentralUser::where('user_id', Auth::id())->first();
            if (!$ringCentralUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not connected to R-Portal',
                ], 404);
            }

            $sharedUserIds = $this->getSharedRingCentralUserIdsByPhone($ringCentralUser->phone_number);
            if (empty($sharedUserIds)) {
                $sharedUserIds = [$ringCentralUser->id];
            }

            $recordingIdText = trim((string) $recordingId);
            $call = RingCentralCallLog::query()
                ->whereIn('ringcentral_user_id', $sharedUserIds)
                ->where(function ($q) use ($recordingIdText) {
                    $q->where('call_id', $recordingIdText);
                    if (ctype_digit($recordingIdText)) {
                        $q->orWhere('id', (int) $recordingIdText);
                    }
                })
                ->orderByDesc('id')
                ->first();

            if ($call && !empty($call->recording_url)) {
                return response()->json([
                    'success' => true,
                    'url' => $call->recording_url,
                    'source' => 'local_db',
                ]);
            }

            // Fallback for legacy consumers that may pass provider recording IDs.
            $result = $this->ringCentralService->getRecordingUrl(Auth::id(), $recordingIdText);
            if (!empty($result['success']) && !empty($result['url'])) {
                return response()->json([
                    'success' => true,
                    'url' => $result['url'],
                    'source' => 'provider',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Recording not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get recording: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Lookup a phone number to see if it's owned/assigned in the account
     */
    public function lookupNumber(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);


        $result = $this->ringCentralService->lookupNumberOwnership(Auth::id(), $request->phone);

        return response()->json($result);
    }

    /**
     * Get available phone numbers for the user
     */
    public function getPhoneNumbers()
    {
        try {
            $ringCentralUser = \App\RingCentralUser::where('user_id', Auth::id())->first();

            if (!$ringCentralUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not connected to R-Portal',
                ], 404);
            }

            // Get available phone numbers from RingCentral
            $result = $this->ringCentralService->refreshPhoneNumbers(Auth::id());

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get phone numbers: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Return a short-lived access token for the browser WebPhone client
     */
    public function webphoneToken(Request $request)
    {
        $requestUuid = (string) Str::uuid();
        try {
            $ringCentralUser = \App\RingCentralUser::where('user_id', Auth::id())->firstOrFail();

            $forceRefresh = $request->query('force_refresh') == '1';
            $instanceId = $request->query('instance_id') ?? $request->header('X-Instance-ID');
            $triggerMeta = $this->parseTokenTriggerMeta($request);
            $expiresAtBefore = $ringCentralUser->token_expires_at ? (int) $ringCentralUser->token_expires_at->timestamp : null;
            $refreshWindowSeconds = $this->ringCentralTokenRefreshWindowSeconds();
            $refreshNeeded = $forceRefresh
                || (
                    $ringCentralUser->token_expires_at
                    && now()->isAfter($ringCentralUser->token_expires_at->copy()->subSeconds($refreshWindowSeconds))
                );

            if (!empty($triggerMeta['warning'])) {
                $this->logTokenActivity('warning', 'webphone_token_trigger_invalid', [
                    'request_uuid' => $requestUuid,
                    'user_id' => (int) Auth::id(),
                    'ringcentral_user_id' => (int) $ringCentralUser->id,
                    'instance_id' => $instanceId,
                    'warning' => $triggerMeta['warning'],
                    'raw_trigger_id' => $triggerMeta['raw_trigger_id'],
                    'raw_trigger_name' => $triggerMeta['raw_trigger_name'],
                    'raw_trigger_mode' => $triggerMeta['raw_trigger_mode'],
                ]);
            }

            $this->logTokenActivity('info', 'webphone_token_request', [
                'request_uuid' => $requestUuid,
                'user_id' => (int) Auth::id(),
                'ringcentral_user_id' => (int) $ringCentralUser->id,
                'force_refresh' => (bool) $forceRefresh,
                'refresh_needed' => (bool) $refreshNeeded,
                'token_expires_at_unix' => $expiresAtBefore,
                'instance_id' => $instanceId,
                'ip' => $request->ip(),
                'trigger_id' => (int) $triggerMeta['trigger_id'],
                'trigger_name' => (string) $triggerMeta['trigger_name'],
                'trigger_mode' => (string) $triggerMeta['trigger_mode'],
            ]);

            if ($refreshNeeded) {
                $this->logTokenActivity('info', 'token_refresh_attempt', [
                    'request_uuid' => $requestUuid,
                    'user_id' => (int) Auth::id(),
                    'ringcentral_user_id' => (int) $ringCentralUser->id,
                    'force_refresh' => (bool) $forceRefresh,
                    'instance_id' => $instanceId,
                    'trigger_id' => (int) $triggerMeta['trigger_id'],
                    'trigger_name' => (string) $triggerMeta['trigger_name'],
                    'trigger_mode' => (string) $triggerMeta['trigger_mode'],
                ]);

                if (!$this->ringCentralService->refreshToken($ringCentralUser, $forceRefresh)) {
                    $this->logTokenActivity('warning', 'token_refresh_failed', [
                        'request_uuid' => $requestUuid,
                        'user_id' => (int) Auth::id(),
                        'ringcentral_user_id' => (int) $ringCentralUser->id,
                        'force_refresh' => (bool) $forceRefresh,
                        'instance_id' => $instanceId,
                        'trigger_id' => (int) $triggerMeta['trigger_id'],
                        'trigger_name' => (string) $triggerMeta['trigger_name'],
                        'trigger_mode' => (string) $triggerMeta['trigger_mode'],
                    ]);
                    return response()->json(['success' => false, 'message' => 'Token refresh failed'], 500);
                }

                $ringCentralUser = \App\RingCentralUser::find($ringCentralUser->id);
                $this->logTokenActivity('info', 'token_refresh_success', [
                    'request_uuid' => $requestUuid,
                    'user_id' => (int) Auth::id(),
                    'ringcentral_user_id' => (int) $ringCentralUser->id,
                    'force_refresh' => (bool) $forceRefresh,
                    'token_expires_at_unix' => $ringCentralUser->token_expires_at ? (int) $ringCentralUser->token_expires_at->timestamp : null,
                    'instance_id' => $instanceId,
                    'trigger_id' => (int) $triggerMeta['trigger_id'],
                    'trigger_name' => (string) $triggerMeta['trigger_name'],
                    'trigger_mode' => (string) $triggerMeta['trigger_mode'],
                ]);
            }

            // Use tokens from phone-number-based token file
            $tokenFileData = null;
            $filePhoneNumber = $ringCentralUser->phone_number;
            $tokensFile = $this->ringCentralService->getTokensFilePathForUser($filePhoneNumber);
            $tokenSource = 'database';
            if (file_exists($tokensFile) && filesize($tokensFile) > 0) {
                try {
                    $fileData = json_decode(file_get_contents($tokensFile), true);
                    if (is_array($fileData) && !empty($fileData['access_token'])) {
                        $tokenFileData = $fileData;
                        $tokenSource = 'token_file';
                        $accessToken = $fileData['access_token'];
                        $refreshToken = $fileData['refresh_token'] ?? null;
                        $nowTs = now()->timestamp;
                        if (!empty($fileData['expire_time'])) {
                            $expiresIn = max(60, intval($fileData['expire_time']) - $nowTs);
                        } elseif ($ringCentralUser->token_expires_at) {
                            $expiresIn = max(60, $ringCentralUser->token_expires_at->diffInSeconds(now()));
                        } elseif (isset($fileData['expires_in'])) {
                            $expiresIn = max(60, intval($fileData['expires_in']));
                        } else {
                            $expiresIn = 60;
                        }
                    } else {
                        // Fallback to DB
                        $tokenSource = 'database_fallback_invalid_file';
                        $accessToken = decrypt($ringCentralUser->access_token);
                        $refreshToken = decrypt($ringCentralUser->refresh_token);
                        $expiresIn = $ringCentralUser->token_expires_at
                            ? max(60, $ringCentralUser->token_expires_at->diffInSeconds(now()))
                            : 60;
                    }
                } catch (\Exception $e) {
                    $tokenSource = 'database_fallback_file_exception';
                    $accessToken = decrypt($ringCentralUser->access_token);
                    $refreshToken = decrypt($ringCentralUser->refresh_token);
                    $expiresIn = $ringCentralUser->token_expires_at
                        ? max(60, $ringCentralUser->token_expires_at->diffInSeconds(now()))
                        : 60;

                    $this->logTokenActivity('warning', 'token_file_read_exception', [
                        'user_id' => (int) Auth::id(),
                        'ringcentral_user_id' => (int) $ringCentralUser->id,
                        'tokens_file' => $tokensFile,
                        'error' => $e->getMessage(),
                    ]);
                }
            } else {
                $tokenSource = 'database_no_file';
                $accessToken = decrypt($ringCentralUser->access_token);
                $refreshToken = decrypt($ringCentralUser->refresh_token);
                $expiresIn = $ringCentralUser->token_expires_at
                    ? max(60, $ringCentralUser->token_expires_at->diffInSeconds(now()))
                    : 60;
            }

            $this->logTokenActivity('info', 'token_source_selected', [
                'user_id' => (int) Auth::id(),
                'ringcentral_user_id' => (int) $ringCentralUser->id,
                'source' => $tokenSource,
                'expires_in' => (int) $expiresIn,
                'has_token_file_data' => is_array($tokenFileData),
            ]);

            $timerState = $this->syncSharedTokenTimer(
                $ringCentralUser,
                $tokenFileData,
                (int) $expiresIn,
                $forceRefresh
            );
            $expiresIn = max(1, (int) $timerState['expires_in']);
            $expiresAtUnix = (int) $timerState['expires_at_unix'];

            $this->logTokenActivity('info', 'token_timer_synced', [
                'user_id' => (int) Auth::id(),
                'ringcentral_user_id' => (int) $ringCentralUser->id,
                'expires_in' => $expiresIn,
                'expires_at_unix' => $expiresAtUnix,
                'server_time_unix' => (int) $timerState['server_time_unix'],
            ]);

            $sipInfo = $this->ringCentralService->getSipInfo(Auth::id(), $forceRefresh);

            if (!$sipInfo) {
                $this->logTokenActivity('warning', 'webphone_token_sip_missing', [
                    'user_id' => (int) Auth::id(),
                    'ringcentral_user_id' => (int) $ringCentralUser->id,
                    'force_refresh' => (bool) $forceRefresh,
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'SIP provisioning info not available. Please ensure you have a digital line assigned.'
                ], 400);
            }
            
            // CHECK: Validate instance access
            // ONE USER ONE ACCESS policy
            $authorizationId = is_array($sipInfo) ? ($sipInfo['authorizationId'] ?? null) : ($sipInfo->authorizationId ?? null);
            $instanceCheck = $this->checkAndManageInstance($authorizationId, $instanceId);
            
            // Handle error response from instance check
            if ($instanceCheck['error']) {
                $this->logTokenActivity('warning', 'webphone_token_instance_conflict', [
                    'user_id' => (int) Auth::id(),
                    'ringcentral_user_id' => (int) $ringCentralUser->id,
                    'instance_id' => $instanceId,
                    'authorization_id' => $authorizationId,
                    'message' => $instanceCheck['message'] ?? 'Instance conflict',
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => $instanceCheck['message']
                ], 409); // 409 Conflict - user already logged in on another device
            }
            

            // Convert SIP info to array format (handle both object and array)
            $sipInfoData = is_array($sipInfo) ? $sipInfo : (array) $sipInfo;
            
            // FIX: Validate SIP info completeness before returning
            $requiredSipFields = ['username', 'password', 'domain'];
            $missingFields = [];
            
            foreach ($requiredSipFields as $field) {
                if (empty($sipInfoData[$field])) {
                    $missingFields[] = $field;
                }
            }
            
            if (!empty($missingFields)) {
                $this->logTokenActivity('warning', 'webphone_token_missing_sip_fields', [
                    'user_id' => (int) Auth::id(),
                    'ringcentral_user_id' => (int) $ringCentralUser->id,
                    'missing_fields' => $missingFields,
                ]);
                
                
                return response()->json([
                    'success' => false,
                    'message' => 'Incomplete SIP information. Required fields missing: ' . implode(', ', $missingFields)
                ], 400);
            }
            
            // Return SIP info as array (WebPhone expects array format)
            $sipInfoArray = [
                [
                    'username' => $sipInfoData['username'] ?? null,
                    'password' => $sipInfoData['password'] ?? null,
                    'authorizationId' => $sipInfoData['authorizationId'] ?? null,
                    'domain' => $sipInfoData['domain'] ?? null,
                    'outboundProxy' => $sipInfoData['outboundProxy'] ?? null,
                    'transport' => $sipInfoData['transport'] ?? 'WSS',
                    'wsUri' => $sipInfoData['wsUri'] ?? null,
                    'displayName' => $sipInfoData['displayName'] ?? null,
                ]
            ];

            $refreshExpiresIn = null;
            if ($ringCentralUser->token_expires_at) {
                $refreshExpiresIn = max(0, now()->diffInSeconds($ringCentralUser->token_expires_at, false));
            }

            $this->logTokenActivity('info', 'webphone_token_response_ok', [
                'request_uuid' => $requestUuid,
                'user_id' => (int) Auth::id(),
                'ringcentral_user_id' => (int) $ringCentralUser->id,
                'force_refresh' => (bool) $forceRefresh,
                'instance_id' => $instanceId,
                'expires_in' => (int) $expiresIn,
                'expires_at_unix' => (int) $expiresAtUnix,
                'refresh_token_expires_in' => $refreshExpiresIn,
                'max_instances' => (int) self::getMaxInstances(),
                'trigger_id' => (int) $triggerMeta['trigger_id'],
                'trigger_name' => (string) $triggerMeta['trigger_name'],
                'trigger_mode' => (string) $triggerMeta['trigger_mode'],
            ]);

            return response()->json([
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'expires_in' => $expiresIn,
                'expires_at_unix' => $expiresAtUnix,
                'server_time_unix' => (int) $timerState['server_time_unix'],
                'refresh_token_expires_in' => $refreshExpiresIn,
                'token_type' => 'Bearer',
                'server' => config('services.ringcentral.server'),
                'client_id' => config('services.ringcentral.client_id'),
                'max_instances' => self::getMaxInstances(),
                'request_uuid' => $requestUuid,
                'sipInfo' => $sipInfoArray
            ]);
        } catch (\Exception $e) {
            $this->logTokenActivity('error', 'webphone_token_exception', [
                'request_uuid' => $requestUuid,
                'user_id' => (int) Auth::id(),
                'message' => $e->getMessage(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate webphone token: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Return shared token timer state (phone-number based) so all devices stay in sync.
     */
    public function webphoneTokenTimer(Request $request)
    {
        try {
            $ringCentralUser = RingCentralUser::where('user_id', Auth::id())->firstOrFail();
            $instanceId = $request->query('instance_id') ?? $request->header('X-Instance-ID');
            $this->logTokenActivity('info', 'webphone_token_timer_request', [
                'user_id' => (int) Auth::id(),
                'ringcentral_user_id' => (int) $ringCentralUser->id,
                'instance_id' => $instanceId,
            ]);

            $tokenFileData = $this->readTokenFileData($ringCentralUser);
            $fallbackExpiresIn = $ringCentralUser->token_expires_at
                ? $ringCentralUser->token_expires_at->diffInSeconds(now(), false)
                : 0;

            $timerState = $this->syncSharedTokenTimer(
                $ringCentralUser,
                $tokenFileData,
                (int) $fallbackExpiresIn,
                false
            );

            return response()->json([
                'success' => true,
                'expires_in' => (int) $timerState['expires_in'],
                'expires_at_unix' => (int) $timerState['expires_at_unix'],
                'server_time_unix' => (int) $timerState['server_time_unix'],
                'phone_key' => (string) $timerState['phone_key'],
            ]);
        } catch (\Exception $e) {
            $this->logTokenActivity('error', 'webphone_token_timer_exception', [
                'user_id' => (int) Auth::id(),
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to sync token timer',
            ], 500);
        }
    }

    private function readTokenFileData(RingCentralUser $ringCentralUser): ?array
    {
        $tokensFile = $this->ringCentralService->getTokensFilePathForUser($ringCentralUser->phone_number);
        if (!file_exists($tokensFile) || filesize($tokensFile) <= 0) {
            return null;
        }

        try {
            $raw = json_decode(file_get_contents($tokensFile), true);
            return is_array($raw) ? $raw : null;
        } catch (\Exception $e) {
            $this->logTokenActivity('warning', 'token_file_read_failed', [
                'user_id' => (int) Auth::id(),
                'ringcentral_user_id' => (int) $ringCentralUser->id,
                'tokens_file' => $tokensFile,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    private function logTokenActivity(string $level, string $event, array $context = []): void
    {
        $context['event'] = $event;
        $context['at'] = now()->toDateTimeString();

        try {
            Log::channel('ringcentral_token')->{$level}('R-Dialer token activity', $context);
        } catch (\Throwable $e) {
            Log::warning('Failed to write R-Dialer token activity log.', [
                'event' => $event,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function parseTokenTriggerMeta(Request $request): array
    {
        $validMap = [
            1 => 'scheduled-interval',
            2 => 'near-expiry-fast-check',
            3 => 'manual-warning-modal',
            4 => 'warning-auto-refresh',
            5 => 'countdown-expired',
            6 => 'silent-refresh',
        ];

        $rawId = $request->query('trigger_id');
        $rawName = trim((string) $request->query('trigger_name', ''));
        $rawMode = strtolower(trim((string) $request->query('trigger_mode', '')));
        $warning = null;

        $triggerId = is_numeric($rawId) ? (int) $rawId : 0;
        $triggerName = 'unknown';
        if (isset($validMap[$triggerId])) {
            $triggerName = $validMap[$triggerId];
        } else {
            $triggerId = 0;
        }

        $isExplicitUnknown = ((string) $rawId === '0') && ($rawName === 'unknown');

        if ($triggerId > 0 && $rawName !== '' && $rawName !== $triggerName) {
            $warning = 'Trigger name does not match trigger_id; normalized to canonical name.';
        } elseif ($triggerId === 0 && ($rawId !== null || $rawName !== '' || $rawMode !== '') && !$isExplicitUnknown) {
            $warning = 'Invalid trigger metadata; normalized to trigger_id=0/trigger_name=unknown.';
        } elseif ($triggerId === 0 && $rawId === null && $rawName === '' && $rawMode === '') {
            $warning = 'Missing trigger metadata; normalized to trigger_id=0/trigger_name=unknown.';
        }

        if (!in_array($rawMode, ['standard', 'forced', 'silent'], true)) {
            $triggerMode = 'standard';
            if (!$warning && $rawMode !== '') {
                $warning = 'Invalid trigger mode; normalized to standard.';
            }
        } else {
            $triggerMode = $rawMode;
        }

        return [
            'trigger_id' => $triggerId,
            'trigger_name' => $triggerName,
            'trigger_mode' => $triggerMode,
            'raw_trigger_id' => $rawId,
            'raw_trigger_name' => $rawName,
            'raw_trigger_mode' => $rawMode,
            'warning' => $warning,
        ];
    }

    private function normalizePhoneForTimerKey(?string $phoneNumber): string
    {
        $digits = preg_replace('/\D+/', '', (string) $phoneNumber);
        if (!empty($digits)) {
            return $digits;
        }

        return 'user_' . (string) Auth::id();
    }

    private function tokenTimerCacheKey(?string $phoneNumber): string
    {
        return 'ringcentral_token_timer_' . hash('sha256', $this->normalizePhoneForTimerKey($phoneNumber));
    }

    private function resolveSharedTokenExpiryUnix(
        RingCentralUser $ringCentralUser,
        ?array $tokenFileData,
        int $fallbackExpiresIn
    ): int {
        $nowTs = now()->timestamp;

        if (is_array($tokenFileData) && !empty($tokenFileData['expire_time']) && is_numeric($tokenFileData['expire_time'])) {
            return (int) $tokenFileData['expire_time'];
        }

        if ($ringCentralUser->token_expires_at) {
            return (int) $ringCentralUser->token_expires_at->timestamp;
        }

        if (is_array($tokenFileData) && isset($tokenFileData['expires_in']) && is_numeric($tokenFileData['expires_in'])) {
            return $nowTs + max(1, (int) $tokenFileData['expires_in']);
        }

        return $nowTs + max(1, (int) $fallbackExpiresIn);
    }

    private function syncSharedTokenTimer(
        RingCentralUser $ringCentralUser,
        ?array $tokenFileData,
        int $fallbackExpiresIn,
        bool $forceOverwrite = false
    ): array {
        $nowTs = now()->timestamp;
        $phoneKey = $this->normalizePhoneForTimerKey($ringCentralUser->phone_number);
        $cacheKey = $this->tokenTimerCacheKey($ringCentralUser->phone_number);
        $resolvedExpiryUnix = $this->resolveSharedTokenExpiryUnix($ringCentralUser, $tokenFileData, $fallbackExpiresIn);

        $cached = Cache::get($cacheKey);
        $cachedExpiryUnix = is_array($cached) && isset($cached['expires_at_unix'])
            ? (int) $cached['expires_at_unix']
            : 0;

        // Keep the furthest known expiry unless we're forcing an overwrite due a refresh request.
        $finalExpiryUnix = $resolvedExpiryUnix;
        if (!$forceOverwrite && $cachedExpiryUnix > $resolvedExpiryUnix) {
            $finalExpiryUnix = $cachedExpiryUnix;
        }

        $cachePayload = [
            'phone_key' => $phoneKey,
            'expires_at_unix' => $finalExpiryUnix,
            'updated_at_unix' => $nowTs,
        ];

        if ($forceOverwrite || $cachedExpiryUnix !== $finalExpiryUnix) {
            $ttlSeconds = max(120, ($finalExpiryUnix - $nowTs) + 3600);
            Cache::put($cacheKey, $cachePayload, now()->addSeconds($ttlSeconds));
        }

        return [
            'phone_key' => $phoneKey,
            'expires_at_unix' => $finalExpiryUnix,
            'expires_in' => $finalExpiryUnix - $nowTs,
            'server_time_unix' => $nowTs,
        ];
    }

    /**
     * Get voicemails
     */
    public function getVoicemails(Request $request)
    {
        try {
            $direction = $request->get('direction');
            $perPage = min(max((int) $request->get('per_page', 25), 1), 100);
            $before = $request->get('before'); // cursor: ISO8601|id
            $refresh = $request->boolean('refresh');
            $q = trim((string) $request->get('q', ''));
            $rcFlow = (string) $request->query('_rc_flow', '');
            $rcSeq = (string) $request->query('_rc_seq', '');

            VoicemailOnlyLog::info('Tab request received', [
                'user_id' => Auth::id(),
                'direction' => $direction,
                'per_page' => $perPage,
                'before' => $before,
                'refresh' => $refresh,
                'q' => $q,
                'rc_flow' => $rcFlow,
                'rc_seq' => $rcSeq,
            ]);

            $ringCentralUser = RingCentralUser::where('user_id', Auth::id())->first();
            if (!$ringCentralUser) {
                VoicemailOnlyLog::warning('Tab request denied: R-Dialer user not connected', [
                    'user_id' => Auth::id(),
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Not connected to R-Portal',
                ], 404);
            }
            VoicemailOnlyLog::info('Tab request resolved R-Dialer user', [
                'user_id' => Auth::id(),
                'ringcentral_user_id' => $ringCentralUser->id,
                'phone_number' => $ringCentralUser->phone_number,
            ]);

            $syncMeta = [
                'attempted' => $refresh,
                'success' => null,
                'message' => null,
                'count' => null,
                'background' => false,
                'queued' => false,
            ];

            if ($refresh) {
                $syncFilters = ['count' => min(max((int) $request->get('count', 50), 1), 50)];
                if ($direction) {
                    $syncFilters['direction'] = $direction;
                }
                if (!empty($ringCentralUser->phone_number)) {
                    $syncFilters['phoneNumber'] = $ringCentralUser->phone_number;
                }

                $queueState = $this->queueRingCentralTabSync(
                    'voicemail',
                    Auth::id(),
                    $ringCentralUser,
                    $syncFilters,
                    [
                        'rc_flow' => $rcFlow,
                        'rc_seq' => $rcSeq,
                    ]
                );
                $syncMeta['background'] = true;
                $syncMeta['queued'] = (bool) ($queueState['queued'] ?? false);
                $syncMeta['message'] = $queueState['message'] ?? null;
            }

            $sharedUserIds = $this->getSharedRingCentralUserIdsByPhone($ringCentralUser->phone_number);
            if (empty($sharedUserIds)) {
                $sharedUserIds = [$ringCentralUser->id];
            }
            $blockedMap = $this->dialerBlockService->getBlockedMapForRingCentralUsers($sharedUserIds);
            $localDbTotalForPhone = RingCentralVoicemail::query()
                ->whereIn('ringcentral_user_id', $sharedUserIds)
                ->get()
                ->filter(function (RingCentralVoicemail $vm) use ($blockedMap) {
                    $phone = $vm->caller_number ?: $vm->phone_number;
                    return !$this->isPhoneBlockedByRingCentralUserMap($blockedMap, (int) $vm->ringcentral_user_id, $phone);
                })
                ->count();
            $localDbUnreadForPhone = RingCentralVoicemail::query()
                ->whereIn('ringcentral_user_id', $sharedUserIds)
                ->where('message_status', 'unread')
                ->get()
                ->filter(function (RingCentralVoicemail $vm) use ($blockedMap) {
                    $phone = $vm->caller_number ?: $vm->phone_number;
                    return !$this->isPhoneBlockedByRingCentralUserMap($blockedMap, (int) $vm->ringcentral_user_id, $phone);
                })
                ->count();

            VoicemailOnlyLog::info('Local DB baseline for voicemail tab', [
                'user_id' => Auth::id(),
                'phone_number' => $ringCentralUser->phone_number,
                'shared_user_ids' => $sharedUserIds,
                'local_total' => $localDbTotalForPhone,
                'local_unread' => $localDbUnreadForPhone,
            ]);

            $query = RingCentralVoicemail::whereIn('ringcentral_user_id', $sharedUserIds);
            if ($q !== '') {
                $query->where(function ($sub) use ($q) {
                    $sub->where('voicemail_id', 'like', "%{$q}%")
                        ->orWhere('caller_number', 'like', "%{$q}%")
                        ->orWhere('phone_number', 'like', "%{$q}%")
                        ->orWhere('transcription', 'like', "%{$q}%")
                        ->orWhere('message_status', 'like', "%{$q}%");
                });
            }
            $filteredTotalBeforeCursor = (clone $query)->get()->filter(function (RingCentralVoicemail $vm) use ($blockedMap) {
                $phone = $vm->caller_number ?: $vm->phone_number;
                return !$this->isPhoneBlockedByRingCentralUserMap($blockedMap, (int) $vm->ringcentral_user_id, $phone);
            })->count();

            if ($before) {
                try {
                    [$timeStr, $id] = explode('|', $before, 2) + [null, null];
                    if ($timeStr) {
                        if (ctype_digit((string) $timeStr)) {
                            $time = Carbon::createFromTimestamp((int) $timeStr)->toDateTimeString();
                        } else {
                            $time = Carbon::parse($timeStr)->toDateTimeString();
                        }
                        $query->where(function ($qwhere) use ($time, $id) {
                            $qwhere->whereRaw('COALESCE(received_at, created_at) < ?', [$time]);
                            if ($id) {
                                $qwhere->orWhere(function ($q2) use ($time, $id) {
                                    $q2->whereRaw('COALESCE(received_at, created_at) = ?', [$time])
                                        ->where('id', '<', (int) $id);
                                });
                            }
                        });
                    }
                } catch (\Exception $e) {
                    // ignore malformed cursor
                }
            }

            $items = $query
                ->orderByRaw('COALESCE(received_at, created_at) DESC')
                ->orderBy('id', 'desc')
                ->limit($perPage + 1)
                ->get();

            $hasMore = $items->count() > $perPage;
            if ($hasMore) {
                $items = $items->slice(0, $perPage);
            }
            $items = $items->values()->filter(function (RingCentralVoicemail $vm) use ($blockedMap) {
                $phone = $vm->caller_number ?: $vm->phone_number;
                return !$this->isPhoneBlockedByRingCentralUserMap($blockedMap, (int) $vm->ringcentral_user_id, $phone);
            })->values();

            $data = $items->map(function (RingCentralVoicemail $vm) {
                return $this->mapVoicemailRecord($vm);
            })->values();

            $nextCursor = null;
            if ($items->count()) {
                $last = $items->last();
                $cursorTime = $this->toUtcIsoFromDatabaseColumns($last, ['received_at', 'created_at']);
                if ($cursorTime) {
                    $nextCursor = $cursorTime . '|' . $last->id;
                }
            }

            $responsePayload = [
                'success' => true,
                'data' => $data,
                'pagination' => [
                    'per_page' => $perPage,
                    'has_more' => $hasMore,
                    'next_cursor' => $nextCursor,
                ],
                'summary' => [
                    'total_available' => $filteredTotalBeforeCursor,
                    'unread_total' => $localDbUnreadForPhone,
                    'local_total' => $localDbTotalForPhone,
                ],
                'source' => 'local_db',
                'sync' => $syncMeta,
            ];

            VoicemailOnlyLog::info('Tab response ready', [
                'user_id' => Auth::id(),
                'phone_number' => $ringCentralUser->phone_number,
                'filtered_total_before_cursor' => $filteredTotalBeforeCursor,
                'returned_items' => $data->count(),
                'has_more' => $hasMore,
                'next_cursor' => $nextCursor,
                'refresh_background' => $syncMeta['background'],
                'refresh_queued' => $syncMeta['queued'],
                'refresh_message' => $syncMeta['message'],
                'rc_flow' => $rcFlow,
                'rc_seq' => $rcSeq,
            ]);

            return response()->json($responsePayload);
        } catch (\Throwable $e) {
            VoicemailOnlyLog::error('Tab request failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get single voicemail
     */
    public function getVoicemail($voicemailId)
    {
        try {
            VoicemailOnlyLog::info('Detail request received', [
                'user_id' => Auth::id(),
                'voicemail_id' => $voicemailId,
            ]);

            $result = $this->ringCentralService->getVoicemail(Auth::id(), $voicemailId);
            VoicemailOnlyLog::info('Detail request completed', [
                'user_id' => Auth::id(),
                'voicemail_id' => $voicemailId,
                'success' => (bool) ($result['success'] ?? false),
            ]);

            return response()->json($result);
        } catch (\Exception $e) {
            VoicemailOnlyLog::error('Detail request failed', [
                'user_id' => Auth::id(),
                'voicemail_id' => $voicemailId,
                'error' => $e->getMessage(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete voicemail
     */
    public function deleteVoicemail($voicemailId)
    {
        try {
            VoicemailOnlyLog::info('Delete request received', [
                'user_id' => Auth::id(),
                'voicemail_id' => $voicemailId,
            ]);

            $ringCentralUser = RingCentralUser::where('user_id', Auth::id())->first();
            if (!$ringCentralUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not connected to R-Portal',
                ], 404);
            }

            $result = $this->ringCentralService->deleteVoicemail(Auth::id(), $voicemailId);
            VoicemailOnlyLog::info('Delete request completed', [
                'user_id' => Auth::id(),
                'voicemail_id' => $voicemailId,
                'success' => (bool) ($result['success'] ?? false),
            ]);

            if (!empty($result['success'])) {
                $result['summary'] = $this->buildVoicemailSummary($ringCentralUser->id, $ringCentralUser->phone_number);
            }

            return response()->json($result);
        } catch (\Exception $e) {
            VoicemailOnlyLog::error('Delete request failed', [
                'user_id' => Auth::id(),
                'voicemail_id' => $voicemailId,
                'error' => $e->getMessage(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getCallRecordings(Request $request)
    {
        try {
            $direction = $request->get('direction');
            $perPage = min(max((int) $request->get('per_page', 25), 1), 100);
            $before = $request->get('before'); // cursor: ISO8601|id
            $refresh = $request->boolean('refresh');
            $q = trim((string) $request->get('q', ''));
            $rcFlow = (string) $request->query('_rc_flow', '');
            $rcSeq = (string) $request->query('_rc_seq', '');


            $ringCentralUser = RingCentralUser::where('user_id', Auth::id())->first();
            if (!$ringCentralUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not connected to R-Portal',
                ], 404);
            }

            $syncMeta = [
                'attempted' => $refresh,
                'success' => null,
                'message' => null,
                'count' => null,
                'background' => false,
                'queued' => false,
            ];

            if ($refresh) {
                $syncFilters = ['count' => min(max((int) $request->get('count', 50), 1), 50)];
                if ($direction) {
                    $syncFilters['direction'] = $direction;
                }
                if (!empty($ringCentralUser->phone_number)) {
                    $syncFilters['phoneNumber'] = $ringCentralUser->phone_number;
                }

                $queueState = $this->queueRingCentralTabSync(
                    'recordings',
                    Auth::id(),
                    $ringCentralUser,
                    $syncFilters,
                    [
                        'rc_flow' => $rcFlow,
                        'rc_seq' => $rcSeq,
                    ]
                );
                $syncMeta['background'] = true;
                $syncMeta['queued'] = (bool) ($queueState['queued'] ?? false);
                $syncMeta['message'] = $queueState['message'] ?? null;
            } else {
            }

            $sharedUserIds = $this->getSharedRingCentralUserIdsByPhone($ringCentralUser->phone_number);
            if (empty($sharedUserIds)) {
                $sharedUserIds = [$ringCentralUser->id];
            }
            $blockedMap = $this->dialerBlockService->getBlockedMapForRingCentralUsers($sharedUserIds);


            $query = RingCentralCallLog::whereIn('ringcentral_user_id', $sharedUserIds)
                ->where(function ($sub) {
                    $sub->whereNull('type')->orWhereRaw('LOWER(type) = ?', ['voice']);
                })
                ->where(function ($sub) {
                    // Recordings tab should only include completed calls.
                    $sub->whereRaw('LOWER(status) = ?', ['completed'])
                        ->orWhereRaw('LOWER(status) = ?', ['call connected'])
                        ->orWhereRaw('LOWER(status) = ?', ['connected']);
                });

            if (!empty($direction)) {
                $query->whereRaw('LOWER(direction) = ?', [strtolower((string) $direction)]);
            }

            if ($q !== '') {
                $query->where(function ($sub) use ($q) {
                    $sub->where('call_id', 'like', "%{$q}%")
                        ->orWhere('from_number', 'like', "%{$q}%")
                        ->orWhere('to_number', 'like', "%{$q}%")
                        ->orWhere('phone_number', 'like', "%{$q}%");
                });
            }

            $filteredTotalBeforeCursor = (clone $query)->get()->filter(function (RingCentralCallLog $call) use ($ringCentralUser, $blockedMap) {
                $counterparty = $this->resolveCounterpartyForCallRow($call, $ringCentralUser->phone_number);
                return !$this->isPhoneBlockedByRingCentralUserMap($blockedMap, (int) $call->ringcentral_user_id, $counterparty);
            })->count();
            $filteredWithRecordingBeforeCursor = (clone $query)
                ->whereNotNull('recording_url')
                ->where('recording_url', '!=', '')
                ->get()
                ->filter(function (RingCentralCallLog $call) use ($ringCentralUser, $blockedMap) {
                    $counterparty = $this->resolveCounterpartyForCallRow($call, $ringCentralUser->phone_number);
                    return !$this->isPhoneBlockedByRingCentralUserMap($blockedMap, (int) $call->ringcentral_user_id, $counterparty);
                })
                ->count();

            if ($before) {
                try {
                    [$timeStr, $id] = explode('|', $before, 2) + [null, null];
                    if ($timeStr) {
                        $time = Carbon::parse($timeStr)->toDateTimeString();
                        $query->where(function ($qwhere) use ($time, $id) {
                            $qwhere->whereRaw('COALESCE(call_started_at, created_at) < ?', [$time]);
                            if ($id) {
                                $qwhere->orWhere(function ($q2) use ($time, $id) {
                                    $q2->whereRaw('COALESCE(call_started_at, created_at) = ?', [$time])
                                        ->where('id', '<', (int) $id);
                                });
                            }
                        });
                    }
                } catch (\Exception $e) {
                    // ignore malformed cursor
                }
            }

            $items = $query
                ->orderByRaw('COALESCE(call_started_at, created_at) DESC')
                ->orderBy('id', 'desc')
                ->limit($perPage + 1)
                ->get();

            $hasMore = $items->count() > $perPage;
            if ($hasMore) {
                $items = $items->slice(0, $perPage);
            }
            $items = $items->values()->filter(function (RingCentralCallLog $call) use ($ringCentralUser, $blockedMap) {
                $counterparty = $this->resolveCounterpartyForCallRow($call, $ringCentralUser->phone_number);
                return !$this->isPhoneBlockedByRingCentralUserMap($blockedMap, (int) $call->ringcentral_user_id, $counterparty);
            })->values();

            $data = $items->map(function (RingCentralCallLog $call) {
                return $this->mapRecordingRecord($call);
            })->values();

            $nextCursor = null;
            if ($items->count()) {
                $last = $items->last();
                $cursorTime = $this->toUtcIsoFromDatabaseColumns($last, ['call_started_at', 'created_at']);
                if ($cursorTime) {
                    $nextCursor = $cursorTime . '|' . $last->id;
                }
            }

            $responsePayload = [
                'success' => true,
                'data' => $data,
                'pagination' => [
                    'per_page' => $perPage,
                    'has_more' => $hasMore,
                    'next_cursor' => $nextCursor,
                ],
                'summary' => [
                    'total_available' => $filteredTotalBeforeCursor,
                    'with_recording_total' => $filteredWithRecordingBeforeCursor,
                ],
                'source' => 'local_db',
                'sync' => $syncMeta,
            ];


            return response()->json($responsePayload);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function queueRingCentralTabSync(string $scope, int $userId, RingCentralUser $ringCentralUser, array $filters, array $trace = []): array
    {
        $normalizedScope = strtolower(trim($scope));
        $phoneKey = preg_replace('/\D/', '', (string) ($ringCentralUser->phone_number ?? ''));
        $lockSuffix = $phoneKey !== '' ? ('phone:' . $phoneKey) : ('user:' . $userId);
        $syncLockKey = sprintf('rc:%s:background-sync:%s', $normalizedScope, $lockSuffix);
        $queued = Cache::add($syncLockKey, now()->toIso8601String(), now()->addMinutes(2));

        if (!$queued) {
            if ($normalizedScope === 'voicemail') {
                VoicemailOnlyLog::info('Background sync skipped (already locked)', [
                    'user_id' => $userId,
                    'phone_number' => $ringCentralUser->phone_number,
                    'filters' => $filters,
                    'rc_flow' => $trace['rc_flow'] ?? '',
                    'rc_seq' => $trace['rc_seq'] ?? '',
                ]);
            }

            return [
                'queued' => false,
                'message' => 'Background sync already running.',
            ];
        }

        if ($normalizedScope === 'voicemail') {
            VoicemailOnlyLog::info('Background sync queued', [
                'user_id' => $userId,
                'phone_number' => $ringCentralUser->phone_number,
                'filters' => $filters,
                'rc_flow' => $trace['rc_flow'] ?? '',
                'rc_seq' => $trace['rc_seq'] ?? '',
            ]);
        }


        app()->terminating(function () use ($normalizedScope, $userId, $syncLockKey, $filters, $trace) {
            $syncResult = null;

            try {
                @set_time_limit(0);
                @ignore_user_abort(true);

                if ($normalizedScope === 'voicemail') {
                    VoicemailOnlyLog::info('Background sync started', [
                        'user_id' => $userId,
                        'filters' => $filters,
                        'rc_flow' => $trace['rc_flow'] ?? '',
                        'rc_seq' => $trace['rc_seq'] ?? '',
                    ]);
                }


                if ($normalizedScope === 'voicemail') {
                    $syncResult = $this->ringCentralService->getVoicemails($userId, $filters);
                } elseif ($normalizedScope === 'recordings') {
                    $syncResult = $this->ringCentralService->getCallRecordings($userId, $filters);
                } else {
                    return;
                }

                if ($normalizedScope === 'voicemail') {
                    VoicemailOnlyLog::info('Background sync finished', [
                        'user_id' => $userId,
                        'success' => (bool) ($syncResult['success'] ?? false),
                        'count' => (int) ($syncResult['count'] ?? 0),
                        'total_synced' => (int) ($syncResult['total_synced'] ?? 0),
                        'pages_fetched' => (int) ($syncResult['pages_fetched'] ?? 0),
                        'api_total_fetched' => (int) ($syncResult['api_total_fetched'] ?? 0),
                        'local_db_total_before_sync' => (int) ($syncResult['local_db_total_before_sync'] ?? 0),
                        'local_db_total_after_sync' => (int) ($syncResult['local_db_total_after_sync'] ?? 0),
                        'message' => $syncResult['message'] ?? null,
                        'rc_flow' => $trace['rc_flow'] ?? '',
                        'rc_seq' => $trace['rc_seq'] ?? '',
                    ]);
                }
            } catch (\Throwable $e) {
                if ($normalizedScope === 'voicemail') {
                    VoicemailOnlyLog::error('Background sync failed', [
                        'user_id' => $userId,
                        'error' => $e->getMessage(),
                        'rc_flow' => $trace['rc_flow'] ?? '',
                        'rc_seq' => $trace['rc_seq'] ?? '',
                    ]);
                }
            } finally {
                Cache::forget($syncLockKey);
            }
        });

        return [
            'queued' => true,
            'message' => 'Refreshing in background.',
        ];
    }

    private function mapVoicemailRecord(RingCentralVoicemail $vm): array
    {
        $callerPhone = $vm->caller_number ?: $vm->phone_number;
        $receivedTime = $this->toUtcIsoFromDatabaseColumns($vm, ['received_at', 'created_at']);

        return [
            'id' => (string) $vm->voicemail_id,
            'voicemail_id' => (string) $vm->voicemail_id,
            'from' => [
                // Do not expose client names on portal.
                'name' => null,
                'phoneNumber' => $callerPhone,
            ],
            'phone_number' => $callerPhone,
            'caller_name' => null,
            'caller_number' => $callerPhone,
            'vmDuration' => (int) ($vm->duration_seconds ?? 0),
            'duration_seconds' => (int) ($vm->duration_seconds ?? 0),
            'transcription' => $vm->transcription,
            'audio_url' => $vm->audio_url,
            'media_url' => $vm->audio_url,
            'recording_url' => $vm->audio_url,
            'readStatus' => $vm->message_status ?: 'unread',
            'message_status' => $vm->message_status ?: 'unread',
            'creationTime' => $receivedTime,
            'received_at' => $receivedTime,
        ];
    }

    private function mapRecordingRecord(RingCentralCallLog $call): array
    {
        return [
            'id' => (string) $call->call_id,
            'call_id' => (string) $call->call_id,
            'recording_url' => $call->recording_url,
            'media_url' => $call->recording_url,
            'start_time' => $this->toUtcIsoFromDatabaseColumns($call, ['call_started_at', 'created_at']),
            'duration_seconds' => (int) ($call->duration_seconds ?? 0),
            'direction' => $call->direction,
            'from' => $call->from_number ?: $call->phone_number,
            'to' => $call->to_number ?: $call->phone_number,
            'from_number' => $call->from_number,
            'to_number' => $call->to_number,
            'phone_number' => $call->phone_number,
        ];
    }

    private function normalizeCallStatusLabel($rawStatus): string
    {
        $status = trim((string) ($rawStatus ?? ''));
        if ($status === '') {
            return 'Unknown';
        }

        $key = strtolower($status);
        $map = [
            'call connected' => 'Completed',
            'connected' => 'Completed',
            'completed' => 'Completed',
            'accepted' => 'Accepted',
            'missed' => 'Missed',
            'voicemail' => 'Voicemail',
            'busy' => 'Busy',
            'rejected' => 'Rejected',
            'blocked' => 'Blocked',
            'no answer' => 'NoAnswer',
            'noanswer' => 'NoAnswer',
            'call failed' => 'CallFailed',
            'callfailed' => 'CallFailed',
            'caller dropped' => 'CallerDropped',
            'callerdropped' => 'CallerDropped',
        ];

        return $map[$key] ?? $status;
    }
    
    /**
     * Check and manage WebPhone instance access
     * 
     * PHONE NUMBER SHARING ARCHITECTURE:
     * ===================================
     * Multiple users can share the same phone_number (authorization_id) as different instances.
     * Token files are stored by phone_number, not user_id.
     * 
     * LOGIC:
     * 1. Each user can have ONLY ONE active instance at a time
     * 2. If same user + same instance_id → Allow refresh (update last_seen_at)
     * 3. If same user + different instance_id → Block with "already logged in on another device"
     * 4. If different user + same phone/auth_id → Allow as separate instance (up to max limit)
     * 5. If max limit reached for phone/auth_id → Block with "max instances reached"
     * 
     * EXAMPLE:
     * - Phone: +13072227674 (authorization_id: abc123)
     * - User A (ID=5) → Instance 1 (using browser Chrome)
     * - User B (ID=6) → Instance 2 (using browser Firefox) 
     * - User C (ID=7) → Instance 3 (using browser Safari)
     * - All share the same token file: ringcentral_tokens_13072227674.json
     * - Each is tracked as separate instance in webphone_instances table
     * 
     * @param string $authorizationId R-Dialer authorization ID (tied to phone_number)
     * @param string $instanceId Browser instance UUID (unique per browser/tab)
     * @return array ['error' => bool, 'message' => string, 'count' => int]
     */
    private function checkAndManageInstance($authorizationId, $instanceId)
    {
        try {
            if (!$authorizationId || !$instanceId) {
                return [
                    'error' => true,
                    'message' => 'Missing authorization or instance ID'
                ];
            }
            
            $userId = Auth::id();
            
            // Clean up old closed instances (older than 1 hour) for all users sharing this auth_id
            WebPhoneInstance::where('authorization_id', $authorizationId)
                ->where('closed_at', '!=', null)
                ->where('closed_at', '<', now()->subHour())
                ->delete();
            
            // Check if this user already has an ACTIVE instance
            $existingInstance = WebPhoneInstance::where('user_id', $userId)
                ->where('closed_at', null)
                ->where('last_seen_at', '>', now()->subMinutes(0.25))
                ->first();
            
            if ($existingInstance) {
                // User HAS an active instance
                if ($existingInstance->instance_id === $instanceId) {
                    // SAME instance_id → Allow refresh (update it)
                    $existingInstance->update([
                        'last_seen_at' => now(),
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                    ]);
                    
                    
                    return [
                        'error' => false,
                        'message' => 'Instance refreshed',
                        'count' => 1
                    ];
                } else {
                    // DIFFERENT instance_id → Block with error (don't replace)
                    
                    return [
                        'error' => true,
                        'message' => 'You are already logged in on another device. Please logout from the other device first.',
                        'count' => 1
                    ];
                }
            } else {
                // User does NOT have an active instance (within 5-minute window)
                
                // FIRST: Check if this INSTANCE_ID already exists (in any state)
                $instanceExists = WebPhoneInstance::where('instance_id', $instanceId)->first();
                if ($instanceExists) {
                    if ($instanceExists->user_id === $userId) {
                        // SAME instance_id belongs to SAME user → Update it (refresh)
                        $instanceExists->update([
                            'last_seen_at' => now(),
                            'ip_address' => request()->ip(),
                            'user_agent' => request()->userAgent(),
                            'closed_at' => null, // Reactivate if closed
                            'authorization_id' => $authorizationId, // Ensure auth_id is up-to-date
                        ]);
                        
                        
                        return [
                            'error' => false,
                            'message' => 'Instance refreshed',
                            'count' => 1
                        ];
                    } else {
                        // SAME instance_id belongs to DIFFERENT user → ERROR
                        
                        return [
                            'error' => true,
                            'message' => 'This browser session is already in use. Please clear browser storage or try a different browser.',
                            'count' => 1
                        ];
                    }
                }
                
                // INSTANCE_ID does NOT exist yet → Check max limit for this authorization_id
                $maxAllowed = self::getMaxInstances();
                $currentCount = WebPhoneInstance::where('authorization_id', $authorizationId)
                    ->where('closed_at', null)
                    ->where('last_seen_at', '>', now()->subMinutes(0.25))
                    ->count();
                
                if ($currentCount >= $maxAllowed) {
                    
                    return [
                        'error' => true,
                        'message' => "Maximum WebPhone instances (" . $maxAllowed . ") reached for this account",
                        'count' => $currentCount
                    ];
                }
                
                // Create NEW instance using updateOrCreate to handle race conditions
                try {
                    // Since user_id has UNIQUE constraint (1-1 relation), we need to check and delete old instance first
                    $oldInstance = WebPhoneInstance::where('user_id', $userId)->first();
                    
                    if ($oldInstance) {
                        // User already has an instance (even if closed) → delete it to allow new one
                        $oldInstance->delete();
                    }
                    
                    // Now create the new instance (no duplicate user_id)
                    $instance = WebPhoneInstance::create([
                        'user_id' => $userId,
                        'instance_id' => $instanceId,
                        'authorization_id' => $authorizationId,
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                        'last_seen_at' => now(),
                        'closed_at' => null,
                    ]);
                    
                } catch (\Illuminate\Database\QueryException $e) {
                    // Log error but return a user-friendly message
                    
                    return [
                        'error' => true,
                        'message' => 'Failed to establish instance session. Please try refreshing the page.'
                    ];
                }
                
                return [
                    'error' => false,
                    'message' => 'Instance created or updated',
                    'count' => 1
                ];
            }
            
        } catch (\Exception $e) {
            
            return [
                'error' => true,
                'message' => 'Instance check failed: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get the count of active WebPhone instances for a user/authorization ID
     * Uses database + cache for persistent tracking across multiple servers
     * 
     * Active instances are those that:
     * 1. Haven't been explicitly closed
     * 2. Have been seen within the last 5 minutes
     * 
     * @param string|null $authorizationId The authorization ID to check
     * @return int Number of active instances
     */
    private function getActiveInstanceCount($authorizationId)
    {
        try {
            if (!$authorizationId) {
                return 0;
            }
            
            // Get instanceId from request header OR query parameter (fallback)
            $instanceId = request()->header('X-Instance-ID') ?? request()->query('instance_id');
            
            
            if (!$instanceId) {
                return 0;
            }
            
            $userId = Auth::id();
            
            // First, clean up old closed instances (older than 1 hour)
            WebPhoneInstance::where('user_id', $userId)
                ->where('closed_at', '!=', null)
                ->where('closed_at', '<', now()->subHour())
                ->delete();
            
            // ENFORCE: Only ONE active instance per user
            // Close all other active instances for this user (different instance_id)
            // ENFORCE: 1-1 Relation - Only ONE instance per user (update or create)
            // If user already has an instance, replace it with the new one
            $instance = WebPhoneInstance::updateOrCreate(
                ['user_id' => $userId], // Search condition: find by user_id
                [ // Update/Create data
                    'instance_id' => $instanceId,
                    'authorization_id' => $authorizationId,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'last_seen_at' => now(),
                    'closed_at' => null, // Always activate when accessing
                ]
            );
            
            // Check if this was a new instance or existing one being replaced
            $isNewInstance = $instance->wasRecentlyCreated;
            
            if ($isNewInstance) {
            } else {
            }
            
            // Count active instances for this USER (should always be 1 now)
            $activeCount = WebPhoneInstance::where('user_id', $userId)
                ->where('closed_at', null)
                ->where('last_seen_at', '>', now()->subMinutes(0.25))
                ->count();
            
            // Cache for 10 seconds to reduce DB hits
            $cacheKey = 'rc_active_count_' . $userId . '_' . hash('sha256', $authorizationId);
            cache()->put($cacheKey, $activeCount, 10);
            
            
            return $activeCount;
            
        } catch (\Exception $e) {
            return 0; // On error, allow the instance
        }
    }
    
    /**
     * Close/dispose an active WebPhone instance
     * Called by frontend when instance is being disposed
     * Only closes the specific instance, not all instances
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function closeInstance(Request $request)
    {
        try {
            $instanceId = $request->header('X-Instance-ID');
            
            if (!$instanceId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Instance ID not provided'
                ], 400);
            }
            
            $userId = Auth::id();
            
            // Find and close only this specific instance
            $instance = WebPhoneInstance::where('user_id', $userId)
                ->where('instance_id', $instanceId)
                ->first();
            
            if (!$instance) {
                
                return response()->json([
                    'success' => false,
                    'message' => 'Instance not found'
                ], 404);
            }
            
            // Close only this instance
            $instance->update(['closed_at' => now()]);
            
            
            // Clear related cache
            $cacheKey = 'rc_active_count_' . $userId . '_' . hash('sha256', $instance->authorization_id);
            cache()->forget($cacheKey);
            
            return response()->json([
                'success' => true,
                'message' => 'Instance closed successfully'
            ]);
            
        } catch (\Exception $e) {
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to close instance: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Server-Sent Events stream for webhook-driven UI refresh signals.
     */
    public function streamWebhookEvents(Request $request)
    {
        if (!Auth::check()) {
            return response('Unauthorized', 401);
        }

        $userId = (int) Auth::id();
        $sinceQuery = (int) $request->query('since', 0);
        $lastEventId = (int) $request->header('Last-Event-ID', 0);
        $since = max(0, $sinceQuery, $lastEventId);
        $historyKey = 'ringcentral:webhook:ui:events:' . $userId;
        $legacyLatestKey = 'ringcentral:webhook:ui:event:' . $userId;

        return response()->stream(function () use ($historyKey, $legacyLatestKey, $since) {
            // Release session lock immediately so other requests from this user are not blocked
            // for the full 55-second stream lifetime.
            session_write_close();

            @set_time_limit(0);
            @ignore_user_abort(true);
            @ini_set('output_buffering', 'off');
            @ini_set('zlib.output_compression', '0');

            if (function_exists('apache_setenv')) {
                @apache_setenv('no-gzip', '1');
            }

            $lastSeq = $since;
            $startedAt = microtime(true);
            $deadlineSeconds = 55;
            $lastPingAt = 0.0;

            while (!connection_aborted() && (microtime(true) - $startedAt) < $deadlineSeconds) {
                $history = Cache::get($historyKey, []);
                $eventsToEmit = [];

                if (is_array($history) && !empty($history)) {
                    foreach ($history as $payload) {
                        if (!is_array($payload)) {
                            continue;
                        }
                        $seq = (int) ($payload['seq'] ?? 0);
                        if ($seq <= $lastSeq) {
                            continue;
                        }
                        $eventsToEmit[] = $payload;
                    }
                } else {
                    // Backward-compatible fallback if only legacy latest payload is available.
                    $legacyPayload = Cache::get($legacyLatestKey);
                    if (is_array($legacyPayload) && (int) ($legacyPayload['seq'] ?? 0) > $lastSeq) {
                        $eventsToEmit[] = $legacyPayload;
                    }
                }

                if (!empty($eventsToEmit)) {
                    foreach ($eventsToEmit as $payload) {
                        $seq = (int) ($payload['seq'] ?? 0);
                        if ($seq <= $lastSeq) {
                            continue;
                        }

                        $eventPayload = [
                            'seq' => $seq,
                            'scopes' => array_values(array_filter((array) ($payload['scopes'] ?? []))),
                            'event' => $payload['event'] ?? null,
                            'ownerId' => $payload['ownerId'] ?? null,
                            'subscriptionId' => $payload['subscriptionId'] ?? null,
                            'partyNumber' => $payload['partyNumber'] ?? null,
                            'messageText' => $payload['messageText'] ?? null,
                            'durationSeconds' => $payload['durationSeconds'] ?? null,
                            'direction' => $payload['direction'] ?? null,
                            'toastEnabled' => (bool) ($payload['toast_enabled'] ?? true),
                            'events' => array_values(array_filter((array) ($payload['events'] ?? []))),
                            'ownerIds' => array_values(array_filter((array) ($payload['ownerIds'] ?? []))),
                            'subscriptionIds' => array_values(array_filter((array) ($payload['subscriptionIds'] ?? []))),
                            'partyNumbers' => array_values(array_filter((array) ($payload['partyNumbers'] ?? []))),
                            'messageTexts' => array_values(array_filter((array) ($payload['messageTexts'] ?? []))),
                            'durationSecondsList' => array_values(array_filter((array) ($payload['durationSecondsList'] ?? []))),
                            'batchedCount' => max(1, (int) ($payload['batched_count'] ?? 1)),
                            'at' => $payload['at'] ?? null,
                        ];

                        echo 'id: ' . $seq . "\n";
                        echo "event: webhook\n";
                        echo 'data: ' . json_encode($eventPayload, JSON_UNESCAPED_SLASHES) . "\n\n";

                        @ob_flush();
                        @flush();
                        $lastSeq = $seq;
                    }
                }

                $now = microtime(true);
                if (($now - $lastPingAt) >= 15) {
                    echo "event: ping\n";
                    echo 'data: {"ts":"' . now()->toIso8601String() . "\"}\n\n";
                    @ob_flush();
                    @flush();
                    $lastPingAt = $now;
                }

                usleep(500000);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache, no-transform',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}

