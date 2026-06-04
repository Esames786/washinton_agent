(function () {
    if (window._rcGlobalWebhookBootstrapDone === '1') return;
    window._rcGlobalWebhookBootstrapDone = '1';

    function normalizeScope(scope) {
        const raw = String(scope || '').toLowerCase().trim();
        if (!raw) return '';
        if (raw === 'message' || raw === 'messages' || raw === 'sms' || raw === 'text' || raw === 'texts') return 'messages';
        if (raw === 'call' || raw === 'calls') return 'calls';
        if (raw === 'voicemail' || raw === 'voicemails') return 'voicemails';
        if (raw === 'recording' || raw === 'recordings') return 'recordings';
        return raw;
    }

    function scopesFromPayload(data) {
        const scopes = Array.isArray(data && data.scopes) ? data.scopes : (data && data.scope ? [data.scope] : []);
        let normalized = scopes.map(normalizeScope).filter(Boolean);
        if (!normalized.length) {
            const eventName = String((data && data.event) || '').toLowerCase();
            if (eventName.includes('/message-store') || eventName.includes('instant?type=sms')) normalized.push('messages');
            if (eventName.includes('/call-log')) normalized.push('calls');
            if (eventName.includes('/voicemail')) normalized.push('voicemails');
            if (eventName.includes('/recording')) normalized.push('recordings');
        }
        return Array.from(new Set(normalized));
    }

    function normalizePhone(value) {
        const raw = String(value || '').trim();
        if (!raw || raw.includes('@')) return '';
        const digits = raw.replace(/\D/g, '');
        if (digits.length < 7 || digits.length > 15) return '';
        return raw.startsWith('+') ? ('+' + digits) : digits;
    }

    function maskPhoneForToast(phone) {
        const normalized = normalizePhone(phone);
        if (!normalized) return '';
        const digits = String(normalized).replace(/\D/g, '');
        if (!digits) return '';
        if (digits.length <= 4) return digits;
        const stars = '*'.repeat(Math.max(6, digits.length - 4));
        return stars + digits.slice(-4);
    }

    function extractPartyNumber(data) {
        const candidates = [
            data && data.partyNumber,
            data && data.party_number,
            Array.isArray(data && data.partyNumbers) ? data.partyNumbers[0] : null,
            Array.isArray(data && data.party_numbers) ? data.party_numbers[0] : null,
            data && data.number,
            data && data.phone
        ];
        for (let i = 0; i < candidates.length; i++) {
            const normalized = normalizePhone(candidates[i]);
            if (normalized) return normalized;
        }
        return '';
    }

    function extractMessageText(data) {
        const candidates = [
            data && data.messageText,
            data && data.message_text,
            Array.isArray(data && data.messageTexts) ? data.messageTexts[0] : null,
            Array.isArray(data && data.message_texts) ? data.message_texts[0] : null,
            data && data.text,
            data && data.subject
        ];
        for (let i = 0; i < candidates.length; i++) {
            const value = candidates[i];
            if (typeof value !== 'string' && typeof value !== 'number') continue;
            let text = String(value).trim();
            if (!text) continue;
            text = text.replace(/\s+/g, ' ');
            if (text.length > 140) text = text.slice(0, 137) + '...';
            return text;
        }
        return '';
    }

    function extractDurationSeconds(data) {
        const candidates = [
            data && data.durationSeconds,
            data && data.duration_seconds,
            Array.isArray(data && data.durationSecondsList) ? data.durationSecondsList[0] : null,
            Array.isArray(data && data.duration_seconds_list) ? data.duration_seconds_list[0] : null,
            data && data.duration
        ];
        for (let i = 0; i < candidates.length; i++) {
            const value = candidates[i];
            if (value === null || value === undefined || value === '') continue;
            if (typeof value === 'number' && Number.isFinite(value)) {
                const seconds = Math.max(0, Math.round(value));
                if (seconds > 0) return seconds;
                continue;
            }
            const text = String(value).trim();
            if (!text) continue;
            const mmss = text.match(/^(\d+):(\d{1,2})$/);
            if (mmss) {
                const seconds = (parseInt(mmss[1], 10) * 60) + parseInt(mmss[2], 10);
                if (seconds > 0) return seconds;
                continue;
            }
            const hhmmss = text.match(/^(\d+):(\d{1,2}):(\d{1,2})$/);
            if (hhmmss) {
                const seconds = (parseInt(hhmmss[1], 10) * 3600) + (parseInt(hhmmss[2], 10) * 60) + parseInt(hhmmss[3], 10);
                if (seconds > 0) return seconds;
                continue;
            }
            if (/^\d+(\.\d+)?$/.test(text)) {
                const seconds = Math.max(0, Math.round(parseFloat(text)));
                if (seconds > 0) return seconds;
            }
        }
        return null;
    }

    function formatDuration(seconds) {
        const s = parseInt(seconds, 10);
        if (!s || s <= 0) return '';
        const minutes = Math.floor(s / 60);
        const rem = s % 60;
        return minutes + ':' + String(rem).padStart(2, '0');
    }

    function ensureToastStyles() {
        if (document.getElementById('rc-global-toast-styles')) return;
        const style = document.createElement('style');
        style.id = 'rc-global-toast-styles';
        style.textContent = `
            #rcToastStack {
                position: fixed;
                right: 16px;
                bottom: 16px;
                z-index: 2147483000;
                display: flex;
                flex-direction: column;
                gap: 10px;
                max-width: min(92vw, 360px);
                pointer-events: none;
            }
            .rc-toast {
                pointer-events: auto;
                color: #fff;
                border-radius: 10px;
                box-shadow: 0 10px 24px rgba(0, 0, 0, 0.2);
                padding: 10px 12px;
                font-size: 13px;
                line-height: 1.35;
                opacity: 0;
                transform: translateY(8px);
                transition: opacity 0.2s ease, transform 0.2s ease;
                display: flex;
                align-items: flex-start;
                gap: 8px;
                background: #1f6feb;
            }
            .rc-toast.show {
                opacity: 1;
                transform: translateY(0);
            }
            .rc-toast .rc-toast-close {
                background: transparent;
                border: 0;
                color: rgba(255,255,255,0.9);
                cursor: pointer;
                font-size: 16px;
                line-height: 1;
                margin-left: auto;
                padding: 0;
            }
        `;
        document.head.appendChild(style);
    }

    function playPop() {
        try {
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (!AudioCtx) return;
            const ctx = new AudioCtx();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'triangle';
            osc.frequency.setValueAtTime(760, ctx.currentTime);
            gain.gain.setValueAtTime(0.0001, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.08, ctx.currentTime + 0.02);
            gain.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + 0.14);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.16);
            window.setTimeout(function () {
                try { ctx.close(); } catch (_) { }
            }, 220);
        } catch (_) { }
    }

    function showToast(message, durationMs) {
        if (!message) return;
        ensureToastStyles();
        let stack = document.getElementById('rcToastStack');
        if (!stack) {
            stack = document.createElement('div');
            stack.id = 'rcToastStack';
            document.body.appendChild(stack);
        }

        const toast = document.createElement('div');
        toast.className = 'rc-toast';
        toast.innerHTML = `
            <span>${String(message)}</span>
            <button type="button" class="rc-toast-close" aria-label="Close">&times;</button>
        `;
        stack.appendChild(toast);

        const removeToast = function () {
            toast.classList.remove('show');
            window.setTimeout(function () {
                if (toast.parentNode) toast.parentNode.removeChild(toast);
            }, 200);
        };

        const closeBtn = toast.querySelector('.rc-toast-close');
        if (closeBtn) closeBtn.addEventListener('click', removeToast);

        window.requestAnimationFrame(function () {
            toast.classList.add('show');
        });
        window.setTimeout(removeToast, Math.max(1800, parseInt(durationMs, 10) || 4200));
        playPop();
    }

    function notify(scope, count, meta) {
        const total = 1;
        const key = normalizeScope(scope) || 'generic';
        const metaObj = meta && typeof meta === 'object' ? meta : {};
        const partyNumber = normalizePhone(metaObj.partyNumber || metaObj.number || metaObj.phone || '');
        const maskedPartyNumber = maskPhoneForToast(partyNumber);
        const messageText = extractMessageText(metaObj);
        const durationSeconds = extractDurationSeconds(metaObj);
        const durationLabel = formatDuration(durationSeconds);

        const map = {
            messages: total === 1
                ? (maskedPartyNumber
                    ? ('New message from ' + maskedPartyNumber + (messageText ? ': ' + messageText : ''))
                    : ('New message received' + (messageText ? ': ' + messageText : '')))
                : (maskedPartyNumber
                    ? (total + ' new messages (latest: ' + maskedPartyNumber + ')' + (messageText ? ': ' + messageText : ''))
                    : (total + ' new messages received' + (messageText ? ': ' + messageText : ''))),
            calls: total === 1
                ? (maskedPartyNumber ? 'New call from ' + maskedPartyNumber : 'New call event received')
                : (maskedPartyNumber ? (total + ' new call events (latest: ' + maskedPartyNumber + ')') : (total + ' new call events received')),
            voicemails: total === 1
                ? (maskedPartyNumber
                    ? ('New voicemail from ' + maskedPartyNumber + (durationLabel ? ' (' + durationLabel + ')' : ''))
                    : ('New voicemail received' + (durationLabel ? ' (' + durationLabel + ')' : '')))
                : (maskedPartyNumber
                    ? (total + ' new voicemails (latest: ' + maskedPartyNumber + ')' + (durationLabel ? ' (' + durationLabel + ')' : ''))
                    : (total + ' new voicemails received' + (durationLabel ? ' (' + durationLabel + ')' : ''))),
            recordings: total === 1
                ? (maskedPartyNumber
                    ? ('New recording for ' + maskedPartyNumber + (durationLabel ? ' (' + durationLabel + ')' : ''))
                    : ('New recording available' + (durationLabel ? ' (' + durationLabel + ')' : '')))
                : (maskedPartyNumber
                    ? (total + ' new recordings (latest: ' + maskedPartyNumber + ')' + (durationLabel ? ' (' + durationLabel + ')' : ''))
                    : (total + ' new recordings available' + (durationLabel ? ' (' + durationLabel + ')' : ''))),
            generic: total === 1
                ? (maskedPartyNumber ? 'New R-Dialer event for ' + maskedPartyNumber : 'New R-Dialer event received')
                : (maskedPartyNumber ? (total + ' new R-Dialer events (latest: ' + maskedPartyNumber + ')') : (total + ' new R-Dialer events received')),
        };
        const text = map[key] || (total === 1 ? 'New R-Dialer event received' : total + ' new R-Dialer events received');

        if (typeof window.rcNotifyWebhookEvent === 'function') {
            window.rcNotifyWebhookEvent(key, total, { partyNumber: partyNumber, messageText: messageText, durationSeconds: durationSeconds });
            return;
        }

        showToast(text, 5000);
    }

    function buildStreamUrl() {
        const base = String(window.RC_GLOBAL_WEBHOOK_STREAM_URL || '').trim();
        if (!base) return '';
        let since = 0;
        try {
            since = parseInt((window.localStorage && window.localStorage.getItem('rcWebhookStreamLastSeq')) || '0', 10) || 0;
        } catch (_) {
            since = 0;
        }
        const sep = base.indexOf('?') >= 0 ? '&' : '?';
        return base + sep + 'since=' + encodeURIComponent(String(since));
    }

    function startGlobalStream() {
        if (typeof window.EventSource === 'undefined') return;
        if (window._rcWebhookStreamStarted === '1') return;

        // If full R-Dialer portal stream helper is present, use it.
        if (typeof window.rcStartWebhookEventStream === 'function') {
            try {
                window.rcStartWebhookEventStream();
            } catch (_) { }
            return;
        }

        const streamUrl = buildStreamUrl();
        if (!streamUrl) return;

        const source = new EventSource(streamUrl);
        window._rcWebhookStreamStarted = '1';
        window._rcWebhookEventSource = source;

        source.addEventListener('webhook', function (event) {
            try {
                const payload = event && event.data ? JSON.parse(event.data) : {};
                const seqFromPayload = parseInt(payload && payload.seq ? payload.seq : 0, 10) || 0;
                const seqFromHeader = parseInt((event && event.lastEventId) ? event.lastEventId : 0, 10) || 0;
                const seq = Math.max(seqFromPayload, seqFromHeader);
                if (seq > 0) {
                    try {
                        if (window.localStorage) window.localStorage.setItem('rcWebhookStreamLastSeq', String(seq));
                    } catch (_) { }
                }

                const scopes = scopesFromPayload(payload);
                const rawBatchedCount = Math.max(1, parseInt((payload.batchedCount ?? payload.batched_count ?? 1), 10) || 1);
                const eventCount = 1;
                const direction = String(payload.direction || '').toLowerCase().trim() || null;
                const toastEnabled = !(
                    payload.toastEnabled === false ||
                    payload.toast_enabled === false ||
                    String(payload.toastEnabled || '').toLowerCase() === 'false' ||
                    String(payload.toast_enabled || '').toLowerCase() === 'false'
                );
                const partyNumber = extractPartyNumber(payload);
                const messageText = extractMessageText(payload);
                const durationSeconds = extractDurationSeconds(payload);
                try {
                    console.info('[RC Global Webhook Toast Meta]', {
                        seq: payload.seq || null,
                        event: payload.event || null,
                        scopes: scopes,
                        batchedCount: eventCount,
                        rawBatchedCount: rawBatchedCount,
                        direction: direction,
                        toastEnabled: toastEnabled,
                        partyNumber: partyNumber || null,
                        messageText: messageText || null,
                        durationSeconds: durationSeconds,
                        partyNumbers: Array.isArray(payload.partyNumbers) ? payload.partyNumbers : [],
                        messageTexts: Array.isArray(payload.messageTexts) ? payload.messageTexts : [],
                        durationSecondsList: Array.isArray(payload.durationSecondsList) ? payload.durationSecondsList : [],
                    });
                } catch (_) { }
                if (!scopes.length) {
                    if (toastEnabled) {
                        notify('generic', eventCount, { partyNumber: partyNumber, messageText: messageText, durationSeconds: durationSeconds });
                    }
                    return;
                }
                scopes.forEach(function (scope) {
                    if (toastEnabled) {
                        notify(scope, eventCount, { partyNumber: partyNumber, messageText: messageText, durationSeconds: durationSeconds });
                    }
                });
            } catch (_) { }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', startGlobalStream, { once: true });
    } else {
        startGlobalStream();
    }
})();
