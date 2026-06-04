/**
 * R-Dialer  Calls Module
 * Handles call history loading and rendering
 */

function formatCallStatus(rawStatus) {
    if (!rawStatus) return 'Unknown';
    const status = String(rawStatus).trim();
    const map = {
        Completed: 'Completed',
        Missed: 'Missed',
        Busy: 'Busy',
        Voicemail: 'Voicemail',
        NoAnswer: 'No Answer',
        CallFailed: 'Call Failed',
        CallerDropped: 'Caller Dropped',
        Rejected: 'Rejected',
        Blocked: 'Blocked',
        Accepted: 'Accepted',
        Unknown: 'Unknown'
    };
    if (map[status]) return map[status];
    return status.replace(/([a-z])([A-Z])/g, '$1 $2');
}

function callStatusClass(label) {
    const key = String(label || '').toLowerCase();
    if (key === 'missed' || key === 'no answer' || key === 'rejected' || key === 'call failed') {
        return 'rc-status-missed';
    }
    if (key === 'busy' || key === 'blocked') {
        return 'rc-status-warning';
    }
    if (key === 'voicemail') {
        return 'rc-status-voicemail';
    }
    if (key === 'completed' || key === 'accepted') {
        return 'rc-status-success';
    }
    return 'rc-status-neutral';
}

function formatDirection(rawDirection) {
    if (!rawDirection) return 'Unknown';
    const dir = String(rawDirection).trim().toLowerCase();
    if (dir === 'inbound') return 'InComing';
    if (dir === 'outbound') return 'OutGoing';
    return dir.charAt(0).toUpperCase() + dir.slice(1);
}

function formatCallDuration(rawSeconds) {
    const total = Math.max(0, parseInt(rawSeconds, 10) || 0);
    const hours = Math.floor(total / 3600);
    const minutes = Math.floor((total % 3600) / 60);
    const seconds = total % 60;

    if (hours > 0) {
        return `${hours} hr ${minutes} min ${seconds} sec`;
    }
    if (minutes > 0) {
        return `${minutes} min ${seconds} sec`;
    }
    return `${seconds} sec`;
}

function rcCallsLoadingMarkup(label) {
    return `
        <div class="py-3 text-center">
            <div class="d-inline-flex align-items-center px-3 py-2 rounded-pill rc-loading-pill">
                <span class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></span>
                <span class="ms-2 text-muted">${escapeHtml(label || 'Loading calls...')}</span>
            </div>
        </div>`;
}

function ensureCallsStatsElement() {
    const listEl = document.getElementById('tabCallsList');
    if (!listEl || !listEl.parentNode) return null;

    let statsEl = document.getElementById('tabCallsStats');
    if (!statsEl) {
        statsEl = document.createElement('div');
        statsEl.id = 'tabCallsStats';
        statsEl.className = 'px-1 mb-2 text-muted small';
        listEl.parentNode.insertBefore(statsEl, listEl);
    }
    return statsEl;
}

function renderCallsTestingStats(shownCount, totalCount, unreadCount) {
    const statsEl = ensureCallsStatsElement();
    if (!statsEl) return;
    const shown = Math.max(0, parseInt(shownCount, 10) || 0);
    const total = Math.max(0, parseInt(
        window._rc_callsTotalAvailable !== null && window._rc_callsTotalAvailable !== undefined
            ? window._rc_callsTotalAvailable
            : totalCount
    , 10) || 0);
    const unread = Math.max(0, parseInt(
        window._rc_callsUnreadTotal !== null && window._rc_callsUnreadTotal !== undefined
            ? window._rc_callsUnreadTotal
            : unreadCount
    , 10) || 0);
    statsEl.textContent = `${shown} shown / ${total} total` + (unread ? ` | ${unread} unread` : '');
}

function setCallsLoadMoreLoading(isLoading) {
    const listEl = document.getElementById('tabCallsList');
    if (!listEl) return;

    let loader = document.getElementById('tabCallsScrollLoader');
    if (isLoading) {
        if (!loader) {
            loader = document.createElement('div');
            loader.id = 'tabCallsScrollLoader';
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

function normalizeDirectionKey(rawDirection) {
    const dir = String(rawDirection || '').trim().toLowerCase();
    if (dir === 'inbound') return 'incoming';
    if (dir === 'outbound') return 'outgoing';
    return dir;
}

function isMissedStatus(rawStatus) {
    const key = String(rawStatus || '').trim().toLowerCase();
    return key === 'missed' || key === 'noanswer' || key === 'no answer' || key === 'rejected' || key === 'callfailed' || key === 'call failed';
}

function rcAvatarColor(text) {
    const colors = ['#6366f1','#0ea5e9','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6'];
    const digits = String(text || '').replace(/\D/g, '');
    const seed = digits ? parseInt(digits.slice(-3), 10) : 0;
    return colors[seed % colors.length];
}

function rcGetConnectedPhoneMasked() {
    const el = document.getElementById('connectedPhoneNumber');
    const full = el ? (el.getAttribute('data-full-number') || el.textContent || '') : '';
    return (typeof maskPhoneNumber === 'function') ? maskPhoneNumber(full) : full;
}

function rcRenderCallDetailInSidePanel(call) {
    if (!call || typeof window.rcShowCallsDetailPanel !== 'function') return false;

    const fromPhone = call.from || '';
    const toPhone = call.to || '';
    const normalizedDir = normalizeDirectionKey(call.direction);
    const isOutgoing = normalizedDir === 'outgoing';
    const primaryPhone = isOutgoing ? (toPhone || fromPhone) : (fromPhone || toPhone);
    const secondaryPhone = isOutgoing ? fromPhone : toPhone;

    const mask = p => p ? ((typeof maskPhoneNumber === 'function') ? maskPhoneNumber(p) : p) : '';
    const primaryMasked = mask(primaryPhone) || 'Unknown';
    const secondaryMasked = mask(secondaryPhone);
    const actionPhone = (typeof normalizeActionPhone === 'function') ? normalizeActionPhone(primaryPhone || secondaryPhone) : (primaryPhone || secondaryPhone);

    const initials = (primaryPhone.replace(/\D/g, '').slice(-2) || 'RC').toUpperCase();
    const avatarColor = rcAvatarColor(primaryPhone);
    const displayTime = (typeof formatCallDisplayTime === 'function') ? formatCallDisplayTime(call.start_time) : (call.start_time || '');
    const duration = (typeof formatCallDuration === 'function') ? formatCallDuration(call.duration_seconds) : '';
    const statusLabel = (typeof formatCallStatus === 'function') ? formatCallStatus(call.status) : (call.status || '');
    const fieldLabel = isOutgoing ? 'To' : 'From';
    const fieldValue = isOutgoing ? (secondaryMasked || rcGetConnectedPhoneMasked() + ' (me)') : (secondaryMasked || rcGetConnectedPhoneMasked() + ' (me)');
    const mePhone = rcGetConnectedPhoneMasked();

    let statusClass = 'rc-detail-status-neutral';
    let statusText = statusLabel;
    if (isMissedStatus(call.status)) {
        statusClass = 'rc-detail-status-missed';
        statusText = 'Missed call';
    } else if (normalizedDir === 'incoming') {
        statusClass = 'rc-detail-status-incoming';
        statusText = 'Incoming call';
    } else if (normalizedDir === 'outgoing') {
        statusClass = 'rc-detail-status-outgoing';
        statusText = 'Outgoing call';
    }

    const canCall = !!actionPhone;
    const html = `
        <div class="rc-detail-panel py-4">
            <div class="text-center px-3">
                <div class="rc-detail-avatar" style="background:${avatarColor};">${escapeHtml(initials)}</div>
                <div class="rc-detail-contact-name">${escapeHtml(primaryMasked)}</div>
                <div class="rc-detail-contact-phone" title="Copy number" onclick="if(navigator.clipboard)navigator.clipboard.writeText('${escapeHtml(primaryPhone)}')">
                    ${escapeHtml(secondaryMasked || primaryMasked)} <i class="fa fa-clone"></i>
                </div>
                <div class="rc-detail-actions">
                    <button class="rc-detail-action-btn" title="Call" ${canCall ? '' : 'disabled'}
                        onclick="if(typeof rcCallFromList==='function')rcCallFromList('${escapeHtml(actionPhone)}')">
                        <i class="fa fa-phone"></i>
                    </button>
                    <button class="rc-detail-action-btn" title="Message" ${canCall ? '' : 'disabled'}
                        onclick="if(typeof rcMessageFromList==='function')rcMessageFromList('${escapeHtml(actionPhone)}')">
                        <i class="fa fa-comment"></i>
                    </button>
                    <button class="rc-detail-action-btn" title="Add contact" disabled>
                        <i class="fa fa-user-plus"></i>
                    </button>
                    <button class="rc-detail-action-btn" title="Note" disabled>
                        <i class="fa fa-sticky-note-o"></i>
                    </button>
                    <button class="rc-detail-action-btn rc-call-detail-block" data-phone="${escapeHtml(actionPhone)}" title="Block" ${canCall ? '' : 'disabled'}
                        onclick="if(typeof rcBlockFromCall==='function')rcBlockFromCall('${escapeHtml(actionPhone)}')">
                        <i class="fa fa-ban"></i>
                    </button>
                </div>
            </div>
            <div class="rc-detail-info-card">
                <div class="rc-detail-field-label">${escapeHtml(fieldLabel)}</div>
                <div class="rc-detail-field-value">${escapeHtml(mePhone)} (me)</div>
                <hr class="rc-detail-divider">
                <div class="rc-detail-time-row">
                    <span class="rc-detail-time-label">${escapeHtml(displayTime)}</span>
                </div>
                <div class="rc-detail-status ${statusClass}">${escapeHtml(statusText)}</div>
                ${duration ? `<div class="text-muted small mt-1">${escapeHtml(duration)}</div>` : ''}
            </div>
        </div>`;

    const shown = window.rcShowCallsDetailPanel(html);
    if (shown && canCall && window.rcBlockedNumbersApi && typeof window.rcBlockedNumbersApi.decorateBlockActionElement === 'function') {
        setTimeout(function () {
            const btn = Array.from(document.querySelectorAll('.rc-call-detail-block')).find(function (node) {
                return String(node.getAttribute('data-phone') || '') === String(actionPhone || '');
            });
            if (btn) window.rcBlockedNumbersApi.decorateBlockActionElement(btn, actionPhone);
        }, 0);
    }
    return shown;
}

function getCallsTypeFilterValue() {
    const buttonState = String(window._rc_callsTypeFilter || '').trim().toLowerCase();
    const el = document.getElementById('tabCallsTypeFilter');
    const value = (el && el.value) ? String(el.value).trim().toLowerCase() : (buttonState || 'all');
    if (value === 'missed' || value === 'incoming' || value === 'outgoing') return value;
    return 'all';
}

function syncCallsFilterUi(type) {
    const allBtn = document.getElementById('tabCallsAllBtn');
    const filterEl = document.getElementById('tabCallsTypeFilter');
    const filterBtn = document.getElementById('tabCallsFilterBtn');
    const filterBtnText = document.getElementById('tabCallsFilterBtnText');
    const filterMenu = document.getElementById('tabCallsFilterMenu');
    const activeType = (type || 'all').toLowerCase();
    const isAll = activeType === 'all';
    const filterLabels = {
        missed: 'Missed',
        outgoing: 'Outgoing',
        incoming: 'Incoming'
    };

    if (allBtn) {
        allBtn.classList.add('rc-filter-btn');
        allBtn.classList.toggle('is-active', isAll);
    }

    if (filterEl) {
        filterEl.classList.add('rc-filter-select');
        if (isAll && filterEl.value) {
            filterEl.value = '';
        } else if (!isAll && filterEl.value !== activeType) {
            filterEl.value = activeType;
        }
        const hasSpecificFilter = !isAll && !!String(filterEl.value || '').trim();
        filterEl.classList.toggle('is-active', hasSpecificFilter);
    }

    if (filterBtn) {
        filterBtn.classList.add('rc-filter-btn');
        filterBtn.classList.toggle('is-active', !isAll);
    }
    if (filterBtnText) {
        filterBtnText.textContent = isAll ? 'Filter' : (filterLabels[activeType] || 'Filter');
    }
    if (filterMenu) {
        const items = filterMenu.querySelectorAll('[data-filter]');
        items.forEach(item => {
            const value = (item.getAttribute('data-filter') || '').toLowerCase();
            item.classList.toggle('is-selected', value === activeType);
        });
    }
    window._rc_callsTypeFilter = activeType;
}

function filterCallsByType(items, type) {
    const calls = Array.isArray(items) ? items : [];
    if (!calls.length || !type || type === 'all') return calls;
    return calls.filter(call => {
        if (type === 'missed') return isMissedStatus(call.status);
        const direction = normalizeDirectionKey(call.direction);
        if (type === 'incoming') return direction === 'incoming';
        if (type === 'outgoing') return direction === 'outgoing';
        return true;
    });
}

function bindCallsTypeFilterControl() {
    const filterEl = document.getElementById('tabCallsTypeFilter');
    const allBtn = document.getElementById('tabCallsAllBtn');
    const filterBtn = document.getElementById('tabCallsFilterBtn');
    const filterMenu = document.getElementById('tabCallsFilterMenu');
    const filterDropdown = document.getElementById('tabCallsFilterDropdown');

    function closeCallsFilterMenu() {
        if (!filterMenu) return;
        filterMenu.classList.add('d-none');
        if (filterBtn) {
            filterBtn.classList.remove('is-open');
            filterBtn.setAttribute('aria-expanded', 'false');
        }
    }

    function openCallsFilterMenu() {
        if (!filterMenu) return;
        filterMenu.classList.remove('d-none');
        if (filterBtn) {
            filterBtn.classList.add('is-open');
            filterBtn.setAttribute('aria-expanded', 'true');
        }
    }

    function applyCallsFilter(typeValue) {
        const normalized = (typeValue || '').toString().trim().toLowerCase();
        if (filterEl) {
            filterEl.value = normalized === 'all' ? '' : normalized;
        }
        syncCallsFilterUi(normalized || 'all');
        loadCallHistory(null, false, null, false);
    }

    if (filterEl && filterEl.dataset.rcBound !== '1') {
        filterEl.dataset.rcBound = '1';
        filterEl.addEventListener('change', function () {
            const type = getCallsTypeFilterValue();
            syncCallsFilterUi(type);
            loadCallHistory(null, false, null, false);
        });
    }

    if (allBtn && allBtn.dataset.rcBound !== '1') {
        allBtn.dataset.rcBound = '1';
        allBtn.addEventListener('click', function () {
            closeCallsFilterMenu();
            applyCallsFilter('all');
        });
    }

    if (filterBtn && filterBtn.dataset.rcBound !== '1') {
        filterBtn.dataset.rcBound = '1';
        filterBtn.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            if (!filterMenu) return;
            if (filterMenu.classList.contains('d-none')) {
                openCallsFilterMenu();
            } else {
                closeCallsFilterMenu();
            }
        });
    }

    if (filterMenu && filterMenu.dataset.rcBound !== '1') {
        filterMenu.dataset.rcBound = '1';
        filterMenu.addEventListener('click', function (event) {
            const item = event.target.closest('[data-filter]');
            if (!item) return;
            event.preventDefault();
            event.stopPropagation();
            const selected = (item.getAttribute('data-filter') || '').toLowerCase();
            closeCallsFilterMenu();
            applyCallsFilter(selected || 'all');
        });
    }

    if (filterDropdown && filterDropdown.dataset.rcOutsideBound !== '1') {
        filterDropdown.dataset.rcOutsideBound = '1';
        document.addEventListener('click', function (event) {
            if (!filterDropdown.contains(event.target)) {
                closeCallsFilterMenu();
            }
        });
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeCallsFilterMenu();
            }
        });
    }

    syncCallsFilterUi(getCallsTypeFilterValue());
}

function formatCallDisplayTime(startTime) {
    if (typeof window.rcFormatCallTime === 'function') {
        return window.rcFormatCallTime(startTime);
    }
    if (!startTime) return '';
    const fallback = new Date(startTime);
    if (Number.isNaN(fallback.getTime())) return '';
    return fallback.toLocaleString();
}

function normalizeActionPhone(rawPhone) {
    const value = (rawPhone || '').toString().trim();
    return value || '';
}

function normalizePhoneDigits(rawPhone) {
    return String(rawPhone || '').replace(/\D/g, '');
}

function getOwnLineDigits() {
    try {
        const connectedEl = document.getElementById('connectedPhoneNumber');
        const fromDataAttr = connectedEl ? connectedEl.getAttribute('data-full-number') : '';
        const fromSmsInput = document.getElementById('smsFromNumber')?.value || '';
        return normalizePhoneDigits(fromDataAttr || fromSmsInput);
    } catch (_) {
        return '';
    }
}

function chooseCounterpartyPhone(fromPhone, toPhone, isOutgoing) {
    const ownDigits = getOwnLineDigits();
    const fromDigits = normalizePhoneDigits(fromPhone);
    const toDigits = normalizePhoneDigits(toPhone);

    if (ownDigits) {
        const fromIsOwn = fromDigits && fromDigits === ownDigits;
        const toIsOwn = toDigits && toDigits === ownDigits;
        if (fromIsOwn && !toIsOwn) return toPhone;
        if (toIsOwn && !fromIsOwn) return fromPhone;
    }

    return isOutgoing ? (toPhone || fromPhone) : (fromPhone || toPhone);
}

function rcCallFromList(phone) {
    const target = normalizeActionPhone(phone);
    if (!target) return;
    const activateTab = function (href, paneId) {
        return new Promise(resolve => {
            const tabLink = document.querySelector(`a[href="${href}"]`);
            const tabPane = paneId ? document.getElementById(paneId) : null;
            if (!tabLink) {
                resolve();
                return;
            }

            const isActive = (tabLink.classList && tabLink.classList.contains('active'))
                || (tabPane && tabPane.classList && tabPane.classList.contains('active'));
            if (isActive) {
                resolve();
                return;
            }

            let resolved = false;
            const done = function () {
                if (resolved) return;
                resolved = true;
                resolve();
            };

            // Safety timeout in case tab event does not fire.
            setTimeout(done, 600);

            if (window.jQuery && typeof jQuery(tabLink).tab === 'function') {
                jQuery(tabLink).one('shown.bs.tab', done);
                jQuery(tabLink).tab('show');
                return;
            }

            tabLink.click();
            setTimeout(done, 120);
        });
    };

    // Always open Calls tab first, then open the Keypad sub-tab.
    activateTab('#tabCalls', 'tabCalls')
        .then(function () {
            return activateTab('#callKeypad', 'callKeypad');
        })
        .then(function () {
            try {
                if (typeof openDialerModal === 'function') {
                    openDialerModal();
                }
            } catch (e) {
                console.warn('openDialerModal failed before auto-dial', e);
            }

            try {
                const input = document.getElementById('callPhone');
                if (input) input.value = target;
                try {
                    localStorage.setItem('rcLastDialedNumber', target);
                } catch (_) { }

                const callForm = document.getElementById('callForm');
                if (callForm) {
                    // Use the exact same submit flow as manual dialing so UI state
                    // (Calling... button + session wiring) stays consistent.
                    callForm.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
                    return;
                }
            } catch (e) {
                console.warn('Dialer prefill/submit failed', e);
            }

            // Last-resort fallback if call form is unavailable.
            try {
                if (window.webPhone && typeof window.webPhone.makeCall === 'function') {
                    window.webPhone.makeCall(target);
                }
            } catch (e) {
                console.warn('call fallback failed', e);
            }
        });
}

function rcMessageFromList(phone) {
    const target = normalizeActionPhone(phone);
    if (!target) return;
    const digits = target.replace(/\D/g, '');

    function isMessagesTabActive() {
        const tabLink = document.querySelector('a[href="#tabMessages"]');
        const tabPane = document.getElementById('tabMessages');
        if (tabLink && tabLink.classList.contains('active')) return true;
        if (tabPane && tabPane.classList.contains('active')) return true;
        return false;
    }

    function activateMessagesTab() {
        return new Promise(resolve => {
            const tabLink = document.querySelector('a[href="#tabMessages"]');
            if (!tabLink) {
                resolve();
                return;
            }
            if (isMessagesTabActive()) {
                resolve();
                return;
            }

            if (window.jQuery && typeof jQuery(tabLink).tab === 'function') {
                jQuery(tabLink).one('shown.bs.tab', function () {
                    resolve();
                });
                jQuery(tabLink).tab('show');
                return;
            }

            tabLink.click();
            const start = Date.now();
            const waitForActive = function () {
                if (isMessagesTabActive()) {
                    resolve();
                    return;
                }
                if (Date.now() - start > 800) {
                    resolve();
                    return;
                }
                setTimeout(waitForActive, 50);
            };
            setTimeout(waitForActive, 50);
        });
    }

    const openChat = () => {
        window._rc_messageThreadKeyByDigits = window._rc_messageThreadKeyByDigits || {};
        const threadKey = window._rc_messageThreadKeyByDigits[digits] || digits;
        window._rc_messageThreads = window._rc_messageThreads || {};
        if (!window._rc_messageThreads[threadKey]) {
            window._rc_messageThreads[threadKey] = [];
        }
        if (typeof showChatFor === 'function') {
            showChatFor(threadKey);
        }
        const smsPhone = document.getElementById('smsPhone');
        if (smsPhone && digits) {
            smsPhone.value = '+' + digits;
            smsPhone.dispatchEvent(new Event('input', { bubbles: true }));
        }
        if (digits && typeof window.rcSmsModalSetRecipients === 'function') {
            window.rcSmsModalSetRecipients(['+' + digits]);
        }
    };

    activateMessagesTab().then(() => {
        if (typeof loadMessageHistory === 'function') {
            loadMessageHistory(null, false, null, false)
                .then(openChat)
                .catch(openChat);
        } else {
            openChat();
        }
    });
}

async function rcBlockFromCall(phone) {
    const target = normalizeActionPhone(phone);
    if (!target) return;
    if (!window.rcBlockedNumbersApi || typeof window.rcBlockedNumbersApi.toggleNumberFromContext !== 'function') return;
    await window.rcBlockedNumbersApi.toggleNumberFromContext(target, 'Blocked from Calls tab', 'Calls');
}

function bindCallsListActions() {
    const listEl = document.getElementById('tabCallsList');
    if (!listEl || listEl._rc_actionsBound) return;
    listEl._rc_actionsBound = true;

    function closeCallsMoreMenus(scopeEl) {
        const root = scopeEl || document;
        const openMenus = root.querySelectorAll('.rc-call-more-wrap.is-open');
        openMenus.forEach(wrap => {
            wrap.classList.remove('is-open');
            const menu = wrap.querySelector('.rc-call-more-menu');
            if (menu) menu.hidden = true;
            const toggle = wrap.querySelector('.rc-call-action-more');
            if (toggle) toggle.setAttribute('aria-expanded', 'false');
        });
    }

    listEl.addEventListener('click', function (event) {
        const moreBtn = event.target.closest('.rc-call-action-more');
        if (moreBtn) {
            event.preventDefault();
            event.stopPropagation();
            const wrap = moreBtn.closest('.rc-call-more-wrap');
            if (!wrap) return;

            const isOpen = wrap.classList.contains('is-open');
            closeCallsMoreMenus(listEl);
            if (!isOpen) {
                wrap.classList.add('is-open');
                const menu = wrap.querySelector('.rc-call-more-menu');
                if (menu) menu.hidden = false;
                moreBtn.setAttribute('aria-expanded', 'true');
                const blockAction = wrap.querySelector('.rc-call-action-block');
                if (blockAction && window.rcBlockedNumbersApi && typeof window.rcBlockedNumbersApi.decorateBlockActionElement === 'function') {
                    window.rcBlockedNumbersApi.decorateBlockActionElement(blockAction, blockAction.getAttribute('data-phone'));
                }
            }
            return;
        }

        const callBtn = event.target.closest('.rc-call-action-call');
        if (callBtn) {
            event.preventDefault();
            event.stopPropagation();
            closeCallsMoreMenus(listEl);
            rcCallFromList(callBtn.getAttribute('data-phone'));
            return;
        }
        const msgBtn = event.target.closest('.rc-call-action-message');
        if (msgBtn) {
            event.preventDefault();
            event.stopPropagation();
            closeCallsMoreMenus(listEl);
            rcMessageFromList(msgBtn.getAttribute('data-phone'));
            return;
        }
        const blockBtn = event.target.closest('.rc-call-action-block');
        if (blockBtn) {
            event.preventDefault();
            event.stopPropagation();
            closeCallsMoreMenus(listEl);
            rcBlockFromCall(blockBtn.getAttribute('data-phone'));
            return;
        }

        closeCallsMoreMenus(listEl);

        const row = event.target.closest('[data-call-id]');
        if (row && !event.target.closest('.rc-call-action-call, .rc-call-action-more, .rc-call-more-menu, .rc-call-action-message, .rc-call-action-block')) {
            event.preventDefault();
            const stableId = row.getAttribute('data-call-id');
            const call = (Array.isArray(window._rc_callsItems) ? window._rc_callsItems : [])
                .find(c => getCallHistoryStableId(c) === stableId);
            if (call) rcRenderCallDetailInSidePanel(call);
        }
    });

    if (!window._rcCallsOutsideMenuBound) {
        window._rcCallsOutsideMenuBound = true;
        document.addEventListener('click', function (event) {
            if (event.target.closest('#tabCallsList .rc-call-more-wrap')) return;
            closeCallsMoreMenus(document.getElementById('tabCallsList'));
        });
    }

    if (!window._rcCallsEscMenuBound) {
        window._rcCallsEscMenuBound = true;
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeCallsMoreMenus(document.getElementById('tabCallsList'));
            }
        });
    }
}

function formatBadgeCount(count) {
    const num = Math.max(0, parseInt(count || 0, 10) || 0);
    return {
        num,
        label: num > 99 ? '99+' : String(num)
    };
}

function syncCallsSidebarCombinedBadge() {
    const badge = document.getElementById('rcCallsMissedBadge');
    if (!badge) return;

    const missedCalls = Math.max(0, parseInt(window._rcCallsMissedUnseen || 0, 10) || 0);
    const unreadVoicemails = Math.max(0, parseInt(window._rcVoicemailsUnreadTotal || 0, 10) || 0);
    const total = missedCalls + unreadVoicemails;
    const { num, label } = formatBadgeCount(total);

    badge.textContent = label;
    if (num > 0) {
        badge.classList.remove('is-hidden');
        badge.classList.remove('d-none');
    } else {
        badge.classList.add('is-hidden');
        badge.classList.add('d-none');
    }
}
window.rcSyncCallsBadgeTotals = syncCallsSidebarCombinedBadge;

function updateCallsMissedBadge(count) {
    const { num, label } = formatBadgeCount(count);
    window._rcCallsMissedUnseen = num;

    const innerBadge = document.getElementById('rcCallsMissedBadgeInner');
    if (innerBadge) {
        innerBadge.textContent = label;
        if (num > 0) {
            innerBadge.classList.remove('is-hidden');
            innerBadge.classList.remove('d-none');
        } else {
            innerBadge.classList.add('is-hidden');
            innerBadge.classList.add('d-none');
        }
    }

    syncCallsSidebarCombinedBadge();
}

function applyCallsSummary(summary) {
    if (!summary || typeof summary !== 'object') return;
    if (summary.missed_unseen !== undefined) {
        updateCallsMissedBadge(summary.missed_unseen);
    } else if (summary.missed_total !== undefined) {
        updateCallsMissedBadge(summary.missed_total);
    }
}

async function rcParseJsonOrRedirect(response) {
    if (!response) return null;

    const contentType = (response.headers.get('content-type') || '').toLowerCase();
    const redirectedToLogin = !!(response.redirected && response.url && /\/login(?:[/?#]|$)/i.test(response.url));
    const unauthorized = response.status === 401 || response.status === 403 || response.status === 419;

    if (redirectedToLogin || unauthorized) {
        window.location.href = '/login';
        return null;
    }

    if (!contentType.includes('application/json')) {
        const bodyText = await response.text();
        const looksLikeHtml = /^\s*<!doctype html/i.test(bodyText) || /^\s*<html/i.test(bodyText);
        if (looksLikeHtml) {
            window.location.href = '/login';
            return null;
        }
        throw new Error('Expected JSON response');
    }

    return response.json();
}

function refreshCallsSummary(forceRefresh = false) {
    try {
        const params = new URLSearchParams();
        if (forceRefresh) params.set('refresh', '1');
        const url = rcRoute('ringcentral.api.calls.summary') + (params.toString() ? `?${params.toString()}` : '');
        return fetch(url, {
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(r => rcParseJsonOrRedirect(r))
            .then(data => {
                if (!data) return;
                if (data && data.summary) {
                    applyCallsSummary(data.summary);
                }
            })
            .catch(e => console.warn('calls summary failed', e));
    } catch (e) {
        console.warn('calls summary init failed', e);
    }
    return Promise.resolve();
}

function markMissedCallsSeen() {
    try {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
        return fetch(rcRoute('ringcentral.api.calls.mark-seen'), {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf
            },
            body: JSON.stringify({})
        })
            .then(r => rcParseJsonOrRedirect(r))
            .then(data => {
                if (!data) return;
                if (data && data.summary) {
                    applyCallsSummary(data.summary);
                }
            })
            .catch(e => console.warn('mark calls seen failed', e));
    } catch (e) {
        console.warn('mark calls seen init failed', e);
    }
    return Promise.resolve();
}

function getCallHistoryStableId(call) {
    const item = (call && typeof call === 'object') ? call : {};
    const explicit = item.id || item.call_id || item.session_id || item.telephony_session_id || item.uri;
    if (explicit) return String(explicit);

    const parts = [
        item.start_time,
        item.direction,
        item.from,
        item.to,
        item.duration_seconds,
        item.status
    ].map(v => String(v || '').trim());
    if (parts.some(Boolean)) return parts.join('|');
    return '';
}

function getCallsWebhookSyncCount() {
    const configured = parseInt(window.RC_WEBHOOK_SYNC_COUNT, 10);
    if (Number.isFinite(configured) && configured > 0) return Math.min(100, configured);
    return Math.max(5, Math.min(50, parseInt(window.RC_REFRESH_SYNC_COUNT, 10) || 20));
}

function getCallsSearchValue() {
    const searchEl = document.getElementById('tabCallsSearch');
    return (searchEl && searchEl.value) ? searchEl.value.trim().toLowerCase() : '';
}

function callMatchesSearch(call, query) {
    const q = String(query || '').trim().toLowerCase();
    if (!q) return true;
    const haystack = [
        call && call.from,
        call && call.to,
        call && call.direction,
        call && call.status,
        call && call.start_time,
        call && call.phone_number
    ].map(v => String(v || '').toLowerCase()).join(' ');
    return haystack.includes(q);
}

function renderCallHistoryRow(call) {
    const fromPhone = call.from || '';
    const toPhone = call.to || '';
    const normalizedDir = normalizeDirectionKey(call.direction);
    const isOutgoing = normalizedDir === 'outgoing';
    const primaryPhone = isOutgoing ? (toPhone || fromPhone) : (fromPhone || toPhone);
    const secondaryPhone = isOutgoing ? fromPhone : toPhone;
    const displayFromPhone = fromPhone
        ? ((typeof maskPhoneNumber === 'function') ? maskPhoneNumber(fromPhone) : fromPhone)
        : '';
    const displayToPhone = toPhone
        ? ((typeof maskPhoneNumber === 'function') ? maskPhoneNumber(toPhone) : toPhone)
        : '';
    const displayPrimaryPhone = primaryPhone
        ? ((typeof maskPhoneNumber === 'function') ? maskPhoneNumber(primaryPhone) : primaryPhone)
        : '';
    const displaySecondaryPhone = secondaryPhone
        ? ((typeof maskPhoneNumber === 'function') ? maskPhoneNumber(secondaryPhone) : secondaryPhone)
        : '';
    const direction = formatDirection(call.direction);
    const duration = formatCallDuration(call.duration_seconds);
    const statusLabel = formatCallStatus(call.status);
    const statusClass = callStatusClass(statusLabel);
    const displayTime = formatCallDisplayTime(call.start_time);
    const label = displayPrimaryPhone || displaySecondaryPhone || 'Unknown';
    const initials = ((label || '').toString().replace(/\D/g, '').slice(-2) || '#').toUpperCase();
    const actionPhone = normalizeActionPhone(primaryPhone || secondaryPhone);
    const stableId = getCallHistoryStableId(call);

    let directionIcon = '';
    if (isMissedStatus(call.status)) {
        directionIcon = `
            <span class="rc-call-direction rc-call-missed">
                <i class="fa fa-phone rc-call-phone-icon"></i>
                <i class="fa fa-times rc-call-arrow-icon"></i>
            </span>`;
    } else if (normalizedDir === 'incoming') {
        directionIcon = `
            <span class="rc-call-direction rc-call-incoming">
                <i class="fa fa-phone rc-call-phone-icon"></i>
                <i class="fa fa-arrow-down rc-call-arrow-icon"></i>
            </span>`;
    } else if (normalizedDir === 'outgoing') {
        directionIcon = `
            <span class="rc-call-direction rc-call-outgoing">
                <i class="fa fa-phone rc-call-phone-icon"></i>
                <i class="fa fa-arrow-up rc-call-arrow-icon"></i>
            </span>`;
    } else {
        directionIcon = `
            <span class="rc-call-direction">
                <i class="fa fa-phone rc-call-phone-icon"></i>
            </span>`;
    }

    const rowSearch = (label + ' ' + displayFromPhone + ' ' + displayToPhone + ' ' + statusLabel + ' ' + direction).toString();
    return `
        <a href="#" class="list-group-item list-group-item-action message-item p-3 border rounded-3 mb-2"
           data-call-id="${escapeHtml(stableId)}"
           data-search="${escapeHtml(rowSearch)}">
            <div class="d-flex align-items-center py-2">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:50px; height:50px; flex-shrink: 0; margin-right:10px;">
                    <span class="fw-bold">${escapeHtml(initials)}</span>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="fw-semibold d-block">${escapeHtml(label)}</span>
                            <small class="text-muted d-block preview-text">
                                ${directionIcon}
                                ${escapeHtml(duration)} |
                                <span class="rc-call-status-badge ${escapeHtml(statusClass)}">${escapeHtml(statusLabel)}</span>
                            </small>
                        </div>
                        <div class="text-end ms-2">
                            <span class="text-muted small d-block">${escapeHtml(displayTime)}</span>
                            <div class="rc-call-list-actions mt-1">
                                <button type="button" class="phone-ui-btn rc-call-action-call" data-phone="${escapeHtml(actionPhone)}"><i class="fa fa-phone"></i></button>
                                <div class="rc-call-more-wrap">
                                    <button type="button" class="msg-ui-btn rc-call-action-more" title="More" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h"></i>
                                    </button>
                                    <div class="rc-filter-dropdown-menu rc-call-more-menu" hidden>
                                        <button type="button" class="rc-filter-dropdown-item rc-call-action-message" data-phone="${escapeHtml(actionPhone)}">
                                            <i class="fa fa-comment"></i> Message
                                        </button>
                                        <button type="button" class="rc-filter-dropdown-item rc-call-action-block" data-phone="${escapeHtml(actionPhone)}">
                                            <i class="fa fa-ban"></i> Block
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>`;
}

function mergeCallHistoryItems(existingItems, incomingItems) {
    const existing = Array.isArray(existingItems) ? existingItems : [];
    const incoming = Array.isArray(incomingItems) ? incomingItems : [];
    const byId = new Map();

    existing.forEach(call => {
        const id = getCallHistoryStableId(call);
        if (id) byId.set(id, call);
    });
    incoming.forEach(call => {
        const id = getCallHistoryStableId(call);
        if (id) byId.set(id, call);
    });

    return Array.from(byId.values()).sort((a, b) => {
        const at = new Date((a && a.start_time) || 0).getTime() || 0;
        const bt = new Date((b && b.start_time) || 0).getTime() || 0;
        return bt - at;
    });
}

function findCallRowByStableId(listEl, stableId) {
    if (!listEl) return null;
    const target = String(stableId || '');
    const rows = listEl.querySelectorAll('a.message-item[data-call-id]');
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        if (String(row.getAttribute('data-call-id') || '') === target) {
            return row;
        }
    }
    return null;
}

function upsertCallRowsIncremental(changedItems) {
    const listEl = document.getElementById('tabCallsList');
    if (!listEl || !Array.isArray(changedItems) || !changedItems.length) return;

    const activeFilter = getCallsTypeFilterValue();
    const searchQuery = getCallsSearchValue();
    const filteredChanged = changedItems
        .filter(call => filterCallsByType([call], activeFilter).length > 0)
        .filter(call => callMatchesSearch(call, searchQuery))
        .sort((a, b) => {
            const at = new Date((a && a.start_time) || 0).getTime() || 0;
            const bt = new Date((b && b.start_time) || 0).getTime() || 0;
            return bt - at;
        });

    const placeholder = listEl.querySelector('.text-muted, p.text-danger');
    if (placeholder && listEl.children.length === 1) {
        listEl.innerHTML = '';
    }

    filteredChanged.forEach(call => {
        const stableId = getCallHistoryStableId(call);
        if (!stableId) return;
        const rowHtml = renderCallHistoryRow(call);
        const existing = findCallRowByStableId(listEl, stableId);
        if (existing) {
            existing.outerHTML = rowHtml;
        } else {
            listEl.insertAdjacentHTML('afterbegin', rowHtml);
        }
    });
}

// Load call history
function loadCallHistory(page = null, append = false, beforeCursor = null, forceRefresh = false) {
    // prevent duplicate concurrent loads for calls
    window._rc_loadingCalls = window._rc_loadingCalls || false;
    if (window._rc_loadingCalls) {
        return Promise.resolve();
    }
    window._rc_loadingCalls = true;
    const searchEl = document.getElementById('tabCallsSearch');
    const callTypeFilter = getCallsTypeFilterValue();
    const q = (searchEl && searchEl.value) ? searchEl.value.trim() : '';
    const defaultPageSize = Math.max(1, parseInt(window.RC_INITIAL_PAGE_SIZE, 10) || 50);
    const defaultRefreshCount = Math.max(1, parseInt(window.RC_REFRESH_SYNC_COUNT, 10) || 50);
    const shouldConsiderWebhookToast = !append && !q;
    window._rc_callsSeenIds = window._rc_callsSeenIds instanceof Set ? window._rc_callsSeenIds : new Set();
    const previousCallIds = new Set(window._rc_callsSeenIds);
    const canNotifyNewCalls = shouldConsiderWebhookToast && window._rc_callsNotifyReady === true;
    const newCallIds = new Set();
    let newCallsCount = 0;
    const params = new URLSearchParams({
        per_page: String(defaultPageSize)
    });
    if (q) params.set('q', q);
    if (callTypeFilter && callTypeFilter !== 'all') {
        params.set('call_type', callTypeFilter);
        if (callTypeFilter === 'missed') {
            params.set('status', 'Missed');
        } else if (callTypeFilter === 'incoming') {
            params.set('direction', 'Inbound');
        } else if (callTypeFilter === 'outgoing') {
            params.set('direction', 'Outbound');
        }
    }
    if (forceRefresh) {
        params.set('refresh', '1');
        params.set('count', String(defaultRefreshCount));
    }
    if (beforeCursor) params.set('before', beforeCursor);
    if (append) {
        setCallsLoadMoreLoading(true);
    }

    const listEl = document.getElementById('tabCallsList');
    if (listEl && !append) {
        const loadingLabel = forceRefresh ? 'Refreshing calls...' : (q ? 'Searching calls...' : 'Loading calls...');
        listEl.innerHTML = rcCallsLoadingMarkup(loadingLabel);
    }

    return fetch(rcRoute('ringcentral.api.calls') + '?' + params.toString(), {
        credentials: 'same-origin',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(r => rcParseJsonOrRedirect(r))
        .then(data => {
            if (!data) return;
            const payload = Array.isArray(data) ? { data } : (data || {});
            const items = Array.isArray(payload.data) ? payload.data : [];
            window._rc_callsItems = window._rc_callsItems || [];
            items.forEach(call => {
                const cid = getCallHistoryStableId(call);
                if (!cid) return;
                if (canNotifyNewCalls && !previousCallIds.has(cid) && !newCallIds.has(cid)) {
                    newCallIds.add(cid);
                    newCallsCount++;
                }
                window._rc_callsSeenIds.add(cid);
            });
            if (append) {
                window._rc_callsItems = mergeCallHistoryItems(window._rc_callsItems, items);
            } else if (!q) {
                window._rc_callsItems = mergeCallHistoryItems([], items);
            } else {
                window._rc_callsItems = mergeCallHistoryItems(window._rc_callsItems, items);
            }

            const sourceForView = (!q && window._rc_callsItems.length)
                ? window._rc_callsItems
                : items;
            const filteredItems = filterCallsByType(sourceForView, callTypeFilter).filter(call => callMatchesSearch(call, q));
            const unreadCount = items.filter(call => {
                if (call && typeof call.is_seen !== 'undefined') {
                    return !call.is_seen;
                }
                return isMissedStatus(call && call.status);
            }).length;
            const pagination = payload.pagination || {};
            const hasMore = !!pagination.has_more;
            window._rc_callsNextCursor = pagination.next_cursor || null;

            if (payload.summary) {
                applyCallsSummary(payload.summary);
                if (payload.summary.total_calls !== undefined && payload.summary.total_calls !== null) {
                    window._rc_callsTotalAvailable = parseInt(payload.summary.total_calls, 10) || 0;
                } else if (!append) {
                    window._rc_callsTotalAvailable = null;
                }
                if (payload.summary.missed_unseen !== undefined && payload.summary.missed_unseen !== null) {
                    window._rc_callsUnreadTotal = parseInt(payload.summary.missed_unseen, 10) || 0;
                } else if (!append) {
                    window._rc_callsUnreadTotal = null;
                }
            } else if (!append) {
                window._rc_callsTotalAvailable = null;
                window._rc_callsUnreadTotal = null;
            }

            const listEl = document.getElementById('tabCallsList');
            if (!listEl) return;
            listEl.innerHTML = '';

            // Build a modern list for the Calls tab
            if (!filteredItems || !filteredItems.length) {
                if (!append) {
                    const emptyLabel = callTypeFilter === 'all'
                        ? 'No calls found'
                        : `No ${callTypeFilter} calls found`;
                    listEl.innerHTML = `<div class="text-muted">${escapeHtml(emptyLabel)}</div>`;
                }
            } else {
                let listHtml = '';
                filteredItems.forEach(call => {
                    listHtml += renderCallHistoryRow(call);
                });
                if (append) {
                    listEl.insertAdjacentHTML('beforeend', listHtml);
                } else {
                    listEl.innerHTML = listHtml;
                }
                bindCallsListActions();
            }

            renderCallsTestingStats(filteredItems.length, items.length, unreadCount);
            renderCallsLoadMore(hasMore);

            if (shouldConsiderWebhookToast) {
                window._rc_callsNotifyReady = true;
            }
        })
        .catch(error => {
            const el = document.getElementById('tabCallsList');
            if (el) el.innerHTML = '<p class="text-danger">Error loading call history</p>';
            console.error('Error:', error);
        }).finally(() => {
            if (append) {
                setCallsLoadMoreLoading(false);
            }
            window._rc_loadingCalls = false;
        });
}

function loadCallsIncrementalFromWebhook() {
    if (window._rc_loadingCalls) return Promise.resolve();
    window._rc_callsItems = Array.isArray(window._rc_callsItems) ? window._rc_callsItems : [];

    const q = getCallsSearchValue();
    if (window._rc_callsItems.length === 0 || q) {
        return loadCallHistory(null, false, null, false);
    }

    const callTypeFilter = getCallsTypeFilterValue();
    const perPage = getCallsWebhookSyncCount();
    const params = new URLSearchParams({
        per_page: String(perPage),
        refresh: '1',
        count: String(perPage)
    });

    window._rc_loadingCalls = true;
    window._rc_callsSeenIds = window._rc_callsSeenIds instanceof Set ? window._rc_callsSeenIds : new Set();
    const previousCallIds = new Set(window._rc_callsSeenIds);

    return fetch(rcRoute('ringcentral.api.calls') + '?' + params.toString(), {
        credentials: 'same-origin',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(r => rcParseJsonOrRedirect(r))
        .then(data => {
            if (!data) return;
            const payload = Array.isArray(data) ? { data } : (data || {});
            const incoming = Array.isArray(payload.data) ? payload.data : [];
            const changedItems = [];
            const changedMap = new Map();
            let newCallsCount = 0;

            incoming.forEach(call => {
                const cid = getCallHistoryStableId(call);
                if (!cid) return;
                const existing = (window._rc_callsItems || []).find(it => getCallHistoryStableId(it) === cid);
                const isChanged = !existing
                    || String(existing.status || '') !== String(call.status || '')
                    || String(existing.duration_seconds || '') !== String(call.duration_seconds || '')
                    || String(existing.start_time || '') !== String(call.start_time || '');
                if (isChanged && !changedMap.has(cid)) {
                    changedMap.set(cid, call);
                    changedItems.push(call);
                }
                if (!previousCallIds.has(cid)) {
                    newCallsCount++;
                }
                window._rc_callsSeenIds.add(cid);
            });

            window._rc_callsItems = mergeCallHistoryItems(window._rc_callsItems, incoming);

            if (payload.summary) {
                applyCallsSummary(payload.summary);
                if (payload.summary.total_calls !== undefined && payload.summary.total_calls !== null) {
                    window._rc_callsTotalAvailable = parseInt(payload.summary.total_calls, 10) || 0;
                }
                if (payload.summary.missed_unseen !== undefined && payload.summary.missed_unseen !== null) {
                    window._rc_callsUnreadTotal = parseInt(payload.summary.missed_unseen, 10) || 0;
                }
            }

            upsertCallRowsIncremental(changedItems);

            const sourceForView = window._rc_callsItems || [];
            const filteredItems = filterCallsByType(sourceForView, callTypeFilter).filter(call => callMatchesSearch(call, q));
            const unreadCount = sourceForView.filter(call => {
                if (call && typeof call.is_seen !== 'undefined') return !call.is_seen;
                return isMissedStatus(call && call.status);
            }).length;
            renderCallsTestingStats(filteredItems.length, sourceForView.length, unreadCount);

            window._rc_callsNotifyReady = true;
        })
        .catch(error => {
            console.warn('Incremental calls webhook refresh failed', error);
        })
        .finally(() => {
            window._rc_loadingCalls = false;
        });
}
window.loadCallsIncrementalFromWebhook = loadCallsIncrementalFromWebhook;

function renderCallsLoadMore(hasMore) {
    try {
        window._rc_callsHasMore = !!hasMore;
        const listEl = document.getElementById('tabCallsList');
        if (!listEl) return;

        if (!listEl._rc_callsScrollLoadBound) {
            listEl._rc_callsScrollLoadBound = true;
            let loading = false;
            listEl.addEventListener('scroll', function () {
                try {
                    if (loading || window._rc_loadingCalls) return;
                    if (!window._rc_callsHasMore || !window._rc_callsNextCursor) return;

                    const distanceFromBottom = listEl.scrollHeight - listEl.scrollTop - listEl.clientHeight;
                    if (distanceFromBottom > 120) return;

                    loading = true;
                    const nextCursor = window._rc_callsNextCursor || null;
                    loadCallHistory(null, true, nextCursor).finally(function () {
                        loading = false;
                    });
                } catch (_) { }
            });
        }

        let footer = document.getElementById('tabCallsLoadMore');
        if (!footer && listEl && listEl.parentNode) {
            footer = document.createElement('div');
            footer.id = 'tabCallsLoadMore';
            listEl.parentNode.insertBefore(footer, listEl);
        }
        if (!footer) return;

        let btn = footer.querySelector('button');
        if (!btn) {
            footer.innerHTML = '<button type="button" class="ui-btn" title="Refresh calls"><i class="fa fa-refresh"></i></button>';
            btn = footer.querySelector('button');
            if (btn) {
                btn.addEventListener('click', function () {
                    if (window._rc_loadingCalls) return;
                    setCallsLoadMoreRefreshing(true);
                    loadCallHistory(null, false, null, true)
                        .then(function () {
                            const nextCursor = window._rc_callsNextCursor || null;
                            if (!nextCursor) return Promise.resolve();
                            return loadCallHistory(null, true, nextCursor, false);
                        })
                        .catch(function () { /* no-op */ })
                        .finally(function () {
                            setCallsLoadMoreRefreshing(false);
                        });
                });
            }
        }

        footer.style.display = 'block';
    } catch (_) { }
}

function setCallsLoadMoreRefreshing(isLoading) {
    try {
        const footer = document.getElementById('tabCallsLoadMore');
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

function isCallsTabActive() {
    try {
        const outer = document.getElementById('tabCalls');
        const inner = document.getElementById('callCalls');
        return !!(
            outer && inner
            && outer.classList.contains('active')
            && inner.classList.contains('active')
        );
    } catch (_) {
        return false;
    }
}

// Initialize calls controls/badges on DOMContentLoaded
document.addEventListener('DOMContentLoaded', function () {
    try {
        bindCallsTypeFilterControl();
        refreshCallsSummary();
    } catch (e) {
        console.warn('calls module init failed on load', e);
    }
});
