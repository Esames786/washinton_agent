/**
 * R-Dialer  Voicemail Module
 * Modern voicemail list rendering + playback controls
 */

let rcVoicemailItems = [];
let rcVoicemailLoading = false;
let rcVoicemailNextCursor = null;
let rcVoicemailLastAutoRefreshAt = 0;
let rcVoicemailSyncNotice = '';
let rcVoicemailReqSeq = 0;
let rcVoicemailRefreshRetryTimer = null;
let rcVoicemailBackgroundPollTimer = null;
let rcVoicemailTotalAvailable = null;
let rcVoicemailUnreadTotal = null;

function rcVmFormatBadgeCount(count) {
    const num = Math.max(0, parseInt(count || 0, 10) || 0);
    return {
        num,
        label: num > 99 ? '99+' : String(num)
    };
}

function updateVoicemailUnreadBadge(count) {
    const badges = [
        document.getElementById('rcVoicemailsUnreadBadgeInner'),
        document.getElementById('rcVoicemailsUnreadBadge')
    ].filter(Boolean);

    const { num, label } = rcVmFormatBadgeCount(count);
    window._rcVoicemailsUnreadTotal = num;

    badges.forEach((badge) => {
        badge.textContent = label;
        if (num > 0) {
            badge.classList.remove('is-hidden');
            badge.classList.remove('d-none');
        } else {
            badge.classList.add('is-hidden');
            badge.classList.add('d-none');
        }
    });

    if (typeof window.rcSyncCallsBadgeTotals === 'function') {
        window.rcSyncCallsBadgeTotals();
    }
}

function applyVoicemailSummary(summary) {
    if (!summary || typeof summary !== 'object') return;
    if (summary.total_available !== undefined && summary.total_available !== null) {
        rcVoicemailTotalAvailable = parseInt(summary.total_available, 10) || 0;
    }
    if (summary.unread_total !== undefined && summary.unread_total !== null) {
        rcVoicemailUnreadTotal = parseInt(summary.unread_total, 10) || 0;
        updateVoicemailUnreadBadge(rcVoicemailUnreadTotal);
    }
}

function pauseVoicemailAudio() {
    const players = [
        document.getElementById('voicemailAudio'),
        document.getElementById('rcVoicemailSideAudio')
    ].filter(Boolean);
    players.forEach((player) => {
        player.pause();
        player.currentTime = 0;
    });
}

window.pauseVoicemailAudio = pauseVoicemailAudio;

function rcVmEscapeHtml(value) {
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

function rcVmFormatDuration(rawSeconds) {
    const total = Math.max(0, parseInt(rawSeconds, 10) || 0);
    if (!total) return '0 sec';
    const hours = Math.floor(total / 3600);
    const minutes = Math.floor((total % 3600) / 60);
    const seconds = total % 60;

    if (hours > 0) return `${hours} hr ${minutes} min ${seconds} sec`;
    if (minutes > 0) return `${minutes} min ${seconds} sec`;
    return `${seconds} sec`;
}

function rcVmFormatTime(rawTime) {
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

function rcVmNormalizeStatus(rawStatus) {
    const key = String(rawStatus || '').trim().toLowerCase();
    if (!key) return 'Unknown';
    if (key === 'read' || key === 'listened') return 'Read';
    if (key === 'unread' || key === 'new') return 'Unread';
    return key.charAt(0).toUpperCase() + key.slice(1);
}

function rcVmStatusClass(label) {
    const key = String(label || '').toLowerCase();
    if (key === 'unread') return 'rc-status-missed';
    if (key === 'read') return 'rc-status-success';
    return 'rc-status-neutral';
}

function rcVmExtractText(rawValue) {
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
            const extracted = rcVmExtractText(candidates[i]);
            if (extracted) return extracted;
        }
    }
    return '';
}

function rcVmExtractDate(rawItem) {
    const item = rawItem && typeof rawItem === 'object' ? rawItem : {};
    const candidates = [
        item.creationTime,
        item.created_time,
        item.received_at,
        item.receivedAt,
        item.lastModifiedTime,
        item.last_modified_time,
        item.updatedAt,
        item.createdAt
    ];
    for (let i = 0; i < candidates.length; i++) {
        const text = rcVmExtractText(candidates[i]);
        if (text) return text;
    }
    return '';
}

function rcVmNormalizeItem(rawItem, index) {
    const item = rawItem && typeof rawItem === 'object' ? rawItem : {};
    const fromObj = item.from && typeof item.from === 'object' ? item.from : {};

    const id = item.id || item.voicemail_id || item.messageId || `vm-${index + 1}`;
    const phone = rcVmExtractText(
        fromObj.phoneNumber ||
        fromObj.extensionNumber ||
        item.phone_number ||
        item.caller_number ||
        item.from_number ||
        item.phoneNumber ||
        item.from
    );
    // Never render or index caller names in portal voicemail UI.
    const labelSource = phone || 'Unknown';
    const label = phone
        ? ((typeof maskPhoneNumber === 'function') ? maskPhoneNumber(phone) : phone)
        : labelSource;

    const createdAt = rcVmExtractDate(item);
    const status = rcVmNormalizeStatus(item.readStatus || item.message_status || item.status);

    const recordingUrl = item.media_url || item.audio_url || item.recording_url || '';
    const transcriptionRaw = item.transcription || item.transcript || '';
    const transcription = typeof transcriptionRaw === 'string'
        ? transcriptionRaw
        : (transcriptionRaw && transcriptionRaw.text ? transcriptionRaw.text : '');

    return {
        id: String(id),
        phone: String(phone || '').trim(),
        label,
        callerName: '',
        durationSeconds: parseInt(item.vmDuration || item.duration_seconds || item.duration || 0, 10) || 0,
        createdAt,
        status,
        statusClass: rcVmStatusClass(status),
        transcription: String(transcription || '').trim(),
        recordingUrl: String(recordingUrl || '').trim(),
        hasRecording: !!recordingUrl,
        searchText: [labelSource, phone, status, createdAt, transcription]
            .join(' ')
            .trim()
            .toLowerCase()
    };
}

async function rcVmParseJsonOrRedirect(response) {
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

function getVoicemailSearchValue() {
    const searchEl = document.getElementById('tabVoicemailsSearch');
    return (searchEl && searchEl.value) ? searchEl.value.trim().toLowerCase() : '';
}

function getVoicemailTypeFilterValue() {
    const buttonState = String(window._rc_voicemailTypeFilter || '').trim().toLowerCase();
    const filterEl = document.getElementById('tabVoicemailsTypeFilter');
    const raw = (filterEl && filterEl.value)
        ? String(filterEl.value).trim().toLowerCase()
        : (buttonState || 'all');
    const allowed = new Set(['all', 'unread', 'read', 'with_recording', 'no_recording']);
    return allowed.has(raw) ? raw : 'all';
}

function syncVoicemailFilterUi(type) {
    const filterType = String(type || 'all').toLowerCase();
    const allBtn = document.getElementById('tabVoicemailsAllBtn');
    const unreadBtn = document.getElementById('tabVoicemailsUnreadBtn');
    const filterEl = document.getElementById('tabVoicemailsTypeFilter');

    if (allBtn) {
        allBtn.classList.add('rc-filter-btn');
        allBtn.classList.toggle('is-active', filterType === 'all');
    }
    if (unreadBtn) {
        unreadBtn.classList.add('rc-filter-btn');
        unreadBtn.classList.toggle('is-active', filterType === 'unread');
    }

    if (filterEl) {
        filterEl.classList.add('rc-filter-select');
        if (filterType === 'all' && filterEl.value) {
            filterEl.value = '';
        } else if (filterType !== 'all' && filterEl.value !== filterType) {
            filterEl.value = filterType;
        }
        filterEl.classList.toggle('is-active', filterType !== 'all');
    }

    window._rc_voicemailTypeFilter = filterType;
}

function filterVoicemailItems(items) {
    const source = Array.isArray(items) ? items : [];
    const type = getVoicemailTypeFilterValue();
    const query = getVoicemailSearchValue();

    return source.filter(item => {
        if (type === 'unread' && String(item.status).toLowerCase() !== 'unread') return false;
        if (type === 'read' && String(item.status).toLowerCase() !== 'read') return false;
        if (type === 'with_recording' && !item.hasRecording) return false;
        if (type === 'no_recording' && item.hasRecording) return false;
        if (query && !String(item.searchText || '').includes(query)) return false;
        return true;
    });
}

function renderVoicemailStats(filteredCount, totalCount) {
    const statsEl = document.getElementById('voicemailStats');
    if (!statsEl) return;
    statsEl.style.display = '';

    const shownCount = Math.max(0, parseInt(filteredCount, 10) || 0);
    const loadedTotal = Math.max(0, parseInt(totalCount, 10) || 0);
    const displayTotal = rcVoicemailTotalAvailable !== null
        ? Math.max(0, parseInt(rcVoicemailTotalAvailable, 10) || 0)
        : loadedTotal;
    const unreadCount = rcVoicemailUnreadTotal !== null
        ? Math.max(0, parseInt(rcVoicemailUnreadTotal, 10) || 0)
        : rcVoicemailItems.filter(item => String(item.status).toLowerCase() === 'unread').length;

    let text = `${shownCount} shown / ${displayTotal} total` + (unreadCount ? ` | ${unreadCount} unread` : '');
    if (rcVoicemailSyncNotice) {
        text += ` | ${rcVoicemailSyncNotice}`;
    }
    statsEl.textContent = text;
}

function renderVoicemailList(items) {
    const listEl = document.getElementById('tabVoicemailsList');
    if (!listEl) return;

    if (!items.length) {
        const type = getVoicemailTypeFilterValue();
        const label = type === 'all' ? 'No voicemails found' : `No ${type.replace('_', ' ')} voicemails found`;
        listEl.innerHTML = `<div class="text-muted">${rcVmEscapeHtml(label)}</div>`;
        return;
    }

    listEl.innerHTML = items.map(rcVmRenderListItem).join('');
}

function rcVmRenderListItem(item) {
    const digits = String(item.phone || '').replace(/\D/g, '');
    const initials = (digits.slice(-2) || 'VM').toUpperCase();
    const displayTime = rcVmFormatTime(item.createdAt) || 'Unknown date';
    const duration = rcVmFormatDuration(item.durationSeconds);
    const canActionPhone = !!item.phone;
    const isUnread = String(item.status || '').toLowerCase() === 'unread';
    const toggleReadTitle = isUnread ? 'Mark as read' : 'Mark as unread';
    const toggleReadIcon = isUnread ? 'fa-envelope-open-o' : 'fa-envelope-o';
    const toggleReadTarget = isUnread ? 'read' : 'unread';

    return `
        <a href="#"
           class="list-group-item list-group-item-action message-item p-3 border rounded-3 mb-2"
           data-id="${rcVmEscapeHtml(item.id)}"
           data-search="${rcVmEscapeHtml(item.searchText)}">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                     style="width:50px; height:50px; flex-shrink:0; margin-right:10px;">
                    <span class="fw-bold">${rcVmEscapeHtml(initials)}</span>
                </div>
                <div class="flex-grow-1" style="min-width:0;">
                    <span class="fw-semibold d-block">${rcVmEscapeHtml(item.label || 'Unknown')}</span>
                    <small class="text-muted d-block preview-text">
                        ${rcVmEscapeHtml(duration)} |
                        <span class="rc-call-status-badge ${rcVmEscapeHtml(item.statusClass)}">${rcVmEscapeHtml(item.status)}</span>
                    </small>
                    ${item.transcription ? `<small class="text-muted d-block preview-text">${rcVmEscapeHtml(item.transcription)}</small>` : ''}
                </div>
                <div class="text-end ms-2">
                    <span class="text-muted small d-block">${rcVmEscapeHtml(displayTime)}</span>
                    <div class="rc-call-list-actions mt-1">
                        <button type="button" class="phone-ui-btn rc-voicemail-action-play" data-id="${rcVmEscapeHtml(item.id)}" title="Play voicemail">
                            <i class="fa fa-play"></i>
                        </button>
                        ${canActionPhone ? `
                            <button type="button" class="phone-ui-btn rc-voicemail-action-call" data-phone="${rcVmEscapeHtml(item.phone)}" title="Call">
                                <i class="fa fa-phone"></i>
                            </button>
                            <button type="button" class="msg-ui-btn rc-voicemail-action-message" data-phone="${rcVmEscapeHtml(item.phone)}" title="Message">
                                <i class="fa fa-comment"></i>
                            </button>
                        ` : ''}
                        <div class="rc-voicemail-more-wrap">
                            <button type="button"
                                    class="msg-ui-btn rc-voicemail-action-more"
                                    title="More"
                                    aria-haspopup="true"
                                    aria-expanded="false">
                                <i class="fa fa-ellipsis-h"></i>
                            </button>
                            <div class="rc-filter-dropdown-menu rc-voicemail-more-menu" hidden>
                                ${canActionPhone ? `
                                <button type="button"
                                        class="rc-filter-dropdown-item rc-voicemail-action-block"
                                        data-phone="${rcVmEscapeHtml(item.phone)}"
                                        title="Block number">
                                    <i class="fa fa-ban"></i> Block
                                </button>` : ''}
                                <button type="button"
                                        class="rc-filter-dropdown-item rc-voicemail-action-toggle-read"
                                        data-id="${rcVmEscapeHtml(item.id)}"
                                        data-status="${rcVmEscapeHtml(toggleReadTarget)}"
                                        title="${rcVmEscapeHtml(toggleReadTitle)}">
                                    <i class="fa ${rcVmEscapeHtml(toggleReadIcon)}"></i> ${rcVmEscapeHtml(toggleReadTitle)}
                                </button>
                                <button type="button"
                                        class="rc-filter-dropdown-item rc-voicemail-action-delete"
                                        data-id="${rcVmEscapeHtml(item.id)}"
                                        title="Delete voicemail">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>`;
}

function rcVmGetWebhookSyncCount() {
    const configured = parseInt(window.RC_WEBHOOK_SYNC_COUNT, 10);
    if (Number.isFinite(configured) && configured > 0) return Math.min(100, configured);
    return Math.max(5, Math.min(50, parseInt(window.RC_REFRESH_SYNC_COUNT, 10) || 20));
}

function rcVmFindRowById(listEl, id) {
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

function rcVmUpsertRowsIncremental(changedItems) {
    const listEl = document.getElementById('tabVoicemailsList');
    if (!listEl || !Array.isArray(changedItems) || !changedItems.length) return;

    const filteredChanged = filterVoicemailItems(changedItems)
        .slice()
        .sort((a, b) => new Date(b.createdAt || 0) - new Date(a.createdAt || 0));

    const placeholder = listEl.querySelector('.text-muted, p.text-danger');
    if (placeholder && listEl.children.length === 1) {
        listEl.innerHTML = '';
    }

    filteredChanged.forEach(function (item) {
        const existing = rcVmFindRowById(listEl, item.id);
        const rowHtml = rcVmRenderListItem(item);
        if (existing) {
            existing.outerHTML = rowHtml;
        } else {
            listEl.insertAdjacentHTML('afterbegin', rowHtml);
        }
    });

    renderVoicemailStats(filterVoicemailItems(rcVoicemailItems).length, rcVoicemailItems.length);
}

function closeVoicemailMoreMenus(scopeEl) {
    const root = scopeEl || document;
    const openMenus = root.querySelectorAll('.rc-voicemail-more-wrap.is-open');
    openMenus.forEach(function (wrap) {
        wrap.classList.remove('is-open');
        const menu = wrap.querySelector('.rc-voicemail-more-menu');
        if (menu) menu.hidden = true;
        const toggle = wrap.querySelector('.rc-voicemail-action-more');
        if (toggle) toggle.setAttribute('aria-expanded', 'false');
    });
}

function rcRenderVoicemailDetailInSidePanel(item, audioUrl) {
    if (!item || typeof window.rcShowCallsDetailPanel !== 'function') return false;

    const displayTime = rcVmFormatTime(item.createdAt) || 'Unknown date';
    const duration = rcVmFormatDuration(item.durationSeconds);
    const totalSecs = parseInt(item.durationSeconds || 0, 10);
    const fromLabel = item.label || 'Unknown';
    const canActionPhone = !!item.phone;
    const actionPhone = rcVmEscapeHtml(item.phone || '');

    const colors = ['#6366f1','#0ea5e9','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6'];
    const digits = String(item.phone || '').replace(/\D/g, '');
    const avatarColor = colors[(digits ? parseInt(digits.slice(-3), 10) : 0) % colors.length];
    const initials = (digits.slice(-2) || 'VM').toUpperCase();

    const connectedEl = document.getElementById('connectedPhoneNumber');
    const connectedFull = connectedEl ? (connectedEl.getAttribute('data-full-number') || '') : '';
    const connectedMasked = (typeof maskPhoneNumber === 'function') ? maskPhoneNumber(connectedFull) : connectedFull;

    const html = `
        <div class="rc-detail-panel py-4">
            <div class="text-center px-3">
                <div class="rc-detail-avatar" style="background:${avatarColor};">${rcVmEscapeHtml(initials)}</div>
                <div class="rc-detail-contact-name">${rcVmEscapeHtml(fromLabel)}</div>
                <div class="rc-detail-contact-phone" title="Copy number"
                     onclick="if(navigator.clipboard)navigator.clipboard.writeText('${rcVmEscapeHtml(item.phone || '')}')">
                    ${rcVmEscapeHtml(fromLabel)} <i class="fa fa-clone"></i>
                </div>
                <div class="rc-detail-actions">
                    <button class="rc-detail-action-btn" title="Call" ${canActionPhone ? '' : 'disabled'}
                        onclick="if(typeof rcVoicemailCall==='function')rcVoicemailCall('${actionPhone}')">
                        <i class="fa fa-phone"></i>
                    </button>
                    <button class="rc-detail-action-btn" title="Message" ${canActionPhone ? '' : 'disabled'}
                        onclick="if(typeof rcVoicemailMessage==='function')rcVoicemailMessage('${actionPhone}')">
                        <i class="fa fa-comment"></i>
                    </button>
                    <button class="rc-detail-action-btn" title="Add contact" disabled><i class="fa fa-user-plus"></i></button>
                    <button class="rc-detail-action-btn" title="Note" disabled><i class="fa fa-sticky-note-o"></i></button>
                    <button class="rc-detail-action-btn rc-voicemail-detail-block" data-phone="${actionPhone}" title="Block" ${canActionPhone ? '' : 'disabled'}
                        onclick="if(typeof rcVoicemailBlock==='function')rcVoicemailBlock('${actionPhone}')"><i class="fa fa-ban"></i></button>
                </div>
            </div>
            <div class="rc-detail-info-card">
                <div class="rc-detail-field-label">To</div>
                <div class="rc-detail-field-value">${rcVmEscapeHtml(connectedMasked)} (me)</div>
                <hr class="rc-detail-divider">
                <div class="rc-detail-time-row">
                    <span class="rc-detail-time-label">${rcVmEscapeHtml(displayTime)}</span>
                    <div class="rc-detail-audio-controls">
                        <button class="rc-detail-speed-btn" id="rcVmSideSpeedBtn" onclick="rcVmSideToggleSpeed()">1x</button>
                        ${audioUrl ? `<button class="rc-detail-download-btn" title="Download" onclick="rcVmSideDownload('${rcVmEscapeHtml(audioUrl)}')"><i class="fa fa-download"></i></button>` : ''}
                    </div>
                </div>
                <audio id="rcVoicemailSideAudio" preload="metadata" style="display:none;" ${audioUrl ? '' : 'disabled'}>
                    ${audioUrl ? `<source src="${rcVmEscapeHtml(audioUrl)}" type="audio/mpeg">` : ''}
                </audio>
                <div class="rc-detail-audio-player">
                    <button class="rc-detail-play-btn" id="rcVmSidePlayBtn" onclick="rcVmSideTogglePlay()" ${audioUrl ? '' : 'disabled'}>
                        <i class="fa fa-play" id="rcVmSidePlayIcon"></i>
                    </button>
                    <div class="rc-detail-progress-wrap">
                        <input type="range" class="rc-detail-progress" id="rcVmSideProgress" min="0" max="${totalSecs || 100}" value="0"
                            oninput="rcVmSideSeek(this.value)">
                    </div>
                    <span class="rc-detail-duration" id="rcVmSideDuration">00:00 / ${rcVmEscapeHtml(duration)}</span>
                </div>
            </div>
        </div>`;

    const rendered = window.rcShowCallsDetailPanel(html);
    if (rendered) {
        if (canActionPhone && window.rcBlockedNumbersApi && typeof window.rcBlockedNumbersApi.decorateBlockActionElement === 'function') {
            setTimeout(function () {
                const btn = Array.from(document.querySelectorAll('.rc-voicemail-detail-block')).find(function (node) {
                    return String(node.getAttribute('data-phone') || '') === String(actionPhone || '');
                });
                if (btn) window.rcBlockedNumbersApi.decorateBlockActionElement(btn, actionPhone);
            }, 0);
        }
        const audio = document.getElementById('rcVoicemailSideAudio');
        if (audio) {
            audio.addEventListener('timeupdate', function () {
                const prog = document.getElementById('rcVmSideProgress');
                const dur = document.getElementById('rcVmSideDuration');
                if (prog) prog.value = Math.floor(audio.currentTime);
                if (dur) dur.textContent = rcVmSideFmtTime(audio.currentTime) + ' / ' + rcVmSideFmtTime(audio.duration || totalSecs);
            });
            audio.addEventListener('ended', function () {
                const icon = document.getElementById('rcVmSidePlayIcon');
                if (icon) { icon.classList.remove('fa-pause'); icon.classList.add('fa-play'); }
            });
        }
    }
    return rendered;
}

function rcVmSideFmtTime(secs) {
    const s = Math.floor(isNaN(secs) ? 0 : secs);
    return String(Math.floor(s / 60)).padStart(2, '0') + ':' + String(s % 60).padStart(2, '0');
}

function rcVmSideTogglePlay() {
    const audio = document.getElementById('rcVoicemailSideAudio');
    const icon = document.getElementById('rcVmSidePlayIcon');
    if (!audio) return;
    if (audio.paused) {
        audio.play().catch(function () {});
        if (icon) { icon.classList.remove('fa-play'); icon.classList.add('fa-pause'); }
    } else {
        audio.pause();
        if (icon) { icon.classList.remove('fa-pause'); icon.classList.add('fa-play'); }
    }
}

function rcVmSideSeek(val) {
    const audio = document.getElementById('rcVoicemailSideAudio');
    if (audio) audio.currentTime = parseFloat(val) || 0;
}

function rcVmSideToggleSpeed() {
    const audio = document.getElementById('rcVoicemailSideAudio');
    const btn = document.getElementById('rcVmSideSpeedBtn');
    if (!audio || !btn) return;
    const rates = [1, 1.5, 2];
    const cur = rates.indexOf(audio.playbackRate);
    const next = rates[(cur + 1) % rates.length];
    audio.playbackRate = next;
    btn.textContent = next + 'x';
}

function rcVmSideDownload(url) {
    if (!url) return;
    const a = document.createElement('a');
    a.href = url; a.download = 'voicemail.mp3'; a.target = '_blank';
    document.body.appendChild(a); a.click(); document.body.removeChild(a);
}

function applyVoicemailFiltersAndRender() {
    const filtered = filterVoicemailItems(rcVoicemailItems);
    renderVoicemailList(filtered);
    renderVoicemailStats(filtered.length, rcVoicemailItems.length);
}

function rcVmMergeItems(existingItems, incomingItems) {
    const existing = Array.isArray(existingItems) ? existingItems : [];
    const incoming = Array.isArray(incomingItems) ? incomingItems : [];
    const byId = new Map();

    existing.forEach(item => {
        byId.set(String(item.id), item);
    });
    incoming.forEach(item => {
        byId.set(String(item.id), item);
    });

    return Array.from(byId.values()).sort((a, b) => new Date(b.createdAt || 0) - new Date(a.createdAt || 0));
}

function findVoicemailById(id) {
    const target = String(id || '').trim();
    if (!target) return null;
    return rcVoicemailItems.find(item => String(item.id) === target) || null;
}

async function fetchSingleVoicemailDetails(itemId) {
    if (!itemId) return null;

    try {
        const url = rcRoute('ringcentral.api.voicemail', { id: itemId });
        const response = await fetch(url, {
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        const payload = await rcVmParseJsonOrRedirect(response);
        if (!payload || !payload.success || !payload.data) return null;

        const details = payload.data;
        const item = findVoicemailById(itemId);
        if (item) {
            item.recordingUrl = details.audio_url || details.media_url || item.recordingUrl;
            item.hasRecording = !!item.recordingUrl;
            item.transcription = details.transcription || item.transcription;
            item.status = rcVmNormalizeStatus(details.message_status || 'Read');
            item.statusClass = rcVmStatusClass(item.status);
            item.searchText = [item.searchText, details.transcription || '', item.status].join(' ').toLowerCase();
            rcVoicemailUnreadTotal = rcVoicemailItems.filter(vm => String(vm.status || '').toLowerCase() === 'unread').length;
            updateVoicemailUnreadBadge(rcVoicemailUnreadTotal);
        }

        return details;
    } catch (error) {
        console.warn('Failed to load voicemail details', error);
        return null;
    }
}

function openVoicemailModal() {
    if (window.jQuery && jQuery('#voicemailModal').length && typeof jQuery.fn.modal === 'function') {
        jQuery('#voicemailModal').modal('show');
        return;
    }

    const modal = document.getElementById('voicemailModal');
    if (modal) {
        modal.classList.add('show');
        modal.style.display = 'block';
        modal.removeAttribute('aria-hidden');
    }
}

function rcVmTryAutoPlay(player) {
    if (!player || typeof player.play !== 'function') return;
    try {
        const maybePromise = player.play();
        if (maybePromise && typeof maybePromise.catch === 'function') {
            maybePromise.catch(function () { });
        }
    } catch (_) { }
}

async function showVoicemailModalById(itemId, autoPlay = false) {
    const item = findVoicemailById(itemId);
    if (!item) return;

    const vmFrom = document.getElementById('vmFrom');
    const vmDuration = document.getElementById('vmDuration');
    const vmReceived = document.getElementById('vmReceived');
    const vmTranscription = document.getElementById('vmTranscription');
    const voicemailAudio = document.getElementById('voicemailAudio');

    if (vmFrom) vmFrom.textContent = item.label || 'Unknown';
    if (vmDuration) vmDuration.textContent = rcVmFormatDuration(item.durationSeconds);
    if (vmReceived) vmReceived.textContent = rcVmFormatTime(item.createdAt);
    if (vmTranscription) vmTranscription.textContent = item.transcription || 'No transcription available';

    let audioUrl = item.recordingUrl;
    if (!audioUrl) {
        const details = await fetchSingleVoicemailDetails(item.id);
        audioUrl = (details && (details.audio_url || details.media_url)) || item.recordingUrl;

        if (vmTranscription && details && details.transcription) {
            vmTranscription.textContent = details.transcription;
        }
    }

    if (voicemailAudio) {
        pauseVoicemailAudio();
        voicemailAudio.src = audioUrl || '';
        voicemailAudio.load();
    }

    const renderedInSidePanel = rcRenderVoicemailDetailInSidePanel(item, audioUrl);
    if (!renderedInSidePanel) {
        openVoicemailModal();
    }

    if (autoPlay && audioUrl) {
        const activePlayer = renderedInSidePanel
            ? document.getElementById('rcVoicemailSideAudio')
            : document.getElementById('voicemailAudio');
        rcVmTryAutoPlay(activePlayer);
    }
    applyVoicemailFiltersAndRender();
}

window.showVoicemailModal = function legacyShowVoicemailModal(id, from, duration, received, transcription, mediaUrl) {
    const vmFrom = document.getElementById('vmFrom');
    const vmDuration = document.getElementById('vmDuration');
    const vmReceived = document.getElementById('vmReceived');
    const vmTranscription = document.getElementById('vmTranscription');
    const voicemailAudio = document.getElementById('voicemailAudio');

    if (vmFrom) vmFrom.textContent = (typeof maskPhoneNumber === 'function') ? maskPhoneNumber(from) : (from || 'Unknown');
    if (vmDuration) vmDuration.textContent = duration || 'Unknown';
    if (vmReceived) vmReceived.textContent = received || '';
    if (vmTranscription) vmTranscription.textContent = transcription || 'No transcription available';
    if (voicemailAudio) {
        pauseVoicemailAudio();
        voicemailAudio.src = mediaUrl || '';
        voicemailAudio.load();
    }

    openVoicemailModal();
};

function rcVoicemailCall(phone) {
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
        console.warn('Failed to start call from voicemail', error);
    }
}
window.rcVoicemailCall = rcVoicemailCall;

function rcVoicemailMessage(phone) {
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
        console.warn('Failed to start message from voicemail', error);
    }
}
window.rcVoicemailMessage = rcVoicemailMessage;

async function rcVoicemailBlock(phone) {
    if (!phone) return;
    if (!window.rcBlockedNumbersApi || typeof window.rcBlockedNumbersApi.toggleNumberFromContext !== 'function') return;
    await window.rcBlockedNumbersApi.toggleNumberFromContext(phone, 'Blocked from Voicemail tab', 'Voicemail');
}
window.rcVoicemailBlock = rcVoicemailBlock;

async function setVoicemailStatus(itemId, targetStatus) {
    const id = String(itemId || '').trim();
    const requestedStatus = String(targetStatus || '').toLowerCase() === 'unread' ? 'unread' : 'read';
    if (!id) return false;

    try {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
        const response = await fetch(rcRoute('ringcentral.api.voicemails.mark-status'), {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrf
            },
            body: JSON.stringify({
                voicemail_ids: [id],
                status: requestedStatus
            })
        });

        const payload = await rcVmParseJsonOrRedirect(response);
        if (!response.ok || !payload || !payload.success) {
            throw new Error((payload && payload.message) || 'Failed to update voicemail status');
        }

        const normalizedStatus = requestedStatus === 'unread' ? 'Unread' : 'Read';
        const item = findVoicemailById(id);
        if (item) {
            item.status = normalizedStatus;
            item.statusClass = rcVmStatusClass(normalizedStatus);
            item.searchText = [item.label || '', item.phone || '', normalizedStatus, item.createdAt || '', item.transcription || '']
                .join(' ')
                .trim()
                .toLowerCase();
        }

        applyVoicemailSummary(payload.summary || null);
        if (!payload.summary) {
            rcVoicemailUnreadTotal = rcVoicemailItems.filter(vm => String(vm.status || '').toLowerCase() === 'unread').length;
            updateVoicemailUnreadBadge(rcVoicemailUnreadTotal);
        }

        applyVoicemailFiltersAndRender();
        return true;
    } catch (error) {
        console.warn('Failed to update voicemail status', error);
        return false;
    }
}

async function deleteVoicemailItem(itemId) {
    const id = String(itemId || '').trim();
    if (!id) return false;

    const confirmed = window.confirm('Delete this voicemail?');
    if (!confirmed) return false;

    try {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
        const response = await fetch(rcRoute('ringcentral.api.voicemail.delete', { id: id }), {
            method: 'DELETE',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrf
            }
        });

        const payload = await rcVmParseJsonOrRedirect(response);
        if (!response.ok || !payload || !payload.success) {
            throw new Error((payload && payload.message) || 'Failed to delete voicemail');
        }

        rcVoicemailItems = rcVoicemailItems.filter(item => String(item.id) !== id);
        applyVoicemailSummary(payload.summary || null);
        if (!payload.summary) {
            rcVoicemailTotalAvailable = Math.max(0, parseInt(rcVoicemailTotalAvailable || rcVoicemailItems.length, 10) - 1);
            rcVoicemailUnreadTotal = rcVoicemailItems.filter(vm => String(vm.status || '').toLowerCase() === 'unread').length;
            updateVoicemailUnreadBadge(rcVoicemailUnreadTotal);
        }

        pauseVoicemailAudio();
        applyVoicemailFiltersAndRender();
        return true;
    } catch (error) {
        console.warn('Failed to delete voicemail', error);
        return false;
    }
}

function bindVoicemailListActions() {
    const listEl = document.getElementById('tabVoicemailsList');
    if (!listEl || listEl.dataset.rcBound === '1') return;
    listEl.dataset.rcBound = '1';

    listEl.addEventListener('click', function (event) {
        const moreBtn = event.target.closest('.rc-voicemail-action-more');
        if (moreBtn) {
            event.preventDefault();
            event.stopPropagation();
            const wrap = moreBtn.closest('.rc-voicemail-more-wrap');
            if (!wrap) return;

            const isOpen = wrap.classList.contains('is-open');
            closeVoicemailMoreMenus(listEl);
            if (!isOpen) {
                wrap.classList.add('is-open');
                const menu = wrap.querySelector('.rc-voicemail-more-menu');
                if (menu) menu.hidden = false;
                moreBtn.setAttribute('aria-expanded', 'true');
                const blockAction = wrap.querySelector('.rc-voicemail-action-block');
                if (blockAction && window.rcBlockedNumbersApi && typeof window.rcBlockedNumbersApi.decorateBlockActionElement === 'function') {
                    window.rcBlockedNumbersApi.decorateBlockActionElement(blockAction, blockAction.getAttribute('data-phone'));
                }
            }
            return;
        }

        const playBtn = event.target.closest('.rc-voicemail-action-play');
        if (playBtn) {
            event.preventDefault();
            event.stopPropagation();
            showVoicemailModalById(playBtn.getAttribute('data-id'), true);
            return;
        }

        const callBtn = event.target.closest('.rc-voicemail-action-call');
        if (callBtn) {
            event.preventDefault();
            event.stopPropagation();
            rcVoicemailCall(callBtn.getAttribute('data-phone'));
            return;
        }

        const msgBtn = event.target.closest('.rc-voicemail-action-message');
        if (msgBtn) {
            event.preventDefault();
            event.stopPropagation();
            rcVoicemailMessage(msgBtn.getAttribute('data-phone'));
            return;
        }

        const blockBtn = event.target.closest('.rc-voicemail-action-block');
        if (blockBtn) {
            event.preventDefault();
            event.stopPropagation();
            closeVoicemailMoreMenus(listEl);
            rcVoicemailBlock(blockBtn.getAttribute('data-phone'));
            return;
        }

        const toggleReadBtn = event.target.closest('.rc-voicemail-action-toggle-read');
        if (toggleReadBtn) {
            event.preventDefault();
            event.stopPropagation();
            closeVoicemailMoreMenus(listEl);
            const targetStatus = toggleReadBtn.getAttribute('data-status') || 'read';
            setVoicemailStatus(toggleReadBtn.getAttribute('data-id'), targetStatus);
            return;
        }

        const deleteBtn = event.target.closest('.rc-voicemail-action-delete');
        if (deleteBtn) {
            event.preventDefault();
            event.stopPropagation();
            closeVoicemailMoreMenus(listEl);
            deleteVoicemailItem(deleteBtn.getAttribute('data-id'));
            return;
        }

        const row = event.target.closest('[data-id]');
        if (row) {
            event.preventDefault();
            closeVoicemailMoreMenus(listEl);
            showVoicemailModalById(row.getAttribute('data-id'));
        }
    });

    if (!window._rcVoicemailOutsideMenuBound) {
        window._rcVoicemailOutsideMenuBound = true;
        document.addEventListener('click', function (event) {
            if (event.target.closest('#tabVoicemailsList .rc-voicemail-more-wrap')) return;
            closeVoicemailMoreMenus(document.getElementById('tabVoicemailsList'));
        });
    }

    if (!window._rcVoicemailEscMenuBound) {
        window._rcVoicemailEscMenuBound = true;
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeVoicemailMoreMenus(document.getElementById('tabVoicemailsList'));
            }
        });
    }
}

function bindVoicemailControls() {
    const searchEl = document.getElementById('tabVoicemailsSearch');
    const filterEl = document.getElementById('tabVoicemailsTypeFilter');
    const allBtn = document.getElementById('tabVoicemailsAllBtn');
    const unreadBtn = document.getElementById('tabVoicemailsUnreadBtn');

    if (searchEl && searchEl.dataset.rcBound !== '1') {
        searchEl.dataset.rcBound = '1';
        let searchDebounce = null;
        searchEl.addEventListener('input', function () {
            clearTimeout(searchDebounce);
            searchDebounce = setTimeout(applyVoicemailFiltersAndRender, 140);
        });
    }

    if (filterEl && filterEl.dataset.rcBound !== '1') {
        filterEl.dataset.rcBound = '1';
        filterEl.addEventListener('change', function () {
            const type = getVoicemailTypeFilterValue();
            syncVoicemailFilterUi(type);
            applyVoicemailFiltersAndRender();
        });
    }

    if (allBtn && allBtn.dataset.rcBound !== '1') {
        allBtn.dataset.rcBound = '1';
        allBtn.addEventListener('click', function () {
            if (filterEl) filterEl.value = '';
            syncVoicemailFilterUi('all');
            applyVoicemailFiltersAndRender();
        });
    }

    if (unreadBtn && unreadBtn.dataset.rcBound !== '1') {
        unreadBtn.dataset.rcBound = '1';
        unreadBtn.addEventListener('click', function () {
            if (filterEl) filterEl.value = 'unread';
            syncVoicemailFilterUi('unread');
            applyVoicemailFiltersAndRender();
        });
    }

    syncVoicemailFilterUi(getVoicemailTypeFilterValue());
}

function bindVoicemailModalEvents() {
    const modalElement = document.getElementById('voicemailModal');
    if (!modalElement || modalElement.dataset.rcBound === '1') return;
    modalElement.dataset.rcBound = '1';

    modalElement.addEventListener('click', function (event) {
        if (event.target === modalElement) {
            pauseVoicemailAudio();
        }
    });

    const closeBtn = modalElement.querySelector('.close');
    if (closeBtn) {
        closeBtn.addEventListener('click', pauseVoicemailAudio);
    }

    if (window.jQuery && jQuery('#voicemailModal').length) {
        jQuery('#voicemailModal').on('hidden.bs.modal', function () {
            pauseVoicemailAudio();
        });
    }
}

function bindVoicemailTabLoad() {
    const selector = 'a[href="#callVoicemail"], a[href="#tabVoicemails"]';

    if (window.jQuery) {
        const tabs = jQuery(selector);
        if (tabs.length) {
            tabs.off('shown.bs.tab.rcVoicemail').on('shown.bs.tab.rcVoicemail', function () {
                loadVoicemails().then(function () {
                    rcVmMaybeBackgroundRefresh();
                });
            });
        }
        return;
    }

    document.querySelectorAll(selector).forEach(tab => {
        if (tab.dataset.rcVoicemailBound === '1') return;
        tab.dataset.rcVoicemailBound = '1';
        tab.addEventListener('click', function () {
            setTimeout(function () {
                loadVoicemails().then(function () {
                    rcVmMaybeBackgroundRefresh();
                });
            }, 100);
        });
    });
}

function isVoicemailTabActive() {
    try {
        const outer = document.getElementById('tabCalls');
        const inner = document.getElementById('callVoicemail');
        return !!(
            outer && inner
            && outer.classList.contains('active')
            && inner.classList.contains('active')
        );
    } catch (_) {
        return false;
    }
}

function rcVmLoadingMarkup(label) {
    return `
        <div class="py-3 text-center">
            <div class="d-inline-flex align-items-center px-3 py-2 rounded-pill rc-loading-pill">
                <span class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></span>
                <span class="ms-2 text-muted">${rcVmEscapeHtml(label || 'Loading voicemails...')}</span>
            </div>
        </div>`;
}

function setVoicemailLoadMoreLoading(isLoading) {
    const listEl = document.getElementById('tabVoicemailsList');
    if (!listEl) return;

    let loader = document.getElementById('tabVoicemailsScrollLoader');
    if (isLoading) {
        if (!loader) {
            loader = document.createElement('div');
            loader.id = 'tabVoicemailsScrollLoader';
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

function refreshVoicemailTab() {
    return loadVoicemails(true, false, null, true, 'manual');
}

window.refreshVoicemailTab = refreshVoicemailTab;

function rcVmMaybeBackgroundRefresh() {
    if (rcVoicemailLoading) {
        setTimeout(rcVmMaybeBackgroundRefresh, 400);
        return;
    }

    const now = Date.now();
    const throttleMs = 45000;
    if ((now - rcVoicemailLastAutoRefreshAt) < throttleMs) return;
    rcVoicemailLastAutoRefreshAt = now;
    loadVoicemails(true, false, null, true, 'auto').catch(function () { });
}

function rcVmScheduleRefreshRetry(delayMs = 15000) {
    if (rcVoicemailRefreshRetryTimer) return;
    rcVoicemailRefreshRetryTimer = setTimeout(function () {
        rcVoicemailRefreshRetryTimer = null;
        loadVoicemails(true, false, null, true, 'retry').catch(function () { });
    }, delayMs);
}

function rcVmScheduleBackgroundPoll(delayMs = 4000) {
    if (rcVoicemailBackgroundPollTimer) return;
    rcVoicemailBackgroundPollTimer = setTimeout(function () {
        rcVoicemailBackgroundPollTimer = null;
        loadVoicemails(false, false, null, true, 'background_poll').catch(function () { });
    }, delayMs);
}

function renderVoicemailLoadMore(hasMore) {
    try {
        window._rc_voicemailsHasMore = !!hasMore;
        const listEl = document.getElementById('tabVoicemailsList');
        if (!listEl) return;

        if (!listEl._rc_voicemailsScrollLoadBound) {
            listEl._rc_voicemailsScrollLoadBound = true;
            let loading = false;
            listEl.addEventListener('scroll', function () {
                try {
                    if (loading || rcVoicemailLoading) return;
                    if (!window._rc_voicemailsHasMore || !rcVoicemailNextCursor) return;

                    const distanceFromBottom = listEl.scrollHeight - listEl.scrollTop - listEl.clientHeight;
                    if (distanceFromBottom > 120) return;

                    loading = true;
                    loadVoicemails(false, true, rcVoicemailNextCursor, false).finally(function () {
                        loading = false;
                    });
                } catch (_) { }
            });
        }

        let footer = document.getElementById('tabVoicemailsLoadMore');
        if (!footer && listEl && listEl.parentNode) {
            footer = document.createElement('div');
            footer.id = 'tabVoicemailsLoadMore';
            listEl.parentNode.insertBefore(footer, listEl);
        }
        if (!footer) return;

        let btn = footer.querySelector('button');
        if (!btn) {
            footer.innerHTML = '<button type="button" class="ui-btn" title="Refresh voicemails"><i class="fa fa-refresh"></i></button>';
            btn = footer.querySelector('button');
            if (btn) {
                btn.addEventListener('click', function () {
                    if (rcVoicemailLoading) return;
                    setVoicemailLoadMoreRefreshing(true);
                    loadVoicemails(true, false, null, true, 'manual_button')
                        .then(function () {
                            const nextCursor = rcVoicemailNextCursor || null;
                            if (!nextCursor) return Promise.resolve();
                            return loadVoicemails(false, true, nextCursor, false, 'manual_button_cursor');
                        })
                        .catch(function () { /* no-op */ })
                        .finally(function () {
                            setVoicemailLoadMoreRefreshing(false);
                        });
                });
            }
        }

        footer.style.display = 'block';
    } catch (_) { }
}

function setVoicemailLoadMoreRefreshing(isLoading) {
    try {
        const footer = document.getElementById('tabVoicemailsLoadMore');
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

function loadVoicemails(forceRefresh = false, append = false, beforeCursor = null, mergeWithExisting = false, debugTrigger = 'default') {
    if (rcVoicemailLoading) {
        return Promise.resolve();
    }

    rcVoicemailLoading = true;
    const shouldScheduleAutoRefresh = !forceRefresh && !append && !beforeCursor && debugTrigger !== 'webhook_event';
    if (append) {
        setVoicemailLoadMoreLoading(true);
    }
    const listEl = document.getElementById('tabVoicemailsList');
    if (listEl && !append && (!mergeWithExisting || !rcVoicemailItems.length)) {
        const loadingLabel = forceRefresh
            ? 'Refreshing voicemails...'
            : ((getVoicemailSearchValue() || '').trim() ? 'Searching voicemails...' : 'Loading voicemails...');
        listEl.innerHTML = rcVmLoadingMarkup(loadingLabel);
    }

    const defaultPageSize = Math.max(1, parseInt(window.RC_INITIAL_PAGE_SIZE, 10) || 50);
    const defaultRefreshCount = Math.max(1, parseInt(window.RC_REFRESH_SYNC_COUNT, 10) || 50);
    const params = { per_page: defaultPageSize };
    const q = getVoicemailSearchValue();
    const shouldConsiderWebhookToast = !append && !q;
    const canNotifyNewVoicemails = shouldConsiderWebhookToast && window._rc_voicemailNotifyReady === true;
    const previousVoicemailIds = new Set((rcVoicemailItems || []).map(item => String(item && item.id ? item.id : '')));
    if (q) params.q = q;
    if (beforeCursor) params.before = beforeCursor;
    const flow = forceRefresh
        ? `refresh_${String(debugTrigger || 'default')}`
        : (append ? 'load_more' : 'local');
    rcVoicemailReqSeq += 1;
    params._rc_flow = flow;
    params._rc_seq = String(rcVoicemailReqSeq);
    if (forceRefresh) {
        params.refresh = 1;
        params.count = defaultRefreshCount;
        params._ts = Date.now();
    }

    const url = rcRoute('ringcentral.api.voicemails', {}, params);

    return fetch(url, {
        credentials: 'same-origin',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => rcVmParseJsonOrRedirect(response))
        .then(payload => {
            const listEl = document.getElementById('tabVoicemailsList');
            if (!listEl) return;

            if (!payload || !payload.success) {
                const message = String((payload && payload.message) || '').toLowerCase();
                const isEmptyResponse = message.includes('no voicemail');
                if ((forceRefresh || append || mergeWithExisting) && rcVoicemailItems.length) {
                    rcVoicemailSyncNotice = (payload && payload.message) || "Can't get new from server. Retry in a min.";
                    renderVoicemailStats(rcVoicemailItems.length, rcVoicemailItems.length);
                    rcVmScheduleRefreshRetry();
                    return;
                }

                listEl.innerHTML = isEmptyResponse
                    ? '<div class="text-muted">No voicemails found</div>'
                    : `<p class="text-danger">${rcVmEscapeHtml((payload && payload.message) || 'Failed to load voicemails')}</p>`;
                rcVoicemailItems = [];
                rcVoicemailTotalAvailable = 0;
                rcVoicemailUnreadTotal = 0;
                updateVoicemailUnreadBadge(0);
                renderVoicemailStats(0, 0);
                return;
            }

            if (payload.sync && payload.sync.attempted) {
                if (payload.sync.success === false) {
                    rcVoicemailSyncNotice = payload.sync.message || "Can't get new from server. Retry in a min.";
                    rcVmScheduleRefreshRetry();
                } else if (payload.sync.success === true) {
                    rcVoicemailSyncNotice = '';
                }
                if (payload.sync.background) {
                    rcVmScheduleBackgroundPoll();
                }
            } else if (!forceRefresh) {
                rcVoicemailSyncNotice = '';
            }

            const records = Array.isArray(payload.data) ? payload.data : [];
            const normalized = records
                .map((record, index) => rcVmNormalizeItem(record, index))
                .sort((a, b) => new Date(b.createdAt || 0) - new Date(a.createdAt || 0));
            const newVoicemailIds = new Set();
            let newVoicemailsCount = 0;
            if (canNotifyNewVoicemails) {
                normalized.forEach(item => {
                    const id = String(item && item.id ? item.id : '');
                    if (!id) return;
                    if (!previousVoicemailIds.has(id) && !newVoicemailIds.has(id)) {
                        newVoicemailIds.add(id);
                        newVoicemailsCount++;
                    }
                });
            }

            if (append) {
                rcVoicemailItems = rcVmMergeItems(rcVoicemailItems, normalized);
            } else if (mergeWithExisting) {
                rcVoicemailItems = rcVmMergeItems(rcVoicemailItems, normalized);
            } else {
                rcVoicemailItems = normalized;
            }
            const summary = payload.summary || {};
            applyVoicemailSummary(summary);
            if (summary.total_available === undefined || summary.total_available === null) {
                if (!append && !mergeWithExisting) {
                    rcVoicemailTotalAvailable = rcVoicemailItems.length;
                }
            }
            if (summary.unread_total === undefined || summary.unread_total === null) {
                if (!append && !mergeWithExisting) {
                    rcVoicemailUnreadTotal = rcVoicemailItems.filter(item => String(item.status || '').toLowerCase() === 'unread').length;
                }
                updateVoicemailUnreadBadge(rcVoicemailUnreadTotal || 0);
            }

            applyVoicemailFiltersAndRender();

            if (shouldConsiderWebhookToast) {
                window._rc_voicemailNotifyReady = true;
            }

            const pagination = payload.pagination || {};
            rcVoicemailNextCursor = pagination.next_cursor || null;
            renderVoicemailLoadMore(!!pagination.has_more);
        })
        .catch(error => {
            const listEl = document.getElementById('tabVoicemailsList');
            const hasExisting = rcVoicemailItems.length > 0;
            if (listEl && !(forceRefresh && hasExisting) && !(append && hasExisting)) {
                listEl.innerHTML = '<p class="text-danger">Error loading voicemails</p>';
                updateVoicemailUnreadBadge(0);
            }
            if (forceRefresh || append) {
                rcVoicemailSyncNotice = "Can't get new from server. Retry in a min.";
                renderVoicemailStats(rcVoicemailItems.length || 0, rcVoicemailItems.length || 0);
                rcVmScheduleRefreshRetry();
            }
            console.error('Error loading voicemails:', error);
        })
        .finally(() => {
            if (append) {
                setVoicemailLoadMoreLoading(false);
            }
            rcVoicemailLoading = false;
            if (shouldScheduleAutoRefresh) {
                setTimeout(rcVmMaybeBackgroundRefresh, 0);
            }
        });
}

function loadVoicemailsIncrementalFromWebhook() {
    if (rcVoicemailLoading) return Promise.resolve();
    if (!Array.isArray(rcVoicemailItems) || rcVoicemailItems.length === 0) {
        return loadVoicemails(false, false, null, true, 'webhook_incremental_bootstrap');
    }

    const q = getVoicemailSearchValue();
    if (q) {
        return loadVoicemails(false, false, null, true, 'webhook_incremental_fallback_search');
    }

    const perPage = rcVmGetWebhookSyncCount();
    const params = {
        per_page: perPage,
        refresh: 1,
        count: perPage,
        _rc_flow: 'refresh_webhook_incremental',
        _rc_seq: String(++rcVoicemailReqSeq),
        _ts: Date.now()
    };

    const previousIds = new Set((rcVoicemailItems || []).map(item => String(item && item.id ? item.id : '')));
    const listEl = document.getElementById('tabVoicemailsList');

    rcVoicemailLoading = true;
    return fetch(rcRoute('ringcentral.api.voicemails', {}, params), {
        credentials: 'same-origin',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => rcVmParseJsonOrRedirect(response))
        .then(payload => {
            if (!payload || !payload.success) return;

            const records = Array.isArray(payload.data) ? payload.data : [];
            const normalized = records
                .map((record, index) => rcVmNormalizeItem(record, index))
                .sort((a, b) => new Date(b.createdAt || 0) - new Date(a.createdAt || 0));

            const changedItems = [];
            const changedMap = new Map();
            normalized.forEach(item => {
                const id = String(item.id || '');
                if (!id) return;
                const prev = findVoicemailById(id);
                const isChanged = !prev
                    || String(prev.status || '') !== String(item.status || '')
                    || String(prev.transcription || '') !== String(item.transcription || '')
                    || String(prev.recordingUrl || '') !== String(item.recordingUrl || '');
                if (isChanged && !changedMap.has(id)) {
                    changedMap.set(id, item);
                    changedItems.push(item);
                }
            });

            rcVoicemailItems = rcVmMergeItems(rcVoicemailItems, normalized);
            applyVoicemailSummary(payload.summary || null);
            if (!payload.summary) {
                rcVoicemailUnreadTotal = rcVoicemailItems.filter(vm => String(vm.status || '').toLowerCase() === 'unread').length;
                updateVoicemailUnreadBadge(rcVoicemailUnreadTotal);
            }

            if (listEl && changedItems.length > 0) {
                rcVmUpsertRowsIncremental(changedItems);
            }

            const newCount = normalized.reduce((acc, item) => {
                return acc + (previousIds.has(String(item.id || '')) ? 0 : 1);
            }, 0);
            window._rc_voicemailNotifyReady = true;
        })
        .catch(error => {
            console.warn('Incremental voicemail webhook refresh failed', error);
        })
        .finally(() => {
            rcVoicemailLoading = false;
        });
}

window.loadVoicemails = loadVoicemails;
window.loadVoicemailsIncrementalFromWebhook = loadVoicemailsIncrementalFromWebhook;

document.addEventListener('DOMContentLoaded', function () {
    bindVoicemailControls();
    bindVoicemailListActions();
    bindVoicemailModalEvents();
    bindVoicemailTabLoad();
});
