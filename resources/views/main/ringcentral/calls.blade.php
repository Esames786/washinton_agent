@section('styles')
    <link rel="stylesheet" href="{{ asset('css/call.css') }}">

@endsection

<!-- Calls Tab (Outer) -->
<div class="tab-pane" id="tabCalls" role="tabpanel">

    <!-- Inner Tabs Header -->
    <ul class="nav nav-tabs rc-inner-tabs mb-2" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#callKeypad" role="tab">Keypad</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#callCalls" role="tab">Calls</a>
            <span id="rcCallsMissedBadgeInner" class="badge bg-danger ms-1 is-hidden">0</span>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#callVoicemail" role="tab">Voicemail</a>
            <span id="rcVoicemailsUnreadBadgeInner" class="badge bg-danger ms-1 is-hidden">0</span>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#callRecordings" role="tab">Recordings</a>
        </li>

    </ul>

    <!-- Inner Tabs Content -->
    <div class="tab-content">

        <!-- Keypad -->
        <div class="tab-pane fade show active" id="callKeypad" role="tabpanel">
            {{-- yahan aap apna keypad include/markup lagao --}}
            <div class="dialer-inline-host">
                @include('main.ringcentral.dialer')
            </div>
        </div>

        <!-- Calls (your existing UI) -->
        <div class="tab-pane fade" id="callCalls" role="tabpanel">
            <div class="my-3 px-3 d-flex align-items-center flex-wrap gap-3">

                <div class="rc-flex-gap-6-fullwidth" style="flex:1">
                    <input id="tabCallsSearch" class="form-control form-control-sm rc-msg-search"
                        placeholder="Search calls...">
                </div>
                <div class="rc-flex-gap-8">
                    <button type="button" id="tabCallsAllBtn" class="btn btn-sm rc-filter-btn is-active">All</button>
                    <div class="rc-filter-dropdown" id="tabCallsFilterDropdown">
                        <button type="button" id="tabCallsFilterBtn"
                            class="btn btn-sm rc-filter-btn rc-filter-dropdown-toggle"
                            aria-haspopup="true"
                            aria-expanded="false">
                            <span id="tabCallsFilterBtnText">Filter</span>
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                        </button>
                        <div id="tabCallsFilterMenu" class="rc-filter-dropdown-menu d-none" role="menu" aria-label="Call filter options">
                            <button type="button" class="rc-filter-dropdown-item" data-filter="missed">Missed</button>
                            <button type="button" class="rc-filter-dropdown-item" data-filter="outgoing">Outgoing</button>
                            <button type="button" class="rc-filter-dropdown-item" data-filter="incoming">Incoming</button>
                        </div>
                    </div>
                    <select id="tabCallsTypeFilter" class="form-select form-select-sm rc-filter-select d-none">
                        <option value="">Filter</option>
                        <option value="missed">Missed</option>
                        <option value="outgoing">Outgoing</option>
                        <option value="incoming">Incoming</option>
                    </select>
                    <div id="tabCallsLoadMore" style="display:none;"></div>
                </div>
            </div>

            <!-- Keep this ID exactly same (JS uses it) -->
            <div id="tabCallsList" class="list-group rc-scroll-container"></div>
        </div>

        <!-- Voicemail -->
        <div class="tab-pane fade" id="callVoicemail" role="tabpanel">
            {{-- agar aapka voicemail ka existing include hai, yahan call karo --}}
            @include('main.ringcentral.voicemail')
        </div>

        <!-- Notes -->
        <div class="tab-pane fade" id="callNotes" role="tabpanel">
            <div class="p-3 text-muted">Notes UI here�</div>
        </div>

        <!-- Recordings -->
        <div class="tab-pane fade" id="callRecordings" role="tabpanel">
            @include('main.ringcentral.recordings')
        </div>

    </div>
</div>

<div class="modal fade" id="rcBlockedNumbersModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Blocked Numbers</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="rcBlockedNumbersForm" class="mb-3">
                    <div class="mb-2">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="rcBlockedNumberInput" placeholder="+1XXXXXXXXXX or 10 digits" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Reason (optional)</label>
                        <input type="text" class="form-control" id="rcBlockedNumberReason" maxlength="1000" placeholder="Reason">
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-ban"></i> Block Number</button>
                    </div>
                </form>
                <div class="mb-2">
                    <input type="search" class="form-control form-control-sm" id="rcBlockedNumbersSearch" placeholder="Search blocked numbers...">
                </div>
                <div id="rcBlockedNumbersList" class="list-group rc-scroll-container" style="max-height:300px;overflow:auto;"></div>
            </div>
        </div>
    </div>
</div>
