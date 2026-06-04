<div class="my-3 px-3 d-flex align-items-center flex-wrap gap-3">
    <div class="rc-flex-gap-6-fullwidth" style="flex:1">
        <input id="tabRecordingsSearch" class="form-control form-control-sm rc-msg-search"
            placeholder="Search recordings...">
    </div>
    <div id="tabRecordingsLoadMore" style="display:none;"></div>
</div>

<div id="tabRecordingsStats" class="px-3 mb-2 text-muted small"></div>
<div id="tabRecordingsList" class="list-group rc-scroll-container"></div>

<div class="modal fade" id="recordingModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Call Recording</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <p class="mb-1"><strong>Duration:</strong> <span id="recordingDurationText"></span></p>
                    <p class="mb-0"><strong>Recorded:</strong> <span id="recordingRecordedAtText"></span></p>
                </div>
                <div class="mt-3">
                    <audio id="recordingAudio" controls class="rc-audio-player">
                        Your browser does not support the audio element.
                    </audio>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="pauseRecordingAudio()">
                    Close
                </button>
                <a href="#" id="recordingDownloadLink" class="btn btn-outline-primary" target="_blank" rel="noopener">
                    Download
                </a>
            </div>
        </div>
    </div>
</div>
