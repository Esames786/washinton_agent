@extends('layouts.innerpages')

@section('template_title')
    Mailbox
@endsection

@include('partials.mainsite_pages.return_function')

@section('content')
<style>
    :root {
        --mb-primary: #012862;
        --mb-accent:  #e01f26;
        --mb-border:  #e7edf5;
        --mb-shadow:  0 10px 28px rgba(1,40,98,.08);
        --mb-radius:  14px;
    }
    .mailbox-wrap { display:flex; gap:16px; align-items:stretch; }
    .mailbox-left  { width:240px; background:#fff; border:1px solid var(--mb-border); border-radius:var(--mb-radius); box-shadow:var(--mb-shadow); padding:14px; }
    .mailbox-mid   { width:360px; background:#fff; border:1px solid var(--mb-border); border-radius:var(--mb-radius); box-shadow:var(--mb-shadow); padding:14px; }
    .mailbox-right { flex:1; background:#fff; border:1px solid var(--mb-border); border-radius:var(--mb-radius); box-shadow:var(--mb-shadow); padding:16px; min-height:520px; }

    .folder-item { display:flex; align-items:center; justify-content:space-between; padding:10px 12px; border-radius:10px; cursor:pointer; font-weight:500; margin-bottom:5px; transition:all .15s; }
    .folder-item:hover { background:#f7faff; }
    .folder-item.active { background:var(--mb-primary); color:#fff; }
    .folder-badge { font-size:12px; font-weight:600; padding:2px 8px; border-radius:999px; background:#eef2f7; color:#4b5563; }
    .folder-item.active .folder-badge { background:rgba(255,255,255,.2); color:#fff; }

    .mail-row { border:1px solid var(--mb-border); border-radius:12px; padding:12px; margin-bottom:8px; cursor:pointer; background:#fff; transition:all .15s; }
    .mail-row:hover { background:#fbfdff; border-color:#cfe0f0; }
    .mail-row.unread { border-left:4px solid var(--mb-accent); }
    .mail-name { font-size:14px; font-weight:700; color:#1f2937; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .mail-date { font-size:12px; color:#6b7280; white-space:nowrap; }
    .mail-subject-line { font-size:13px; font-weight:600; color:#111827; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; margin-bottom:3px; }
    .mail-preview-line { font-size:12px; color:#6b7280; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .mail-row-top { display:flex; justify-content:space-between; align-items:center; gap:8px; margin-bottom:4px; }

    .mail-list { max-height:520px; overflow:auto; }
    .mail-topbar { display:flex; gap:8px; align-items:center; margin-bottom:12px; }
    .mail-search { flex:1; border-radius:10px; border:1px solid var(--mb-border); min-height:40px; }
    .mail-pagination { display:flex; justify-content:space-between; align-items:center; margin-top:12px; padding-top:10px; border-top:1px solid var(--mb-border); }
    .mail-actions { display:flex; gap:8px; justify-content:flex-end; margin-bottom:14px; padding-bottom:12px; border-bottom:1px solid var(--mb-border); }
    .mail-empty { text-align:center; color:#6b7280; padding:30px 10px; background:#f8fafc; border:1px dashed #d7e1ee; border-radius:12px; }

    #previewArea { min-height:400px; }
    .reply-quote-box { border:1px solid var(--mb-border); border-radius:12px; background:#f8fbff; overflow:hidden; }
    .reply-quote-head { padding:10px 14px; background:#eef4fb; border-bottom:1px solid var(--mb-border); font-size:13px; color:#4b5563; line-height:1.7; }
    .reply-quote-body { padding:12px; max-height:220px; overflow:auto; background:#fff; font-size:13px; color:#111827; }

    .ck.ck-editor__main > .ck-editor__editable { min-height:220px !important; }

    /* Compose modal sizing */
    #composeModal .modal-dialog { max-width:92% !important; width:92% !important; }
    #composeModal .modal-content { max-height:90vh; overflow-y:auto; }
    #composeModal .modal-body { overflow-y:auto; }

    @media (max-width:1100px) {
        .mailbox-wrap { flex-direction:column; }
        .mailbox-left, .mailbox-mid, .mailbox-right { width:100%; }
    }
</style>

<div class="page-header">
    <div class="text-secondary text-center text-uppercase w-100">
        <h1 class="my-4"><b>Mailbox</b></h1>
    </div>
</div>

@if(!$mailbox)
    <div class="alert alert-warning">
        <strong>No mailbox assigned!</strong> Please contact admin to assign an email account to your user.
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>
@endif
@if(session('Error!'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('Error!') }}<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>
@endif
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
    </div>
@endif

<div class="mailbox-wrap">
    {{-- LEFT: folders --}}
    <div class="mailbox-left">
        <div class="mb-3">
            <div class="font-weight-bold text-primary" style="word-break:break-word;">{{ $mailbox?->email ?? 'Mailbox' }}</div>
            <small class="text-muted">Assigned mailbox</small>
        </div>
        <div class="mb-3">
            <button class="btn btn-primary btn-block" id="composeBtn"
                    data-toggle="modal" data-target="#composeModal"
                    {{ !$mailbox ? 'disabled' : '' }}>
                <i class="fa fa-edit mr-1"></i> Compose Mail
            </button>
        </div>
        <div id="folderList">
            @foreach($folders as $key => $label)
                <div class="folder-item {{ $key === 'INBOX' ? 'active' : '' }}" data-folder="{{ $key }}">
                    <span>{{ $label }}</span>
                    <span class="folder-badge">0</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- MIDDLE: message list --}}
    <div class="mailbox-mid">
        <div class="font-weight-bold text-primary mb-2">Messages</div>
        <div class="mail-topbar">
            <input type="text" class="form-control mail-search" id="mailSearch" placeholder="Search...">
            <button class="btn btn-light btn-sm" id="refreshBtn" type="button"><i class="fa fa-refresh"></i></button>
        </div>
        <div class="small text-muted mb-2" id="mailListMeta">Loading...</div>
        <div class="mail-list" id="mailList">
            <div class="text-muted text-center py-3">Loading...</div>
        </div>
        <div class="mail-pagination">
            <button class="btn btn-outline-secondary btn-sm" id="prevPageBtn" type="button">Previous</button>
            <div class="small text-muted" id="pageInfo">Page 1</div>
            <button class="btn btn-outline-secondary btn-sm" id="nextPageBtn" type="button">Next</button>
        </div>
    </div>

    {{-- RIGHT: preview --}}
    <div class="mailbox-right">
        <div class="font-weight-bold text-primary mb-2">Preview</div>
        <div class="mail-actions">
            <button class="btn btn-outline-secondary btn-sm" id="replyBtn"
                    data-toggle="modal" data-target="#composeModal"
                    type="button" {{ !$mailbox ? 'disabled' : '' }}>Reply</button>
            <button class="btn btn-outline-secondary btn-sm" id="forwardBtn"
                    data-toggle="modal" data-target="#composeModal"
                    type="button" {{ !$mailbox ? 'disabled' : '' }}>Forward</button>
        </div>
        <div id="previewArea">
            <div class="d-flex flex-column align-items-center justify-content-center text-center" style="min-height:360px;">
                <i class="fa fa-envelope" style="font-size:48px;color:#b7c4d6;"></i>
                <div class="mt-2 font-weight-bold" style="color:#344054;">Select a message</div>
                <div class="text-muted small">Open any email from the list to preview it here.</div>
            </div>
        </div>
    </div>
</div>

{{-- COMPOSE MODAL --}}
<div class="modal fade" id="composeModal" tabindex="-1">
    <div class="modal-dialog" style="max-width:92%;width:92%;margin:1.5rem auto;">
        <form method="POST" action="{{ route('mailbox.send') }}" enctype="multipart/form-data" id="composeForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Compose Email</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">To</label>
                                <input type="text" class="form-control" name="to" id="mail_to" placeholder="a@x.com, b@y.com" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold">CC (optional)</label>
                                <input type="text" class="form-control" name="cc" id="mail_cc" placeholder="cc@x.com">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold">BCC (optional)</label>
                                <input type="text" class="form-control" name="bcc" id="mail_bcc" placeholder="bcc@x.com">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="font-weight-bold">Subject</label>
                                <input type="text" class="form-control" name="subject" id="mail_subject" required maxlength="190">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="font-weight-bold">Message</label>
                                {{-- CKEditor replaces this textarea — no required attribute --}}
                                <textarea class="form-control" name="body_plain" id="mail_body" rows="10"></textarea>
                                <input type="hidden" name="body" id="mail_body_html">
                            </div>
                        </div>
                        <div class="col-md-12 d-none" id="replyOriginalWrap">
                            <label class="font-weight-bold mb-2">Original message</label>
                            <div class="reply-quote-box">
                                <div class="reply-quote-head" id="replyOriginalHeader"></div>
                                <div class="reply-quote-body" id="replyOriginalBody"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="font-weight-bold">Attachments (optional)</label>
                                <input type="file" class="form-control-file" name="attachments[]" multiple>
                                <small class="text-muted">Allowed: jpg,png,pdf,doc,docx,xls,xlsx,csv,txt,zip. Max 5MB each.</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="sendMailBtn">Send</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/super-build/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let replyContext = null;
    let mailEditor   = null;

    // ── CKEditor ──────────────────────────────────────────────────────────
    function MailUploadAdapter(loader) { this.loader = loader; }
    MailUploadAdapter.prototype.upload = function () {
        return this.loader.file.then(file => new Promise((resolve, reject) => {
            const data = new FormData();
            data.append('upload', file);
            fetch("{{ route('mailbox.inline_upload') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                body: data
            }).then(r => r.json()).then(r => r.url ? resolve({ default: r.url }) : reject('Upload failed.')).catch(() => reject('Upload failed.'));
        }));
    };
    function MailUploadAdapterPlugin(editor) {
        editor.plugins.get('FileRepository').createUploadAdapter = loader => new MailUploadAdapter(loader);
    }

    CKEDITOR.ClassicEditor.create(document.getElementById('mail_body'), {
        extraPlugins: [MailUploadAdapterPlugin],
        toolbar: { items: ['undo','redo','|','heading','|','fontfamily','fontsize','|','bold','italic','underline','|','fontColor','fontBackgroundColor','|','alignment','|','bulletedList','numberedList','|','link','blockQuote','insertTable','imageUpload','|','removeFormat'], shouldNotGroupWhenFull: true },
        removePlugins: ['AIAssistant','CKBox','CKFinder','EasyImage','RealTimeCollaborativeComments','RealTimeCollaborativeTrackChanges','RealTimeCollaborativeRevisionHistory','PresenceList','Comments','TrackChanges','TrackChangesData','RevisionHistory','Pagination','WProofreader','MathType','SlashCommand','Template','DocumentOutline','FormatPainter','TableOfContents','PasteFromOfficeEnhanced']
    }).then(editor => { mailEditor = editor; }).catch(console.error);

    // ── Form submit ───────────────────────────────────────────────────────
    document.getElementById('composeForm').addEventListener('submit', function (e) {
        // Sync CKEditor content to hidden field
        const editorHtml = mailEditor ? mailEditor.getData() : document.getElementById('mail_body').value;

        // Validate body not empty
        if (!editorHtml || editorHtml.trim() === '' || editorHtml.trim() === '<p>&nbsp;</p>') {
            e.preventDefault();
            alert('Please enter a message body.');
            return;
        }

        document.getElementById('mail_body_html').value = buildReplyHtml(editorHtml);

        // Also sync to textarea so server gets body_plain
        if (mailEditor) {
            document.getElementById('mail_body').value = editorHtml;
        }

        const btn = document.getElementById('sendMailBtn');
        if (btn) { btn.disabled = true; btn.innerText = 'Sending...'; }
    });

    $('#composeModal').on('hidden.bs.modal', function () {
        replyContext = null;
        document.getElementById('replyOriginalWrap').classList.add('d-none');
        document.getElementById('replyOriginalHeader').innerHTML = '';
        document.getElementById('replyOriginalBody').innerHTML   = '';
        const btn = document.getElementById('sendMailBtn');
        if (btn) { btn.disabled = false; btn.innerText = 'Send'; }
    });

    // ── Mailbox state ─────────────────────────────────────────────────────
    const hasMailbox = @json((bool)($mailbox ?? false));
    if (!hasMailbox) return;

    let activeFolder  = 'INBOX';
    let page          = 1;
    const limit       = 50;
    let currentSearch = '';
    let currentTotal  = 0;
    let lastOpenedMsg = null;

    const folderList  = document.getElementById('folderList');
    const mailList    = document.getElementById('mailList');
    const previewArea = document.getElementById('previewArea');
    const pageInfo    = document.getElementById('pageInfo');
    const prevPageBtn = document.getElementById('prevPageBtn');
    const nextPageBtn = document.getElementById('nextPageBtn');
    const mailListMeta= document.getElementById('mailListMeta');

    // ── Load folders ──────────────────────────────────────────────────────
    async function loadFolders() {
        const res  = await fetch('{{ url("/mailbox/folders") }}');
        const data = await res.json();
        if (!data.ok) return;
        folderList.innerHTML = '';
        data.items.forEach(f => {
            const div = document.createElement('div');
            div.className    = 'folder-item ' + (f.key === activeFolder ? 'active' : '');
            div.dataset.folder = f.key;
            div.innerHTML    = `<span>${esc(f.label)}</span><span class="folder-badge">${f.key !== 'Sent' && f.unread > 0 ? f.unread : f.total}</span>`;
            folderList.appendChild(div);
        });
    }

    // ── Load messages ─────────────────────────────────────────────────────
    async function loadFolderMessages(folder) {
        mailList.innerHTML = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm" role="status"></div></div>';
        const q   = encodeURIComponent(currentSearch || '');
        const res = await fetch(`{{ url("/mailbox/folder") }}/${folder}?page=${page}&limit=${limit}&q=${q}`);
        const data= await res.json();
        if (!data.ok) { mailList.innerHTML = `<div class="alert alert-danger">${data.message}</div>`; return; }
        currentTotal = Number(data.total || 0);
        mailList.innerHTML = '';
        if (!data.items || !data.items.length) {
            mailList.innerHTML = '<div class="mail-empty">No messages found.</div>';
        } else {
            data.items.forEach(m => {
                const row = document.createElement('div');
                row.className      = 'mail-row ' + (m.seen ? '' : 'unread');
                row.dataset.uid    = m.uid;
                row.dataset.folder = folder;
                row.innerHTML = `
                    <div class="mail-row-top">
                        <div class="mail-name">${esc(m.from || '-')}</div>
                        <div class="mail-date">${esc(shortDate(m.date || ''))}</div>
                    </div>
                    <div class="mail-subject-line">${esc(m.subject || '(no subject)')}</div>
                    <div class="mail-preview-line">${m.has_attachments ? '📎 ' : ''}${esc(m.snippet || '')}</div>
                `;
                mailList.appendChild(row);
            });
        }
        const start = currentTotal === 0 ? 0 : ((page - 1) * limit) + 1;
        const end   = Math.min(page * limit, currentTotal);
        mailListMeta.innerHTML = `Showing ${start}-${end} of ${currentTotal}`;
        pageInfo.innerHTML     = `Page ${page}`;
        prevPageBtn.disabled   = page <= 1;
        nextPageBtn.disabled   = end >= currentTotal;
    }

    // ── Load single message ───────────────────────────────────────────────
    async function loadMessage(folder, uid) {
        previewArea.innerHTML = '<div class="text-center py-4"><div class="spinner-border spinner-border-sm" role="status"></div></div>';
        const res  = await fetch(`{{ url("/mailbox/message") }}/${folder}/${uid}`);
        const data = await res.json();
        if (!data.ok) { previewArea.innerHTML = `<div class="alert alert-danger">${data.message}</div>`; return; }

        let attsHtml = '';
        if (data.attachments && data.attachments.length) {
            attsHtml = '<div class="mt-3"><div class="font-weight-bold mb-2">Attachments</div>' +
                data.attachments.map(a => `<div class="d-flex justify-content-between align-items-center border rounded p-2 mb-2"><div><div class="font-weight-bold">${esc(a.name)}</div><small class="text-muted">${esc(a.mime)} • ${a.size} bytes</small></div><a class="btn btn-outline-primary btn-sm" target="_blank" href="${a.download_url}">Download</a></div>`).join('') + '</div>';
        }

        previewArea.innerHTML = `
            <div class="mb-2">
                <div class="text-muted small">From: <strong>${esc(data.from || '-')}</strong></div>
                <div class="text-muted small">To: <strong>${esc(data.to || '-')}</strong></div>
                <h5 class="mt-1 text-primary">${esc(data.subject || '')}</h5>
                <div class="text-muted small">${esc(data.date || '')}</div>
            </div>
            <hr>
            <div>${data.body_html || ''}</div>
            ${attsHtml}
        `;

        lastOpenedMsg = data;
        document.querySelectorAll('.mail-row').forEach(r => { if (r.dataset.uid == uid) r.classList.remove('unread'); });
        loadFolders();
    }

    // ── Events ────────────────────────────────────────────────────────────
    folderList.addEventListener('click', async function (e) {
        const item = e.target.closest('.folder-item');
        if (!item) return;
        document.querySelectorAll('.folder-item').forEach(x => x.classList.remove('active'));
        item.classList.add('active');
        activeFolder = item.dataset.folder;
        page = 1;
        await loadFolderMessages(activeFolder);
    });

    mailList.addEventListener('click', async function (e) {
        const row = e.target.closest('.mail-row');
        if (!row) return;
        await loadMessage(row.dataset.folder, row.dataset.uid);
    });

    let searchTimer = null;
    document.getElementById('mailSearch').addEventListener('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(async () => { currentSearch = this.value.trim(); page = 1; await loadFolderMessages(activeFolder); }, 350);
    });

    document.getElementById('refreshBtn').addEventListener('click', async function () {
        await loadFolders(); await loadFolderMessages(activeFolder);
    });

    prevPageBtn.addEventListener('click', async function () { if (page > 1) { page--; await loadFolderMessages(activeFolder); } });
    nextPageBtn.addEventListener('click', async function () { if (page * limit < currentTotal) { page++; await loadFolderMessages(activeFolder); } });

    document.getElementById('composeBtn').addEventListener('click', function () {
        document.getElementById('mail_to').value      = '';
        document.getElementById('mail_cc').value      = '';
        document.getElementById('mail_bcc').value     = '';
        document.getElementById('mail_subject').value = '';
        if (mailEditor) mailEditor.setData('');
    });

    document.getElementById('replyBtn').addEventListener('click', function () {
        if (!lastOpenedMsg) return;
        replyContext = { mode: 'reply', original: lastOpenedMsg };
        document.getElementById('mail_to').value      = lastOpenedMsg.from_email || '';
        document.getElementById('mail_subject').value = prefix(lastOpenedMsg.subject, 'Re:');
        if (mailEditor) mailEditor.setData('');
        renderOriginal(lastOpenedMsg, 'reply');
    });

    document.getElementById('forwardBtn').addEventListener('click', function () {
        if (!lastOpenedMsg) return;
        replyContext = { mode: 'forward', original: lastOpenedMsg };
        document.getElementById('mail_to').value      = '';
        document.getElementById('mail_subject').value = prefix(lastOpenedMsg.subject, 'Fwd:');
        if (mailEditor) mailEditor.setData('');
        renderOriginal(lastOpenedMsg, 'forward');
    });

    // ── Helpers ───────────────────────────────────────────────────────────
    function renderOriginal(msg, mode) {
        document.getElementById('replyOriginalWrap').classList.remove('d-none');
        document.getElementById('replyOriginalHeader').innerHTML = mode === 'reply'
            ? `<div><strong>From:</strong> ${esc(msg.from)}</div><div><strong>Date:</strong> ${esc(msg.date)}</div><div><strong>Subject:</strong> ${esc(msg.subject)}</div>`
            : `<div><strong>Forwarded message</strong></div><div><strong>From:</strong> ${esc(msg.from)}</div><div><strong>Date:</strong> ${esc(msg.date)}</div><div><strong>Subject:</strong> ${esc(msg.subject)}</div>`;
        document.getElementById('replyOriginalBody').innerHTML = msg.body_html || esc(msg.snippet || '');
    }

    function buildReplyHtml(userHtml) {
        if (!replyContext || !replyContext.original) return userHtml;
        const msg = replyContext.original;
        const meta = replyContext.mode === 'reply'
            ? `<div style="margin-top:16px;padding-top:12px;border-top:1px solid #dadce0;color:#5f6368;font-size:13px;">On ${esc(msg.date)}, ${esc(msg.from)} wrote:</div>`
            : `<div style="margin-top:16px;padding-top:12px;border-top:1px solid #dadce0;color:#5f6368;font-size:13px;">---------- Forwarded message ----------<br>From: ${esc(msg.from)}<br>Date: ${esc(msg.date)}<br>Subject: ${esc(msg.subject)}</div>`;
        return `<div style="font-family:Arial,sans-serif;font-size:14px;line-height:1.6;">${userHtml}</div>${meta}<blockquote style="margin:12px 0 0 0;padding-left:12px;border-left:2px solid #dadce0;">${msg.body_html || esc(msg.snippet || '')}</blockquote>`;
    }

    function prefix(subject, pre) {
        const s = (subject || '').trim();
        if (!s) return pre;
        if (s.toLowerCase().startsWith(pre.toLowerCase())) return s;
        return `${pre} ${s}`;
    }

    function shortDate(dateStr) {
        if (!dateStr) return '';
        try {
            const d = new Date(dateStr.replace(' ', 'T'));
            if (isNaN(d.getTime())) return dateStr;
            return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        } catch (e) { return dateStr; }
    }

    function esc(str) {
        return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;');
    }

    // ── Init ──────────────────────────────────────────────────────────────
    (async function () { await loadFolders(); await loadFolderMessages(activeFolder); })();
});
</script>
@endsection
