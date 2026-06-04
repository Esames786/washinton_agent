<div class="my-3 px-3 d-flex align-items-center flex-wrap gap-3">
    <div class="rc-flex-gap-6-fullwidth" style="flex:1">
        <input id="tabVoicemailsSearch" class="form-control form-control-sm rc-msg-search"
            placeholder="Search voicemails...">
    </div>
    <div class="rc-flex-gap-8">
        <button type="button" id="tabVoicemailsAllBtn" class="btn btn-sm rc-filter-btn is-active">All</button>
        <button type="button" id="tabVoicemailsUnreadBtn" class="btn btn-sm rc-filter-btn">Unread</button>
        <div id="tabVoicemailsLoadMore" style="display:none;"></div>
    </div>
</div>

<div id="voicemailStats" class="px-3 mb-2 text-muted small"></div>
<div id="tabVoicemailsList" class="list-group rc-scroll-container"></div>

<div class="modal fade" id="voicemailModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Voicemail Player</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="voicemailDetails">
                    <p><strong>From:</strong> <span id="vmFrom"></span></p>
                    <p><strong>Duration:</strong> <span id="vmDuration"></span></p>
                    <p><strong>Received:</strong> <span id="vmReceived"></span></p>
                </div>
                <div class="mt-3">
                    <audio id="voicemailAudio" controls class="rc-audio-player">
                        Your browser does not support the audio element.
                    </audio>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="pauseVoicemailAudio()">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
