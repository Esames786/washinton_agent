/**
 * R-Dialer  Messages Module
 * Handles message history loading, chat view, and message sending
 */

// Helper: Format display time based on message age (shared config)
function formatMessageTime(creationTime) {
    if (typeof window.rcFormatMessageTime === 'function') {
        return window.rcFormatMessageTime(creationTime);
    }
    if (!creationTime) return '';
    const fallback = new Date(creationTime);
    if (Number.isNaN(fallback.getTime())) return '';
    return fallback.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}

function cacheRcUserPhoneDigits(rawValue) {
    try {
        const digits = (rawValue || '').toString().replace(/\D/g, '');
        if (!digits) return '';
        window._rc_userPhoneDigits = digits;
        return digits;
    } catch (_) {
        return '';
    }
}

function getAuthoritativeRcUserPhoneDigits() {
    try {
        const fromEl = document.getElementById('smsFromNumber');
        const fromValue = (fromEl && fromEl.value) ? String(fromEl.value) : '';
        const fromDigits = fromValue.replace(/\D/g, '');
        if (fromDigits) return fromDigits;

        const connectedEl = document.getElementById('connectedPhoneNumber');
        const connectedRaw = connectedEl
            ? String(connectedEl.getAttribute('data-full-number') || connectedEl.textContent || '')
            : '';
        const connectedDigits = connectedRaw.replace(/\D/g, '');
        if (connectedDigits) return connectedDigits;
    } catch (_) { }
    return '';
}

function getRcUserPhoneDigits() {
    try {
        const cached = (window._rc_userPhoneDigits || '').toString().replace(/\D/g, '');
        if (cached) return cached;

        const authoritative = getAuthoritativeRcUserPhoneDigits();
        if (authoritative) return cacheRcUserPhoneDigits(authoritative);

        return '';
    } catch (_) {
        return '';
    }
}

function inferRcUserPhoneDigitsFromMessages(messages) {
    try {
        // Never infer if UI already provides authoritative connected/from number.
        const authoritative = getAuthoritativeRcUserPhoneDigits();
        if (authoritative) {
            return cacheRcUserPhoneDigits(authoritative);
        }

        if (!Array.isArray(messages) || !messages.length) return '';

        const score = {};
        const addScore = (digits, points) => {
            const normalized = (digits || '').toString().replace(/\D/g, '');
            if (!normalized) return;
            score[normalized] = (score[normalized] || 0) + points;
        };

        const pickNumber = (value) => (value || '').toString().replace(/\D/g, '');
        messages.forEach(message => {
            const direction = (message && message.direction ? String(message.direction) : '').toLowerCase();
            const fromDigits = pickNumber(
                (message && message.from && (message.from.phoneNumber || message.from.phone || message.from.number))
                || message.fromPhone
                || message.fromNumber
            );
            const toDigits = pickNumber(
                (message && Array.isArray(message.to) && message.to[0] && (message.to[0].phoneNumber || message.to[0].phone || message.to[0].number || message.to[0].value))
                || message.toPhone
                || message.toNumber
            );

            addScore(fromDigits, direction.includes('out') ? 5 : 1);
            addScore(toDigits, direction.includes('in') ? 5 : 1);
            if (fromDigits && toDigits && fromDigits === toDigits) {
                addScore(fromDigits, 1);
            }
        });

        let bestDigits = '';
        let bestScore = 0;
        Object.keys(score).forEach(digits => {
            const value = score[digits] || 0;
            if (value > bestScore) {
                bestScore = value;
                bestDigits = digits;
            }
        });

        if (!bestDigits) return '';
        return cacheRcUserPhoneDigits(bestDigits);
    } catch (_) {
        return '';
    }
}

function normalizeAttachments(attachments, options = {}) {
    if (!Array.isArray(attachments) || !attachments.length) return [];
    const hasMessageText = !!((options.messageText || '').toString().trim());

    const flat = [];
    const stack = attachments.slice();
    while (stack.length) {
        const item = stack.shift();
        if (!item) continue;
        if (Array.isArray(item)) {
            stack.push(...item);
            continue;
        }
        if (typeof item === 'object') {
            flat.push(item);
        }
    }

    if (!flat.length) return [];

    const isPseudoPlainTextAttachment = (att) => {
        const contentType = ((att && att.contentType) || '').toString().toLowerCase();
        if (!contentType.startsWith('text/plain')) return false;

        const fileName = ((att && (att.fileName || att.filename)) || '').toString().trim().toLowerCase();
        const isGenericName = !fileName || fileName === 'attachment' || /^attachment(_[a-z0-9]+)?$/i.test(fileName);
        const looksLikeRealTextFile = fileName.endsWith('.txt') || fileName.endsWith('.csv') || fileName.endsWith('.log');
        if (looksLikeRealTextFile) return false;

        const hasOnlyUri = !!((att && (att.uri || att.contentUri)) && !(att.path || att.stored_path));
        return isGenericName || hasOnlyUri;
    };

    const hasNonTextAttachment = flat.some(att => {
        const ct = ((att.contentType || '') + '').toLowerCase();
        return ct && !ct.startsWith('text/plain');
    });

    let filtered = flat;
    if (hasMessageText) {
        filtered = filtered.filter(att => !isPseudoPlainTextAttachment(att));
    }
    if (hasNonTextAttachment) {
        filtered = filtered.filter(att => !isPseudoPlainTextAttachment(att));
    }

    const seen = new Set();
    const deduped = [];
    filtered.forEach(att => {
        const keyParts = [
            (att.path || att.stored_path || '').toString().trim(),
            (att.local_path || '').toString().trim(),
            (att.url || '').toString().trim(),
            (att.uri || att.contentUri || '').toString().trim(),
            (att.fileName || att.filename || '').toString().trim(),
            (att.contentType || '').toString().trim(),
        ];
        const key = keyParts.join('|');
        if (seen.has(key)) return;
        seen.add(key);
        deduped.push(att);
    });

    return deduped;
}

function toForwardableAttachments(attachments) {
    const normalized = normalizeAttachments(attachments || []);
    if (!normalized.length) return [];
    return normalized.map(att => ({
        fileName: (att.fileName || att.filename || 'attachment').toString(),
        contentType: (att.contentType || 'application/octet-stream').toString(),
        size: typeof att.size === 'number' ? att.size : null,
        path: (att.path || att.stored_path || '').toString().trim(),
        uri: (att.uri || att.contentUri || '').toString().trim(),
        local_path: (att.local_path || '').toString().trim(),
        url: (att.url || '').toString().trim(),
        download_url: (att.download_url || '').toString().trim(),
    }));
}

function encodeForwardPayload(payload) {
    try {
        return btoa(encodeURIComponent(JSON.stringify(payload || {})));
    } catch (_) {
        return '';
    }
}

function decodeForwardPayload(encoded) {
    try {
        const raw = atob((encoded || '').toString());
        return JSON.parse(decodeURIComponent(raw));
    } catch (_) {
        return null;
    }
}

function renderSmsForwardDraftNotice() {
    const notice = document.getElementById('smsForwardAttachmentNotice');
    if (!notice) return;
    const draft = window._rc_smsForwardDraft || null;
    const attachments = draft && Array.isArray(draft.attachments) ? draft.attachments : [];
    if (!draft || !attachments.length) {
        notice.style.display = 'none';
        notice.innerHTML = '';
        return;
    }
    const names = attachments
        .map(att => (att && (att.fileName || att.filename)) ? String(att.fileName || att.filename) : 'attachment')
        .slice(0, 3)
        .map(name => escapeHtml(name));
    const moreCount = Math.max(0, attachments.length - names.length);
    const namesLabel = names.join(', ') + (moreCount > 0 ? ` +${moreCount} more` : '');
    notice.style.display = 'block';
    notice.innerHTML = `Forwarding ${attachments.length} attachment(s): ${namesLabel} <button type="button" class="btn btn-link btn-sm p-0 align-baseline" id="smsForwardDraftClearBtn">Clear</button>`;
}

function setSmsForwardDraft(draft) {
    if (!draft) {
        window._rc_smsForwardDraft = null;
        renderSmsForwardDraftNotice();
        return;
    }
    window._rc_smsForwardDraft = {
        text: (draft.text || '').toString(),
        attachments: toForwardableAttachments(draft.attachments || []),
    };
    renderSmsForwardDraftNotice();
}

function openForwardMessageModal(encodedPayload) {
    const payload = decodeForwardPayload(encodedPayload);
    if (!payload) {
        alert('Unable to forward this message.');
        return;
    }

    const forwardText = (payload.text || '').toString().trim();
    const forwardAttachments = Array.isArray(payload.attachments) ? payload.attachments : [];
    setSmsForwardDraft({ text: forwardText, attachments: forwardAttachments });

    const smsMessage = document.getElementById('smsMessage');
    if (smsMessage) smsMessage.value = forwardText;

    const smsPhone = document.getElementById('smsPhone');
    if (smsPhone) {
        smsPhone.value = '';
        smsPhone.dispatchEvent(new Event('input', { bubbles: true }));
    }
    if (typeof window.rcSmsModalClearRecipients === 'function') {
        window.rcSmsModalClearRecipients();
    }

    if (typeof $ === 'function' && $('#sendMessageModal').length) {
        $('#sendMessageModal').modal('show');
    }
}

function renderForwardAttachmentPreview() {
    const preview = document.getElementById('rcChatAttachmentPreview');
    if (!preview) return;

    const existingForwarded = preview.querySelectorAll('.rc-forwarded-attachment-chip');
    existingForwarded.forEach(node => node.remove());

    const forwarded = (window._rc_pendingForwardAttachments || [])
        .filter(att => att && ((att.fileName || att.filename) || att.path || att.uri));
    forwarded.forEach(att => {
        const chip = document.createElement('div');
        chip.className = 'rc-attachment-chip rc-forwarded-attachment-chip';

        const badge = document.createElement('span');
        badge.className = 'rc-attachment-badge';
        badge.textContent = 'fwd';
        badge.style.cssText = 'display:inline-block;font-size:0.7rem;padding:2px 6px;background:#6c757d;color:#fff;border-radius:3px;margin-right:4px;';

        const nameSpan = document.createElement('span');
        nameSpan.textContent = (att.fileName || att.filename || 'attachment').toString();

        chip.appendChild(badge);
        chip.appendChild(nameSpan);
        preview.appendChild(chip);
    });
}

window.rcRenderForwardAttachmentPreview = renderForwardAttachmentPreview;

function renderAttachmentsHtml(attachments, options = {}) {
    const normalizedAttachments = options.preNormalized
        ? (Array.isArray(attachments) ? attachments : [])
        : normalizeAttachments(attachments, options);
    if (!normalizedAttachments.length) return '';
    let html = '<div class="rc-chat-attachments">';
    normalizedAttachments.forEach(att => {
        const fileName = (att.fileName || att.filename || 'attachment').toString();
        const contentType = (att.contentType || '').toString();
        const url = resolveAttachmentUrl(att);
        const downloadUrl = (att && att.download_url) ? String(att.download_url) : buildAttachmentDownloadUrl(url);
        const openUrl = buildAttachmentOpenUrl(downloadUrl, contentType, fileName);
        const openTarget = openUrl.startsWith('ms-') ? '_self' : '_blank';
        if (!url) {
            html += `<div class="text-muted small">${escapeHtml(fileName)}</div>`;
            return;
        }
        const attachmentName = `<span class="rc-attachment-name" title="${escapeHtml(fileName)}">${escapeHtml(fileName)}</span>`;
        const downloadBtn = `<a class="rc-attachment-download-btn" href="${escapeHtml(downloadUrl)}" target="_blank" rel="noopener" download title="Download" aria-label="Download"><i class="fa fa-download" aria-hidden="true"></i></a>`;
        const commonAttrs = `class="rc-attachment-item rc-attachment-trigger" role="button" tabindex="0" data-content-type="${escapeHtml(contentType)}" data-open-url="${escapeHtml(openUrl)}" data-open-target="${escapeHtml(openTarget)}" data-viewer-src="${escapeHtml(url)}" data-file-name="${escapeHtml(fileName)}" data-download-url="${escapeHtml(downloadUrl)}"`;
        if (contentType.startsWith('image/')) {
            html += `<div ${commonAttrs}><div class="rc-attachment-meta"><img src="${escapeHtml(url)}" alt="${escapeHtml(fileName)}" style="width:360px;border-radius:6px;cursor:pointer;margin-bottom:6px;" />${attachmentName}<span class="rc-attachment-actions">${downloadBtn}</span></div></div>`;
        } else if (contentType.startsWith('audio/')) {
            html += `<div ${commonAttrs}><div class="rc-attachment-meta"><audio controls src="${escapeHtml(url)}"></audio>${attachmentName}<span class="rc-attachment-actions">${downloadBtn}</span></div></div>`;
        } else if (contentType.startsWith('video/')) {
            html += `<div ${commonAttrs}><div class="rc-attachment-meta"><video controls style="max-width:220px;" src="${escapeHtml(url)}"></video>${attachmentName}<span class="rc-attachment-actions">${downloadBtn}</span></div></div>`;
        } else {
            html += `<div ${commonAttrs}><div class="rc-attachment-meta"><div class="rc-attachment-file-label">${escapeHtml(fileName)}</div>${attachmentName}<span class="rc-attachment-actions">${downloadBtn}</span></div></div>`;
        }
    });
    html += '</div>';
    return html;
}

function resolveAttachmentUrl(attachment) {
    if (!attachment || typeof attachment !== 'object') return '';

    let routeBase = '/api/r/attachment';
    try {
        if (typeof rcRoute === 'function') {
            routeBase = rcRoute('ringcentral.api.attachment');
        } else if (window.RC_ROUTES && window.RC_ROUTES['ringcentral.api.attachment']) {
            routeBase = window.RC_ROUTES['ringcentral.api.attachment'];
        }
    } catch (_) {
        routeBase = '/api/r/attachment';
    }
    const pickStoredPath = (value) => {
        const raw = (value || '').toString().trim().replace(/^\/+/, '');
        if (!raw) return '';
        if (raw.startsWith('storage/')) return raw.slice('storage/'.length);
        if (raw.startsWith('ringcentral_attachments/')) return raw;
        return '';
    };

    const explicitPath = pickStoredPath(attachment.path || attachment.stored_path);
    if (explicitPath) {
        return `${routeBase}?path=${encodeURIComponent(explicitPath)}`;
    }

    const localPath = (attachment.local_path || '').toString().trim();
    if (localPath) {
        const storedPath = pickStoredPath(localPath);
        if (storedPath) {
            return `${routeBase}?path=${encodeURIComponent(storedPath)}`;
        }
        return localPath;
    }

    const directUrl = (attachment.url || '').toString().trim();
    if (directUrl) {
        const storedPath = pickStoredPath(directUrl);
        if (storedPath) {
            return `${routeBase}?path=${encodeURIComponent(storedPath)}`;
        }
        return directUrl;
    }

    const contentUri = (attachment.uri || attachment.contentUri || '').toString().trim();
    if (contentUri) {
        try {
            return `${routeBase}?uri=${encodeURIComponent(btoa(contentUri))}`;
        } catch (_) {
            return '';
        }
    }

    return '';
}

function buildAttachmentDownloadUrl(url) {
    const raw = (url || '').toString().trim();
    if (!raw) return '';
    if (raw.startsWith('blob:') || raw.startsWith('data:')) return raw;
    try {
        const parsed = new URL(raw, window.location.origin);
        parsed.searchParams.set('download', '1');
        return parsed.toString();
    } catch (_) {
        return raw;
    }
}

function buildAttachmentOpenUrl(url, contentType = '', fileName = '') {
    const raw = (url || '').toString().trim();
    if (!raw) return '';
    if (raw.startsWith('blob:') || raw.startsWith('data:')) return raw;

    const name = (fileName || '').toString().toLowerCase();
    const type = (contentType || '').toString().toLowerCase();
    const officeUrl = encodeURI(raw);

    const isWord = type === 'application/msword'
        || type === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        || name.endsWith('.doc')
        || name.endsWith('.docx');
    if (isWord) return `ms-word:ofe|u|${officeUrl}`;

    const isExcel = type === 'application/vnd.ms-excel'
        || type === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        || name.endsWith('.xls')
        || name.endsWith('.xlsx')
        || name.endsWith('.csv');
    if (isExcel) return `ms-excel:ofe|u|${officeUrl}`;

    const isPowerPoint = type === 'application/vnd.ms-powerpoint'
        || type === 'application/vnd.openxmlformats-officedocument.presentationml.presentation'
        || name.endsWith('.ppt')
        || name.endsWith('.pptx');
    if (isPowerPoint) return `ms-powerpoint:ofe|u|${officeUrl}`;

    return raw;
}

function ensureAttachmentViewer() {
    let viewer = document.getElementById('rcAttachmentViewer');
    if (viewer) return viewer;

    const wrapper = document.createElement('div');
    wrapper.id = 'rcAttachmentViewer';
    wrapper.className = 'rc-attachment-viewer d-none';
    wrapper.setAttribute('aria-hidden', 'true');
    wrapper.innerHTML = `
        <div class="rc-attachment-viewer-bar">
            <div id="rcAttachmentViewerTitle" class="rc-attachment-viewer-title">Attachment</div>
            <div class="rc-attachment-viewer-actions">
                <a id="rcAttachmentViewerDownload" class="rc-attachment-viewer-action" href="#" target="_blank" rel="noopener" download title="Download">
                    <i class="fa fa-download"></i>
                </a>
                <button id="rcAttachmentViewerClose" type="button" class="rc-attachment-viewer-action" title="Close">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
        <div id="rcAttachmentViewerStage" class="rc-attachment-viewer-stage">
            <img id="rcAttachmentViewerImage" class="rc-attachment-viewer-image" alt="attachment preview" />
        </div>
        <div class="rc-attachment-viewer-zoom">
            <button id="rcAttachmentViewerZoomOut" type="button">-</button>
            <span id="rcAttachmentViewerZoomValue">100%</span>
            <button id="rcAttachmentViewerZoomIn" type="button">+</button>
        </div>
    `;
    document.body.appendChild(wrapper);
    viewer = wrapper;

    const closeBtn = document.getElementById('rcAttachmentViewerClose');
    const stage = document.getElementById('rcAttachmentViewerStage');
    const zoomIn = document.getElementById('rcAttachmentViewerZoomIn');
    const zoomOut = document.getElementById('rcAttachmentViewerZoomOut');
    const image = document.getElementById('rcAttachmentViewerImage');

    if (closeBtn) closeBtn.addEventListener('click', closeAttachmentViewer);
    if (stage) {
        stage.addEventListener('click', (e) => {
            if (e.target === stage) closeAttachmentViewer();
        });
    }
    if (zoomIn) zoomIn.addEventListener('click', () => setAttachmentViewerScale((window._rc_attachmentViewerScale || 1) + 0.1));
    if (zoomOut) zoomOut.addEventListener('click', () => setAttachmentViewerScale((window._rc_attachmentViewerScale || 1) - 0.1));
    if (image) {
        image.addEventListener('wheel', (e) => {
            e.preventDefault();
            const delta = e.deltaY > 0 ? -0.1 : 0.1;
            setAttachmentViewerScale((window._rc_attachmentViewerScale || 1) + delta);
        }, { passive: false });
    }

    return viewer;
}

function setAttachmentViewerScale(scale) {
    const image = document.getElementById('rcAttachmentViewerImage');
    const zoomValue = document.getElementById('rcAttachmentViewerZoomValue');
    if (!image || !zoomValue) return;

    const next = Math.max(0.2, Math.min(5, Number(scale) || 1));
    window._rc_attachmentViewerScale = next;
    image.style.transform = `scale(${next})`;
    zoomValue.textContent = `${Math.round(next * 100)}%`;
}

function openAttachmentViewer(src, fileName, downloadUrl) {
    if (!src) return;
    const viewer = ensureAttachmentViewer();
    const image = document.getElementById('rcAttachmentViewerImage');
    const title = document.getElementById('rcAttachmentViewerTitle');
    const downloadLink = document.getElementById('rcAttachmentViewerDownload');
    if (!viewer || !image || !title || !downloadLink) return;

    image.src = src;
    image.alt = fileName || 'attachment';
    title.textContent = fileName || 'Attachment';
    downloadLink.href = downloadUrl || src;

    setAttachmentViewerScale(1);
    viewer.classList.remove('d-none');
    viewer.setAttribute('aria-hidden', 'false');
    document.body.classList.add('rc-attachment-viewer-open');
}

function closeAttachmentViewer() {
    const viewer = document.getElementById('rcAttachmentViewer');
    if (!viewer) return;
    viewer.classList.add('d-none');
    viewer.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('rc-attachment-viewer-open');
}

function setupAttachmentInteractions() {
    if (window._rc_attachmentInteractionsBound === '1') return;
    window._rc_attachmentInteractionsBound = '1';

    ensureAttachmentViewer();

    document.addEventListener('click', function (e) {
        try {
            const downloadBtn = e.target && e.target.closest ? e.target.closest('.rc-attachment-download-btn') : null;
            if (downloadBtn) {
                e.stopPropagation();
                return;
            }

            const trigger = e.target && e.target.closest ? e.target.closest('.rc-attachment-trigger') : null;
            if (!trigger) return;

            if (e.target && e.target.closest && e.target.closest('audio,video')) return;

            const type = (trigger.getAttribute('data-content-type') || '').toLowerCase();
            const openUrl = trigger.getAttribute('data-open-url') || '';
            const openTarget = trigger.getAttribute('data-open-target') || '_blank';
            const viewerSrc = trigger.getAttribute('data-viewer-src') || openUrl;
            const fileName = trigger.getAttribute('data-file-name') || 'attachment';
            const downloadUrl = trigger.getAttribute('data-download-url') || openUrl;

            if (!openUrl && !viewerSrc) return;

            e.preventDefault();
            e.stopPropagation();

            if (type.startsWith('image/')) {
                openAttachmentViewer(viewerSrc || openUrl, fileName, downloadUrl);
                return;
            }

            if ((openUrl || '').startsWith('ms-')) {
                window.location.href = openUrl;
                return;
            }

            window.open(openUrl, openTarget, 'noopener');
        } catch (_) { }
    });

    document.addEventListener('keydown', function (e) {
        const focusedTrigger = document.activeElement && document.activeElement.classList && document.activeElement.classList.contains('rc-attachment-trigger')
            ? document.activeElement
            : null;
        if (focusedTrigger && (e.key === 'Enter' || e.key === ' ')) {
            e.preventDefault();
            focusedTrigger.click();
            return;
        }

        if (e.key === 'Escape') {
            closeAttachmentViewer();
        }
    });
}

function escapeHtml(value) {
    if (value === null || value === undefined) return '';
    return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function linkify(text) {
    if (!text) return '';
    return String(text).replace(/(https?:\/\/[^\s]+)/g, function(url) {
        const escaped = escapeHtml(url);
        return '<a href="' + escaped + '" target="_blank" rel="noopener noreferrer">' + escaped + '</a>';
    });
}

function formatTemplatePreview(text) {
    if (text === null || text === undefined) return '';

    const escaped = escapeHtml(text);
    const linked = linkify(escaped);
    return linked.replace(/\n/g, '<br>');
}

function setupEmojiPicker() {
    const picker = document.getElementById('rcEmojiPicker');
    const btn = document.getElementById('rcEmojiBtn');
    const input = document.getElementById('chatMessageInputRC');
    if (!picker || !btn || !input) return;

    const btnWrapper = btn.parentElement;
    if (btnWrapper) {
        btnWrapper.style.position = btnWrapper.style.position || 'relative';
        if (picker.parentNode === btnWrapper && picker.nextSibling !== btn) {
            btnWrapper.insertBefore(picker, btn);
        }
    }

    const emojis = ['😀','😁','😂','🤣','😊','😍','😎','😢','😡','👍','👎','🙏','🎉','🔥','💯','❤️','✅','🚀','📎','📞','📌','⭐','✨','🙌','🤝','🤔'];
    picker.innerHTML = '';
    emojis.forEach(e => {
        const b = document.createElement('button');
        b.type = 'button';
        b.textContent = e;
        b.addEventListener('click', () => {
            input.value = input.value + e;
            input.focus();
            picker.classList.add('d-none');
            picker.style.display = '';
        });
        picker.appendChild(b);
    });

    let isOpen = false;
    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        isOpen = !isOpen;
        picker.classList.toggle('d-none', !isOpen);
        picker.style.display = isOpen ? 'grid' : 'none';
        if (isOpen) {
            picker.style.position = 'absolute';
            picker.style.left = '0';
            picker.style.bottom = 'auto';
            picker.style.top = '55%';
        }
    });

    // Close when clicking outside
    const hidePicker = (e) => {
        if (!picker.contains(e.target) && !btn.contains(e.target)) {
            isOpen = false;
            picker.classList.add('d-none');
            picker.style.display = 'none';
        }
    };

    document.removeEventListener('click', hidePicker); // prevent duplication
    document.addEventListener('click', hidePicker);
}

function refreshTemplateControls() {
    if (window._rc_templateRefreshInProgress) {
        return;
    }
    window._rc_templateRefreshInProgress = true;

    const rcTemplateSelect = document.getElementById('rcTemplateSelect');
    const rcTemplateSelectModal = document.getElementById('rcTemplateSelectModal');
    const rcTemplateList = document.getElementById('rcTemplateList');
    if (!rcTemplateSelect && !rcTemplateSelectModal && !rcTemplateList) {
        window._rc_templateRefreshInProgress = false;
        return;
    }

    // clear existing
    if (rcTemplateSelect) {
        rcTemplateSelect.innerHTML = '<option value="">Templates</option>';
    }
    if (rcTemplateSelectModal) {
        rcTemplateSelectModal.innerHTML = '<option value="">Templates</option>';
    }
    if (rcTemplateList) {
        rcTemplateList.innerHTML = '<div class="text-muted small">Loading templates…</div>';
    }

    fetch(rcRoute('ringcentral.api.templates'))
        .then(r => r.json())
        .then(data => {
            const templates = data.templates || [];
            if (!templates.length) {
                rcTemplateList.innerHTML = '<div class="text-muted small">No templates yet.</div>';
                return;
            }

            rcTemplateList.innerHTML = '';
            templates.forEach(t => {
                const opt = document.createElement('option');
                opt.value = t.id;
                opt.textContent = t.name;
                opt.setAttribute('data-description', t.description || '');
                if (rcTemplateSelect) {
                    rcTemplateSelect.appendChild(opt);
                }

                if (rcTemplateSelectModal) {
                    const opt2 = opt.cloneNode(true);
                    rcTemplateSelectModal.appendChild(opt2);
                }

                if (rcTemplateList) {
                    const item = document.createElement('div');
                    item.className = 'rc-template-item';
                    const preview = formatTemplatePreview(t.description || '');
                    item.innerHTML = `
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="rc-template-name">${escapeHtml(t.name)}</div>
                                <div class="rc-template-desc" style="white-space: pre-wrap; word-break: break-word;">${preview}</div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary rc-template-apply">Apply</button>
                        </div>
                    `;

                    const applyBtn = item.querySelector('.rc-template-apply');
                    if (applyBtn) {
                        applyBtn.addEventListener('click', (e) => {
                            e.stopPropagation();
                            if (rcTemplateSelect) {
                                rcTemplateSelect.value = t.id;
                            }
                            const chatInput = document.getElementById('chatMessageInputRC');
                            if (chatInput) {
                                chatInput.value = t.description || '';
                                chatInput.focus();
                            }
                            const smsInput = document.getElementById('smsMessage');
                            if (smsInput) {
                                smsInput.value = t.description || '';
                            }
                            const templateModal = document.getElementById('rcTemplateModal');
                            if (templateModal) {
                                $(templateModal).modal('hide');
                            }
                        });
                    }

                    item.addEventListener('click', () => {
                        if (rcTemplateSelect) {
                            rcTemplateSelect.value = t.id;
                        }
                        const chatInput = document.getElementById('chatMessageInputRC');
                        if (chatInput) {
                            chatInput.value = t.description || '';
                            chatInput.focus();
                        }
                        const smsInput = document.getElementById('smsMessage');
                        if (smsInput) {
                            smsInput.value = t.description || '';
                        }
                        const templateModal = document.getElementById('rcTemplateModal');
                        if (templateModal) {
                            $(templateModal).modal('hide');
                        }
                    });
                    rcTemplateList.appendChild(item);
                }
            });
        })
        .catch(() => {
            rcTemplateList.innerHTML = '<div class="text-danger small">Unable to load templates.</div>';
        })
        .finally(() => {
            window._rc_templateRefreshInProgress = false;
        });

    function bindSelect(s) {
        if (!s) return;
        s.addEventListener('change', () => {
            const selected = s.options[s.selectedIndex];
            const desc = selected ? selected.getAttribute('data-description') : '';
            if (desc) {
                const targetField = s.id === 'rcTemplateSelect' ? document.getElementById('chatMessageInputRC') : document.getElementById('smsMessage');
                if (targetField) {
                    targetField.value = desc;
                    targetField.focus();
                }
            }
        });
    }

    bindSelect(rcTemplateSelect);
    bindSelect(rcTemplateSelectModal);
}

function setupTemplateEditor() {
    const createBtn = document.getElementById('rcCreateTemplateBtn');
    const modal = document.getElementById('rcCreateTemplateModal');
    const saveBtn = document.getElementById('rcSaveTemplateBtn');
    const form = document.getElementById('rcCreateTemplateForm');
    const nameInput = document.getElementById('rcTemplateName');
    const descInput = document.getElementById('rcTemplateDescription');
    const errorDiv = document.getElementById('rcCreateTemplateError');
    const templateModal = document.getElementById('rcTemplateModal');

    if (!createBtn || !modal || !saveBtn || !form || !nameInput || !descInput || !errorDiv) return;

    const modalSelectorGroup = document.getElementById('rcTemplateSelectModalGroup');
    if (modalSelectorGroup) {
        modalSelectorGroup.style.display = 'none';
    }

    if (!createBtn || !modal || !saveBtn || !form || !nameInput || !descInput || !errorDiv) return;

    $(createBtn).on('click', () => {
        nameInput.value = '';
        descInput.value = '';
        errorDiv.style.display = 'none';
        $(modal).modal('show');
        $(templateModal).modal('hide');
    });

    let isCreatingTemplate = false;

    // Remove any previous click handlers to prevent double API call
    saveBtn.replaceWith(saveBtn.cloneNode(true));
    const newSaveBtn = document.getElementById('rcSaveTemplateBtn');
    newSaveBtn.addEventListener('click', () => {
        if (isCreatingTemplate) {
            return;
        }

        errorDiv.style.display = 'none';
        const name = nameInput.value.trim();
        const description = descInput.value.trim();
        if (!name || !description) {
            errorDiv.textContent = 'Name and text are required.';
            errorDiv.style.display = 'block';
            return;
        }

        isCreatingTemplate = true;
        const restoreSaveTemplateBtn = window.rcSetActionButtonLoading
            ? window.rcSetActionButtonLoading(newSaveBtn, { loadingText: 'Saving...', statusText: 'Saving template...' })
            : function () {
                newSaveBtn.disabled = false;
            };

        fetch(rcRoute('ringcentral.api.templates.create'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({ name, description })
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) {
                errorDiv.textContent = data.message || 'Cannot create template';
                errorDiv.style.display = 'block';
                return;
            }
            $(modal).modal('hide');
            refreshTemplateControls();
        })
        .catch(() => {
            errorDiv.textContent = 'Template creation failed. Please try again.';
            errorDiv.style.display = 'block';
        })
        .finally(() => {
            isCreatingTemplate = false;
            restoreSaveTemplateBtn();
        });
    });
}

function setupTemplateModal() {
    const useBtn = document.getElementById('rcUseTemplateBtn');
    const modal = document.getElementById('rcTemplateModal');
    if (!useBtn || !modal) return;

    useBtn.addEventListener('click', () => {
        refreshTemplateControls();
        $(modal).modal('show');
    });
}

function setupAttachmentPicker() {
    const btn = document.getElementById('rcAttachBtn');
    const input = document.getElementById('rcChatAttachmentInput');
    const preview = document.getElementById('rcChatAttachmentPreview');
    if (!btn || !input || !preview) return;

    if (btn.dataset.rcAttachmentBound === '1' || input.dataset.rcAttachmentBound === '1') {
        return;
    }
    btn.dataset.rcAttachmentBound = '1';
    input.dataset.rcAttachmentBound = '1';

    window._rc_pendingAttachments = [];

    const maxUploadBytes = 50 * 1024 * 1024;
    const mmsAttachmentMax = 1500000;
    const mmsTypes = ['image/', 'video/', 'audio/', 'application/pdf'];
    const isAllowedFile = (file) => {
        if (!file || !file.type) return false;
        if (typeof file.size === 'number' && file.size > maxUploadBytes) return false;
        return true;
    };

    const isMmsCompatible = (file) => {
        if (!file || !file.type) return false;
        const typeOk = mmsTypes.some(prefix => file.type.startsWith(prefix));
        const sizeOk = typeof file.size === 'number' ? file.size <= mmsAttachmentMax : false;
        return typeOk && sizeOk;
    };

    const getMimeLabel = (type) => {
        if (!type) return 'unknown';
        if (type.startsWith('image/')) return 'image';
        if (type.startsWith('video/')) return 'video';
        if (type.startsWith('audio/')) return 'audio';
        if (type === 'application/pdf') return 'pdf';
        return 'file';
    };

    function renderPreview() {
        preview.innerHTML = '';
        window._rc_pendingAttachments.forEach((file, idx) => {
            const chip = document.createElement('div');
            chip.className = 'rc-attachment-chip';

            const label = isMmsCompatible(file) ? getMimeLabel(file.type) : 'link';
            const badge = document.createElement('span');
            badge.className = 'rc-attachment-badge';
            badge.textContent = label;
            badge.style.cssText = 'display:inline-block;font-size:0.7rem;padding:2px 6px;background:#0d6efd;color:#fff;border-radius:3px;margin-right:4px;';

            const nameSpan = document.createElement('span');
            nameSpan.textContent = escapeHtml(file.name || 'attachment');

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.textContent = '×';
            removeBtn.style.cssText = 'border:none;background:none;cursor:pointer;color:#dc3545;font-weight:bold;margin-left:6px;padding:0;';

            removeBtn.addEventListener('click', () => {
                window._rc_pendingAttachments.splice(idx, 1);
                renderPreview();
            });

            chip.appendChild(badge);
            chip.appendChild(nameSpan);
            chip.appendChild(removeBtn);
            preview.appendChild(chip);
        });
        renderForwardAttachmentPreview();
    }

    btn.addEventListener('click', (e) => {
        e.preventDefault();
        input.value = '';
        input.click();
    });

    const getFileKey = (file) => `${file.name}|${file.size}|${file.lastModified}`;

    input.addEventListener('change', () => {
        const files = Array.from(input.files || []);

        if (files.length > 0) {
            const validFiles = [];
            const rejectedFiles = [];

            files.forEach(file => {
                if (isAllowedFile(file)) {
                    validFiles.push(file);
                } else {
                    rejectedFiles.push(file.name);
                }
            });

            if (rejectedFiles.length > 0) {
                alert(`File(s) rejected (too large). Max size per file is 50MB:\n\n${rejectedFiles.join(', ')}`);
            }

            if (validFiles.length > 0) {
                const existingKeys = new Set(window._rc_pendingAttachments.map(getFileKey));
                const dedupedFiles = validFiles.filter(file => !existingKeys.has(getFileKey(file)));
                window._rc_pendingAttachments = window._rc_pendingAttachments.concat(dedupedFiles);
                renderPreview();
            }
        }

        input.value = '';
    });
}

function getMessagesTypeFilterValue() {
    const el = document.getElementById('tabMessagesTypeFilter');
    const value = (el && el.value) ? String(el.value).trim().toLowerCase() : 'all';
    if (value === 'unread' || value === 'muted' || value === 'favourites' || value === 'draft' || value === 'failed') return value;
    return 'all';
}

// Backward-compatible alias for older references.
function getMessagesDirectionFilterValue() {
    return getMessagesTypeFilterValue();
}

function syncMessagesFilterUi(type) {
    const allBtn = document.getElementById('tabMessagesAllBtn');
    const filterEl = document.getElementById('tabMessagesTypeFilter');
    const filterBtn = document.getElementById('tabMessagesFilterBtn');
    const filterBtnText = document.getElementById('tabMessagesFilterBtnText');
    const filterMenu = document.getElementById('tabMessagesFilterMenu');
    const activeType = (type || 'all').toLowerCase();
    const isAll = activeType === 'all';
    const filterLabels = {
        unread: 'Unread',
        muted: 'Muted',
        favourites: 'Favorites',
        draft: 'Draft',
        failed: 'Failed'
    };

    if (allBtn) {
        allBtn.classList.add('rc-filter-btn');
        allBtn.classList.toggle('is-active', isAll);
    }
    if (filterEl) {
        filterEl.classList.add('rc-filter-select');
        if (isAll && filterEl.value) {
            filterEl.value = '';
        } else if (activeType !== 'all' && filterEl.value !== activeType) {
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
}

function bindMessagesTypeFilterControl() {
    const filterEl = document.getElementById('tabMessagesTypeFilter');
    const allBtn = document.getElementById('tabMessagesAllBtn');
    const filterBtn = document.getElementById('tabMessagesFilterBtn');
    const filterMenu = document.getElementById('tabMessagesFilterMenu');
    const filterDropdown = document.getElementById('tabMessagesFilterDropdown');

    function closeMessagesFilterMenu() {
        if (!filterMenu) return;
        filterMenu.classList.add('d-none');
        if (filterBtn) {
            filterBtn.classList.remove('is-open');
            filterBtn.setAttribute('aria-expanded', 'false');
        }
    }

    function openMessagesFilterMenu() {
        if (!filterMenu) return;
        filterMenu.classList.remove('d-none');
        if (filterBtn) {
            filterBtn.classList.add('is-open');
            filterBtn.setAttribute('aria-expanded', 'true');
        }
    }

    function applyMessagesFilter(typeValue) {
        const normalized = (typeValue || '').toString().trim().toLowerCase();
        if (filterEl) {
            filterEl.value = normalized === 'all' ? '' : normalized;
        }
        syncMessagesFilterUi(normalized || 'all');
        loadMessageHistory(null, false, null, false);
    }

    if (filterEl && filterEl.dataset.rcBound !== '1') {
        filterEl.dataset.rcBound = '1';
        filterEl.addEventListener('change', function () {
            syncMessagesFilterUi(getMessagesTypeFilterValue());
            loadMessageHistory(null, false, null, false);
        });
    }

    if (allBtn && allBtn.dataset.rcBound !== '1') {
        allBtn.dataset.rcBound = '1';
        allBtn.addEventListener('click', function () {
            closeMessagesFilterMenu();
            applyMessagesFilter('all');
        });
    }

    if (filterBtn && filterBtn.dataset.rcBound !== '1') {
        filterBtn.dataset.rcBound = '1';
        filterBtn.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            if (!filterMenu) return;
            if (filterMenu.classList.contains('d-none')) {
                openMessagesFilterMenu();
            } else {
                closeMessagesFilterMenu();
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
            closeMessagesFilterMenu();
            applyMessagesFilter(selected || 'all');
        });
    }

    if (filterDropdown && filterDropdown.dataset.rcOutsideBound !== '1') {
        filterDropdown.dataset.rcOutsideBound = '1';
        document.addEventListener('click', function (event) {
            if (!filterDropdown.contains(event.target)) {
                closeMessagesFilterMenu();
            }
        });
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeMessagesFilterMenu();
            }
        });
    }

    syncMessagesFilterUi(getMessagesTypeFilterValue());
}

function getMessagesSearchValue() {
    const searchEl = document.getElementById('tabMessagesSearch');
    return (searchEl && searchEl.value) ? searchEl.value.trim() : '';
}

function getMessagesWebhookSyncCount() {
    const configured = parseInt(window.RC_WEBHOOK_SYNC_COUNT, 10);
    if (Number.isFinite(configured) && configured > 0) return Math.min(100, configured);
    return Math.max(5, Math.min(50, parseInt(window.RC_REFRESH_SYNC_COUNT, 10) || 20));
}

function rcMsgIsUnread(message) {
    try {
        const status = (message.readStatus || message.status || '').toString().toLowerCase();
        return status === 'unread';
    } catch (_) {
        return false;
    }
}

function rcMsgExtractFromNumber(message) {
    try {
        const from = message && message.from && typeof message.from === 'object' ? message.from : {};
        const candidates = [from.phoneNumber, from.phone, from.number, message.fromPhone, message.fromNumber, message.from_number];
        for (let i = 0; i < candidates.length; i++) {
            const digits = String(candidates[i] || '').replace(/\D/g, '');
            if (digits) return digits;
        }
    } catch (_) { }
    return '';
}

function rcMsgParsePhoneCandidates(value) {
    const raw = (value || '').toString();
    if (!raw) return [];
    const parts = raw.split(/[;,]+/g).map(v => v.trim()).filter(Boolean);
    const normalized = [];
    parts.forEach((part) => {
        const digits = part.replace(/\D/g, '');
        if (digits && !normalized.includes(digits)) normalized.push(digits);
    });
    return normalized;
}

function rcMsgExtractToNumbers(message) {
    try {
        const numbers = [];
        const addCandidate = (candidate) => {
            rcMsgParsePhoneCandidates(candidate).forEach((digits) => {
                if (!numbers.includes(digits)) numbers.push(digits);
            });
        };

        if (message && Array.isArray(message.to) && message.to.length) {
            message.to.forEach((entry) => {
                const t = (entry && typeof entry === 'object') ? entry : {};
                addCandidate(t.phoneNumber);
                addCandidate(t.phone);
                addCandidate(t.number);
                addCandidate(t.value);
            });
        }
        addCandidate(message && message.toPhone);
        addCandidate(message && message.toNumber);
        addCandidate(message && message.to_number);
        return numbers;
    } catch (_) { }
    return [];
}

function rcMsgExtractToNumber(message) {
    const list = rcMsgExtractToNumbers(message);
    return list[0] || '';
}

function rcMsgCreateThreadKey(message) {
    const from = rcMsgExtractFromNumber(message);
    const toNumbers = rcMsgExtractToNumbers(message);
    const to = toNumbers[0] || '';

    const participants = [];
    if (from) participants.push(from);
    toNumbers.forEach((digits) => {
        if (digits && !participants.includes(digits)) participants.push(digits);
    });
    if (participants.length >= 3 || toNumbers.length > 1) {
        return 'grp:' + participants.sort().join('|');
    }
    if (from && to) {
        return [from, to].sort().join('|');
    }

    const userNum = getRcUserPhoneDigits();
    const candidate = from && from !== userNum
        ? from
        : (to && to !== userNum ? to : (from || to));
    if (candidate) return candidate;

    const fallback = message && (message.threadId || message.conversationId || message.id || message.message_id);
    return (fallback || ('unknown_' + Math.random().toString(36).slice(2, 8))).toString();
}

function rcMsgBuildThreads(messages) {
    const threads = {};
    const source = Array.isArray(messages) ? messages : [];
    source.forEach(message => {
        const key = rcMsgCreateThreadKey(message);
        if (!threads[key]) threads[key] = [];
        threads[key].push(message);
    });
    Object.keys(threads).forEach(key => {
        threads[key].sort((a, b) => new Date(b.creationTime || 0) - new Date(a.creationTime || 0));
    });
    return threads;
}

function rcMsgTimestampValue(value) {
    const ts = new Date(value || 0).getTime();
    return Number.isFinite(ts) ? ts : 0;
}

function rcMsgResolveThreadDigits(threadKey, latestMessage) {
    const key = String(threadKey || '');
    const userNum = getRcUserPhoneDigits();
    if (key.startsWith('grp:')) {
        return '';
    }
    if (!key.includes('|')) {
        return key.replace(/\D/g, '') || key;
    }

    const parts = key.split('|').filter(Boolean);
    if (parts.length === 2) {
        if (userNum) {
            return parts[0] === userNum ? parts[1] : parts[0];
        }
        const latestDirection = (latestMessage && latestMessage.direction ? String(latestMessage.direction) : '').toLowerCase();
        const latestFrom = rcMsgExtractFromNumber(latestMessage || {});
        const latestTo = rcMsgExtractToNumber(latestMessage || {});
        if (latestDirection.includes('out') && latestTo) return latestTo;
        if (latestDirection.includes('in') && latestFrom) return latestFrom;
        if (latestFrom && parts.includes(latestFrom)) return latestFrom;
        if (latestTo && parts.includes(latestTo)) return latestTo;
        return parts[0];
    }
    return key.replace(/\D/g, '') || key;
}

function rcMsgBuildThreadViewModel(threadKey, messages) {
    const msgs = Array.isArray(messages) ? messages : [];
    const latest = msgs[0] || {};
    const rawPreview = (latest.subject || latest.text || latest.body || '').toString().trim();
    const hasAttachments = Array.isArray(latest.attachments) && latest.attachments.length > 0;
    const preview = rawPreview ? rawPreview.slice(0, 80) : (hasAttachments ? 'Attachment' : '(empty message)');
    const displayTime = formatMessageTime(latest.creationTime);
    const unreadCount = msgs.reduce((acc, m) => acc + (rcMsgIsUnread(m) ? 1 : 0), 0);
    const isGroupThread = String(threadKey || '').startsWith('grp:');
    const threadDigits = rcMsgResolveThreadDigits(threadKey, latest);
    const groupMembers = isGroupThread
        ? String(threadKey || '').slice(4).split('|').filter(Boolean)
        : [];
    const userNum = getRcUserPhoneDigits();
    const groupPeers = groupMembers.filter(v => !userNum || v !== userNum);

    let displayLabel = (threadDigits && threadDigits.length)
        ? ((typeof maskPhoneNumber === 'function') ? maskPhoneNumber(threadDigits) : ('+' + threadDigits))
        : threadKey;
    if (isGroupThread) {
        const sample = groupPeers.slice(0, 2).map((digits) => {
            return (typeof maskPhoneNumber === 'function') ? maskPhoneNumber(digits) : ('+' + digits);
        }).join(', ');
        displayLabel = `Group (${groupPeers.length || groupMembers.length})`;
        if (sample) displayLabel += ` • ${sample}`;
    }

    const digitsNorm = (threadDigits || '').toString().replace(/\D/g, '');
    if (userNum && digitsNorm && userNum === digitsNorm) {
        displayLabel = displayLabel + ' (me)';
    }

    const canCall = !isGroupThread && !!threadDigits && /\d/.test(threadDigits.toString());
    const initials = isGroupThread
        ? 'GR'
        : (((threadDigits || '').toString().replace(/\D/g, '').slice(-2) || '##').toUpperCase());

    return {
        key: threadKey,
        latest,
        preview,
        displayTime,
        unreadCount,
        threadDigits,
        displayLabel,
        canCall,
        initials
    };
}

function rcMsgRenderThreadRow(model) {
    return `<a href="#"
                class="list-group-item list-group-item-action message-item p-3 border rounded-3 mb-2${model.unreadCount > 0 ? ' rc-thread-unread' : ''}"
                data-user="${escapeHtml(model.key)}">
                <div class="d-flex align-items-center py-2">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:50px; height:50px; flex-shrink: 0; margin-right:10px;">
                        <span class="fw-bold">${escapeHtml(model.initials)}</span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="fw-semibold d-block${model.unreadCount > 0 ? ' fw-bold' : ''}">${escapeHtml(model.displayLabel)}</span>
                                <small class="text-muted d-block preview-text${model.unreadCount > 0 ? ' fw-bold' : ''}">${escapeHtml(model.preview)}</small>
                            </div>
                            <div>
                                <span class="text-muted small rc-msg-time">${escapeHtml(model.displayTime)}</span>
                                <div class="rc-msg-right text-end ms-2">
                                    ${model.canCall ? `
                                        <button type="button"
                                            class="phone-ui-btn rc-message-action-call rc-call-icon"
                                            data-phone="${escapeHtml(model.threadDigits)}">
                                            <i class="fa fa-phone"></i>
                                        </button>` : ''}
                                    ${model.unreadCount > 0 ? `
                                        <span class="badge bg-danger rc-unread-badge">${model.unreadCount}</span>` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>`;
}

function rcMsgFindThreadRow(listEl, threadKey) {
    if (!listEl) return null;
    const target = String(threadKey || '');
    const rows = listEl.querySelectorAll('a.message-item[data-user]');
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        if (String(row.getAttribute('data-user') || '') === target) {
            return row;
        }
    }
    return null;
}

function rcMsgSyncUnreadBadge() {
    try {
        const badge = document.getElementById('rcTextUnreadBadge');
        if (!badge) return;

        let totalUnread = null;
        if (window._rc_messagesUnreadTotal !== null && window._rc_messagesUnreadTotal !== undefined) {
            totalUnread = Math.max(0, parseInt(window._rc_messagesUnreadTotal, 10) || 0);
        } else {
            const store = window._rc_messageStore || [];
            totalUnread = store.reduce((acc, m) => acc + (rcMsgIsUnread(m) ? 1 : 0), 0);
        }

        badge.textContent = totalUnread > 99 ? '99+' : String(totalUnread);
        if (totalUnread > 0) {
            badge.classList.remove('d-none');
            badge.classList.remove('is-hidden');
        } else {
            badge.classList.add('d-none');
            badge.classList.add('is-hidden');
        }
    } catch (_) { }
}

function loadMessagesIncrementalFromWebhook() {
    window._rc_loadingMessages = window._rc_loadingMessages || false;
    if (window._rc_loadingMessages) {
        try {
            console.info('[RC Messages/Webhook] Incremental refresh skipped: already loading');
        } catch (_) { }
        return Promise.resolve();
    }

    window._rc_messageStore = Array.isArray(window._rc_messageStore) ? window._rc_messageStore : [];
    if (window._rc_messageIds instanceof Set) {
        // keep existing set
    } else {
        window._rc_messageIds = new Set((window._rc_messageStore || [])
            .map(m => m && (m.id || m.message_id || m.messageId || m._id))
            .filter(Boolean)
            .map(String));
    }

    const filterType = getMessagesTypeFilterValue();
    const q = getMessagesSearchValue();
    const supportsIncremental = !q && (filterType === 'all' || filterType === 'unread');
    try {
        console.info('[RC Messages/Webhook] Incremental refresh triggered', {
            storeCount: window._rc_messageStore.length,
            filterType: filterType,
            search: q || '',
            supportsIncremental: supportsIncremental
        });
    } catch (_) { }
    if (!supportsIncremental || window._rc_messageStore.length === 0) {
        try {
            console.info('[RC Messages/Webhook] Falling back to full loadMessageHistory', {
                reason: !supportsIncremental ? 'unsupported_filter_or_search' : 'empty_message_store'
            });
        } catch (_) { }
        return loadMessageHistory(null, false, null, false, false);
    }

    const perPage = getMessagesWebhookSyncCount();
    const params = new URLSearchParams({
        per_page: String(perPage),
        refresh: '1',
        count: String(perPage)
    });

    window._rc_loadingMessages = true;
    return fetch(rcRoute('ringcentral.api.messages') + '?' + params.toString(), {
        credentials: 'same-origin',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(r => r.json())
        .then(data => {
            const payload = Array.isArray(data) ? { data } : (data || {});
            const incomingMessages = Array.isArray(payload.data) ? payload.data : [];
            const summary = payload.summary || {};
            if (summary.total_available !== undefined && summary.total_available !== null) {
                window._rc_messagesTotalAvailable = parseInt(summary.total_available, 10) || 0;
            }
            if (summary.unread_total !== undefined && summary.unread_total !== null) {
                window._rc_messagesUnreadTotal = parseInt(summary.unread_total, 10) || 0;
            }

            const byId = new Map();
            (window._rc_messageStore || []).forEach(m => {
                const id = m && (m.id || m.message_id || m.messageId || m._id);
                if (id) byId.set(String(id), m);
            });

            const changedThreadKeys = new Set();
            let newMessagesCount = 0;
            incomingMessages.forEach(m => {
                const id = m && (m.id || m.message_id || m.messageId || m._id);
                const key = rcMsgCreateThreadKey(m);
                if (key) changedThreadKeys.add(key);
                if (!id) return;
                const sid = String(id);
                if (!byId.has(sid)) {
                    newMessagesCount++;
                }
                byId.set(sid, m);
                window._rc_messageIds.add(sid);
            });

            window._rc_messageStore = Array.from(byId.values()).sort((a, b) => rcMsgTimestampValue(b.creationTime) - rcMsgTimestampValue(a.creationTime));
            inferRcUserPhoneDigitsFromMessages(window._rc_messageStore);
            const threads = rcMsgBuildThreads(window._rc_messageStore);
            window._rc_messageThreads = threads;

            const listEl = document.getElementById('messagesList');
            if (listEl) {
                const placeholder = listEl.querySelector('.text-muted, p.text-danger');
                if (placeholder && listEl.children.length === 1) {
                    listEl.innerHTML = '';
                }

                const orderedKeys = Array.from(changedThreadKeys).sort((a, b) => {
                    const aTime = rcMsgTimestampValue(((threads[a] || [])[0] || {}).creationTime);
                    const bTime = rcMsgTimestampValue(((threads[b] || [])[0] || {}).creationTime);
                    return bTime - aTime;
                });

                // Inserting at `afterbegin` reverses iteration order; walk from oldest->newest
                // so newest ends up at the top.
                for (let idx = orderedKeys.length - 1; idx >= 0; idx -= 1) {
                    const key = orderedKeys[idx];
                    const msgs = threads[key] || [];
                    if (!msgs.length) continue;
                    const model = rcMsgBuildThreadViewModel(key, msgs);
                    const existing = rcMsgFindThreadRow(listEl, key);
                    const shouldShow = filterType === 'all' || model.unreadCount > 0;
                    if (!shouldShow) {
                        if (existing) existing.remove();
                        return;
                    }

                    if (model.threadDigits) {
                        window._rc_messageThreadKeyByDigits = window._rc_messageThreadKeyByDigits || {};
                        window._rc_messageThreadKeyByDigits[model.threadDigits] = key;
                    }

                    const rowHtml = rcMsgRenderThreadRow(model);
                    if (existing) {
                        existing.remove();
                        listEl.insertAdjacentHTML('afterbegin', rowHtml);
                    } else {
                        listEl.insertAdjacentHTML('afterbegin', rowHtml);
                    }
                }
            }
            try {
                console.info('[RC Messages/Webhook] Incremental refresh applied', {
                    incomingCount: incomingMessages.length,
                    changedThreads: changedThreadKeys.size,
                    newMessagesCount: newMessagesCount,
                    renderedRows: listEl ? listEl.querySelectorAll('a.message-item').length : null
                });
            } catch (_) { }

            const threadKeys = Object.keys(threads);
            let shownThreadsCount = 0;
            let unreadThreadsCount = 0;
            threadKeys.forEach(key => {
                const model = rcMsgBuildThreadViewModel(key, threads[key] || []);
                if (filterType === 'unread' && model.unreadCount <= 0) return;
                shownThreadsCount++;
                if (model.unreadCount > 0) unreadThreadsCount++;
            });
            renderMessagesTestingStats(shownThreadsCount, threadKeys.length, unreadThreadsCount);

            try {
                const chatView = document.getElementById('chatViewCard');
                const activeKey = window._rc_activeThreadKey;
                const isChatOpen = chatView && !chatView.classList.contains('d-none');
                if (isChatOpen && activeKey && threads[activeKey] && typeof showChatFor === 'function') {
                    showChatFor(activeKey, { forceScrollBottom: false, focusInput: false });
                }
            } catch (_) { }

            window._rc_messagesNotifyReady = true;
            rcMsgSyncUnreadBadge();
        })
        .catch(error => {
            console.warn('[RC Messages/Webhook] Incremental refresh failed', error);
        })
        .finally(() => {
            window._rc_loadingMessages = false;
        });
}

// Load message history and populate leftSection messagesList grouped by recipient
function loadMessageHistory(page = null, append = false, beforeCursor = null, forceRefresh = false, useInitialBeforeCursor = true, prependNew = false) {
    // prevent duplicate concurrent loads for messages
    window._rc_loadingMessages = window._rc_loadingMessages || false;
    if (window._rc_loadingMessages) {
        return Promise.resolve();
    }
    window._rc_loadingMessages = true;
    const searchEl = document.getElementById('tabMessagesSearch');
    const q = (searchEl && searchEl.value) ? searchEl.value.trim() : '';
    const defaultPageSize = Math.max(1, parseInt(window.RC_INITIAL_PAGE_SIZE, 10) || 50);
    const defaultRefreshCount = Math.max(1, parseInt(window.RC_REFRESH_SYNC_COUNT, 10) || 50);
    const shouldConsiderWebhookToast = !append && !q;
    const previousMessageIds = (window._rc_messageIds instanceof Set) ? new Set(window._rc_messageIds) : new Set();
    const canNotifyNewMessages = shouldConsiderWebhookToast && window._rc_messagesNotifyReady === true;
    const newMessageIds = new Set();
    let newMessagesCount = 0;
    const messageTypeFilter = getMessagesTypeFilterValue();
    const isPageLoadRefresh = !append && !beforeCursor;
    if (isPageLoadRefresh) {
        const loadingLabel = q ? 'Searching messages...' : 'Refreshing messages...';
        setMessagesLoading(true, loadingLabel);
    } else {
        setMessagesLoading(false);
    }
    const params = new URLSearchParams({
        per_page: String(defaultPageSize)
    });
    if (q) params.set('q', q);
    const initialBeforeCursor = (window.RC_MESSAGES_INITIAL_BEFORE_CURSOR || '').toString().trim();
    if (beforeCursor) {
        params.set('before', beforeCursor);
    } else if (forceRefresh && useInitialBeforeCursor && initialBeforeCursor) {
        params.set('before', initialBeforeCursor);
    } else if (forceRefresh) {
        params.set('refresh', '1');
        params.set('count', String(defaultRefreshCount));
    }
    return fetch(rcRoute('ringcentral.api.messages') + '?' + params.toString())
        .then(r => r.json())
        .then(data => {
            // Show toast if fallback to local DB due to timeout/limit
            if (data && data.fallback_to_local && data.fallback_message) {
                showRcToast(data.fallback_message, 'warning');
            }
            // Toast utility for RC messages
            function showRcToast(msg, type = 'info') {
                if (typeof window.rcShowPortalToast === 'function') {
                    window.rcShowPortalToast(msg, type, { playSound: false, duration: 4200 });
                    return;
                }
                let toast = document.getElementById('rcToastMsg');
                if (!toast) {
                    toast = document.createElement('div');
                    toast.id = 'rcToastMsg';
                    toast.style.position = 'fixed';
                    toast.style.bottom = '32px';
                    toast.style.left = '50%';
                    toast.style.transform = 'translateX(-50%)';
                    toast.style.zIndex = 9999;
                    toast.style.minWidth = '220px';
                    toast.style.maxWidth = '90vw';
                    toast.style.padding = '12px 24px';
                    toast.style.borderRadius = '6px';
                    toast.style.fontSize = '1rem';
                    toast.style.boxShadow = '0 2px 12px rgba(0,0,0,0.12)';
                    toast.style.color = '#fff';
                    toast.style.background = type === 'warning' ? '#f0ad4e' : (type === 'error' ? '#d9534f' : '#007bff');
                    toast.style.opacity = '0';
                    toast.style.pointerEvents = 'none';
                    document.body.appendChild(toast);
                }
                toast.textContent = msg;
                toast.style.background = type === 'warning' ? '#f0ad4e' : (type === 'error' ? '#d9534f' : '#007bff');
                toast.style.opacity = '1';
                toast.style.transition = 'opacity 0.3s';
                setTimeout(() => {
                    toast.style.opacity = '0';
                }, 4000);
            }
            const payload = Array.isArray(data) ? { data } : (data || {});
            const incomingMessages = Array.isArray(payload.data) ? payload.data : [];
            const pagination = payload.pagination || {};
            const summary = payload.summary || {};
            const hasMore = !!pagination.has_more;
            window._rc_messagesNextCursor = pagination.next_cursor || null;
            window._rc_messagesHasMore = hasMore;
            window._rc_messagesNoMore = !hasMore;
            if (summary.total_available !== undefined && summary.total_available !== null) {
                window._rc_messagesTotalAvailable = parseInt(summary.total_available, 10) || 0;
            } else if (!append) {
                window._rc_messagesTotalAvailable = null;
            }
            if (summary.unread_total !== undefined && summary.unread_total !== null) {
                window._rc_messagesUnreadTotal = parseInt(summary.unread_total, 10) || 0;
            } else if (!append) {
                window._rc_messagesUnreadTotal = null;
            }

            window._rc_messageStore = window._rc_messageStore || [];
            window._rc_messageIds = window._rc_messageIds || new Set();

            if (!append) {
                window._rc_messageStore = [];
                window._rc_messageIds = new Set();
            }

            const prependBuffer = [];
            incomingMessages.forEach(m => {
                const mid = m.id || m.message_id || m.messageId || m._id;
                if (mid && canNotifyNewMessages && !previousMessageIds.has(mid) && !newMessageIds.has(mid)) {
                    newMessageIds.add(mid);
                    newMessagesCount++;
                }
                if (mid && window._rc_messageIds.has(mid)) return;
                if (mid) window._rc_messageIds.add(mid);
                if (append && prependNew) {
                    prependBuffer.push(m);
                } else {
                    window._rc_messageStore.push(m);
                }
            });

            if (append && prependNew && prependBuffer.length) {
                // Keep newest items at top while preserving already-loaded rows.
                window._rc_messageStore = prependBuffer.concat(window._rc_messageStore);
            }

            const mergedData = { data: window._rc_messageStore };

            const listEl = document.getElementById('messagesList');
            const msgHistoryEl = document.getElementById('messageHistory');
            if (!listEl) {
                // fallback to legacy view
                msgHistoryEl.innerHTML = '<p class="text-muted">Messages UI not available</p>';
                return;
            }

            listEl.innerHTML = '';

            // map of numeric digits -> canonical thread key used in the list
            window._rc_messageThreadKeyByDigits = window._rc_messageThreadKeyByDigits || {};

            const threads = {}; // keyed by digits or fallback
            let totalThreadsCount = 0;
            let shownThreadsCount = 0;
            let unreadThreadsCount = 0;

            function isUnreadMessage(message) {
                try {
                    const status = (message.readStatus || message.status || '').toString().toLowerCase();
                    return status === 'unread';
                } catch (e) { return false; }
            }

            function hasTruthyFlag(value) {
                if (value === true || value === 1) return true;
                const normalized = String(value || '').toLowerCase().trim();
                return normalized === '1'
                    || normalized === 'true'
                    || normalized === 'yes'
                    || normalized === 'y'
                    || normalized === 'on';
            }

            function hasFailedStatus(message) {
                try {
                    const raw = [
                        message.status,
                        message.readStatus,
                        message.messageStatus,
                        message.deliveryStatus,
                        message.smsStatus,
                        message.errorCode,
                        message.error
                    ].map(v => String(v || '').toLowerCase()).join(' ');

                    if (!raw) return false;
                    return raw.includes('failed')
                        || raw.includes('fail')
                        || raw.includes('undeliver')
                        || raw.includes('error')
                        || raw.includes('rejected');
                } catch (_) {
                    return false;
                }
            }

            function isDraftMessage(message) {
                try {
                    return hasTruthyFlag(message.isDraft)
                        || hasTruthyFlag(message.draft)
                        || String(message.status || '').toLowerCase() === 'draft'
                        || String(message.readStatus || '').toLowerCase() === 'draft';
                } catch (_) {
                    return false;
                }
            }

            function isMutedMessage(message) {
                try {
                    return hasTruthyFlag(message.isMuted)
                        || hasTruthyFlag(message.muted)
                        || hasTruthyFlag(message.threadMuted)
                        || hasTruthyFlag(message.conversationMuted);
                } catch (_) {
                    return false;
                }
            }

            function isFavouriteMessage(message) {
                try {
                    return hasTruthyFlag(message.isFavourite)
                        || hasTruthyFlag(message.isFavorite)
                        || hasTruthyFlag(message.favourite)
                        || hasTruthyFlag(message.favorite)
                        || hasTruthyFlag(message.starred);
                } catch (_) {
                    return false;
                }
            }

            function normalizeThreadFilterKey(value) {
                const raw = String(value || '').trim();
                if (!raw) return '';
                if (raw.includes('|')) {
                    const parts = raw.split('|')
                        .map(part => String(part || '').replace(/\D/g, ''))
                        .filter(Boolean)
                        .sort();
                    return parts.length ? parts.join('|') : raw;
                }
                const digits = raw.replace(/\D/g, '');
                return digits || raw;
            }

            function addFilterKey(set, value) {
                const raw = String(value || '').trim();
                if (!raw) return;
                set.add(raw);
                const normalized = normalizeThreadFilterKey(raw);
                if (normalized) set.add(normalized);
            }

            function collectFilterKeys(set, source) {
                if (!source) return;
                if (source instanceof Set) {
                    source.forEach(value => addFilterKey(set, value));
                    return;
                }
                if (Array.isArray(source)) {
                    source.forEach(value => addFilterKey(set, value));
                    return;
                }
                if (typeof source === 'string') {
                    try {
                        const parsed = JSON.parse(source);
                        collectFilterKeys(set, parsed);
                        return;
                    } catch (_) {
                        source.split(',').forEach(value => addFilterKey(set, value));
                        return;
                    }
                }
                if (typeof source === 'object') {
                    Object.keys(source).forEach(key => {
                        if (hasTruthyFlag(source[key])) addFilterKey(set, key);
                    });
                }
            }

            function loadThreadFilterSet(configKeys) {
                const result = new Set();
                configKeys.forEach(key => {
                    try {
                        collectFilterKeys(result, window[key]);
                    } catch (_) { }
                    try {
                        const stored = window.localStorage ? window.localStorage.getItem(key) : null;
                        collectFilterKeys(result, stored);
                    } catch (_) { }
                });
                return result;
            }

            function updateMessagesUnreadBadge() {
                try {
                    const badge = document.getElementById('rcTextUnreadBadge');
                    if (!badge) return;

                    let totalUnread = null;
                    if (window._rc_messagesUnreadTotal !== null && window._rc_messagesUnreadTotal !== undefined) {
                        totalUnread = Math.max(0, parseInt(window._rc_messagesUnreadTotal, 10) || 0);
                    } else {
                        const store = window._rc_messageStore || [];
                        totalUnread = store.reduce((acc, m) => acc + (isUnreadMessage(m) ? 1 : 0), 0);
                    }

                    badge.textContent = totalUnread > 99 ? '99+' : String(totalUnread);
                    if (totalUnread > 0) {
                        badge.classList.remove('d-none');
                        badge.classList.remove('is-hidden');
                    } else {
                        badge.classList.add('d-none');
                        badge.classList.add('is-hidden');
                    }
                } catch (e) { /* ignore */ }
            }

            const mutedThreads = loadThreadFilterSet([
                'rc_message_threads_muted',
                'rc_message_muted_threads',
                'rcMutedMessageThreads'
            ]);
            const favouriteThreads = loadThreadFilterSet([
                'rc_message_threads_favourites',
                'rc_message_threads_favorites',
                'rcMessageFavouriteThreads',
                'rcMessageFavoriteThreads'
            ]);
            const draftThreads = loadThreadFilterSet([
                'rc_message_threads_draft',
                'rcMessageDraftThreads'
            ]);

            function threadMatchesFilter(filterValue, threadKey, threadDigits, messages, unreadCount) {
                const normalizedFilter = String(filterValue || 'all').toLowerCase();
                if (normalizedFilter === 'all') return true;

                const normalizedThreadKey = normalizeThreadFilterKey(threadKey);
                const normalizedDigits = normalizeThreadFilterKey(threadDigits);
                const hasFailed = messages.some(hasFailedStatus);
                const hasDraft = messages.some(isDraftMessage)
                    || draftThreads.has(threadKey)
                    || draftThreads.has(normalizedThreadKey)
                    || draftThreads.has(threadDigits)
                    || draftThreads.has(normalizedDigits);
                const hasMuted = messages.some(isMutedMessage)
                    || mutedThreads.has(threadKey)
                    || mutedThreads.has(normalizedThreadKey)
                    || mutedThreads.has(threadDigits)
                    || mutedThreads.has(normalizedDigits);
                const hasFavourite = messages.some(isFavouriteMessage)
                    || favouriteThreads.has(threadKey)
                    || favouriteThreads.has(normalizedThreadKey)
                    || favouriteThreads.has(threadDigits)
                    || favouriteThreads.has(normalizedDigits);

                if (normalizedFilter === 'unread') return unreadCount > 0;
                if (normalizedFilter === 'failed') return hasFailed;
                if (normalizedFilter === 'draft') return hasDraft;
                if (normalizedFilter === 'muted') return hasMuted;
                if (normalizedFilter === 'favourites') return hasFavourite;
                return true;
            }

            // Helper: get the user's own phone number (normalized without +/-)
            function getUserPhoneNumber() {
                try {
                    return getRcUserPhoneDigits();
                } catch (e) { return ''; }
            }

            // Helper: check if given digits match user's own number
            function isUserOwnNumber(digits) {
                const userNum = getUserPhoneNumber();
                const digitsNorm = (digits || '').toString().replace(/\D/g, '');
                return !!(userNum && digitsNorm && userNum === digitsNorm);
            }

            // Helper: extract from number from a message
            function extractFromNumber(message) {
                try {
                    return rcMsgExtractFromNumber(message);
                } catch (e) { return ''; }
            }

            // Helper: extract to number from a message
            function extractToNumber(message) {
                try { return rcMsgExtractToNumber(message); } catch (e) { return ''; }
            }

            function extractToNumbers(message) {
                try { return rcMsgExtractToNumbers(message); } catch (e) { return []; }
            }

            // Helper: create a bidirectional conversation key from two phone numbers
            function createConversationKey(fromNum, toNum, toNumList = []) {
                try {
                    const from = (fromNum || '').toString().replace(/\D/g, '');
                    const to = (toNum || '').toString().replace(/\D/g, '');
                    const toList = Array.isArray(toNumList)
                        ? toNumList.map(v => (v || '').toString().replace(/\D/g, '')).filter(Boolean)
                        : [];
                    const participants = [];
                    if (from) participants.push(from);
                    toList.forEach((v) => {
                        if (v && !participants.includes(v)) participants.push(v);
                    });
                    if (participants.length >= 3 || toList.length > 1) {
                        return 'grp:' + participants.sort().join('|');
                    }
                    if (!from || !to) return '';
                    const pair = [from, to].sort();
                    return pair.join('|');
                } catch (e) { return ''; }
            }

            // Helper: try to extract a phone-like candidate from a message object (legacy fallback)
            function extractPhoneCandidateFromMessage(message) {
                try {
                    const from = extractFromNumber(message);
                    const to = extractToNumber(message);
                    const userNum = getUserPhoneNumber();
                    if (from && from !== userNum) return from;
                    if (to && to !== userNum) return to;
                    if (to) return to;
                    return '';
                } catch (e) { return ''; }
            }

            if (mergedData.data && mergedData.data.length) {
                inferRcUserPhoneDigitsFromMessages(mergedData.data);
                mergedData.data.forEach(message => {
                    // Extract both from and to numbers
                    const fromNum = extractFromNumber(message);
                    const toNum = extractToNumber(message);
                    const toNums = extractToNumbers(message);

                    // Try to create a bidirectional conversation key
                    let threadKey = createConversationKey(fromNum, toNum, toNums);

                    // If we don't have both from and to, try the legacy single-number approach
                    if (!threadKey) {
                        let digits = extractPhoneCandidateFromMessage(message);

                        // If we still don't have digits, try to pick numbers from message body or subject if present
                        if (!digits) {
                            const text = (message.subject || message.text || message.body || '').toString();
                            const found = text.match(/(\+?\d[\d\s\-().]{6,}\d)/);
                            if (found && found[1]) digits = found[1].replace(/\D/g, '');
                        }

                        // final fallback: use a stable placeholder that includes a message id (avoids collapsing unrelated threads)
                        if (!digits) {
                            const stable = message.threadId || message.conversationId || message.id || ('unknown_' + (message.from?.name || message.to?.[0]?.name || Math.random().toString(36).slice(2,6)));
                            digits = stable.toString().replace(/\D/g, '') || ('unknown_' + (message.id || Math.random().toString(36).slice(2,6)));
                        }
                        threadKey = digits;
                    }

                    if (!threads[threadKey]) threads[threadKey] = [];
                    threads[threadKey].push(message);
                });

                // Build list items by most-recent-first and prefer lookup by any phone found in the thread
                const sortedThreadKeys = Object.keys(threads).sort((a, b) => {
                    const aTime = rcMsgTimestampValue(((threads[a] || [])[0] || {}).creationTime);
                    const bTime = rcMsgTimestampValue(((threads[b] || [])[0] || {}).creationTime);
                    return bTime - aTime;
                });
                sortedThreadKeys.forEach(key => {
                    totalThreadsCount++;
                    const msgs = threads[key];
                    msgs.sort((a, b) => rcMsgTimestampValue(b.creationTime) - rcMsgTimestampValue(a.creationTime));
                    const latest = msgs[0] || {};
                    const rawPreview = (latest.subject || latest.text || latest.body || '').toString().trim();
                    const hasAttachments = Array.isArray(latest.attachments) && latest.attachments.length > 0;
                    const preview = rawPreview ? rawPreview.slice(0, 80) : (hasAttachments ? 'Attachment' : '(empty message)');
                    const displayTime = formatMessageTime(latest.creationTime);
                    const unreadCount = msgs.reduce((acc, m) => acc + (isUnreadMessage(m) ? 1 : 0), 0);

                    // For bidirectional keys (contains |), extract the party that is NOT the user
                    // For single-number keys, use that directly
                    const userNum = getUserPhoneNumber();
                    let threadDigits = '';

                    if (key && key.startsWith('grp:')) {
                        threadDigits = '';
                    } else if (key && key.includes('|')) {
                        // Bidirectional key: find the OTHER party
                        const parts = key.split('|').filter(p => p);
                        if (parts.length === 2) {
                            if (userNum) {
                                threadDigits = (parts[0] === userNum) ? parts[1] : parts[0];
                            } else {
                                const latestDirection = (latest.direction || '').toString().toLowerCase();
                                const latestFrom = extractFromNumber(latest);
                                const latestTo = extractToNumber(latest);
                                if (latestDirection.includes('out') && latestTo) {
                                    threadDigits = latestTo;
                                } else if (latestDirection.includes('in') && latestFrom) {
                                    threadDigits = latestFrom;
                                } else if (latestFrom && parts.includes(latestFrom)) {
                                    threadDigits = latestFrom;
                                } else if (latestTo && parts.includes(latestTo)) {
                                    threadDigits = latestTo;
                                } else {
                                    threadDigits = parts[0];
                                }
                            }
                        } else {
                            threadDigits = key;
                        }
                    } else {
                        // Single number or unknown key
                        threadDigits = key.toString().replace(/\D/g, '') || key;
                    }

                    if (!threadMatchesFilter(messageTypeFilter, key, threadDigits, msgs, unreadCount)) {
                        return;
                    }
                    shownThreadsCount++;
                    if (unreadCount > 0) unreadThreadsCount++;

                    // Map all phone digits in this thread to the canonical thread key
                    // This helps both inbound and outbound messages route to the same thread
                    try {
                        if (threadDigits) {
                            window._rc_messageThreadKeyByDigits = window._rc_messageThreadKeyByDigits || {};
                            window._rc_messageThreadKeyByDigits[threadDigits] = key;
                        }
                    } catch (_) { }

                    // Always show masked phone number instead of contact names
                    const isGroupThread = key && key.startsWith('grp:');
                    let displayLabel = (threadDigits && threadDigits.length)
                        ? ((typeof maskPhoneNumber === 'function') ? maskPhoneNumber(threadDigits) : ('+' + threadDigits))
                        : key;
                    if (isGroupThread) {
                        const members = key.slice(4).split('|').filter(Boolean);
                        const peers = userNum ? members.filter(v => v !== userNum) : members;
                        const sample = peers.slice(0, 2)
                            .map(v => (typeof maskPhoneNumber === 'function') ? maskPhoneNumber(v) : ('+' + v))
                            .join(', ');
                        displayLabel = `Group (${peers.length || members.length})`;
                        if (sample) displayLabel += ` • ${sample}`;
                    }

                    // Append "(me)" if this is the user's own number
                    if (isUserOwnNumber(threadDigits)) {
                        displayLabel = displayLabel + ' (me)';
                    }

                    const canCall = !isGroupThread && !!threadDigits && /\d/.test(threadDigits.toString());

                    const digitsForInitials = (threadDigits || '').toString().replace(/\D/g, '');
                    const initials = isGroupThread ? 'GR' : ((digitsForInitials.slice(-2) || '##').toUpperCase());

                    const a = document.createElement('a');
                    a.href = '#';
                    a.className = 'list-group-item list-group-item-action message-item p-3 border rounded-3 mb-2' + (unreadCount > 0 ? ' rc-thread-unread' : '');
                    // Use the thread key as the stable data-user so showChatFor can locate the thread
                    a.setAttribute('data-user', key);
                    a.innerHTML = `<div class="d-flex align-items-center py-2">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:50px; height:50px; flex-shrink: 0; margin-right:10px;">
                                <span class="fw-bold">${escapeHtml(initials)}</span>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <span class="fw-semibold d-block${unreadCount > 0 ? ' fw-bold' : ''}">${escapeHtml(displayLabel)}</span>
                                        <small class="text-muted d-block preview-text${unreadCount > 0 ? ' fw-bold' : ''}">${escapeHtml(preview)}</small>
                                    </div>
                                    <div>
                                        <span class="text-muted small rc-msg-time">${escapeHtml(displayTime)}</span>
                                        <div class="rc-msg-right text-end ms-2">
                                            ${canCall ? `
                                                <button type="button"
                                                    class="phone-ui-btn rc-message-action-call rc-call-icon"
                                                    data-phone="${escapeHtml(threadDigits)}">
                                                    <i class="fa fa-phone"></i>
                                                </button>` : ''}

                                            ${unreadCount > 0 ? `
                                                <span class="badge bg-danger rc-unread-badge">${unreadCount}</span>` : ''}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;

                    listEl.appendChild(a);
                });

                // store threads for chat view
                window._rc_messageThreads = threads;

                // Keep active chat in sync after list refresh (webhook -> DB -> UI).
                try {
                    const chatView = document.getElementById('chatViewCard');
                    const activeKey = window._rc_activeThreadKey;
                    const isChatOpen = chatView && !chatView.classList.contains('d-none');
                    if (isChatOpen && activeKey && threads[activeKey] && typeof showChatFor === 'function') {
                        showChatFor(activeKey, { forceScrollBottom: false, focusInput: false });
                    }
                } catch (_) { }

                if (!append && listEl.children.length === 0) {
                    listEl.innerHTML = '<div class="text-muted">No messages found</div>';
                }
                const totalAvailableForRows = Math.max(0, parseInt(
                    window._rc_messagesTotalAvailable !== null && window._rc_messagesTotalAvailable !== undefined
                        ? window._rc_messagesTotalAvailable
                        : totalThreadsCount
                , 10) || 0);
                ensureMessagesMinimumRows(listEl, 10, totalAvailableForRows > 0);
                renderMessagesTestingStats(shownThreadsCount, totalThreadsCount, unreadThreadsCount);

                // If chat view is open, mark newly loaded unread messages as read
                try {
                    const chatView = document.getElementById('chatViewCard');
                    const activeKey = window._rc_activeThreadKey;
                    const isChatOpen = chatView && !chatView.classList.contains('d-none');
                    if (isChatOpen && activeKey && threads[activeKey]) {
                        const unreadIds = (threads[activeKey] || [])
                            .filter(m => ((m.readStatus || m.status || '').toString().toLowerCase() === 'unread'))
                            .map(m => m.id || m.message_id)
                            .filter(Boolean);
                        if (unreadIds.length) {
                            markMessagesRead(unreadIds, activeKey);
                        }
                    }
                } catch (e) { /* ignore */ }
            } else {
                if (!append) listEl.innerHTML = '<div class="text-muted">No messages found</div>';
                window._rc_messageThreads = {};
                clearMessagesPlaceholderRows(listEl);
                renderMessagesTestingStats(0, 0, 0);
            }

            updateMessagesUnreadBadge();

            // Also show a compact table in legacy pane for debugging
            if (msgHistoryEl) {
                msgHistoryEl.innerHTML = '<div class="text-muted small">Loaded ' + (mergedData.data ? mergedData.data.length : 0) + ' messages</div>';
            }

            renderMessagesLoadMore(hasMore);

            if (shouldConsiderWebhookToast) {
                window._rc_messagesNotifyReady = true;
            }

            try {
                const list = document.getElementById('messagesList');
                if (list) updateMessagesListScrollHints(list);
            } catch (e) { /* ignore */ }

            // Attach a scroll listener for infinite-scroll (load older messages when near bottom)
            try {
                const list = document.getElementById('messagesList');
                if (list && !list._rc_scrollListenerAttached) {
                    list._rc_scrollListenerAttached = true;
                    let loading = false;
                    list.addEventListener('scroll', function () {
                        try {
                            if (loading) return;
                            if (!window._rc_messagesNextCursor) {
                                updateMessagesListScrollHints(list);
                                return;
                            }

                            const distanceFromBottom = list.scrollHeight - list.scrollTop - list.clientHeight;
                            if (distanceFromBottom > 120) return; // not near bottom

                            loading = true;
                            setMessagesListLoading(list, true);

                            const prevBottomOffset = list.scrollHeight - list.scrollTop;
                            loadMessageHistory(null, true, window._rc_messagesNextCursor)
                                .then(() => {
                                    const nextBottomOffset = list.scrollHeight - prevBottomOffset;
                                    list.scrollTop = Math.max(0, nextBottomOffset);
                                })
                                .finally(() => {
                                    loading = false;
                                    setMessagesListLoading(list, false);
                                    updateMessagesListScrollHints(list);
                                });
                        } catch (e) { /* ignore */ }
                    });
                }
            } catch (e) { /* ignore */ }
        })
        .catch(error => {
            const msgHistoryEl = document.getElementById('messageHistory');
            if (msgHistoryEl) msgHistoryEl.innerHTML = '<p class="text-danger">Error loading message history</p>';
            console.error('Error:', error);
        }).finally(() => {
            window._rc_loadingMessages = false;
            setMessagesLoading(false);
        });
}

// Expose real implementation for loaders that reference the stub hook.
window._rc_loadMessageHistory_stub = loadMessageHistory;
window.loadMessagesIncrementalFromWebhook = loadMessagesIncrementalFromWebhook;

// Show chat view for a selected thread
function showChatFor(digits, options = {}) {
    const chatView = document.getElementById('chatViewCard');
    const chatUserName = document.getElementById('chatUserName');
    const chatInbox = document.getElementById('chatInboxContent');
    const listEl = document.getElementById('messagesList');
    const shouldForceScrollBottom = options.forceScrollBottom !== undefined ? !!options.forceScrollBottom : true;
    const shouldFocusInput = options.focusInput !== undefined ? !!options.focusInput : true;

    // Ensure initial open scrolls to bottom
    window._rc_chatForceScrollBottom = shouldForceScrollBottom;

    if (!window._rc_messageThreads || !window._rc_messageThreads[digits]) return;

    const sortedMsgs = window._rc_messageThreads[digits].slice().sort((a, b) => new Date(a.creationTime || 0) - new Date(b.creationTime || 0));
    const seenMessageKeys = new Set();
    const msgs = sortedMsgs.filter(m => {
        const msgId = (m && (m.id || m.message_id || m.messageId)) ? String(m.id || m.message_id || m.messageId) : '';
        const attachmentSignature = normalizeAttachments(m && m.attachments).map(att => {
            return [
                (att.path || att.stored_path || '').toString().trim(),
                (att.local_path || '').toString().trim(),
                (att.url || '').toString().trim(),
                (att.uri || att.contentUri || '').toString().trim(),
                (att.fileName || att.filename || '').toString().trim(),
            ].join('|');
        }).join('||');
        const fallbackKey = [
            (m && m.creationTime) ? String(m.creationTime) : '',
            (m && m.direction) ? String(m.direction) : '',
            (m && (m.subject || m.text || m.body)) ? String(m.subject || m.text || m.body) : '',
            attachmentSignature,
        ].join('::');

        const key = msgId ? ('id:' + msgId) : ('fb:' + fallbackKey);
        if (seenMessageKeys.has(key)) return false;
        seenMessageKeys.add(key);
        return true;
    });

    function getUserDigits() {
        try {
            return getRcUserPhoneDigits();
        } catch (e) { return ''; }
    }

    function resolveOtherPartyFromThreadKey(threadKey) {
        const userNum = getUserDigits();
        if ((threadKey || '').toString().startsWith('grp:')) return '';
        if (!threadKey || !threadKey.includes('|')) return '';
        const parts = threadKey.split('|').map(p => (p || '').toString().replace(/\D/g, '')).filter(Boolean);
        if (parts.length !== 2) return parts[0] || '';
        if (!userNum) return parts[0];
        return (parts[0] === userNum) ? parts[1] : parts[0];
    }

    function resolveOtherPartyFromMessages(messages) {
        const userNum = getUserDigits();
        for (const m of messages) {
            try {
                const from = (m.from && (m.from.phoneNumber || m.from.number || m.from.phone)) ? (m.from.phoneNumber || m.from.number || m.from.phone).toString() : '';
                const to = (m.to && Array.isArray(m.to) && m.to[0] && (m.to[0].phoneNumber || m.to[0].number || m.to[0].phone))
                    ? (m.to[0].phoneNumber || m.to[0].number || m.to[0].phone).toString()
                    : '';
                const fromDigits = from.replace(/\D/g, '');
                const toDigits = to.replace(/\D/g, '');
                if (fromDigits && fromDigits !== userNum) return fromDigits;
                if (toDigits && toDigits !== userNum) return toDigits;
            } catch (e) { /* ignore */ }
        }
        return '';
    }

    // build chat messages
    let html = '';
    if (window._rc_chatLoading && window._rc_activeThreadKey === digits) {
        html += '<div class="text-muted small text-center my-2" data-chat-loader>Loading messages...</div>';
    }
    if (window._rc_chatNoMore && window._rc_activeThreadKey === digits) {
        html += '<div class="text-muted small text-center my-2" data-chat-nomore>No previous messages</div>';
    }
    msgs.forEach(m => {
        const direction = (m.direction || '').toString().toLowerCase();
        const rawText = (m.subject || m.text || m.body || '').toString().trim();
        const normalizedAttachments = normalizeAttachments(m.attachments, { messageText: rawText });
        const hasAttachmentsNormalized = normalizedAttachments.length > 0;
        const text = rawText ? formatTemplatePreview(rawText) : (hasAttachmentsNormalized ? '' : '<em class="text-muted">(empty message)</em>');
        const time = formatMessageTime(m.creationTime);
        const attachmentsHtml = renderAttachmentsHtml(normalizedAttachments, { preNormalized: true });
        const forwardPayload = encodeForwardPayload({
            text: rawText,
            attachments: toForwardableAttachments(normalizedAttachments),
        });
        const canForward = !!(rawText || normalizedAttachments.length);
        const forwardActionHtml = canForward
            ? `<div class="mt-1"><button type="button" class="btn btn-link btn-sm p-0 rc-message-action-forward" data-forward-payload="${escapeHtml(forwardPayload)}"><i class="fa fa-share"></i> Forward</button></div>`
            : '';

        // Extract phone number for meta line
        let phoneNumber = '';
        const isOutbound = /outbound/i.test(direction) || /out/i.test(direction);
        const userFromNumber = getRcUserPhoneDigits();

        if (isOutbound) {
            // For outgoing, show YOUR number (from)
            phoneNumber = (m.from && m.from.phoneNumber) ? m.from.phoneNumber : (userFromNumber || '');
        } else {
            // For incoming, show the sender number
            phoneNumber = (m.from && m.from.phoneNumber) ? m.from.phoneNumber : '';
        }

        const metaParts = [];
        if (phoneNumber) {
            const masked = (typeof maskPhoneNumber === 'function') ? maskPhoneNumber(phoneNumber) : phoneNumber;
            metaParts.push('<strong>' + escapeHtml(masked) + '</strong>');
        }
        metaParts.push(escapeHtml(String(time)));
        const metaLine = metaParts.join(' • ');

       if (/outbound/i.test(direction) || /out/i.test(direction)) {
    html += `<div class="mb-3 d-flex justify-content-end">
        <div style="max-width:80%; text-align:right;">
             <small class="text-muted d-block" style="font-size:0.85em;">${metaLine}</small>
             ${attachmentsHtml ? attachmentsHtml : ''}
             ${text ? `<p style="background:#f1f1f1;padding:10px;border-radius:8px;word-wrap:break-word;margin:0;">${text}</p>` : ''}
             ${forwardActionHtml}
        </div>
    </div>`;
} else {
    html += `<div class="mb-3 d-flex justify-content-start">
        <div style="max-width:80%; text-align:left;">
            <small class="text-muted d-block" style="font-size:0.85em;">${metaLine}</small>
            ${attachmentsHtml ? attachmentsHtml : ''}
            ${text ? `<p style="background:#f1f1f1;padding:10px;border-radius:8px;word-wrap:break-word;margin:0;">${text}</p>` : ''}
            ${forwardActionHtml}
        </div>
    </div>`;
}
    });

    try {
        // Helper: check if digits match user's own number
        function isUserOwnNumber(d) {
            try {
                const userNum = getRcUserPhoneDigits();
                const digitsNorm = (d || '').toString().replace(/\D/g, '');
                return !!(userNum && digitsNorm && userNum === digitsNorm);
            } catch (e) { return false; }
        }

        // Use masked number for header display
        let displayName = '';
        try {
            let numberForDisplay = digits;
            if (numberForDisplay && numberForDisplay.startsWith('grp:')) {
                const members = numberForDisplay.slice(4).split('|').map(v => (v || '').toString().replace(/\D/g, '')).filter(Boolean);
                const userNum = getRcUserPhoneDigits();
                const peers = userNum ? members.filter(v => v !== userNum) : members;
                const sample = peers.slice(0, 2)
                    .map(v => (typeof maskPhoneNumber === 'function') ? maskPhoneNumber(v) : ('+' + v))
                    .join(', ');
                displayName = `Group (${peers.length || members.length})`;
                if (sample) displayName += ` • ${sample}`;
                numberForDisplay = '';
            } else if (numberForDisplay && numberForDisplay.includes('|')) {
                const parts = numberForDisplay.split('|');
                const userNum = getRcUserPhoneDigits();
                numberForDisplay = (parts[0] === userNum) ? parts[1] : parts[0];
            }
            if (!displayName) {
                const cleanedNumber = (numberForDisplay || '').toString().replace(/\D/g, '');
                displayName = cleanedNumber
                    ? ((typeof maskPhoneNumber === 'function') ? maskPhoneNumber(cleanedNumber) : ('+' + cleanedNumber))
                    : (digits || 'Unknown');
            }
        } catch (_) { }

        // Append "(me)" if this is the user's own number
        if (isUserOwnNumber(digits)) {
            displayName = displayName + ' (me)';
        }

        if (chatUserName) {
            chatUserName.textContent = displayName;
            // Extract just the other party's phone (not the pipe-separated thread key)
            try {
                let titlePhone = digits;
                // If digits is a pipe-separated key like "12403410040|12404550355", extract the other party
                if (titlePhone && titlePhone.startsWith('grp:')) {
                    titlePhone = '';
                } else if (titlePhone && titlePhone.includes('|')) {
                    const parts = titlePhone.split('|');
                    const userNum = getRcUserPhoneDigits();
                    // Use the part that's NOT the user's number
                    titlePhone = (parts[0] === userNum) ? parts[1] : parts[0];
                }
                chatUserName.title = titlePhone
                    ? ((typeof maskPhoneNumber === 'function') ? maskPhoneNumber(titlePhone) : titlePhone)
                    : ((typeof maskPhoneNumber === 'function') ? maskPhoneNumber(digits) : digits);
            } catch (e) { /* ignore */ }
        }
    } catch (e) {
        if (chatUserName) {
            const fallbackName = digits
                ? ((typeof maskPhoneNumber === 'function') ? maskPhoneNumber(digits) : digits)
                : 'Unknown';
            chatUserName.textContent = fallbackName;
        }
    }
    if (chatInbox) chatInbox.innerHTML = html;

    // Scroll to bottom to show latest messages (only if user not reading history)
    if (chatInbox) {
        setTimeout(() => {
            const threshold = 40;
            const atBottom = (chatInbox.scrollHeight - chatInbox.scrollTop - chatInbox.clientHeight) <= threshold;
            if (window._rc_chatForceScrollBottom || (!chatInbox._rc_userScrolledUp && atBottom)) {
                chatInbox.scrollTop = chatInbox.scrollHeight;
            }
            window._rc_chatForceScrollBottom = false;
        }, 50);
    }

    // Set current chat recipient globally and on the chat card for fallbacks
    try {
        // Determine the other party (never your own number)
        const threadKeyOther = resolveOtherPartyFromThreadKey(digits);
        const messageOther = resolveOtherPartyFromMessages(msgs);
        const otherPartyDigits = threadKeyOther || messageOther;

        const currentRecipient = otherPartyDigits || digits;
        window._rc_currentChatUser = currentRecipient;
        if (chatView) chatView.setAttribute('data-current-user', currentRecipient);
        window._rc_activeThreadKey = digits;
        refreshChatBlockButtonState();

        // Only set smsPhone when we have a numeric value
        const smsPhoneEl = document.getElementById('smsPhone');
        if (smsPhoneEl && otherPartyDigits) {
            smsPhoneEl.value = (otherPartyDigits.indexOf('+') === 0) ? otherPartyDigits : ('+' + otherPartyDigits);
            smsPhoneEl.dispatchEvent(new Event('input', { bubbles: true }));
        }
        if (otherPartyDigits && typeof window.rcSmsModalSetRecipients === 'function') {
            window.rcSmsModalSetRecipients([otherPartyDigits]);
        }

        // Focus the chat input for quick typing
        const inputEl = document.getElementById('chatMessageInputRC');
        if (inputEl && shouldFocusInput) { inputEl.focus(); inputEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' }); }
    } catch (e) { console.warn('Failed to set current chat user', e); }

    // toggle views
    if (listEl) listEl.classList.add('d-none');
    if (chatView) chatView.classList.remove('d-none');

    // Attach scroll listener for chat history loading
    try {
        if (chatInbox && !chatInbox._rc_scrollListenerAttached) {
            chatInbox._rc_scrollListenerAttached = true;
            let loading = false;
            chatInbox.addEventListener('scroll', function () {
                try {
                    if (loading) return;
                    const threshold = 40;
                    const atBottom = (chatInbox.scrollHeight - chatInbox.scrollTop - chatInbox.clientHeight) <= threshold;
                    chatInbox._rc_userScrolledUp = !atBottom;
                    if (chatInbox.scrollTop > 80) return;
                    if (!window._rc_messagesNextCursor) {
                        window._rc_chatNoMore = true;
                        if (window._rc_activeThreadKey === digits) {
                            showChatFor(digits, { forceScrollBottom: false, focusInput: false });
                        }
                        return;
                    }
                    loading = true;
                    window._rc_chatLoading = true;
                    const prevHeight = chatInbox.scrollHeight;
                    loadMessageHistory(null, true, window._rc_messagesNextCursor)
                        .then(() => {
                            window._rc_chatLoading = false;
                            window._rc_chatNoMore = !window._rc_messagesNextCursor;
                            if (window._rc_activeThreadKey === digits) {
                                showChatFor(digits, { forceScrollBottom: false, focusInput: false });
                                const newHeight = chatInbox.scrollHeight;
                                chatInbox.scrollTop = Math.max(0, newHeight - prevHeight);
                            }
                        })
                        .catch(() => {
                            window._rc_chatLoading = false;
                        })
                        .finally(() => {
                            setTimeout(() => { loading = false; }, 400);
                        });
                } catch (e) { /* ignore */ }
            });
        }
    } catch (e) { /* ignore */ }

    // Mark messages as read when thread is opened
    try {
        const unreadIds = (window._rc_messageThreads[digits] || [])
            .filter(m => ((m.readStatus || m.status || '').toString().toLowerCase() === 'unread'))
            .map(m => m.id || m.message_id)
            .filter(Boolean);

        if (unreadIds.length) {
            markMessagesRead(unreadIds, digits);
        }
    } catch (e) { /* ignore */ }
}

async function markMessagesRead(messageIds, threadKey) {
    if (!Array.isArray(messageIds) || messageIds.length === 0) {
        return;
    }

    const normalizedIds = Array.from(new Set(messageIds.map(id => String(id)))).sort();
    const markKey = (threadKey || '') + '|' + normalizedIds.join(',');

    window._rc_markReadInProgress = window._rc_markReadInProgress || new Set();
    if (window._rc_markReadInProgress.has(markKey)) {
        return;
    }

    window._rc_markReadInProgress.add(markKey);

    try {
        const r = await fetch(rcRoute('ringcentral.api.messages.mark-read'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ message_ids: normalizedIds })
        });

        const data = await r.json().catch(() => null);
        if (!r.ok || !data || !data.success) return;

        // Update local thread store statuses
        const updated = new Set(data.updated_ids || messageIds);
        if (window._rc_messageThreads && window._rc_messageThreads[threadKey]) {
            window._rc_messageThreads[threadKey] = window._rc_messageThreads[threadKey].map(m => {
                const mid = m.id || m.message_id;
                if (updated.has(mid)) {
                    m.readStatus = 'read';
                    m.status = 'read';
                }
                return m;
            });
        }

        if (window._rc_messageStore && window._rc_messageStore.length) {
            window._rc_messageStore = window._rc_messageStore.map(m => {
                const mid = m.id || m.message_id;
                if (updated.has(mid)) {
                    m.readStatus = 'read';
                    m.status = 'read';
                }
                return m;
            });
        }

        // Update thread list item UI
        const listItem = document.querySelector(`#messagesList a.message-item[data-user="${threadKey}"]`);
        if (listItem) {
            listItem.classList.remove('rc-thread-unread');
            const badge = listItem.querySelector('.badge');
            if (badge) badge.remove();
            const textEls = listItem.querySelectorAll('.fw-bold');
            textEls.forEach(el => el.classList.remove('fw-bold'));
        }

        try {
            const globalBadge = document.getElementById('rcTextUnreadBadge');
            if (globalBadge) {
                if (window._rc_messagesUnreadTotal !== null && window._rc_messagesUnreadTotal !== undefined) {
                    const currentSummaryUnread = Math.max(0, parseInt(window._rc_messagesUnreadTotal, 10) || 0);
                    window._rc_messagesUnreadTotal = Math.max(0, currentSummaryUnread - updated.size);
                }

                let totalUnread = null;
                if (window._rc_messagesUnreadTotal !== null && window._rc_messagesUnreadTotal !== undefined) {
                    totalUnread = Math.max(0, parseInt(window._rc_messagesUnreadTotal, 10) || 0);
                } else if (window._rc_messageStore) {
                    totalUnread = window._rc_messageStore.reduce((acc, m) => {
                        const status = (m.readStatus || m.status || '').toString().toLowerCase();
                        return acc + (status === 'unread' ? 1 : 0);
                    }, 0);
                } else {
                    totalUnread = 0;
                }

                globalBadge.textContent = totalUnread > 99 ? '99+' : String(totalUnread);
                if (totalUnread > 0) {
                    globalBadge.classList.remove('is-hidden');
                    globalBadge.classList.remove('d-none');
                } else {
                    globalBadge.classList.add('is-hidden');
                    globalBadge.classList.add('d-none');
                }
            }
        } catch (e) { /* ignore */ }
    } catch (e) { /* ignore */ }
    finally {
        window._rc_markReadInProgress.delete(markKey);
    }
}

// Back to messages list from chat view
function backToMessages() {
    const chatView = document.getElementById('chatViewCard');
    const listEl = document.getElementById('messagesList');
    if (chatView) chatView.classList.add('d-none');
    if (listEl) listEl.classList.remove('d-none');
    try {
        // Clear global current chat marker when returning to list
        window._rc_currentChatUser = null;
        if (chatView) chatView.removeAttribute('data-current-user');
        refreshChatBlockButtonState();
    } catch (e) { /* ignore */ }
}

// Resolve recipient digits from thread key or raw value
function resolveRecipientDigits(rawRecipient) {
    const raw = (rawRecipient || '').toString();
    if (!raw) return '';

    // If thread key contains both numbers, pick the other party
    if (raw.includes('|')) {
        const parts = raw.split('|').map(p => (p || '').toString().replace(/\D/g, '')).filter(Boolean);
        const userNum = getRcUserPhoneDigits();
        if (parts.length === 2 && userNum) {
            return (parts[0] === userNum) ? parts[1] : parts[0];
        }
        return parts[0] || '';
    }

    const digits = raw.replace(/\D/g, '');
    const userNum = getRcUserPhoneDigits();
    if (userNum && digits === userNum && window._rc_activeThreadKey) {
        const fromThread = (window._rc_activeThreadKey || '').toString();
        if (fromThread.includes('|')) {
            const parts = fromThread.split('|').map(p => (p || '').toString().replace(/\D/g, '')).filter(Boolean);
            if (parts.length === 2) return (parts[0] === userNum) ? parts[1] : parts[0];
        }
    }
    return digits;
}

function resolveGroupRecipientsFromThreadKey(rawThreadKey) {
    const key = (rawThreadKey || '').toString();
    if (!key.startsWith('grp:')) return [];
    const userNum = getRcUserPhoneDigits();
    const members = key
        .slice(4)
        .split('|')
        .map(v => (v || '').toString().replace(/\D/g, ''))
        .filter(Boolean);
    const peers = members.filter(v => !userNum || v !== userNum);
    return Array.from(new Set(peers))
        .map(v => normalizeSmsNumberForClient('+' + v))
        .filter(Boolean);
}

function resolveGroupRecipientsFromMessages(messages) {
    try {
        const userNum = getRcUserPhoneDigits();
        const digitsSet = new Set();
        const addDigits = (raw) => {
            const digits = (raw || '').toString().replace(/\D/g, '');
            if (!digits) return;
            if (userNum && digits === userNum) return;
            digitsSet.add(digits);
        };

        (Array.isArray(messages) ? messages : []).forEach((m) => {
            const fromDigits = rcMsgExtractFromNumber(m);
            if (fromDigits) addDigits(fromDigits);
            const toDigitsList = rcMsgExtractToNumbers(m);
            toDigitsList.forEach(addDigits);
        });

        return Array.from(digitsSet)
            .map(v => normalizeSmsNumberForClient('+' + v))
            .filter(Boolean);
    } catch (_) {
        return [];
    }
}

function getFriendlySmsErrorMessage(rawError) {
    if (typeof window.rcGetFriendlyRingCentralErrorMessage === 'function') {
        return window.rcGetFriendlyRingCentralErrorMessage(rawError, 'Failed to send SMS.');
    }
    const msg = (rawError || '').toString();
    const lower = msg.toLowerCase();

    if (!msg) return 'Failed to send SMS.';
    if (lower.includes('blocked')) return 'Blocked number.';
    if (lower.includes('invalid phone number')) return 'Invalid phone number.';
    if (lower.includes('network')) {
        return 'Network error while sending SMS. Please try again.';
    }
    return msg.replace(/^failed to send sms:\s*/i, '').trim() || 'Failed to send SMS.';
}

function normalizeSmsNumberForClient(rawValue) {
    const externalNormalizer = (window.rcNormalizeSmsPhoneNumber && typeof window.rcNormalizeSmsPhoneNumber === 'function')
        ? window.rcNormalizeSmsPhoneNumber
        : null;
    if (externalNormalizer) {
        return externalNormalizer(rawValue);
    }

    const digits = (rawValue || '').toString().replace(/\D/g, '');
    let local = '';
    if (digits.length === 10) local = digits;
    else if (digits.length === 11 && digits.charAt(0) === '1') local = digits.slice(1);
    else return null;

    if (!/^[2-9]\d{2}[2-9]\d{6}$/.test(local)) return null;
    if (local.slice(1, 3) === '11' || local.slice(4, 6) === '11') return null;
    return '+1' + local;
}

// Send a chat message from the chat footer (with robust error handling and retry)
async function sendChatMessageRC() {
    let restoreChatSendBtn = null;
    try {
        // Resolve recipient from active chat, chat card data attribute, or legacy smsPhone input
        let rawRecipient = (window._rc_currentChatUser || '') || document.getElementById('chatViewCard')?.getAttribute('data-current-user') || document.getElementById('smsPhone')?.value || '';
        const activeThreadKey = (window._rc_activeThreadKey || rawRecipient || '').toString();
        const activeThreadMessages = (window._rc_messageThreads && window._rc_messageThreads[activeThreadKey])
            ? window._rc_messageThreads[activeThreadKey]
            : [];
        const threadKeyGroupRecipients = resolveGroupRecipientsFromThreadKey(activeThreadKey);
        const messageGroupRecipients = resolveGroupRecipientsFromMessages(activeThreadMessages);
        const mergedGroupRecipients = Array.from(new Set([].concat(threadKeyGroupRecipients, messageGroupRecipients)));
        const isGroupThread = mergedGroupRecipients.length > 1;
        let digits = '';
        if (!isGroupThread) {
            digits = resolveRecipientDigits(rawRecipient);
            if (!digits) {
                alert('No recipient selected');
                return;
            }
        }

        const input = document.getElementById('chatMessageInputRC');
        if (!input) return;
        const message = input.value.trim();

        const attachments = window._rc_pendingAttachments || [];
        const forwardedAttachments = (window._rc_pendingForwardAttachments || []).filter(att => att && (att.path || att.uri));
        if (!message && attachments.length === 0 && forwardedAttachments.length === 0) {
            return;
        }

        const sendBtn = document.getElementById('sendChatBtnRC');
        restoreChatSendBtn = window.rcSetActionButtonLoading
            ? window.rcSetActionButtonLoading(sendBtn, { loadingText: 'Sending...', iconOnly: true, statusText: 'Sending message...' })
            : null;

        // ensure smsFromNumber exists
        const from = document.getElementById('smsFromNumber')?.value || (getRcUserPhoneDigits() ? ('+' + getRcUserPhoneDigits()) : '');
        const to_numbers = isGroupThread ? mergedGroupRecipients : [];
        const to_number = !isGroupThread ? normalizeSmsNumberForClient('+' + digits) : null;
        if (!isGroupThread && !to_number) {
            alert('Invalid phone number. Use a US/Canada number in +1XXXXXXXXXX format.');
            return;
        }
        if (isGroupThread && to_numbers.length < 2) {
            alert('Group recipients are invalid. Please reopen this conversation from the messages list.');
            return;
        }

        // Prepare a temporary id for optimistic UI
        const tempId = 'tmp_' + Date.now() + '_' + Math.random().toString(36).slice(2, 8);

        // Resolve a canonical thread key for these digits so outbound messages
        // are stored under the same thread as incoming messages (avoid creating
        // a separate thread under the sender/your own number).
        const resolveThreadKeyForDigits = (d) => {
            const norm = (d || '').toString().replace(/\D/g, '');
            if (!norm) return d;
            const threads = window._rc_messageThreads || {};

            // Check explicit mapping if present
            if (window._rc_messageThreadKeyByDigits && window._rc_messageThreadKeyByDigits[norm]) {
                return window._rc_messageThreadKeyByDigits[norm];
            }

            // Search existing threads for a message that references this number
            for (const k of Object.keys(threads)) {
                if (!k) continue;
                try {
                    if ((k || '').toString().replace(/\D/g, '') === norm) return k;
                    const msgs = threads[k] || [];
                    for (const m of msgs) {
                        try {
                            const cand = (function (message) {
                                try {
                                    if (!message) return '';
                                    if (message.from && (message.from.phoneNumber || message.from.number || message.from.phone)) return (message.from.phoneNumber || message.from.number || message.from.phone).toString();
                                    if (message.to) {
                                        if (Array.isArray(message.to)) {
                                            for (const t of message.to) {
                                                if (t && (t.phoneNumber || t.number || t.phone)) return (t.phoneNumber || t.number || t.phone).toString();
                                            }
                                        } else if (message.to.phoneNumber || message.to.number || message.to.phone) return (message.to.phoneNumber || message.to.number || message.to.phone).toString();
                                    }
                                    if (message.subject) return message.subject.toString();
                                    if (message.body) return message.body.toString();
                                } catch (e) { }
                                return '';
                            })(m) || '';
                            if (cand.toString().replace(/\D/g, '') === norm) return k;
                        } catch (e) { /* ignore per-message errors */ }
                    }
                } catch (e) { /* ignore thread scan errors */ }
            }

            // Fallback to a normalized +NNN key
            return ('+' + norm);
        };

        const canonicalKey = isGroupThread ? rawRecipient : resolveThreadKeyForDigits(digits);

        // update UI optimistically with sending state
        const chatInbox = document.getElementById('chatInboxContent');
        const time = new Date().toLocaleString();
        if (chatInbox) {
            const uiText = message ? message : '';
            const tempAttachments = (attachments || []).map(file => {
                try {
                    return {
                        fileName: file.name,
                        contentType: file.type || 'application/octet-stream',
                        local_path: URL.createObjectURL(file)
                    };
                } catch (_) {
                    return null;
                }
            }).filter(Boolean);
            const tempForwardAttachments = (forwardedAttachments || []).map(att => ({
                fileName: (att.fileName || att.filename || 'attachment').toString(),
                contentType: (att.contentType || 'application/octet-stream').toString(),
                path: (att.path || '').toString().trim(),
                uri: (att.uri || '').toString().trim(),
                local_path: (att.local_path || '').toString().trim(),
                url: (att.url || '').toString().trim(),
                download_url: (att.download_url || '').toString().trim(),
            }));
            const renderedAttachments = tempAttachments.concat(tempForwardAttachments);
            const attachmentsHtml = renderAttachmentsHtml(renderedAttachments);
            const nodeHtml = `
                <div class="mb-2 text-end rc-outbound message-temp sending" style="display: table-caption;" data-temp-id="${tempId}">
                    ${uiText ? `<p style="display:inline-block;background:#dff0d8;padding:8px;border-radius:8px;max-width:80%;">${formatTemplatePreview(uiText)}</p>` : ''}
                    ${attachmentsHtml ? attachmentsHtml : ''}
                    <br><small class="text-muted">${time}</small>
                    <div class="rc-send-status text-muted small">Sending...</div>
                </div>`;
            chatInbox.insertAdjacentHTML('beforeend', nodeHtml);
            chatInbox.scrollTop = chatInbox.scrollHeight;
        }

        // push into local thread store using the canonical thread key (map digits -> thread key)
        if (!window._rc_messageThreads) window._rc_messageThreads = {};
        const targetKey = canonicalKey || ((window._rc_messageThreadKeyByDigits && window._rc_messageThreadKeyByDigits[digits]) || digits);
        if (!window._rc_messageThreads[targetKey]) window._rc_messageThreads[targetKey] = [];
        const localAttachmentMeta = (attachments || []).map(file => {
            try {
                return {
                    fileName: file.name,
                    contentType: file.type || 'application/octet-stream',
                    local_path: URL.createObjectURL(file)
                };
            } catch (_) {
                return null;
            }
        }).filter(Boolean);

        window._rc_messageThreads[targetKey].push({
            tempId: tempId,
            direction: 'Outbound',
            text: message,
            attachments: localAttachmentMeta.concat(forwardedAttachments || []),
            attachmentsFiles: attachments,
            forwardedAttachments: forwardedAttachments,
            creationTime: new Date(),
            status: 'sending'
        });

        // clear input
        input.value = '';
        window._rc_pendingAttachments = [];
        window._rc_pendingForwardAttachments = [];
        setSmsForwardDraft(null);
        const preview = document.getElementById('rcChatAttachmentPreview');
        if (preview) preview.innerHTML = '';

        // Set smsPhone hidden for compatibility and ensure current chat user matches the canonical thread
        const smsPhoneEl = document.getElementById('smsPhone');
        try {
            if (smsPhoneEl) {
                smsPhoneEl.value = isGroupThread
                    ? to_numbers.join(', ')
                    : ((targetKey && targetKey.toString().indexOf('+') === 0) ? targetKey : to_number);
            }
            window._rc_currentChatUser = targetKey;
            const chatViewEl = document.getElementById('chatViewCard');
            if (chatViewEl) chatViewEl.setAttribute('data-current-user', targetKey);
            refreshChatBlockButtonState();
        } catch (e) { /* ignore */ }

        // send to server using helper
        const result = await sendChatPayload(
            isGroupThread ? to_numbers : to_number,
            from,
            message,
            attachments,
            forwardedAttachments,
            {
                createGroupText: isGroupThread,
            }
        );

        if (result.ok) {
            // Mark message as sent in UI and thread store
            markMessageSent(tempId, result.data);
        } else {
            // Mark failed and surface reason
            const errTextRaw = result.error || (result.data && (result.data.error || result.data.message)) || (result.statusText || 'unknown');
            const errText = getFriendlySmsErrorMessage(errTextRaw);
            markMessageFailed(tempId, errText);
            alert(errText);
        }
    } catch (err) {
        console.error('sendChatMessageRC failed', err);
        alert(getFriendlySmsErrorMessage(err && err.message ? err.message : 'unknown'));
    } finally {
        if (typeof restoreChatSendBtn === 'function') {
            restoreChatSendBtn();
        }
    }
}

function callCurrentChatUser() {
    try {
        if (window._rc_callingFromChat) {
            return;
        }
        window._rc_callingFromChat = true;
        setTimeout(() => { window._rc_callingFromChat = false; }, 800);

        let rawRecipient = (window._rc_currentChatUser || '') || document.getElementById('chatViewCard')?.getAttribute('data-current-user') || document.getElementById('smsPhone')?.value || '';
        const digits = resolveRecipientDigits(rawRecipient);
        if (!digits) {
            alert('No recipient selected');
            return;
        }
        const target = digits.indexOf('+') === 0 ? digits : ('+' + digits);

        if (typeof rcCallFromList === 'function') {
            rcCallFromList(target);
            return;
        }

        if (window.webPhone && typeof window.webPhone.makeCall === 'function') {
            window.webPhone.makeCall(target);
            return;
        }

        if (typeof openDialerModal === 'function') {
            openDialerModal();
        }
        const input = document.getElementById('callPhone');
        if (input) input.value = target;
    } catch (e) {
        console.warn('callCurrentChatUser failed', e);
    }
}

function callFromMessagesList(phone) {
    try {
        if (window._rc_callingFromMessageList) {
            return;
        }
        window._rc_callingFromMessageList = true;
        setTimeout(() => { window._rc_callingFromMessageList = false; }, 800);

        const raw = (phone || '').toString();
        const digits = raw.replace(/\D/g, '');
        if (!digits) return;
        const target = raw.trim().indexOf('+') === 0 ? raw.trim() : ('+' + digits);

        if (typeof rcCallFromList === 'function') {
            rcCallFromList(target);
            return;
        }

        if (window.webPhone && typeof window.webPhone.makeCall === 'function') {
            window.webPhone.makeCall(target);
            return;
        }

        if (typeof openDialerModal === 'function') {
            openDialerModal();
        }
        const input = document.getElementById('callPhone');
        if (input) input.value = target;
    } catch (e) {
        console.warn('callFromMessagesList failed', e);
    }
}

// Helper to POST SMS and return structured result
async function sendChatPayload(toTarget, from, message, attachments = [], forwardedAttachments = [], options = {}) {
    try {
        const isGroup = Array.isArray(toTarget);
        let normalizedTo = null;
        let normalizedToNumbers = [];

        if (isGroup) {
            normalizedToNumbers = Array.from(new Set(
                (toTarget || [])
                    .map(value => normalizeSmsNumberForClient(value))
                    .filter(Boolean)
            ));
            if (normalizedToNumbers.length < 2) {
                return { ok: false, error: 'Invalid group recipients. Use US/Canada numbers in +1XXXXXXXXXX format.' };
            }
        } else {
            normalizedTo = normalizeSmsNumberForClient(toTarget);
            if (!normalizedTo) {
                return { ok: false, error: 'Invalid phone number. Use a US/Canada number in +1XXXXXXXXXX format.' };
            }
        }

        const safeAttachments = (attachments || []).filter(file => {
            if (!file) return false;
            if (typeof file.size !== 'number') return false;
            return true;
        });
        const safeForwardedAttachments = (forwardedAttachments || [])
            .map(att => (att && typeof att === 'object') ? att : null)
            .filter(att => att && ((att.path && String(att.path).trim()) || (att.uri && String(att.uri).trim())));

        const hasAttachments = Array.isArray(safeAttachments) && safeAttachments.length > 0;
        let r;
        if (hasAttachments) {
            const form = new FormData();
            form.append('from_number', from);
            if (isGroup) {
                normalizedToNumbers.forEach((toNumber) => form.append('to_numbers[]', toNumber));
                form.append('create_group_text', options && options.createGroupText ? '1' : '0');
            } else {
                form.append('to_number', normalizedTo);
            }
            if (message) form.append('message', message);

            // Append files using standard [] notation for Laravel arrays
            safeAttachments.forEach((file, idx) => {
                form.append('attachments[]', file);
            });
            if (safeForwardedAttachments.length) {
                form.append('forwarded_attachments', JSON.stringify(safeForwardedAttachments));
            }

            r = await fetch(rcRoute('ringcentral.api.send-sms'), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: form
            });
        } else {
            r = await fetch(rcRoute('ringcentral.api.send-sms'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    from_number: from,
                    ...(isGroup
                        ? {
                            to_numbers: normalizedToNumbers,
                            create_group_text: !!(options && options.createGroupText),
                        }
                        : { to_number: normalizedTo }),
                    message: message,
                    forwarded_attachments: safeForwardedAttachments
                })
            });
        }

        const text = await r.text();

        let data = null;
        try { data = text ? JSON.parse(text) : null; } catch (e) { data = null; }

        return {
            ok: r.ok && data && data.success,
            status: r.status,
            statusText: r.statusText,
            data: data,
            error: (data && (data.error || data.message)) || (!r.ok ? (text || r.statusText) : null)
        };
    } catch (err) {
        return { ok: false, error: err.message || 'Network error' };
    }
}

function markMessageSent(tempId, serverData) {
    try {
        const el = document.querySelector(`[data-temp-id="${tempId}"]`);
        if (!el) return;
        el.classList.remove('sending');
        el.classList.add('sent');
        const statusEl = el.querySelector('.rc-send-status');
        if (statusEl) statusEl.innerHTML = '<span style="color:green">Sent</span>';

        const serverMessageId =
            (serverData && (serverData.message_id || serverData.messageId || serverData.id))
            || (serverData && serverData.data && serverData.data.id)
            || null;
        const savedAttachments = (serverData && Array.isArray(serverData.attachments))
            ? serverData.attachments
            : ((serverData && serverData.data && Array.isArray(serverData.data.attachments)) ? serverData.data.attachments : []);

        if (savedAttachments.length) {
            const renderedAttachments = renderAttachmentsHtml(savedAttachments);
            const existingAttachments = el.querySelector('.rc-chat-attachments');
            if (existingAttachments) {
                existingAttachments.outerHTML = renderedAttachments;
            } else if (statusEl) {
                statusEl.insertAdjacentHTML('beforebegin', renderedAttachments);
            } else {
                el.insertAdjacentHTML('beforeend', renderedAttachments);
            }
        }

        // update local thread store entry if present (replace tempId with server id if returned)
        try {
            Object.keys(window._rc_messageThreads || {}).forEach(k => {
                window._rc_messageThreads[k] = window._rc_messageThreads[k].map(m => {
                    if (m.tempId === tempId) {
                        const oldAttachments = Array.isArray(m.attachments) ? m.attachments : [];
                        oldAttachments.forEach(att => {
                            const oldUrl = (att && att.local_path) ? String(att.local_path) : '';
                            if (oldUrl.startsWith('blob:')) {
                                try { URL.revokeObjectURL(oldUrl); } catch (_) { }
                            }
                        });
                        m.status = 'sent';
                        if (serverMessageId) m.id = serverMessageId;
                        if (savedAttachments.length) {
                            m.attachments = savedAttachments;
                            m.attachmentsFiles = [];
                        }
                    }
                    return m;
                });
            });
        } catch (e) { /* ignore */ }
    } catch (e) { console.warn('markMessageSent failed', e); }
}

function markMessageFailed(tempId, reason) {
    try {
        const el = document.querySelector(`[data-temp-id="${tempId}"]`);
        if (!el) return;
        el.classList.remove('sending');
        el.classList.add('failed');
        const statusEl = el.querySelector('.rc-send-status');
        if (statusEl) statusEl.innerHTML = `<span style="color:#b00">Failed: ${escapeHtml(reason)}</span> <button class="btn btn-link btn-sm" onclick="retryChatMessageRC('${tempId}')">Retry</button>`;

        // mark in local thread store
        try {
            Object.keys(window._rc_messageThreads || {}).forEach(k => {
                window._rc_messageThreads[k] = window._rc_messageThreads[k].map(m => {
                    if (m.tempId === tempId) {
                        m.status = 'failed';
                        m.error = reason;
                    }
                    return m;
                });
            });
        } catch (e) { /* ignore */ }
    } catch (e) { console.warn('markMessageFailed failed', e); }
}

async function retryChatMessageRC(tempId) {
    try {
        // find the message in thread store
        const threads = window._rc_messageThreads || {};
        let found = null;
        let foundKey = null;
        Object.keys(threads).forEach(k => {
            (threads[k] || []).forEach(m => {
                if (m.tempId === tempId) { found = m; foundKey = k; }
            });
        });
        if (!found || !foundKey) {
            alert('Message to retry not found');
            return;
        }

        // Update UI to resending
        const el = document.querySelector(`[data-temp-id="${tempId}"]`);
        if (el) {
            el.classList.remove('failed');
            el.classList.add('sending');
            const statusEl = el.querySelector('.rc-send-status');
            if (statusEl) statusEl.textContent = 'Resending...';
        }

        const to_number = '+' + resolveRecipientDigits(foundKey);
        const from = document.getElementById('smsFromNumber')?.value || (getRcUserPhoneDigits() ? ('+' + getRcUserPhoneDigits()) : '');

        const result = await sendChatPayload(to_number, from, found.text, found.attachmentsFiles || [], found.forwardedAttachments || []);
        if (result.ok) {
            markMessageSent(tempId, result.data);
        } else {
            const errText = getFriendlySmsErrorMessage(result.error || (result.data && (result.data.error || result.data.message)) || 'unknown');
            markMessageFailed(tempId, errText);
            alert(errText);
        }
    } catch (err) {
        console.error('retryChatMessageRC failed', err);
        alert(getFriendlySmsErrorMessage(err && err.message ? err.message : 'unknown'));
    }
}

function renderMessagesLoadMore(hasMore) {
    try {
        const listEl = document.getElementById('messagesList');
        let footer = document.getElementById('messagesLoadMore');
        if (!footer && listEl && listEl.parentNode) {
            footer = document.createElement('div');
            footer.id = 'messagesLoadMore';
            footer.className = 'd-inline-flex align-items-center';
            listEl.parentNode.appendChild(footer);
        }
        if (!footer) return;

        let btn = footer.querySelector('button');
        if (!btn) {
            footer.innerHTML = '<button type="button" class="ui-btn" title="Refresh messages"><i class="fa fa-refresh"></i></button>';
            btn = footer.querySelector('button');
            if (btn) {
                btn.addEventListener('click', function () {
                    setMessagesLoadMoreRefreshing(true);
                    // Pull latest and prepend only new rows at top without resetting list.
                    loadMessageHistory(null, true, null, true, false, true)
                        .catch(function () { /* no-op */ })
                        .finally(function () {
                            setMessagesLoadMoreRefreshing(false);
                        });
                });
            }
        }

        // Keep refresh control visible at all times per UI requirement.
        footer.style.display = 'block';
    } catch (_) { }
}

function setMessagesLoadMoreRefreshing(isLoading) {
    try {
        const footer = document.getElementById('messagesLoadMore');
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

function setMessagesListLoading(listEl, isLoading) {
    if (!listEl) return;
    let loader = document.getElementById('messagesListScrollLoader');
    if (isLoading) {
        if (!loader) {
            loader = document.createElement('div');
            loader.id = 'messagesListScrollLoader';
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

function ensureMessagesStatsElement() {
    const listEl = document.getElementById('messagesList');
    if (!listEl || !listEl.parentNode) return null;

    let statsEl = document.getElementById('tabMessagesStats');
    if (!statsEl) {
        statsEl = document.createElement('div');
        statsEl.id = 'tabMessagesStats';
        statsEl.className = 'px-1 mb-2 text-muted small';
        listEl.parentNode.insertBefore(statsEl, listEl);
    }
    return statsEl;
}

function renderMessagesTestingStats(shownCount, totalCount, unreadCount) {
    const statsEl = ensureMessagesStatsElement();
    if (!statsEl) return;
    const shown = Math.max(0, parseInt(shownCount, 10) || 0);
    const total = Math.max(0, parseInt(
        window._rc_messagesTotalAvailable !== null && window._rc_messagesTotalAvailable !== undefined
            ? window._rc_messagesTotalAvailable
            : totalCount
    , 10) || 0);
    const unread = Math.max(0, parseInt(
        window._rc_messagesUnreadTotal !== null && window._rc_messagesUnreadTotal !== undefined
            ? window._rc_messagesUnreadTotal
            : unreadCount
    , 10) || 0);
      statsEl.textContent = `${shown} shown / ${total} total` + (unread ? ` | ${unread} unread` : '');
}

function clearMessagesPlaceholderRows(listEl) {
    if (!listEl) return;
    const placeholders = listEl.querySelectorAll('.rc-message-placeholder');
    placeholders.forEach(node => {
        if (node && node.parentNode) node.parentNode.removeChild(node);
    });
}

function ensureMessagesMinimumRows(listEl, minRows, hasAnyMessages) {
    if (!listEl) return;
    clearMessagesPlaceholderRows(listEl);
    if (!hasAnyMessages) return;

    const minCount = Math.max(0, parseInt(minRows, 10) || 0);
    if (!minCount) return;

    const appendPlaceholderRow = () => {
        const box = document.createElement('div');
        box.className = 'list-group-item message-item p-3 border rounded-3 mb-2 rc-message-placeholder';
        box.setAttribute('aria-hidden', 'true');
        box.innerHTML = `
            <div class="d-flex align-items-center py-2">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:50px;height:50px;flex-shrink:0;margin-right:10px;background:#eef2f7;color:#94a3b8;">
                    <span class="fw-bold">--</span>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="fw-semibold d-block" style="color:#94a3b8;">No thread</span>
                            <small class="d-block" style="color:#b0b9c6;">Waiting for messages...</small>
                        </div>
                        <span class="small" style="color:#c3c9d3;">--:--</span>
                    </div>
                </div>
            </div>`;
        listEl.appendChild(box);
    };

    const realRows = listEl.querySelectorAll('a.message-item[data-user]').length;
    const realRowsAlreadyScrollable = listEl.clientHeight > 0 && (listEl.scrollHeight > listEl.clientHeight + 1);
    if (realRowsAlreadyScrollable) {
        // Real rows are enough; keep dummy rows removed.
        return;
    }

    const missing = Math.max(0, minCount - realRows);
    for (let i = 0; i < missing; i++) {
        appendPlaceholderRow();
    }

    // Keep appending placeholders until the list is scrollable.
    // Safety cap prevents runaway rendering if layout is not measurable yet.
    if (listEl.clientHeight <= 0) return;
    const maxExtraRows = 200;
    let extraAdded = 0;
    while ((listEl.scrollHeight <= listEl.clientHeight + 1) && extraAdded < maxExtraRows) {
        appendPlaceholderRow();
        extraAdded++;
    }
}

function updateMessagesListScrollHints(listEl, forceNoMoreTop = false) {
    if (!listEl) return;
    let topHint = document.getElementById('messagesListNoMoreTop');
    if (!topHint) {
        topHint = document.createElement('div');
        topHint.id = 'messagesListNoMoreTop';
        topHint.className = 'text-muted small text-center my-2';
        topHint.textContent = 'No previous messages';
        listEl.insertAdjacentElement('afterbegin', topHint);
    }

    let bottomHint = document.getElementById('messagesListNoMoreBottom');
    if (!bottomHint) {
        bottomHint = document.createElement('div');
        bottomHint.id = 'messagesListNoMoreBottom';
        bottomHint.className = 'text-muted small text-center my-2';
        bottomHint.textContent = 'No more messages';
        listEl.insertAdjacentElement('beforeend', bottomHint);
    }

    const noMore = !!window._rc_messagesNoMore;
    topHint.style.display = (forceNoMoreTop || (noMore && listEl.scrollTop <= 80)) ? 'block' : 'none';
    const atBottom = (listEl.scrollHeight - listEl.scrollTop - listEl.clientHeight) < 20;
    bottomHint.style.display = (noMore && atBottom) ? 'block' : 'none';
}

function setMessagesLoading(isLoading, label = null) {
    try {
        const el = ensureMessagesLoadingElement();
        if (!el) return;
        window._rc_messagesLoadingActive = !!isLoading;
        if (label) {
            const labelEl = el.querySelector('[data-label]');
            if (labelEl) labelEl.textContent = label;
        }
        syncMessagesLoadingIndicatorVisibility();
    } catch (_) { }
}

function syncMessagesLoadingIndicatorVisibility() {
    try {
        const el = ensureMessagesLoadingElement();
        if (!el) return;
        const pane = document.getElementById('tabMessages');
        const messagesActive = !!(pane && pane.classList.contains('active'));
        const shouldShow = !!window._rc_messagesLoadingActive && messagesActive;
        el.style.display = shouldShow ? 'block' : 'none';
    } catch (_) { }
}

function ensureMessagesLoadingElement() {
    try {
        let el = document.getElementById('messagesLoading');
        const listEl = document.getElementById('messagesList');
        if (!listEl) return el || null;
        if (!el) {
            el = document.createElement('div');
            el.id = 'messagesLoading';
            el.className = 'rc-messages-list-loading';
            el.style.display = 'none';
            el.innerHTML = `
                <div class="d-inline-flex align-items-center px-3 py-2 rounded-pill rc-loading-pill">
                    <span class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></span>
                    <span class="ms-2 text-muted" data-label>Refreshing messages...</span>
                </div>`;
        }
        if (el.parentNode !== listEl || listEl.firstChild !== el) {
            listEl.insertBefore(el, listEl.firstChild || null);
        }
        return el;
    } catch (_) {
        return null;
    }
}

async function blockCurrentChatUser() {
    try {
        let rawRecipient = (window._rc_currentChatUser || '') || document.getElementById('chatViewCard')?.getAttribute('data-current-user') || document.getElementById('smsPhone')?.value || '';
        const digits = resolveRecipientDigits(rawRecipient);
        if (!digits) {
            alert('No recipient selected');
            return;
        }
        const target = digits.indexOf('+') === 0 ? digits : ('+' + digits);

        if (!window.rcBlockedNumbersApi || typeof window.rcBlockedNumbersApi.toggleNumberFromContext !== 'function') {
            return;
        }
        await window.rcBlockedNumbersApi.toggleNumberFromContext(target, 'Blocked from Messages tab', 'Messages');
        refreshChatBlockButtonState();
    } catch (e) {
        console.warn('blockCurrentChatUser failed', e);
    }
}

function refreshChatBlockButtonState() {
    try {
        const btn = document.getElementById('chatBlockBtnRC');
        if (!btn) return;

        let rawRecipient = (window._rc_currentChatUser || '') || document.getElementById('chatViewCard')?.getAttribute('data-current-user') || document.getElementById('smsPhone')?.value || '';
        const digits = resolveRecipientDigits(rawRecipient);
        if (!digits) {
            btn.setAttribute('title', 'Block');
            btn.classList.remove('btn-danger');
            btn.classList.add('btn-outline-danger');
            return;
        }

        const target = digits.indexOf('+') === 0 ? digits : ('+' + digits);
        if (window.rcBlockedNumbersApi && typeof window.rcBlockedNumbersApi.decorateBlockActionElement === 'function') {
            window.rcBlockedNumbersApi.decorateBlockActionElement(btn, target);
        }
    } catch (e) {
        console.warn('refreshChatBlockButtonState failed', e);
    }
}

function rcHideMessagesLoadingIndicators() {
    try {
        const ids = ['messagesListScrollLoader', 'messagesListLoader'];
        ids.forEach(function (id) {
            const node = document.getElementById(id);
            if (node) node.style.display = 'none';
        });
        syncMessagesLoadingIndicatorVisibility();
    } catch (_) { }
}

window.rcHideMessagesLoadingIndicators = rcHideMessagesLoadingIndicators;
window.rcSyncMessagesLoadingIndicator = syncMessagesLoadingIndicatorVisibility;

function isMessagesTabActive() {
    try {
        const pane = document.getElementById('tabMessages');
        return !!(pane && pane.classList.contains('active'));
    } catch (_) {
        return false;
    }
}

// Hook up send button and Enter key
document.addEventListener('click', function (e) {
    const sendBtn = e.target && e.target.closest ? e.target.closest('#sendChatBtnRC') : null;
    if (sendBtn) {
        sendChatMessageRC();
    }
    const chatCallBtn = e.target && e.target.closest ? e.target.closest('#chatCallBtnRC') : null;
    if (chatCallBtn) {
        e.preventDefault();
        e.stopPropagation();
        callCurrentChatUser();
    }
    const chatBlockBtn = e.target && e.target.closest ? e.target.closest('#chatBlockBtnRC') : null;
    if (chatBlockBtn) {
        e.preventDefault();
        e.stopPropagation();
        blockCurrentChatUser();
    }
    const callBtn = e.target && e.target.closest && e.target.closest('.rc-message-action-call');
    if (callBtn) {
        e.preventDefault();
        e.stopPropagation();
        callFromMessagesList(callBtn.getAttribute('data-phone'));
    }
    const forwardBtn = e.target && e.target.closest && e.target.closest('.rc-message-action-forward');
    if (forwardBtn) {
        e.preventDefault();
        e.stopPropagation();
        openForwardMessageModal(forwardBtn.getAttribute('data-forward-payload'));
    }
    const clearForwardDraftBtn = e.target && e.target.closest && e.target.closest('#smsForwardDraftClearBtn');
    if (clearForwardDraftBtn) {
        e.preventDefault();
        e.stopPropagation();
        setSmsForwardDraft(null);
    }
});

function bindChatComposerKeyboardShortcuts() {
    const input = document.getElementById('chatMessageInputRC');
    if (!input) return;
    if (input.dataset.rcComposerKeyBound === '1') return;
    input.dataset.rcComposerKeyBound = '1';

    input.addEventListener('keydown', function (e) {
        const isEnter = (e.key === 'Enter' || e.keyCode === 13);
        if (!isEnter) return;

        // IME safety: Enter during composition should not send.
        if (e.isComposing || e.keyCode === 229) return;

        // Shift+Enter inserts newline (default textarea behavior).
        if (e.shiftKey) return;

        // Enter sends.
        e.preventDefault();
        sendChatMessageRC();
    });
}

function initMessageEnhancements() {
    bindMessagesTypeFilterControl();
    setupEmojiPicker();
    setupAttachmentPicker();
    setupAttachmentInteractions();
    refreshTemplateControls();
    setupTemplateEditor();
    setupTemplateModal();
    bindChatComposerKeyboardShortcuts();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initMessageEnhancements);
} else {
    initMessageEnhancements();
}

// Delegate clicks for message items
document.addEventListener('click', function (e) {
    try {
        const a = e.target.closest && e.target.closest('#messagesList a.message-item');
        if (a) {
            if (e.target && e.target.closest && e.target.closest('.rc-message-action-call')) {
                return;
            }
            e.preventDefault();
            const user = a.getAttribute('data-user');
            showChatFor(user);
        }
    } catch (err) { /* ignore */ }
});

// Helper function to extract phone candidate from a message (for retry logic)
function extractPhoneCandidateFromMessage(message) {
    try {
        const from = message.from || {};
        const to = message.to || {};
        const cand = [];

        if (from.phoneNumber) cand.push(from.phoneNumber);
        if (from.phone) cand.push(from.phone);
        if (from.number) cand.push(from.number);

        if (Array.isArray(to) && to[0]) {
            if (to[0].phoneNumber) cand.push(to[0].phoneNumber);
            if (to[0].phone) cand.push(to[0].phone);
            if (to[0].number) cand.push(to[0].number);
        } else if (to) {
            if (to.phoneNumber) cand.push(to.phoneNumber);
            if (to.phone) cand.push(to.phone);
            if (to.number) cand.push(to.number);
        }

        for (const v of cand) {
            const digs = (v || '').toString().replace(/\D/g, '');
            if (digs) return digs;
        }
        return '';
    } catch (e) { return ''; }
}


// Override default alert with modal
window.alert = function(message) {

    // Close send message modal if open
    $('#sendMessageModal').modal('hide');

    // Set message text
    const msgBox = document.getElementById('alertModalMessage');
    if (msgBox) {
        msgBox.innerText = message;
    }

    // Show alert modal
    $('#alertModal').modal('show');
};
