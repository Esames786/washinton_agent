/**
 * R-Dialer  Recordings Module
 * Modern recordings list rendering + playback controls
 */

let rcRecordingsItems = [];
let rcRecordingsLoading = false;
let rcRecordingsNextCursor = null;
let rcRecordingsLastAutoRefreshAt = 0;
let rcRecordingsReqSeq = 0;
let rcRecordingsSyncNotice = '';
let rcRecordingsRefreshRetryTimer = null;
let rcRecordingsBackgroundPollTimer = null;
let rcRecordingsTotalAvailable = null;
let rcRecordingsWithRecordingTotal = null;
let rcRecordingsResolvedUrlById = {};

function setupRecordingUI(caps) {
    const startBtn = document.getElementById('startRecordingBtn');
    const pauseBtn = document.getElementById('pauseRecordingBtn');
    const resumeBtn = document.getElementById('resumeRecordingBtn');
    const stopBtn = document.getElementById('stopRecordingBtn');
    const recStatusEl = document.getElementById('recordingStatus');

    const hideAll = () => {
        if (startBtn) startBtn.style.display = 'none';
        if (pauseBtn) pauseBtn.style.display = 'none';
        if (resumeBtn) resumeBtn.style.display = 'none';
        if (stopBtn) stopBtn.style.display = 'none';
    };

    if (!caps) {
        hideAll();
        if (recStatusEl) recStatusEl.textContent = 'Recording not available on this account';
        return;
    }

    const { canStart, canPause, canResume, canStop, autoRecording } = caps;
    hideAll();

    if (!canStart && !canPause && !canResume && !canStop) {
        if (recStatusEl) recStatusEl.textContent = 'Recording not available on this account';
        return;
    }

    if (autoRecording) {
        if (recStatusEl) recStatusEl.textContent = 'Recording is auto-enabled by account policy';
        if (pauseBtn && canPause) pauseBtn.style.display = 'inline-block';
        if (resumeBtn && canResume) resumeBtn.style.display = 'none';
        if (stopBtn && canStop) stopBtn.style.display = 'inline-block';
        if (startBtn) startBtn.style.display = 'none';
        return;
    }

    if (recStatusEl) recStatusEl.textContent = 'Recording available';
    if (startBtn && canStart) startBtn.style.display = 'inline-block';
    if (pauseBtn && canPause) pauseBtn.style.display = 'none';
    if (resumeBtn && canResume) resumeBtn.style.display = 'none';
    if (stopBtn && canStop) stopBtn.style.display = 'none';
}

function updateSpeakerStatus(text) {
    const speakerStatusEl = document.getElementById('speakerStatus');
    if (speakerStatusEl) speakerStatusEl.textContent = text;
}

function pauseRecordingAudio() {
    const players = [
        document.getElementById('recordingAudio'),
        document.getElementById('rcRecordingSideAudio')
    ].filter(Boolean);
    players.forEach((player) => {
        player.pause();
        player.currentTime = 0;
    });
}

window.pauseRecordingAudio = pauseRecordingAudio;

document.addEventListener('ringcentral:recordingStarted', function () {
    try {
        const el = document.getElementById('recordingStatus');
        if (el) el.textContent = 'Recording';
    } catch (_) { }
});

document.addEventListener('ringcentral:recordingPaused', function () {
    try {
        const el = document.getElementById('recordingStatus');
        if (el) el.textContent = 'Recording paused';
    } catch (_) { }
});

document.addEventListener('ringcentral:recordingResumed', function () {
    try {
        const el = document.getElementById('recordingStatus');
        if (el) el.textContent = 'Recording';
    } catch (_) { }
});

document.addEventListener('ringcentral:recordingStopped', function () {
    try {
        const el = document.getElementById('recordingStatus');
        if (el) el.textContent = 'Not recording';
    } catch (_) { }
});

function rcRecEscapeHtml(value) {
    return String(value || '').replace(/[&"'<>]/g, function (ch) {
        return ({
            '&': '&amp;',
            '"': '&quot;',
            "'": '&#39;',
            '<': '&lt;',
            '>': '&gt;'
        })[ch];
    });
}

function rcRecFormatDuration(rawSeconds) {
    const total = Math.max(0, parseInt(rawSeconds, 10) || 0);
    if (!total) return '0 sec';

    const hours = Math.floor(total / 3600);
    const minutes = Math.floor((total % 3600) / 60);
    const seconds = total % 60;

    if (hours > 0) return `${hours} hr ${minutes} min ${seconds} sec`;
    if (minutes > 0) return `${minutes} min ${seconds} sec`;
    return `${seconds} sec`;
}

function rcRecFormatTime(rawTime) {
    if (!rawTime) return '';
    if (typeof window.rcFormatLocalDateTime === 'function') {
        return window.rcFormatLocalDateTime(rawTime, {
            year: 'numeric',
            month: 'short',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    const dt = new Date(rawTime);
    if (Number.isNaN(dt.getTime())) return '';
    return dt.toLocaleString(undefined, {
        year: 'numeric',
        month: 'short',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function rcRecExtractText(rawValue) {
    if (rawValue === null || rawValue === undefined) return '';
    if (typeof rawValue === 'string' || typeof rawValue === 'number') return String(rawValue).trim();
    if (typeof rawValue === 'object') {
        const candidates = [
            rawValue.phoneNumber,
            rawValue.extensionNumber,
            rawValue.number,
            rawValue.value,
            rawValue.name,
            rawValue.text,
            rawValue.label
        ];
        for (let i = 0; i < candidates.length; i++) {
            const extracted = rcRecExtractText(candidates[i]);
            if (extracted) return extracted;
        }
    }
    return '';
}

function rcRecDigits(rawPhone) {
    return String(rawPhone || '').replace(/\D/g, '');
}

function rcRecGetOwnLineDigits() {
    try {
        const connectedEl = document.getElementById('connectedPhoneNumber');
        const fromDataAttr = connectedEl ? connectedEl.getAttribute('data-full-number') : '';
        const fromSmsInput = document.getElementById('smsFromNumber')?.value || '';
        return rcRecDigits(fromDataAttr || fromSmsInput);
    } catch (_) {
        return '';
    }
}

function rcRecChooseCounterpartyPhone(fromPhone, toPhone, directionRaw) {
    const direction = String(directionRaw || '').trim().toLowerCase();
    const isOutgoing = direction === 'outbound' || direction === 'outgoing';
    const ownDigits = rcRecGetOwnLineDigits();
    const fromDigits = rcRecDigits(fromPhone);
    const toDigits = rcRecDigits(toPhone);

    if (ownDigits) {
        const fromIsOwn = fromDigits && fromDigits === ownDigits;
        const toIsOwn = toDigits && toDigits === ownDigits;
        if (fromIsOwn && !toIsOwn) return String(toPhone || '').trim();
        if (toIsOwn && !fromIsOwn) return String(fromPhone || '').trim();
    }

    return String(isOutgoing ? (toPhone || fromPhone) : (fromPhone || toPhone) || '').trim();
}

function rcRecNormalizeItem(rawItem, index) {
    const item = rawItem && typeof rawItem === 'object' ? rawItem : {};
    const id = item.call_id || item.id || `rec-${index + 1}`;
    const recordingUrl = String(item.recording_url || item.media_url || '').trim();
    const direction = String(item.direction || '').trim();
    const fromPhone = rcRecExtractText(item.from_number || item.from);
    const toPhone = rcRecExtractText(item.to_number || item.to);
    const actionPhone = rcRecChooseCounterpartyPhone(fromPhone, toPhone, direction);
    const displayPhone = actionPhone || fromPhone || toPhone || '';
    const displayLabel = displayPhone
        ? ((typeof maskPhoneNumber === 'function') ? maskPhoneNumber(displayPhone) : displayPhone)
        : 'Recording';

    return {
        id: String(id),
        direction,
        fromPhone: String(fromPhone || '').trim(),
        toPhone: String(toPhone || '').trim(),
        actionPhone: String(actionPhone || '').trim(),
        label: displayLabel,
        startTime: item.start_time || item.created_time || item.createdAt || '',
        durationSeconds: parseInt(item.duration_seconds || item.duration || 0, 10) || 0,
        recordingUrl,
        hasRecording: !!recordingUrl,
        searchText: [
            direction,
            fromPhone,
            toPhone,
            actionPhone,
            item.start_time || '',
            item.duration_seconds || '',
            recordingUrl ? 'with recording' : 'no recording'
        ]
            .join(' ')
            .trim()
            .toLowerCase()
    };
}

async function rcRecParseJsonOrRedirect(response) {
    if (typeof rcParseJsonOrRedirect === 'function') {
        return rcParseJsonOrRedirect(response);
    }

    if (!response) return null;

    const contentType = (response.headers.get('content-type') || '').toLowerCase();
    const redirectedToLogin = !!(response.redirected && response.url && /\/login(?:[/?#]|$)/i.test(response.url));
    const unauthorized = response.status === 401 || response.status === 403 || response.status === 419;

    if (redirectedToLogin || unauthorized) {
        window.location.href = '/login';
        return null;
    }

    if (!contentType.includes('application/json')) {
        throw new Error('Expected JSON response');
    }

    return response.json();
}

function getRecordingsSearchValue() {
    const searchEl = document.getElementById('tabRecordingsSearch');
    return (searchEl && searchEl.value) ? searchEl.value.trim().toLowerCase() : '';
}

function filterRecordings(items) {
    const source = Array.isArray(items) ? items : [];
    const query = getRecordingsSearchValue();

    return source.filter(item => {
        if (query && !String(item.searchText || '').includes(query)) return false;
        return true;
    });
}

function renderRecordingsStats(filteredCount, totalCount) {
    const statsEl = document.getElementById('tabRecordingsStats');
    if (!statsEl) return;
    statsEl.style.display = '';

    const displayTotal = rcRecordingsTotalAvailable !== null
        ? Math.max(0, parseInt(rcRecordingsTotalAvailable, 10) || 0)
        : Math.max(0, parseInt(totalCount, 10) || 0);
    const withRecordingCount = rcRecordingsWithRecordingTotal !== null
        ? Math.max(0, parseInt(rcRecordingsWithRecordingTotal, 10) || 0)
        : rcRecordingsItems.filter(item => item.hasRecording).length;
    let text = `${filteredCount} shown / ${displayTotal} total | ${withRecordingCount} with recording`;
    if (rcRecordingsSyncNotice) {
        text += ` | ${rcRecordingsSyncNotice}`;
    }
    statsEl.textContent = text;
}

function renderRecordingsList(items) {
    const listEl = document.getElementById('tabRecordingsList');
    if (!listEl) return;

    if (!items.length) {
        listEl.innerHTML = '<div class="text-muted">No recordings found</div>';
        return;
    }

    listEl.innerHTML = items.map(rcRecRenderListItem).join('');
}

function rcRecRenderListItem(item) {
    const label = item.label || 'Recording';
    const displayTime = rcRecFormatTime(item.startTime) || 'Unknown date';
    const duration = rcRecFormatDuration(item.durationSeconds);
    const initials = (rcRecDigits(item.actionPhone || item.fromPhone || item.toPhone).slice(-2) || 'RC').toUpperCase();
    const hasDownload = !!item.hasRecording;
    const canActionPhone = !!item.actionPhone;
    const hasMoreActions = hasDownload || canActionPhone;

    return `
        <a href="#"
           class="list-group-item list-group-item-action message-item p-3 border rounded-3 mb-2"
           data-id="${rcRecEscapeHtml(item.id)}"
           data-search="${rcRecEscapeHtml(item.searchText)}">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                     style="width:50px; height:50px; flex-shrink:0; margin-right:10px;">
                    <span class="fw-bold">${rcRecEscapeHtml(initials)}</span>
                </div>
                <div class="flex-grow-1" style="min-width:0;">
                    <span class="fw-semibold d-block">${rcRecEscapeHtml(label)}</span>
                    <small class="text-muted d-block preview-text">${rcRecEscapeHtml(duration)}</small>
                </div>
                <div class="text-end ms-2">
                    <span class="text-muted small d-block">${rcRecEscapeHtml(displayTime)}</span>
                    <div class="rc-call-list-actions mt-1">
                        <button type="button" class="phone-ui-btn rc-recording-action-play"
                                data-id="${rcRecEscapeHtml(item.id)}"
                                ${item.hasRecording ? '' : 'disabled'}
                                title="Play recording">
                            <i class="fa fa-play"></i>
                        </button>
                        ${canActionPhone ? `
                            <button type="button" class="phone-ui-btn rc-recording-action-call" data-phone="${rcRecEscapeHtml(item.actionPhone)}" title="Call">
                                <i class="fa fa-phone"></i>
                            </button>
                        ` : ''}
                        ${hasMoreActions ? `
                            <div class="rc-recording-more-wrap">
                                <button type="button"
                                        class="msg-ui-btn rc-recording-action-more"
                                        title="More"
                                        aria-haspopup="true"
                                        aria-expanded="false">
                                    <i class="fa fa-ellipsis-h"></i>
                                </button>
                                <div class="rc-filter-dropdown-menu rc-recording-more-menu" hidden>
                                    ${canActionPhone ? `
                                        <button type="button" class="rc-filter-dropdown-item rc-recording-action-message" data-phone="${rcRecEscapeHtml(item.actionPhone)}" title="Message">
                                            <i class="fa fa-comment"></i> Message
                                        </button>
                                        <button type="button" class="rc-filter-dropdown-item rc-recording-action-block" data-phone="${rcRecEscapeHtml(item.actionPhone)}" title="Block number">
                                            <i class="fa fa-ban"></i> Block
                                        </button>
                                    ` : ''}
                                    ${hasDownload ? `
                                        <button type="button"
                                           class="rc-filter-dropdown-item rc-recording-action-download"
                                           data-id="${rcRecEscapeHtml(item.id)}"
                                           data-url="${rcRecEscapeHtml(item.recordingUrl)}"
                                           data-filename="recording-${rcRecEscapeHtml(item.id)}.mp3"
                                           title="Download recording">
                                            <i class="fa fa-download"></i> Download
                                        </button>
                                    ` : ''}
                                </div>
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        </a>`;
}

function rcRecGetWebhookSyncCount() {
    const configured = parseInt(window.RC_WEBHOOK_SYNC_COUNT, 10);
    if (Number.isFinite(configured) && configured > 0) return Math.min(100, configured);
    return Math.max(5, Math.min(50, parseInt(window.RC_REFRESH_SYNC_COUNT, 10) || 20));
}

function rcRecFindRowById(listEl, id) {
    if (!listEl) return null;
    const target = String(id || '');
    const rows = listEl.querySelectorAll('a.message-item[data-id]');
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        if (String(row.getAttribute('data-id') || '') === target) {
            return row;
        }
    }
    return null;
}

function rcRecUpsertRowsIncremental(changedItems) {
    const listEl = document.getElementById('tabRecordingsList');
    if (!listEl || !Array.isArray(changedItems) || !changedItems.length) return;

    const filteredChanged = filterRecordings(changedItems)
        .slice()
        .sort((a, b) => new Date(b.startTime || 0) - new Date(a.startTime || 0));

    const placeholder = listEl.querySelector('.text-muted, p.text-danger');
    if (placeholder && listEl.children.length === 1) {
        listEl.innerHTML = '';
    }

    filteredChanged.forEach(function (item) {
        const existing = rcRecFindRowById(listEl, item.id);
        const rowHtml = rcRecRenderListItem(item);
        if (existing) {
            existing.outerHTML = rowHtml;
        } else {
            listEl.insertAdjacentHTML('afterbegin', rowHtml);
        }
    });

    renderRecordingsStats(filterRecordings(rcRecordingsItems).length, rcRecordingsItems.length);
}

function rcRenderRecordingDetailInSidePanel(item) {
    if (!item || typeof window.rcShowCallsDetailPanel !== 'function') return false;

    const displayTime = rcRecFormatTime(item.startTime) || 'Unknown date';
    const duration = rcRecFormatDuration(item.durationSeconds);
    const totalSecs = parseInt(item.durationSeconds || 0, 10);
    const mask = p => p ? ((typeof maskPhoneNumber === 'function') ? maskPhoneNumber(p) : p) : 'Unknown';
    const counterPhone = item.actionPhone || item.fromPhone || item.toPhone || '';
    const counterMasked = mask(counterPhone);
    const canActionPhone = !!item.actionPhone;
    const actionPhone = rcRecEscapeHtml(item.actionPhone || '');

    const colors = ['#6366f1','#0ea5e9','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6'];
    const digits = rcRecDigits(counterPhone);
    const avatarColor = colors[(digits ? parseInt(digits.slice(-3), 10) : 0) % colors.length];
    const initials = (digits.slice(-2) || 'RC').toUpperCase();

    const connectedEl = document.getElementById('connectedPhoneNumber');
    const connectedFull = connectedEl ? (connectedEl.getAttribute('data-full-number') || '') : '';
    const connectedMasked = (typeof maskPhoneNumber === 'function') ? maskPhoneNumber(connectedFull) : connectedFull;

    const transcription = item.transcription || '';

    const html = `
        <div class="rc-detail-panel py-4">
            <div class="text-center px-3">
                <div class="rc-detail-avatar" style="background:${avatarColor};">${rcRecEscapeHtml(initials)}</div>
                <div class="rc-detail-contact-name">${rcRecEscapeHtml(item.label || counterMasked)}</div>
                <div class="rc-detail-contact-phone" title="Copy number"
                     onclick="if(navigator.clipboard)navigator.clipboard.writeText('${rcRecEscapeHtml(counterPhone)}')">
                    ${rcRecEscapeHtml(counterMasked)} <i class="fa fa-clone"></i>
                </div>
                <div class="rc-detail-actions">
                    <button class="rc-detail-action-btn" title="Call" ${canActionPhone ? '' : 'disabled'}
                        onclick="if(typeof rcRecordingCall==='function')rcRecordingCall('${actionPhone}')">
                        <i class="fa fa-phone"></i>
                    </button>
                    <button class="rc-detail-action-btn" title="Message" ${canActionPhone ? '' : 'disabled'}
                        onclick="if(typeof rcRecordingMessage==='function')rcRecordingMessage('${actionPhone}')">
                        <i class="fa fa-comment"></i>
                    </button>
                    <button class="rc-detail-action-btn" title="Note" disabled><i class="fa fa-sticky-note-o"></i></button>
                    <button class="rc-detail-action-btn rc-recording-detail-block" data-phone="${actionPhone}" title="Block" ${canActionPhone ? '' : 'disabled'}
                        onclick="if(typeof rcRecordingBlock==='function')rcRecordingBlock('${actionPhone}')"><i class="fa fa-ban"></i></button>
                </div>
            </div>
            <div class="rc-detail-info-card">
                <div class="rc-detail-field-label">From</div>
                <div class="rc-detail-field-value">${rcRecEscapeHtml(connectedMasked)} (me)</div>
                <hr class="rc-detail-divider">
                <div class="rc-detail-time-row">
                    <span class="rc-detail-time-label">${rcRecEscapeHtml(displayTime)}</span>
                    <div class="rc-detail-audio-controls">
                        <button class="rc-detail-speed-btn" id="rcRecSideSpeedBtn" onclick="rcRecSideToggleSpeed()">1x</button>
                        ${item.recordingUrl ? `<button class="rc-detail-download-btn" title="Download"
                            onclick="rcRecSideDownload('${rcRecEscapeHtml(item.recordingUrl)}','recording-${rcRecEscapeHtml(item.id)}.mp3')">
                            <i class="fa fa-download"></i></button>` : ''}
                    </div>
                </div>
                <audio id="rcRecordingSideAudio" preload="metadata" style="display:none;" ${item.recordingUrl ? '' : 'disabled'}>
                    ${item.recordingUrl ? `<source src="${rcRecEscapeHtml(item.recordingUrl)}" type="audio/mpeg">` : ''}
                </audio>
                <div class="rc-detail-audio-player">
                    <button class="rc-detail-play-btn" id="rcRecSidePlayBtn" onclick="rcRecSideTogglePlay()" ${item.recordingUrl ? '' : 'disabled'}>
                        <i class="fa fa-play" id="rcRecSidePlayIcon"></i>
                    </button>
                    <div class="rc-detail-progress-wrap">
                        <input type="range" class="rc-detail-progress" id="rcRecSideProgress" min="0" max="${totalSecs || 100}" value="0"
                            oninput="rcRecSideSeek(this.value)">
                    </div>
                    <span class="rc-detail-duration" id="rcRecSideDuration">00:00 / ${rcRecEscapeHtml(duration)}</span>
                </div>
            </div>
            <div class="rc-detail-tabs-nav">
                <button class="rc-detail-tab-btn active" id="rcRecTabNotes"
                    onclick="rcRecSideShowTab('notes')">Notes</button>
                <button class="rc-detail-tab-btn" id="rcRecTabTranscript"
                    onclick="rcRecSideShowTab('transcript')">Transcript</button>
            </div>
            <div class="rc-detail-tab-pane active" id="rcRecPaneNotes">
                <div class="rc-detail-ai-label">Content generated by AI</div>
                <div class="text-muted">${transcription ? rcRecEscapeHtml(transcription) : 'No notes available.'}</div>
            </div>
            <div class="rc-detail-tab-pane" id="rcRecPaneTranscript">
                <div class="text-muted">${transcription ? rcRecEscapeHtml(transcription) : 'No transcript available.'}</div>
            </div>
        </div>`;

    const rendered = window.rcShowCallsDetailPanel(html);
    if (rendered) {
        if (canActionPhone && window.rcBlockedNumbersApi && typeof window.rcBlockedNumbersApi.decorateBlockActionElement === 'function') {
            setTimeout(function () {
                const btn = Array.from(document.querySelectorAll('.rc-recording-detail-block')).find(function (node) {
                    return String(node.getAttribute('data-phone') || '') === String(actionPhone || '');
                });
                if (btn) window.rcBlockedNumbersApi.decorateBlockActionElement(btn, actionPhone);
            }, 0);
        }
        const audio = document.getElementById('rcRecordingSideAudio');
        if (audio) {
            audio.addEventListener('timeupdate', function () {
                const prog = document.getElementById('rcRecSideProgress');
                const dur = document.getElementById('rcRecSideDuration');
                if (prog) prog.value = Math.floor(audio.currentTime);
                if (dur) dur.textContent = rcRecSideFmtTime(audio.currentTime) + ' / ' + rcRecSideFmtTime(audio.duration || totalSecs);
            });
            audio.addEventListener('ended', function () {
                const icon = document.getElementById('rcRecSidePlayIcon');
                if (icon) { icon.classList.remove('fa-pause'); icon.classList.add('fa-play'); }
            });
        }
    }
    return rendered;
}

function rcRecSideFmtTime(secs) {
    const s = Math.floor(isNaN(secs) ? 0 : secs);
    return String(Math.floor(s / 60)).padStart(2, '0') + ':' + String(s % 60).padStart(2, '0');
}

function rcRecSideTogglePlay() {
    const audio = document.getElementById('rcRecordingSideAudio');
    const icon = document.getElementById('rcRecSidePlayIcon');
    if (!audio) return;
    if (audio.paused) {
        audio.play().catch(function () {});
        if (icon) { icon.classList.remove('fa-play'); icon.classList.add('fa-pause'); }
    } else {
        audio.pause();
        if (icon) { icon.classList.remove('fa-pause'); icon.classList.add('fa-play'); }
    }
}

function rcRecSideSeek(val) {
    const audio = document.getElementById('rcRecordingSideAudio');
    if (audio) audio.currentTime = parseFloat(val) || 0;
}

function rcRecSideToggleSpeed() {
    const audio = document.getElementById('rcRecordingSideAudio');
    const btn = document.getElementById('rcRecSideSpeedBtn');
    if (!audio || !btn) return;
    const rates = [1, 1.5, 2];
    const cur = rates.indexOf(audio.playbackRate);
    const next = rates[(cur + 1) % rates.length];
    audio.playbackRate = next;
    btn.textContent = next + 'x';
}

function rcRecSideDownload(url, filename) {
    if (!url) return;
    const a = document.createElement('a');
    a.href = url; a.download = filename || 'recording.mp3'; a.target = '_blank';
    document.body.appendChild(a); a.click(); document.body.removeChild(a);
}

function rcRecSideShowTab(tab) {
    const notesBtn = document.getElementById('rcRecTabNotes');
    const transcriptBtn = document.getElementById('rcRecTabTranscript');
    const notesPane = document.getElementById('rcRecPaneNotes');
    const transcriptPane = document.getElementById('rcRecPaneTranscript');
    if (!notesBtn || !transcriptBtn) return;
    if (tab === 'notes') {
        notesBtn.classList.add('active'); transcriptBtn.classList.remove('active');
        if (notesPane) notesPane.classList.add('active');
        if (transcriptPane) transcriptPane.classList.remove('active');
    } else {
        transcriptBtn.classList.add('active'); notesBtn.classList.remove('active');
        if (transcriptPane) transcriptPane.classList.add('active');
        if (notesPane) notesPane.classList.remove('active');
    }
}

function applyRecordingsFiltersAndRender() {
    const filtered = filterRecordings(rcRecordingsItems);
    renderRecordingsList(filtered);
    renderRecordingsStats(filtered.length, rcRecordingsItems.length);
}

function rcRecMergeItems(existingItems, incomingItems) {
    const existing = Array.isArray(existingItems) ? existingItems : [];
    const incoming = Array.isArray(incomingItems) ? incomingItems : [];
    const byId = new Map();

    existing.forEach(item => {
        byId.set(String(item.id), item);
    });
    incoming.forEach(item => {
        byId.set(String(item.id), item);
    });

    return Array.from(byId.values()).sort((a, b) => new Date(b.startTime || 0) - new Date(a.startTime || 0));
}

function findRecordingById(id) {
    const target = String(id || '').trim();
    if (!target) return null;
    return rcRecordingsItems.find(item => String(item.id) === target) || null;
}

function closeRecordingsMoreMenus(scopeEl) {
    const root = scopeEl || document;
    const openMenus = root.querySelectorAll('.rc-recording-more-wrap.is-open');
    openMenus.forEach(function (wrap) {
        wrap.classList.remove('is-open');
        const menu = wrap.querySelector('.rc-recording-more-menu');
        if (menu) menu.hidden = true;
        const toggle = wrap.querySelector('.rc-recording-action-more');
        if (toggle) toggle.setAttribute('aria-expanded', 'false');
    });
}

async function rcRecResolveRecordingUrlById(itemId, fallbackUrl = '') {
    const id = String(itemId || '').trim();
    const fallback = String(fallbackUrl || '').trim();
    if (!id) return fallback;

    if (rcRecordingsResolvedUrlById[id]) {
        return rcRecordingsResolvedUrlById[id];
    }

    try {
        const response = await fetch(rcRoute('ringcentral.api.recording', { id }), {
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        const payload = await rcRecParseJsonOrRedirect(response);
        if (response.ok && payload && payload.success && payload.url) {
            const resolved = String(payload.url).trim();
            if (resolved) {
                rcRecordingsResolvedUrlById[id] = resolved;
                return resolved;
            }
        }
    } catch (error) {
        console.warn('Failed to resolve recording URL via API', error);
    }

    return fallback;
}

async function rcRecEnsureItemRecordingUrl(item) {
    if (!item || typeof item !== 'object') return '';
    const fallback = String(item.recordingUrl || '').trim();
    const resolved = await rcRecResolveRecordingUrlById(item.id, fallback);
    if (resolved) {
        item.recordingUrl = resolved;
        item.hasRecording = true;
    }
    return resolved;
}

function openRecordingModal() {
    if (window.jQuery && jQuery('#recordingModal').length && typeof jQuery.fn.modal === 'function') {
        jQuery('#recordingModal').modal('show');
        return;
    }

    const modal = document.getElementById('recordingModal');
    if (modal) {
        modal.classList.add('show');
        modal.style.display = 'block';
        modal.removeAttribute('aria-hidden');
    }
}

function rcRecTryAutoPlay(player) {
    if (!player || typeof player.play !== 'function') return;
    try {
        const maybePromise = player.play();
        if (maybePromise && typeof maybePromise.catch === 'function') {
            maybePromise.catch(function () { });
        }
    } catch (_) { }
}

async function showRecordingModalById(itemId, autoPlay = false) {
    const item = findRecordingById(itemId);
    if (!item) return;

    const durationEl = document.getElementById('recordingDurationText');
    const recordedAtEl = document.getElementById('recordingRecordedAtText');
    const audioEl = document.getElementById('recordingAudio');
    const downloadEl = document.getElementById('recordingDownloadLink');
    const recordingUrl = await rcRecEnsureItemRecordingUrl(item);

    if (durationEl) durationEl.textContent = rcRecFormatDuration(item.durationSeconds);
    if (recordedAtEl) recordedAtEl.textContent = rcRecFormatTime(item.startTime);

    if (audioEl) {
        pauseRecordingAudio();
        audioEl.src = recordingUrl || '';
        audioEl.load();
    }

    if (downloadEl) {
        if (recordingUrl) {
            downloadEl.href = recordingUrl;
            downloadEl.setAttribute('download', `recording-${item.id}.mp3`);
            downloadEl.classList.remove('disabled');
            downloadEl.setAttribute('aria-disabled', 'false');
        } else {
            downloadEl.href = '#';
            downloadEl.classList.add('disabled');
            downloadEl.setAttribute('aria-disabled', 'true');
        }
    }

    const renderedInSidePanel = rcRenderRecordingDetailInSidePanel(item);
    if (!renderedInSidePanel) {
        openRecordingModal();
    }

    if (autoPlay && recordingUrl) {
        const activePlayer = renderedInSidePanel
            ? document.getElementById('rcRecordingSideAudio')
            : document.getElementById('recordingAudio');
        rcRecTryAutoPlay(activePlayer);
    }
}

function rcRecordingCall(phone) {
    if (!phone) return;
    if (typeof rcCallFromList === 'function') {
        rcCallFromList(phone);
        return;
    }

    try {
        if (typeof openDialerModal === 'function') openDialerModal();
        const input = document.getElementById('callPhone');
        if (input) input.value = phone;
    } catch (error) {
        console.warn('Failed to start call from recording', error);
    }
}
window.rcRecordingCall = rcRecordingCall;

function rcRecordingMessage(phone) {
    if (!phone) return;
    if (typeof rcMessageFromList === 'function') {
        rcMessageFromList(phone);
        return;
    }

    try {
        const smsPhone = document.getElementById('smsPhone');
        if (smsPhone) {
            smsPhone.value = phone;
            smsPhone.dispatchEvent(new Event('input', { bubbles: true }));
        }
        if (typeof window.rcSmsModalSetRecipients === 'function') {
            window.rcSmsModalSetRecipients([phone]);
        }
        if (window.jQuery && jQuery('#sendMessageModal').length && typeof jQuery.fn.modal === 'function') {
            jQuery('#sendMessageModal').modal('show');
        }
    } catch (error) {
        console.warn('Failed to start message from recording', error);
    }
}
window.rcRecordingMessage = rcRecordingMessage;

async function rcRecordingBlock(phone) {
    if (!phone) return;
    if (!window.rcBlockedNumbersApi || typeof window.rcBlockedNumbersApi.toggleNumberFromContext !== 'function') return;
    await window.rcBlockedNumbersApi.toggleNumberFromContext(phone, 'Blocked from Recordings tab', 'Recordings');
}
window.rcRecordingBlock = rcRecordingBlock;

function bindRecordingsListActions() {
    const listEl = document.getElementById('tabRecordingsList');
    if (!listEl || listEl.dataset.rcBound === '1') return;
    listEl.dataset.rcBound = '1';

    listEl.addEventListener('click', async function (event) {
        const moreBtn = event.target.closest('.rc-recording-action-more');
        if (moreBtn) {
            event.preventDefault();
            event.stopPropagation();
            const wrap = moreBtn.closest('.rc-recording-more-wrap');
            if (!wrap) return;

            const isOpen = wrap.classList.contains('is-open');
            closeRecordingsMoreMenus(listEl);
            if (!isOpen) {
                wrap.classList.add('is-open');
                const menu = wrap.querySelector('.rc-recording-more-menu');
                if (menu) menu.hidden = false;
                moreBtn.setAttribute('aria-expanded', 'true');
                const blockAction = wrap.querySelector('.rc-recording-action-block');
                if (blockAction && window.rcBlockedNumbersApi && typeof window.rcBlockedNumbersApi.decorateBlockActionElement === 'function') {
                    window.rcBlockedNumbersApi.decorateBlockActionElement(blockAction, blockAction.getAttribute('data-phone'));
                }
            }
            return;
        }

        const playBtn = event.target.closest('.rc-recording-action-play');
        if (playBtn) {
            event.preventDefault();
            event.stopPropagation();
            closeRecordingsMoreMenus(listEl);
            if (!playBtn.disabled) {
                await showRecordingModalById(playBtn.getAttribute('data-id'), true);
            }
            return;
        }

        const callBtn = event.target.closest('.rc-recording-action-call');
        if (callBtn) {
            event.preventDefault();
            event.stopPropagation();
            closeRecordingsMoreMenus(listEl);
            rcRecordingCall(callBtn.getAttribute('data-phone'));
            return;
        }

        const msgBtn = event.target.closest('.rc-recording-action-message');
        if (msgBtn) {
            event.preventDefault();
            event.stopPropagation();
            closeRecordingsMoreMenus(listEl);
            rcRecordingMessage(msgBtn.getAttribute('data-phone'));
            return;
        }
        const blockBtn = event.target.closest('.rc-recording-action-block');
        if (blockBtn) {
            event.preventDefault();
            event.stopPropagation();
            closeRecordingsMoreMenus(listEl);
            rcRecordingBlock(blockBtn.getAttribute('data-phone'));
            return;
        }

        const row = event.target.closest('[data-id]');
        const downloadBtn = event.target.closest('.rc-recording-action-download');
        if (downloadBtn) {
            event.preventDefault();
            event.stopPropagation();
            closeRecordingsMoreMenus(listEl);

            const itemId = String(downloadBtn.getAttribute('data-id') || '').trim();
            const fallbackUrl = String(downloadBtn.getAttribute('data-url') || '').trim();
            const filename = String(downloadBtn.getAttribute('data-filename') || '').trim();
            const item = itemId ? findRecordingById(itemId) : null;
            const url = item
                ? await rcRecEnsureItemRecordingUrl(item)
                : await rcRecResolveRecordingUrlById(itemId, fallbackUrl);
            if (!url) return;

            const link = document.createElement('a');
            link.href = url;
            if (filename) {
                link.setAttribute('download', filename);
            }
            link.setAttribute('target', '_blank');
            link.setAttribute('rel', 'noopener');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            return;
        }

        if (row) {
            event.preventDefault();
            closeRecordingsMoreMenus(listEl);
            await showRecordingModalById(row.getAttribute('data-id'));
        }
    });

    if (!window._rcRecordingsOutsideMenuBound) {
        window._rcRecordingsOutsideMenuBound = true;
        document.addEventListener('click', function (event) {
            if (event.target.closest('#tabRecordingsList .rc-recording-more-wrap')) return;
            closeRecordingsMoreMenus(document.getElementById('tabRecordingsList'));
        });
    }

    if (!window._rcRecordingsEscMenuBound) {
        window._rcRecordingsEscMenuBound = true;
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeRecordingsMoreMenus(document.getElementById('tabRecordingsList'));
            }
        });
    }
}

function bindRecordingsControls() {
    const searchEl = document.getElementById('tabRecordingsSearch');

    if (searchEl && searchEl.dataset.rcBound !== '1') {
        searchEl.dataset.rcBound = '1';
        let searchDebounce = null;
        searchEl.addEventListener('input', function () {
            clearTimeout(searchDebounce);
            searchDebounce = setTimeout(applyRecordingsFiltersAndRender, 140);
        });
    }
}

function bindRecordingModalEvents() {
    const modalEl = document.getElementById('recordingModal');
    if (!modalEl || modalEl.dataset.rcBound === '1') return;
    modalEl.dataset.rcBound = '1';

    modalEl.addEventListener('click', function (event) {
        if (event.target === modalEl) {
            pauseRecordingAudio();
        }
    });

    const closeBtn = modalEl.querySelector('.close');
    if (closeBtn) {
        closeBtn.addEventListener('click', pauseRecordingAudio);
    }

    if (window.jQuery && jQuery('#recordingModal').length) {
        jQuery('#recordingModal').on('hidden.bs.modal', function () {
            pauseRecordingAudio();
        });
    }
}

function bindRecordingsTabLoad() {
    const selector = 'a[href="#callRecordings"], a[href="#tabRecordings"]';

    if (window.jQuery) {
        const tabs = jQuery(selector);
        if (tabs.length) {
            tabs.off('shown.bs.tab.rcRecordings').on('shown.bs.tab.rcRecordings', function () {
                loadCallRecordings().then(function () {
                    rcRecMaybeBackgroundRefresh();
                });
            });
        }
        return;
    }

    document.querySelectorAll(selector).forEach(tab => {
        if (tab.dataset.rcRecordingsBound === '1') return;
        tab.dataset.rcRecordingsBound = '1';
        tab.addEventListener('click', function () {
            setTimeout(function () {
                loadCallRecordings().then(function () {
                    rcRecMaybeBackgroundRefresh();
                });
            }, 100);
        });
    });
}

function isRecordingsTabActive() {
    try {
        const outer = document.getElementById('tabCalls');
        const inner = document.getElementById('callRecordings');
        return !!(
            outer && inner
            && outer.classList.contains('active')
            && inner.classList.contains('active')
        );
    } catch (_) {
        return false;
    }
}

function rcRecLoadingMarkup(label) {
    return `
        <div class="py-3 text-center">
            <div class="d-inline-flex align-items-center px-3 py-2 rounded-pill rc-loading-pill">
                <span class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></span>
                <span class="ms-2 text-muted">${rcRecEscapeHtml(label || 'Loading recordings...')}</span>
            </div>
        </div>`;
}

function setRecordingsLoadMoreLoading(isLoading) {
    const listEl = document.getElementById('tabRecordingsList');
    if (!listEl) return;

    let loader = document.getElementById('tabRecordingsScrollLoader');
    if (isLoading) {
        if (!loader) {
            loader = document.createElement('div');
            loader.id = 'tabRecordingsScrollLoader';
            loader.className = 'rc-scroll-bottom-loader';
            loader.innerHTML = '<span class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></span>';
        }
        if (loader.parentNode !== listEl) {
            listEl.appendChild(loader);
        }
        loader.style.display = 'block';
        return;
    }

    if (loader) {
        loader.style.display = 'none';
    }
}

function refreshRecordingsTab() {
    return loadCallRecordings(true, false, null, true, 'manual');
}

window.refreshRecordingsTab = refreshRecordingsTab;

function rcRecMaybeBackgroundRefresh() {
    if (rcRecordingsLoading) {
        setTimeout(rcRecMaybeBackgroundRefresh, 400);
        return;
    }

    const now = Date.now();
    const throttleMs = 45000;
    if ((now - rcRecordingsLastAutoRefreshAt) < throttleMs) return;
    rcRecordingsLastAutoRefreshAt = now;
    loadCallRecordings(true, false, null, true, 'auto').catch(function () { });
}

function rcRecScheduleRefreshRetry(delayMs = 15000) {
    if (rcRecordingsRefreshRetryTimer) return;
    rcRecordingsRefreshRetryTimer = setTimeout(function () {
        rcRecordingsRefreshRetryTimer = null;
        loadCallRecordings(true, false, null, true, 'retry').catch(function () { });
    }, delayMs);
}

function rcRecScheduleBackgroundPoll(delayMs = 4000) {
    if (rcRecordingsBackgroundPollTimer) return;
    rcRecordingsBackgroundPollTimer = setTimeout(function () {
        rcRecordingsBackgroundPollTimer = null;
        loadCallRecordings(false, false, null, true, 'background_poll').catch(function () { });
    }, delayMs);
}

function renderRecordingsLoadMore(hasMore) {
    try {
        window._rc_recordingsHasMore = !!hasMore;
        const listEl = document.getElementById('tabRecordingsList');
        if (!listEl) return;

        if (!listEl._rc_recordingsScrollLoadBound) {
            listEl._rc_recordingsScrollLoadBound = true;
            let loading = false;
            listEl.addEventListener('scroll', function () {
                try {
                    if (loading || rcRecordingsLoading) return;
                    if (!window._rc_recordingsHasMore || !rcRecordingsNextCursor) return;

                    const distanceFromBottom = listEl.scrollHeight - listEl.scrollTop - listEl.clientHeight;
                    if (distanceFromBottom > 120) return;

                    loading = true;
                    loadCallRecordings(false, true, rcRecordingsNextCursor, false).finally(function () {
                        loading = false;
                    });
                } catch (_) { }
            });
        }

        let footer = document.getElementById('tabRecordingsLoadMore');
        if (!footer && listEl && listEl.parentNode) {
            footer = document.createElement('div');
            footer.id = 'tabRecordingsLoadMore';
            listEl.parentNode.insertBefore(footer, listEl);
        }
        if (!footer) return;

        let btn = footer.querySelector('button');
        if (!btn) {
            footer.innerHTML = '<button type="button" class="ui-btn" title="Refresh recordings"><i class="fa fa-refresh"></i></button>';
            btn = footer.querySelector('button');
            if (btn) {
                btn.addEventListener('click', function () {
                    if (rcRecordingsLoading) return;
                    setRecordingsLoadMoreRefreshing(true);
                    loadCallRecordings(true, false, null, true, 'manual_button')
                        .then(function () {
                            const nextCursor = rcRecordingsNextCursor || null;
                            if (!nextCursor) return Promise.resolve();
                            return loadCallRecordings(false, true, nextCursor, false, 'manual_button_cursor');
                        })
                        .catch(function () { /* no-op */ })
                        .finally(function () {
                            setRecordingsLoadMoreRefreshing(false);
                        });
                });
            }
        }

        footer.style.display = 'block';
    } catch (_) { }
}

function setRecordingsLoadMoreRefreshing(isLoading) {
    try {
        const footer = document.getElementById('tabRecordingsLoadMore');
        if (!footer) return;
        const btn = footer.querySelector('button');
        const icon = btn ? btn.querySelector('i') : null;
        if (!btn || !icon) return;

        const loading = !!isLoading;
        btn.disabled = loading;
        btn.classList.toggle('is-loading', loading);
        icon.classList.toggle('fa-spin', loading);
        icon.classList.toggle('fa-refresh', !loading);
        icon.classList.toggle('fa-spinner', loading);
    } catch (_) { }
}

function loadCallRecordings(forceRefresh = false, append = false, beforeCursor = null, mergeWithExisting = false, debugTrigger = 'default') {
    if (rcRecordingsLoading) {
        return Promise.resolve();
    }

    rcRecordingsLoading = true;
    const shouldScheduleAutoRefresh = !forceRefresh && !append && !beforeCursor && debugTrigger !== 'webhook_event';
    if (append) {
        setRecordingsLoadMoreLoading(true);
    }
    const listEl = document.getElementById('tabRecordingsList');
    if (listEl && !append && (!mergeWithExisting || !rcRecordingsItems.length)) {
        const loadingLabel = forceRefresh
            ? 'Refreshing recordings...'
            : ((getRecordingsSearchValue() || '').trim() ? 'Searching recordings...' : 'Loading recordings...');
        listEl.innerHTML = rcRecLoadingMarkup(loadingLabel);
    }

    const defaultPageSize = Math.max(1, parseInt(window.RC_INITIAL_PAGE_SIZE, 10) || 50);
    const defaultRefreshCount = Math.max(1, parseInt(window.RC_REFRESH_SYNC_COUNT, 10) || 50);
    const params = { per_page: defaultPageSize };
    const q = getRecordingsSearchValue();
    const shouldConsiderWebhookToast = !append && !q;
    const canNotifyNewRecordings = shouldConsiderWebhookToast && window._rc_recordingsNotifyReady === true;
    const previousRecordingIds = new Set((rcRecordingsItems || []).map(item => String(item && item.id ? item.id : '')));
    if (q) params.q = q;
    if (beforeCursor) params.before = beforeCursor;
    const flow = forceRefresh
        ? `refresh_${String(debugTrigger || 'default')}`
        : (append ? 'load_more' : 'local');
    rcRecordingsReqSeq += 1;
    params._rc_flow = flow;
    params._rc_seq = String(rcRecordingsReqSeq);
    if (forceRefresh) {
        params.refresh = 1;
        params.count = defaultRefreshCount;
        params._ts = Date.now();
    }
    const url = rcRoute('ringcentral.refreshRecordings', {}, params);
    console.info('[RC Recordings] request', {
        seq: rcRecordingsReqSeq,
        flow: flow,
        refresh: !!forceRefresh,
        append: !!append,
        before: beforeCursor || null
    });

    return fetch(url, {
        credentials: 'same-origin',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => rcRecParseJsonOrRedirect(response))
        .then(payload => {
            const listEl = document.getElementById('tabRecordingsList');
            if (!listEl) return;

            if (!payload || !payload.success) {
                const message = String((payload && payload.message) || '').toLowerCase();
                const isEmptyResponse = message.includes('no call logs') || message.includes('no recording');
                if ((forceRefresh || append || mergeWithExisting) && rcRecordingsItems.length) {
                    rcRecordingsSyncNotice = (payload && payload.message) || "Can't get new from server. Retry in a min.";
                    renderRecordingsStats(rcRecordingsItems.length, rcRecordingsItems.length);
                    rcRecScheduleRefreshRetry();
                    return;
                }

                listEl.innerHTML = isEmptyResponse
                    ? '<div class="text-muted">No recordings found</div>'
                    : `<p class="text-danger">${rcRecEscapeHtml((payload && payload.message) || 'Failed to load recordings')}</p>`;
                rcRecordingsItems = [];
                rcRecordingsSyncNotice = '';
                renderRecordingsStats(0, 0);
                return;
            }

            if (payload.sync && payload.sync.attempted) {
                if (payload.sync.success === false) {
                    rcRecordingsSyncNotice = payload.sync.message || "Can't get new from server. Retry in a min.";
                    rcRecScheduleRefreshRetry();
                } else if (payload.sync.success === true) {
                    rcRecordingsSyncNotice = '';
                }
                if (payload.sync.background) {
                    rcRecScheduleBackgroundPoll();
                }
            } else if (!forceRefresh) {
                rcRecordingsSyncNotice = '';
            }

            const records = Array.isArray(payload.data) ? payload.data : [];
            const normalized = records
                .map((record, index) => rcRecNormalizeItem(record, index))
                .sort((a, b) => new Date(b.startTime || 0) - new Date(a.startTime || 0));
            const newRecordingIds = new Set();
            let newRecordingsCount = 0;
            if (canNotifyNewRecordings) {
                normalized.forEach(item => {
                    const id = String(item && item.id ? item.id : '');
                    if (!id) return;
                    if (!previousRecordingIds.has(id) && !newRecordingIds.has(id)) {
                        newRecordingIds.add(id);
                        newRecordingsCount++;
                    }
                });
            }

            if (append) {
                rcRecordingsItems = rcRecMergeItems(rcRecordingsItems, normalized);
            } else if (mergeWithExisting) {
                rcRecordingsItems = rcRecMergeItems(rcRecordingsItems, normalized);
            } else {
                rcRecordingsItems = normalized;
            }

            const summary = payload.summary || {};
            if (summary.total_available !== undefined && summary.total_available !== null) {
                rcRecordingsTotalAvailable = parseInt(summary.total_available, 10) || 0;
            } else if (!append && !mergeWithExisting) {
                rcRecordingsTotalAvailable = null;
            }
            if (summary.with_recording_total !== undefined && summary.with_recording_total !== null) {
                rcRecordingsWithRecordingTotal = parseInt(summary.with_recording_total, 10) || 0;
            } else if (!append && !mergeWithExisting) {
                rcRecordingsWithRecordingTotal = null;
            }

            applyRecordingsFiltersAndRender();

            if (shouldConsiderWebhookToast) {
                window._rc_recordingsNotifyReady = true;
            }

            const pagination = payload.pagination || {};
            rcRecordingsNextCursor = pagination.next_cursor || null;
            renderRecordingsLoadMore(!!pagination.has_more);
        })
        .catch(error => {
            const listEl = document.getElementById('tabRecordingsList');
            const hasExisting = rcRecordingsItems.length > 0;
            if (listEl && !(forceRefresh && hasExisting) && !(append && hasExisting)) {
                listEl.innerHTML = '<p class="text-danger">Error loading call recordings</p>';
            }
            if (forceRefresh || append) {
                rcRecordingsSyncNotice = "Can't get new from server. Retry in a min.";
                renderRecordingsStats(rcRecordingsItems.length || 0, rcRecordingsItems.length || 0);
                rcRecScheduleRefreshRetry();
            }
            console.error('Error loading call recordings:', error);
        })
        .finally(() => {
            if (append) {
                setRecordingsLoadMoreLoading(false);
            }
            rcRecordingsLoading = false;
            if (shouldScheduleAutoRefresh) {
                setTimeout(rcRecMaybeBackgroundRefresh, 0);
            }
        });
}

function loadRecordingsIncrementalFromWebhook() {
    if (rcRecordingsLoading) return Promise.resolve();
    if (!Array.isArray(rcRecordingsItems) || rcRecordingsItems.length === 0) {
        return loadCallRecordings(false, false, null, true, 'webhook_incremental_bootstrap');
    }

    const q = getRecordingsSearchValue();
    if (q) {
        return loadCallRecordings(false, false, null, true, 'webhook_incremental_fallback_search');
    }

    const perPage = rcRecGetWebhookSyncCount();
    const params = {
        per_page: perPage,
        refresh: 1,
        count: perPage,
        _rc_flow: 'refresh_webhook_incremental',
        _rc_seq: String(++rcRecordingsReqSeq),
        _ts: Date.now()
    };
    const previousIds = new Set((rcRecordingsItems || []).map(item => String(item && item.id ? item.id : '')));

    rcRecordingsLoading = true;
    return fetch(rcRoute('ringcentral.refreshRecordings', {}, params), {
        credentials: 'same-origin',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => rcRecParseJsonOrRedirect(response))
        .then(payload => {
            if (!payload || !payload.success) return;

            const records = Array.isArray(payload.data) ? payload.data : [];
            const normalized = records
                .map((record, index) => rcRecNormalizeItem(record, index))
                .sort((a, b) => new Date(b.startTime || 0) - new Date(a.startTime || 0));

            const changedItems = [];
            const changedMap = new Map();
            normalized.forEach(item => {
                const id = String(item.id || '');
                if (!id) return;
                const prev = findRecordingById(id);
                const isChanged = !prev
                    || String(prev.durationSeconds || '') !== String(item.durationSeconds || '')
                    || String(prev.recordingUrl || '') !== String(item.recordingUrl || '')
                    || String(prev.startTime || '') !== String(item.startTime || '');
                if (isChanged && !changedMap.has(id)) {
                    changedMap.set(id, item);
                    changedItems.push(item);
                }
            });

            rcRecordingsItems = rcRecMergeItems(rcRecordingsItems, normalized);
            const summary = payload.summary || {};
            if (summary.total_available !== undefined && summary.total_available !== null) {
                rcRecordingsTotalAvailable = parseInt(summary.total_available, 10) || 0;
            }
            if (summary.with_recording_total !== undefined && summary.with_recording_total !== null) {
                rcRecordingsWithRecordingTotal = parseInt(summary.with_recording_total, 10) || 0;
            }

            if (changedItems.length > 0) {
                rcRecUpsertRowsIncremental(changedItems);
            }

            const newCount = normalized.reduce((acc, item) => {
                return acc + (previousIds.has(String(item.id || '')) ? 0 : 1);
            }, 0);
            window._rc_recordingsNotifyReady = true;
        })
        .catch(error => {
            console.warn('Incremental recordings webhook refresh failed', error);
        })
        .finally(() => {
            rcRecordingsLoading = false;
        });
}

window.loadCallRecordings = loadCallRecordings;
window.loadRecordingsIncrementalFromWebhook = loadRecordingsIncrementalFromWebhook;

// Legacy helper kept for backward compatibility with older inline markup.
function pausePreviousRecording() {}

window.pausePreviousRecording = pausePreviousRecording;

document.addEventListener('DOMContentLoaded', function () {
    bindRecordingsControls();
    bindRecordingsListActions();
    bindRecordingModalEvents();
    bindRecordingsTabLoad();
});
