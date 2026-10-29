{{--
    Behaviour for the Dealer Master table partial.

    This lives in its own file because BOTH the main screen and the Assign screen render
    `DealerMaster.table`, and the first version defined these handlers inline on the main screen
    only — so on /dealer-master/all the "add email" box, the History button and the call buttons
    silently did nothing. Any page that includes the table must include this too.
--}}
@php
    // check_call() comes from return_function, which every page including this has already loaded.
    $__dmCheckCall = function_exists('check_call') ? check_call() : 0;
@endphp
{{-- Florida loads the R-Dialer helpers per page, not in the layout: without this hasRDialerAccess is
     undefined here and every call would skip R-Dialer. --}}
@include('partials.ringcentral_js_helpers')
<script>
(function () {
    if (window.__dealerMasterWired) { return; }   // including this twice must not double-bind
    window.__dealerMasterWired = true;

    var CSRF = document.querySelector('meta[name="csrf-token"]')
        ? document.querySelector('meta[name="csrf-token"]').content : '{{ csrf_token() }}';

    // ── Inline email / phone save ────────────────────────────────────────────
    $(document).on('change', '.dm-field', function () {
        var $el = $(this), payload = { _token: CSRF, id: $el.data('id') };
        payload[$el.data('field')] = $el.val();

        $el.css('border-color', '#ffc107');
        $.post('{{ route('dealer.master.save.field') }}', payload)
            .done(function () {
                $el.css('border-color', '#28a745');
                setTimeout(function () { $el.css('border-color', ''); }, 1500);
            })
            .fail(function (xhr) {
                $el.css('border-color', '#dc3545');
                var msg = 'Could not save.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    msg = Object.values(xhr.responseJSON.errors).flat().join(' ');
                }
                alert(msg);
            });
    });

    // ── Call history ─────────────────────────────────────────────────────────
    function loadHistory(id) {
        $.get('{{ route('dealer.master.history') }}', { company_id: id }, function (rows) {
            if (!rows || !rows.length) {
                $('#dmHistoryList').html('<p class="text-muted mb-0">No history yet.</p>');
                return;
            }
            var html = '<table class="table table-sm table-bordered mb-0"><thead><tr>' +
                '<th>When</th><th>Agent</th><th>Result</th><th>Comment</th></tr></thead><tbody>';
            rows.forEach(function (r) {
                var who = r.user ? (r.user.slug || r.user.name) : '-';
                html += '<tr><td>' + (r.created_at || '') + '</td><td>' + who + '</td><td>' +
                    (r.connectStatus || '') + '</td><td>' + (r.comment || '') + '</td></tr>';
            });
            $('#dmHistoryList').html(html + '</tbody></table>');
        }).fail(function () {
            $('#dmHistoryList').html('<p class="text-danger mb-0">Could not load history.</p>');
        });
    }

    $(document).on('click', '.dm-history', function () {
        var id = $(this).data('id');
        $('#dmCompanyId').val(id);
        $('#dmHistoryName').text($(this).data('name'));
        $('#dmHistoryList').html('<p class="text-muted mb-0">Loading…</p>');
        $('#dmHistoryModal').modal('show');
        loadHistory(id);
    });

    $(document).on('submit', '#dmHistoryForm', function (e) {
        e.preventDefault();
        var $f = $(this);
        $.post('{{ route('dealer.master.history.store') }}', $f.serialize() + '&_token=' + CSRF)
            .done(function () {
                $f[0].reset();
                loadHistory($('#dmCompanyId').val());
                // Saving a note returns the dealer to the pool, so the row's owner changed.
                $('#dmAssignNote').show();
            })
            .fail(function () { alert('Could not save the note. Please try again.'); });
    });

    // ── Call / SMS — same order of preference as every other listing screen ──
    window.dmCall = function (num, dealerId) {
        var n = atob(num);
        $.post('{{ route('dealer.master.phone.count') }}', { _token: CSRF, approachId: dealerId, type: 1 });

        if (typeof hasRDialerAccess !== 'undefined' && hasRDialerAccess &&
            typeof launchRingCentralDialer === 'function') {
            launchRingCentralDialer(num);
            return;
        }
        var ct = '{{ $__dmCheckCall }}';
        if (ct == 134) { window.location.href = 'tel:' + n.replace(/\D/g, ''); }
        else if (ct == 135) { window.location.href = 'rcmobile://call/?number=' + n; }
        else {
            alert('No calling method is enabled for your account. Ask an admin to enable R-Dialer or Call App.');
        }
    };

    window.dmMsg = function (num) {
        var n = atob(num);
        if (typeof hasRDialerAccess !== 'undefined' && hasRDialerAccess &&
            typeof launchRingCentralMessage === 'function') {
            launchRingCentralMessage(num);
            return;
        }
        window.location.href = 'rcapp://r/sms?type=new&number=' + n;
    };
})();
</script>

{{-- The history modal has to exist on every page that shows the table, for the same reason. --}}
<div class="modal fade" id="dmHistoryModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Call History — <span id="dmHistoryName"></span></h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="dmAssignNote" class="alert alert-info py-2" style="display:none;">
                    Saved. This dealer has returned to the pool and can be assigned again.
                </div>
                <form id="dmHistoryForm" class="form-row align-items-end mb-3">
                    <input type="hidden" name="CompanyID" id="dmCompanyId">
                    <div class="form-group col-md-4">
                        <label class="form-label">Result</label>
                        <select name="connectStatus" class="form-control" required>
                            <option value="">Select…</option>
                            <option>Connected</option>
                            <option>No Answer</option>
                            <option>Voicemail</option>
                            <option>Wrong Number</option>
                            <option>Not Interested</option>
                            <option>Interested</option>
                            <option>Call Back Later</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-label">Comment</label>
                        <input type="text" name="comment" class="form-control" placeholder="what was said">
                    </div>
                    <div class="form-group col-md-2">
                        <button type="submit" class="btn btn-primary btn-block">Save</button>
                    </div>
                </form>
                <div id="dmHistoryList"><p class="text-muted mb-0">Loading…</p></div>
            </div>
        </div>
    </div>
</div>
