<?php

namespace App\Http\Controllers;

use App\RingCentralCallLog;
use App\RingCentralMessage;
use App\RingCentralUser;
use App\RingCentralVoicemail;
use App\Services\RingCentralDialerBlockService;
use App\Services\RingCentralService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

class RingCentralWebhookController extends Controller
{
    private RingCentralService $ringCentralService;
    private RingCentralDialerBlockService $dialerBlockService;
    private ?string $webhookTraceId = null;

    public function __construct(RingCentralService $ringCentralService, RingCentralDialerBlockService $dialerBlockService)
    {
        $this->ringCentralService = $ringCentralService;
        $this->dialerBlockService = $dialerBlockService;
    }

    /**
     * Handle incoming webhook events from RingCentral.
     */
    public function handle(Request $request)
    {
        // R-Dialer uses validation token headers on webhook requests.
        $validationToken = $this->firstHeaderValue($request, [
            'Validation-Token',
            'X-RingCentral-Validation-Token',
        ]);
        $verificationToken = $this->firstHeaderValue($request, [
            'Verification-Token',
        ]);

        $this->logWebhook('info', 'Incoming R-Dialer webhook request', [
            'method' => strtoupper($request->method()),
            'path' => $request->path(),
            'query' => $request->query(),
            'content_type' => $request->headers->get('content-type'),
            'user_agent' => $request->userAgent(),
            'has_validation_token' => $validationToken !== null && $validationToken !== '',
            'has_verification_token' => $verificationToken !== null && $verificationToken !== '',
            'content_length' => strlen((string) $request->getContent()),
            'ip' => $request->ip(),
        ]);

        // URL probe support.
        if ($request->isMethod('get') || $request->isMethod('head')) {
            $this->logWebhook('info', 'Webhook probe handled (GET/HEAD).');
            return $this->applyValidationHeader(response('OK', 200), $validationToken);
        }

        if (!$request->isMethod('post')) {
            $this->logWebhook('warning', 'Webhook rejected due to invalid method.', [
                'method' => strtoupper($request->method()),
            ]);
            return $this->applyValidationHeader(
                response()->json(['error' => 'Method Not Allowed'], 405),
                $validationToken
            );
        }

        $eventData = json_decode($request->getContent(), true);
        if (!is_array($eventData)) {
            // Subscription validation POST can arrive with empty body.
            if ($validationToken !== null && trim((string) $request->getContent()) === '') {
                $this->logWebhook('info', 'Webhook validation POST handled (empty body with validation token, no business event payload).');
                return $this->applyValidationHeader(response('', 200), $validationToken);
            }

            Log::warning('R-Dialer webhook payload is not valid JSON.');
            $this->logWebhook('warning', 'Webhook payload is not valid JSON.');
            return $this->applyValidationHeader(
                response()->json(['error' => 'Invalid JSON payload'], 400),
                $validationToken
            );
        }

        $configuredSecret = trim((string) config('services.ringcentral.webhook_secret'));
        if ($configuredSecret !== '') {
            // Verification-Token is optional on event deliveries; Validation-Token is used
            // for endpoint validation challenge and should not be treated as event auth.
            $incomingVerificationToken = $this->firstHeaderValue($request, [
                'Verification-Token',
                'X-RingCentral-Verification-Token',
            ]);

            if ($incomingVerificationToken !== null && !hash_equals($configuredSecret, $incomingVerificationToken)) {
                Log::warning('R-Dialer webhook verification token mismatch.', [
                    'event' => $eventData['event'] ?? $eventData['eventType'] ?? null,
                    'ownerId' => $eventData['ownerId'] ?? null,
                ]);
                $this->logWebhook('warning', 'Webhook verification token mismatch.', [
                    'event' => $eventData['event'] ?? $eventData['eventType'] ?? null,
                    'ownerId' => $eventData['ownerId'] ?? null,
                ]);

                return $this->applyValidationHeader(
                    response()->json(['error' => 'Unauthorized'], 401),
                    $validationToken
                );
            }
        }

        $eventName = (string) ($eventData['event'] ?? $eventData['eventType'] ?? '');
        $ownerId = (string) ($eventData['ownerId'] ?? data_get($eventData, 'body.ownerId') ?? '');

        Log::info('R-Dialer webhook event received', [
            'event' => $eventName,
            'ownerId' => $ownerId !== '' ? $ownerId : null,
            'subscriptionId' => $eventData['subscriptionId'] ?? null,
        ]);
        $this->logWebhook('info', 'R-Dialer webhook event received', [
            'event' => $eventName,
            'ownerId' => $ownerId !== '' ? $ownerId : null,
            'subscriptionId' => $eventData['subscriptionId'] ?? null,
        ]);
        $this->logWebhook('info', 'R-Dialer webhook payload details', [
            'event' => $eventName,
            'ownerId' => $ownerId !== '' ? $ownerId : null,
            'top_level_keys' => array_values(array_keys($eventData)),
            'body_keys' => is_array($eventData['body'] ?? null) ? array_values(array_keys($eventData['body'])) : [],
            'payload_from_phone' => data_get($eventData, 'body.from.phoneNumber'),
            'payload_to_phone' => data_get($eventData, 'body.to.0.phoneNumber'),
            'payload_direction' => data_get($eventData, 'body.direction'),
            'payload_message_id' => data_get($eventData, 'body.id') ?? data_get($eventData, 'body.message.id'),
        ]);

        $this->triggerLightweightSync($eventName, $ownerId, $eventData);

        return $this->applyValidationHeader(response()->json(['status' => 'ok'], 200), $validationToken);
    }

    private function triggerLightweightSync(string $eventName, string $ownerId, array $eventData = []): void
    {
        if ($ownerId === '') {
            $this->logWebhook('info', 'Webhook event skipped: ownerId missing.', [
                'event' => $eventName,
            ]);
            return;
        }

        $ringCentralUser = RingCentralUser::where('extension_id', $ownerId)
            ->where('is_active', true)
            ->orderByDesc('updated_at')
            ->first();

        if (!$ringCentralUser) {
            $this->logWebhook('info', 'Webhook event skipped: active R-Dialer user not found for ownerId.', [
                'event' => $eventName,
                'ownerId' => $ownerId,
            ]);
            return;
        }

        $event = strtolower($eventName);
        $userId = (int) $ringCentralUser->user_id;
        $subscriptionId = isset($eventData['subscriptionId']) ? (string) $eventData['subscriptionId'] : null;
        $uiScopes = $this->resolveUiScopesFromEvent($event);
        $partyNumber = $this->resolvePartyNumberForToast($eventData, $ownerId, $uiScopes, $ringCentralUser, $eventName);
        $messageText = $this->resolveMessageTextForToast($eventData, $uiScopes, $ringCentralUser, $eventName);
        $durationSeconds = $this->resolveDurationSecondsForToast($eventData, $uiScopes, $ringCentralUser, $eventName);
        $direction = $this->resolveDirectionForToast($eventData, $uiScopes, $ringCentralUser, $eventName);
        $toastEnabled = $this->shouldShowToastForDirection($direction);
        $localBlockEnabled = (bool) ($ringCentralUser->dialer_local_block_enabled ?? false);
        $isPartyBlocked = $localBlockEnabled && $partyNumber
            ? $this->dialerBlockService->isBlockedForRingCentralUser((int) $ringCentralUser->id, $partyNumber)
            : false;

        $this->logWebhook('info', 'Webhook toast sender resolution completed.', [
            'event' => $eventName,
            'ownerId' => $ownerId,
            'user_id' => $userId,
            'party_number' => $partyNumber,
            'message_text' => $messageText,
            'duration_seconds' => $durationSeconds,
            'direction' => $direction,
            'toast_enabled' => $toastEnabled,
            'local_block_enabled' => $localBlockEnabled,
            'party_blocked' => $isPartyBlocked,
            'scopes' => $uiScopes,
        ]);

        if ($isPartyBlocked) {
            $this->logWebhook('info', 'Webhook UI event suppressed for blocked number.', [
                'event' => $eventName,
                'ownerId' => $ownerId,
                'user_id' => $userId,
                'ringcentral_user_id' => (int) $ringCentralUser->id,
                'party_number' => $partyNumber,
            ]);
            return;
        }

        // Publish UI notification signal immediately so toast is not blocked by sync locks/failures.
        if (!empty($uiScopes)) {
            $this->publishUiWebhookEvent(
                $userId,
                $uiScopes,
                $eventName,
                $ownerId,
                $subscriptionId,
                $partyNumber,
                $messageText,
                $durationSeconds,
                $direction,
                $toastEnabled
            );
        }

        // Do not run heavy R-Dialer sync inside webhook request lifecycle.
        // Sync is performed by incremental UI/API refresh flows after this fast webhook ACK.
        $this->logWebhook('info', 'Webhook sync deferred (non-blocking mode).', [
            'event' => $eventName,
            'ownerId' => $ownerId,
            'user_id' => $userId,
            'scopes' => $uiScopes,
        ]);
    }

    private function resolveUiScopesFromEvent(string $event): array
    {
        $scopes = [];

        if (str_contains($event, 'message-store')) {
            $scopes[] = 'messages';
        }

        if (str_contains($event, 'voicemail')) {
            $scopes[] = 'voicemails';
        }

        if (str_contains($event, 'telephony') || str_contains($event, 'call-log')) {
            $scopes[] = 'calls';
            $scopes[] = 'recordings';
        }

        return array_values(array_unique($scopes));
    }

    private function publishUiWebhookEvent(
        int $userId,
        array $scopes,
        string $eventName,
        string $ownerId,
        ?string $subscriptionId = null,
        ?string $partyNumber = null,
        ?string $messageText = null,
        ?int $durationSeconds = null,
        ?string $direction = null,
        bool $toastEnabled = true
    ): void {
        $normalizedScopes = array_values(array_unique(array_filter(array_map(static function ($scope) {
            return is_string($scope) ? trim($scope) : '';
        }, $scopes))));
        if (empty($normalizedScopes)) {
            return;
        }

        $ttl = now()->addHours(6);
        $historyKey = 'ringcentral:webhook:ui:events:' . $userId;
        $latestKey = 'ringcentral:webhook:ui:event:' . $userId; // legacy key kept for compatibility
        $seqKey = 'ringcentral:webhook:ui:seq:' . $userId;

        $historyLimit = (int) config('services.ringcentral.webhook_event_history_size', 120);
        $historyLimit = max(20, min(500, $historyLimit));

        $nowIso = now()->toIso8601String();
        $nowMs = (int) round(microtime(true) * 1000);

        $history = Cache::get($historyKey, []);
        if (!is_array($history)) {
            $history = [];
        }

        $history = array_values(array_filter($history, static function ($item) {
            return is_array($item) && isset($item['seq']);
        }));

        // Single-event mode: always enqueue a new payload per webhook event (no batching/merge).
        $prevSeq = (int) Cache::get($seqKey, 0);
        if ($prevSeq <= 0 && !empty($history)) {
            $lastHistoryPayload = end($history);
            $prevSeq = is_array($lastHistoryPayload) ? (int) ($lastHistoryPayload['seq'] ?? 0) : 0;
            reset($history);
        }

        $seq = $prevSeq + 1;
        $history[] = [
            'seq' => $seq,
            'scopes' => $normalizedScopes,
            'events' => [$eventName],
            'ownerIds' => [$ownerId],
            'subscriptionIds' => $subscriptionId ? [$subscriptionId] : [],
            'partyNumbers' => $partyNumber ? [$partyNumber] : [],
            'messageTexts' => $messageText ? [$messageText] : [],
            'durationSecondsList' => ($durationSeconds !== null && $durationSeconds > 0) ? [$durationSeconds] : [],
            'event' => $eventName,
            'ownerId' => $ownerId,
            'subscriptionId' => $subscriptionId,
            'partyNumber' => $partyNumber,
            'messageText' => $messageText,
            'durationSeconds' => $durationSeconds,
            'direction' => $direction,
            'toast_enabled' => $toastEnabled,
            'batched_count' => 1,
            'batched_at_ms' => $nowMs,
            'at' => $nowIso,
        ];

        Cache::put($seqKey, $seq, $ttl);

        if (count($history) > $historyLimit) {
            $history = array_slice($history, -$historyLimit);
        }

        $latestPayload = end($history);
        reset($history);

        Cache::put($historyKey, $history, $ttl);
        if (is_array($latestPayload)) {
            Cache::put($latestKey, $latestPayload, $ttl);
            $this->logWebhook('info', 'Webhook UI event queued.', [
                'user_id' => $userId,
                'seq' => (int) ($latestPayload['seq'] ?? 0),
                'scopes' => array_values((array) ($latestPayload['scopes'] ?? [])),
                'party_number' => $latestPayload['partyNumber'] ?? null,
                'party_numbers' => array_values((array) ($latestPayload['partyNumbers'] ?? [])),
                'message_text' => $latestPayload['messageText'] ?? null,
                'message_texts' => array_values((array) ($latestPayload['messageTexts'] ?? [])),
                'duration_seconds' => $latestPayload['durationSeconds'] ?? null,
                'duration_seconds_list' => array_values((array) ($latestPayload['durationSecondsList'] ?? [])),
                'direction' => $latestPayload['direction'] ?? null,
                'toast_enabled' => (bool) ($latestPayload['toast_enabled'] ?? true),
                'batched_count' => (int) ($latestPayload['batched_count'] ?? 1),
                'event' => $latestPayload['event'] ?? null,
            ]);
        }
    }

    private function extractWebhookPartyNumber(array $eventData, string $ownerId): ?string
    {
        $ownerDigits = preg_replace('/\D+/', '', (string) $ownerId);

        $candidatePaths = [
            'body.from.phoneNumber',
            'body.from.phone',
            'body.message.from.phoneNumber',
            'body.message.from.phone',
            'body.party.from.phoneNumber',
            'body.party.from.phone',
            'body.parties.0.from.phoneNumber',
            'body.parties.0.from.phone',
            'body.telephonySession.parties.0.from.phoneNumber',
            'body.telephonySession.parties.0.from.phone',
            'body.to.0.phoneNumber',
            'body.to.0.phone',
            'body.message.to.0.phoneNumber',
            'body.message.to.0.phone',
            'body.party.to.phoneNumber',
            'body.party.to.phone',
            'body.parties.0.to.phoneNumber',
            'body.parties.0.to.phone',
            'body.telephonySession.parties.0.to.phoneNumber',
            'body.telephonySession.parties.0.to.phone',
            'body.voicemail.callerInfo.phoneNumber',
            'body.callerInfo.phoneNumber',
            'from.phoneNumber',
            'from.phone',
            'to.0.phoneNumber',
            'to.0.phone',
            'body.from.extensionNumber',
            'body.to.0.extensionNumber',
            'body.message.from.extensionNumber',
            'body.message.to.0.extensionNumber',
            'body.party.from.extensionNumber',
            'body.party.to.extensionNumber',
            'body.parties.0.from.extensionNumber',
            'body.parties.0.to.extensionNumber',
            'body.telephonySession.parties.0.from.extensionNumber',
            'body.telephonySession.parties.0.to.extensionNumber',
        ];

        $candidates = [];
        $pathHits = [];
        foreach ($candidatePaths as $path) {
            $value = data_get($eventData, $path);
            if (is_array($value)) {
                foreach ($value as $entry) {
                    $normalized = $this->normalizePhoneCandidate($entry);
                    if ($normalized !== null) {
                        $candidates[] = $normalized;
                        $pathHits[] = $path;
                    }
                }
                continue;
            }

            $normalized = $this->normalizePhoneCandidate($value);
            if ($normalized !== null) {
                $candidates[] = $normalized;
                $pathHits[] = $path;
            }
        }

        if (empty($candidates)) {
            $this->logWebhook('info', 'Webhook sender extraction from payload returned empty.', [
                'ownerId' => $ownerId,
                'candidate_paths_checked' => count($candidatePaths),
                'payload_top_level_keys' => array_values(array_keys($eventData)),
                'body_keys' => is_array($eventData['body'] ?? null) ? array_values(array_keys($eventData['body'])) : [],
            ]);
            return null;
        }

        $candidates = array_values(array_unique($candidates));
        $selected = null;
        foreach ($candidates as $candidate) {
            $digits = preg_replace('/\D+/', '', (string) $candidate);
            if ($digits !== '' && $digits !== $ownerDigits) {
                $selected = $candidate;
                break;
            }
        }
        if ($selected === null) {
            $selected = $candidates[0] ?? null;
        }

        $this->logWebhook('info', 'Webhook sender extraction from payload completed.', [
            'ownerId' => $ownerId,
            'selected' => $selected,
            'candidates' => $candidates,
            'path_hits' => array_values(array_unique($pathHits)),
        ]);

        return $selected;
    }

    private function resolvePartyNumberForToast(
        array $eventData,
        string $ownerId,
        array $scopes,
        RingCentralUser $ringCentralUser,
        string $eventName
    ): ?string {
        $numberFromPayload = $this->extractWebhookPartyNumber($eventData, $ownerId);
        if ($numberFromPayload !== null) {
            return $numberFromPayload;
        }

        $this->logWebhook('info', 'Webhook sender missing in payload; generic toast content will be used.', [
            'event' => $eventName,
            'ownerId' => $ownerId,
            'ringcentral_user_id' => $ringCentralUser->id,
            'scopes' => $scopes,
        ]);

        return null;
    }

    private function resolveMessageTextForToast(
        array $eventData,
        array $scopes,
        RingCentralUser $ringCentralUser,
        string $eventName
    ): ?string {
        if (!in_array('messages', $scopes, true)) {
            return null;
        }

        $messageText = $this->extractWebhookMessageText($eventData);
        if ($messageText !== null) {
            $this->logWebhook('info', 'Webhook message text resolved from payload.', [
                'event' => $eventName,
                'ringcentral_user_id' => $ringCentralUser->id,
                'message_text' => $messageText,
            ]);
            return $messageText;
        }

        $this->logWebhook('info', 'Webhook message text missing in payload; generic toast content will be used.', [
            'event' => $eventName,
            'ringcentral_user_id' => $ringCentralUser->id,
        ]);

        return null;
    }

    private function resolveDurationSecondsForToast(
        array $eventData,
        array $scopes,
        RingCentralUser $ringCentralUser,
        string $eventName
    ): ?int {
        $hasVoicemailScope = in_array('voicemails', $scopes, true);
        $hasRecordingScope = in_array('recordings', $scopes, true);
        if (!$hasVoicemailScope && !$hasRecordingScope) {
            return null;
        }

        $durationFromPayload = $this->extractWebhookDurationSeconds($eventData);
        if ($durationFromPayload !== null) {
            $this->logWebhook('info', 'Webhook duration resolved from payload.', [
                'event' => $eventName,
                'ringcentral_user_id' => $ringCentralUser->id,
                'duration_seconds' => $durationFromPayload,
            ]);
            return $durationFromPayload;
        }

        if ($hasVoicemailScope) {
            $latestVoicemail = RingCentralVoicemail::query()
                ->where('ringcentral_user_id', $ringCentralUser->id)
                ->orderByDesc('received_at')
                ->orderByDesc('id')
                ->first(['id', 'duration_seconds', 'received_at']);

            $vmDuration = $this->normalizeDurationCandidate($latestVoicemail->duration_seconds ?? null);
            if ($vmDuration !== null) {
                $this->logWebhook('info', 'Webhook duration fallback resolved from voicemails table.', [
                    'event' => $eventName,
                    'ringcentral_user_id' => $ringCentralUser->id,
                    'voicemail_row_id' => $latestVoicemail->id ?? null,
                    'duration_seconds' => $vmDuration,
                ]);
                return $vmDuration;
            }
        }

        if ($hasRecordingScope) {
            $latestCall = RingCentralCallLog::query()
                ->where('ringcentral_user_id', $ringCentralUser->id)
                ->whereNotNull('recording_url')
                ->orderByDesc('call_started_at')
                ->orderByDesc('id')
                ->first(['id', 'duration_seconds', 'call_started_at']);

            $callDuration = $this->normalizeDurationCandidate($latestCall->duration_seconds ?? null);
            $this->logWebhook('info', 'Webhook duration fallback lookup attempted for recordings.', [
                'event' => $eventName,
                'ringcentral_user_id' => $ringCentralUser->id,
                'call_row_id' => $latestCall->id ?? null,
                'duration_seconds' => $callDuration,
            ]);
            if ($callDuration !== null) {
                return $callDuration;
            }
        }

        return null;
    }

    private function resolveDirectionForToast(
        array $eventData,
        array $scopes,
        RingCentralUser $ringCentralUser,
        string $eventName
    ): ?string {
        $directionFromPayload = $this->extractWebhookDirection($eventData);
        if ($directionFromPayload !== null) {
            $this->logWebhook('info', 'Webhook direction resolved from payload.', [
                'event' => $eventName,
                'ringcentral_user_id' => $ringCentralUser->id,
                'direction' => $directionFromPayload,
            ]);
            return $directionFromPayload;
        }

        if (in_array('messages', $scopes, true)) {
            $latestMessage = RingCentralMessage::query()
                ->where('ringcentral_user_id', $ringCentralUser->id)
                ->orderByDesc('sent_at')
                ->orderByDesc('id')
                ->first(['id', 'direction', 'sent_at']);

            $direction = $this->normalizeDirectionCandidate($latestMessage->direction ?? null);
            if ($direction !== null) {
                $this->logWebhook('info', 'Webhook direction fallback resolved from messages table.', [
                    'event' => $eventName,
                    'ringcentral_user_id' => $ringCentralUser->id,
                    'message_id' => $latestMessage->id ?? null,
                    'direction' => $direction,
                ]);
                return $direction;
            }
        }

        if (in_array('calls', $scopes, true) || in_array('recordings', $scopes, true)) {
            $latestCall = RingCentralCallLog::query()
                ->where('ringcentral_user_id', $ringCentralUser->id)
                ->orderByDesc('call_started_at')
                ->orderByDesc('id')
                ->first(['id', 'direction', 'call_started_at']);

            $direction = $this->normalizeDirectionCandidate($latestCall->direction ?? null);
            if ($direction !== null) {
                $this->logWebhook('info', 'Webhook direction fallback resolved from calls table.', [
                    'event' => $eventName,
                    'ringcentral_user_id' => $ringCentralUser->id,
                    'call_row_id' => $latestCall->id ?? null,
                    'direction' => $direction,
                ]);
                return $direction;
            }
        }

        if (in_array('voicemails', $scopes, true)) {
            // Voicemail toasts are recipient-side by nature.
            return 'inbound';
        }

        return null;
    }

    private function extractWebhookDirection(array $eventData): ?string
    {
        $candidatePaths = [
            'body.direction',
            'body.message.direction',
            'body.voicemail.direction',
            'body.recording.direction',
            'body.party.direction',
            'body.parties.0.direction',
            'body.telephonySession.parties.0.direction',
            'direction',
        ];

        foreach ($candidatePaths as $path) {
            $value = data_get($eventData, $path);
            $direction = $this->normalizeDirectionCandidate($value);
            if ($direction !== null) {
                return $direction;
            }
        }

        return null;
    }

    private function normalizeDirectionCandidate($value): ?string
    {
        if (!is_string($value) && !is_numeric($value)) {
            return null;
        }

        $text = strtolower(trim((string) $value));
        if ($text === '') {
            return null;
        }

        if (str_contains($text, 'outbound') || $text === 'out' || str_contains($text, 'sent')) {
            return 'outbound';
        }

        if (str_contains($text, 'inbound') || $text === 'in' || str_contains($text, 'received') || $text === 'voicemail') {
            return 'inbound';
        }

        return $text;
    }

    private function shouldShowToastForDirection(?string $direction): bool
    {
        return $direction !== 'outbound';
    }

    private function extractPartyNumberFromLocalData(RingCentralUser $ringCentralUser, string $ownerId, array $scopes): ?string
    {
        $ownerDigits = preg_replace('/\D+/', '', (string) $ownerId);
        $portalPhoneDigits = preg_replace('/\D+/', '', (string) ($ringCentralUser->phone_number ?? ''));
        $excludedDigits = array_values(array_unique(array_filter([$ownerDigits, $portalPhoneDigits])));

        $hasMessagesScope = in_array('messages', $scopes, true);
        $hasVoicemailsScope = in_array('voicemails', $scopes, true);
        $hasCallsScope = in_array('calls', $scopes, true) || in_array('recordings', $scopes, true);

        if ($hasMessagesScope) {
            $latestMessage = RingCentralMessage::query()
                ->where('ringcentral_user_id', $ringCentralUser->id)
                ->orderByDesc('sent_at')
                ->orderByDesc('id')
                ->first(['id', 'from_number', 'to_number', 'direction', 'sent_at']);

            $candidate = $this->pickCounterpartyNumber([
                $latestMessage->from_number ?? null,
                $latestMessage->to_number ?? null,
            ], $excludedDigits);

            if ($candidate !== null) {
                $this->logWebhook('info', 'Webhook sender fallback resolved from messages table.', [
                    'ringcentral_user_id' => $ringCentralUser->id,
                    'message_id' => $latestMessage->id ?? null,
                    'direction' => $latestMessage->direction ?? null,
                    'sent_at' => optional($latestMessage->sent_at ?? null)->toIso8601String(),
                    'selected' => $candidate,
                ]);
                return $candidate;
            }
        }

        if ($hasVoicemailsScope) {
            $latestVoicemail = RingCentralVoicemail::query()
                ->where('ringcentral_user_id', $ringCentralUser->id)
                ->orderByDesc('received_at')
                ->orderByDesc('id')
                ->first(['id', 'caller_number', 'phone_number', 'received_at']);

            $candidate = $this->pickCounterpartyNumber([
                $latestVoicemail->caller_number ?? null,
                $latestVoicemail->phone_number ?? null,
            ], $excludedDigits);

            if ($candidate !== null) {
                $this->logWebhook('info', 'Webhook sender fallback resolved from voicemails table.', [
                    'ringcentral_user_id' => $ringCentralUser->id,
                    'voicemail_row_id' => $latestVoicemail->id ?? null,
                    'received_at' => optional($latestVoicemail->received_at ?? null)->toIso8601String(),
                    'selected' => $candidate,
                ]);
                return $candidate;
            }
        }

        if ($hasCallsScope) {
            $latestCall = RingCentralCallLog::query()
                ->where('ringcentral_user_id', $ringCentralUser->id)
                ->orderByDesc('call_started_at')
                ->orderByDesc('id')
                ->first(['id', 'from_number', 'to_number', 'phone_number', 'direction', 'call_started_at']);

            $candidate = $this->pickCounterpartyNumber([
                $latestCall->from_number ?? null,
                $latestCall->to_number ?? null,
                $latestCall->phone_number ?? null,
            ], $excludedDigits);

            if ($candidate !== null) {
                $this->logWebhook('info', 'Webhook sender fallback resolved from calls table.', [
                    'ringcentral_user_id' => $ringCentralUser->id,
                    'call_row_id' => $latestCall->id ?? null,
                    'direction' => $latestCall->direction ?? null,
                    'call_started_at' => optional($latestCall->call_started_at ?? null)->toIso8601String(),
                    'selected' => $candidate,
                ]);
                return $candidate;
            }
        }

        return null;
    }

    private function pickCounterpartyNumber(array $candidates, array $excludedDigits = []): ?string
    {
        $normalizedCandidates = [];
        foreach ($candidates as $candidate) {
            $normalized = $this->normalizePhoneCandidate($candidate);
            if ($normalized !== null) {
                $normalizedCandidates[] = $normalized;
            }
        }
        $normalizedCandidates = array_values(array_unique($normalizedCandidates));
        if (empty($normalizedCandidates)) {
            return null;
        }

        foreach ($normalizedCandidates as $candidate) {
            $digits = preg_replace('/\D+/', '', (string) $candidate);
            if ($digits !== '' && !in_array($digits, $excludedDigits, true)) {
                return $candidate;
            }
        }

        return $normalizedCandidates[0] ?? null;
    }

    private function normalizePhoneCandidate($value): ?string
    {
        if (is_object($value)) {
            $value = (array) $value;
        }

        if (is_array($value)) {
            foreach ($value as $entry) {
                $normalized = $this->normalizePhoneCandidate($entry);
                if ($normalized !== null) {
                    return $normalized;
                }
            }
            return null;
        }

        if (!is_string($value) && !is_numeric($value)) {
            return null;
        }

        $text = trim((string) $value);
        if ($text === '' || strpos($text, '@') !== false) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $text);
        if ($digits === '' || strlen($digits) < 7 || strlen($digits) > 15) {
            return null;
        }

        return strpos($text, '+') === 0 ? '+' . $digits : $digits;
    }

    private function extractWebhookMessageText(array $eventData): ?string
    {
        $candidatePaths = [
            'body.subject',
            'body.text',
            'body.body',
            'body.message.subject',
            'body.message.text',
            'body.message.body',
            'body.message.smsMessage.text',
            'body.message.smsMessage.body',
            'subject',
            'text',
            'bodyText',
        ];

        foreach ($candidatePaths as $path) {
            $value = data_get($eventData, $path);
            $text = $this->normalizeMessageTextCandidate($value);
            if ($text !== null) {
                return $text;
            }
        }

        return null;
    }

    private function normalizeMessageTextCandidate($value): ?string
    {
        if (!is_string($value) && !is_numeric($value)) {
            return null;
        }

        $text = trim((string) $value);
        if ($text === '') {
            return null;
        }

        $text = preg_replace('/\s+/', ' ', $text) ?? $text;
        if (strlen($text) > 140) {
            $text = substr($text, 0, 137) . '...';
        }

        return $text;
    }

    private function extractWebhookDurationSeconds(array $eventData): ?int
    {
        $candidatePaths = [
            'body.duration',
            'body.durationSeconds',
            'body.message.duration',
            'body.message.durationSeconds',
            'body.voicemail.duration',
            'body.voicemail.durationSeconds',
            'body.recording.duration',
            'body.recording.durationSeconds',
            'body.party.duration',
            'body.parties.0.duration',
            'body.telephonySession.parties.0.duration',
            'duration',
            'durationSeconds',
        ];

        foreach ($candidatePaths as $path) {
            $value = data_get($eventData, $path);
            $duration = $this->normalizeDurationCandidate($value);
            if ($duration !== null) {
                return $duration;
            }
        }

        return null;
    }

    private function normalizeDurationCandidate($value): ?int
    {
        if ($value === null) {
            return null;
        }

        if (is_numeric($value)) {
            $seconds = (int) round((float) $value);
            return $seconds > 0 ? $seconds : null;
        }

        if (!is_string($value)) {
            return null;
        }

        $text = trim($value);
        if ($text === '') {
            return null;
        }

        if (preg_match('/^(\d+):(\d{1,2})$/', $text, $m)) {
            $seconds = ((int) $m[1] * 60) + (int) $m[2];
            return $seconds > 0 ? $seconds : null;
        }

        if (preg_match('/^(\d+):(\d{1,2}):(\d{1,2})$/', $text, $m)) {
            $seconds = ((int) $m[1] * 3600) + ((int) $m[2] * 60) + (int) $m[3];
            return $seconds > 0 ? $seconds : null;
        }

        if (is_numeric($text)) {
            $seconds = (int) round((float) $text);
            return $seconds > 0 ? $seconds : null;
        }

        return null;
    }

    private function firstHeaderValue(Request $request, array $headerNames): ?string
    {
        foreach ($headerNames as $name) {
            $value = $request->header($name);
            if (is_string($value) && $value !== '') {
                return $value;
            }
        }

        return null;
    }

    private function applyValidationHeader($response, ?string $validationToken)
    {
        if ($validationToken !== null && $validationToken !== '') {
            $response->headers->set('Validation-Token', $validationToken);
        }

        return $response;
    }

    private function logWebhook(string $level, string $message, array $context = []): void
    {
        $normalizedLevel = in_array($level, ['debug', 'info', 'notice', 'warning', 'error', 'critical', 'alert', 'emergency'], true)
            ? $level
            : 'info';

        $context = array_merge([
            'trace_id' => $this->webhookTraceId(),
        ], $context);

        try {
            Log::channel('ringcentral_webhook')->{$normalizedLevel}($message, $context);
        } catch (Throwable $e) {
            Log::build([
                'driver' => 'daily',
                'path' => storage_path('logs/ringcentral-webhook.log'),
                'level' => 'debug',
                'days' => 30,
            ])->{$normalizedLevel}($message, $context);
        }

        Log::{$normalizedLevel}('[RingCentral webhook] ' . $message, $context);
    }

    private function webhookTraceId(): string
    {
        if ($this->webhookTraceId === null) {
            try {
                $this->webhookTraceId = bin2hex(random_bytes(8));
            } catch (Throwable $e) {
                $this->webhookTraceId = uniqid('rcwh_', true);
            }
        }

        return $this->webhookTraceId;
    }
}
