/**
 * R-Dialer  Audio and Recording Controls
 * Handles microphone, speaker, and recording controls
 */

let isMicMuted = false;
let isSpeakerMuted = false;


// ===== START: UI UPDATE INTERVALS =====
setInterval(function () {
    try {
        const els = document.querySelectorAll('.rc-duration');
        els.forEach(el => {
            const start = el.getAttribute('data-start');
            if (start) el.textContent = formatDuration(parseInt(start));
        });
    } catch (e) { }
}, 1000);
// ===== END: UI UPDATE INTERVALS =====

// ===== START: CALL CLEANUP FUNCTIONS =====
function endCall(options = {}) {
    const skipWebPhoneHangup = !!(options && options.skipWebPhoneHangup);
    // Clear the call timer if it's running
    if (callTimer) {
        clearInterval(callTimer);
        callTimer = null;
    }

    // Clean up WebPhone call if active
    if (!skipWebPhoneHangup && webPhone && webPhone.isCallActive()) {
        webPhone.endCall().catch(err => console.error('Error ending WebPhone call:', err));
    }

    // Reset UI elements (legacy variables no longer used - using _dialerCallStartTime instead)

    // Check if there are any active sessions left
    if (webPhone && webPhone.listSessions && webPhone.listSessions().length === 0) {
        // No active calls, hide call controls
        // (per-call controls removed; all in Active Calls table now)
    }

    // Reset recording controls (removed; handled per-call in Active Calls)
    const recordingStatus = document.getElementById('recordingStatus');
    if (recordingStatus) recordingStatus.textContent = 'Not recording';

    // Reset mute states for mic and speaker
    isMicMuted = false;
    isSpeakerMuted = false;

    // Clear persisted state
    try {
        localStorage.removeItem(CALL_STATE_KEY);
    } catch (_) { }
}
// ===== END: CALL CLEANUP FUNCTIONS =====

// ===== START: RECORDING AND AUDIO CONTROLS =====
async function testSpeaker() {
        const testSpeakerBtn = document.getElementById('testSpeakerBtn');
        const speakerTestStatus = document.getElementById('speakerTestStatus');
        const speakerVolumeContainer = document.getElementById('speakerVolumeContainer');
        const speakerVolume = document.getElementById('speakerVolume');
        const audioOutputSelect = document.getElementById('audioOutputSelect');

        try {
            testSpeakerBtn.disabled = true;
            speakerTestStatus.innerHTML = '<div class="alert alert-info">🔊 Starting tone...</div>';
            speakerVolumeContainer.style.display = 'block';

            const audioContext = new(window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();

            // Create a MediaStreamDestination to route to an audio element
            const destination = audioContext.createMediaStreamDestination();

            // Create 440Hz sine wave (A note)
            oscillator.frequency.value = 440;
            oscillator.type = 'sine';

            // Set initial volume
            const volume = speakerVolume.value / 100;
            gainNode.gain.setValueAtTime(volume * 0.3, audioContext.currentTime);

            oscillator.connect(gainNode);
            gainNode.connect(destination);

            // Create an audio element to play through selected output device
            const audioElement = new Audio();
            audioElement.srcObject = destination.stream;

            // Get selected output device
            const selectedDeviceId = audioOutputSelect ? audioOutputSelect.value : 'default';

            // Set output device using setSinkId if supported
            if (typeof audioElement.setSinkId !== 'undefined') {
                try {
                    // Handle special device IDs
                    let deviceIdToSet = selectedDeviceId;
                    if (selectedDeviceId === 'default' || selectedDeviceId === 'communications') {
                        // Try to find the actual device ID for default/communications
                        const devices = await navigator.mediaDevices.enumerateDevices();
                        const outputs = devices.filter(d => d.kind === 'audiooutput');
                        if (outputs.length > 0) {
                            deviceIdToSet = outputs[0].deviceId; // Use first available device
                        }
                    }

                    await audioElement.setSinkId(deviceIdToSet);
                    const deviceLabel = audioOutputSelect ? 
                        (audioOutputSelect.options[audioOutputSelect.selectedIndex]?.text || selectedDeviceId) : 
                        'Default';
                    console.log('🔊 Speaker test output set to:', deviceLabel);
                    speakerTestStatus.innerHTML = `<div class="alert alert-success">🔊 Playing 440Hz tone on: ${deviceLabel}</div>`;
                } catch (err) {
                    console.warn('Failed to set output device for speaker test:', err);
                    speakerTestStatus.innerHTML = '<div class="alert alert-warning">🔊 Playing 440Hz tone (using default output - device selection failed)</div>';
                }
            } else {
                speakerTestStatus.innerHTML = '<div class="alert alert-warning">🔊 Playing 440Hz tone (browser doesn\'t support output device selection)</div>';
            }

            // Update volume in real-time
            const volumeHandler = (e) => {
                gainNode.gain.setValueAtTime((e.target.value / 100) * 0.3, audioContext.currentTime);
            };
            speakerVolume.addEventListener('input', volumeHandler);

            // Start playing
            await audioElement.play();
            oscillator.start(audioContext.currentTime);
            testSpeakerBtn.textContent = '🔊 Playing (5 sec)...';

            // Stop after 5 seconds
            oscillator.stop(audioContext.currentTime + 5);

            setTimeout(() => {
                audioElement.pause();
                audioElement.srcObject = null;
                destination.stream.getTracks().forEach(track => track.stop());
                audioContext.close();
                speakerVolume.removeEventListener('input', volumeHandler);

                testSpeakerBtn.disabled = false;
                testSpeakerBtn.textContent = '🔊 Test Speaker';
                speakerTestStatus.innerHTML = '<div class="alert alert-success">✅ Speaker test complete!</div>';
                speakerVolumeContainer.style.display = 'none';
            }, 5000);

        } catch (error) {
            console.error('Speaker test error:', error);
            testSpeakerBtn.disabled = false;
            testSpeakerBtn.textContent = '🔊 Test Speaker';
            speakerTestStatus.innerHTML = '<div class="alert alert-danger">❌ Speaker test failed: ' + error.message +
                '</div>';
            speakerVolumeContainer.style.display = 'none';
        }
}    

// Test speaker for the modal/session context used by inline onclick handlers.
async function playTestSpeakerForSession(sessionId = 'global', durationMs = 3000) {
    const statusEl = document.getElementById(`micTestStatus-${sessionId}`) || document.getElementById('micTestStatus-global');
    const outputSel = document.getElementById('audioOutputSelect-speaker') || document.getElementById(`audioOutputSelect-${sessionId}`) || document.getElementById('audioOutputSelect-global');
    const selectedDeviceId = outputSel ? (outputSel.value || 'default') : 'default';

    let audioContext;
    let audioEl;
    let destination;
    let oscillator;

    try {
        if (statusEl) statusEl.innerHTML = '<div class="alert alert-info">Testing speaker...</div>';

        audioContext = new(window.AudioContext || window.webkitAudioContext)();
        oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        destination = audioContext.createMediaStreamDestination();

        oscillator.frequency.value = 440;
        oscillator.type = 'sine';
        gainNode.gain.setValueAtTime(0.08, audioContext.currentTime);

        oscillator.connect(gainNode);
        gainNode.connect(destination);

        audioEl = new Audio();
        audioEl.srcObject = destination.stream;

        if (typeof audioEl.setSinkId === 'function' && selectedDeviceId !== 'default') {
            try { await audioEl.setSinkId(selectedDeviceId); } catch (_) { }
        }

        await audioEl.play();
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + (Math.max(500, durationMs) / 1000));

        setTimeout(() => {
            try { audioEl.pause(); } catch (_) { }
            try { destination.stream.getTracks().forEach(t => t.stop()); } catch (_) { }
            try { audioContext.close(); } catch (_) { }
            if (statusEl) statusEl.innerHTML = '<div class="alert alert-success">Speaker test complete.</div>';
        }, Math.max(500, durationMs) + 50);
    } catch (e) {
        console.warn('playTestSpeakerForSession failed', e);
        try { if (audioEl) audioEl.pause(); } catch (_) { }
        try { if (destination) destination.stream.getTracks().forEach(t => t.stop()); } catch (_) { }
        try { if (audioContext) audioContext.close(); } catch (_) { }
        if (statusEl) statusEl.innerHTML = `<div class="alert alert-danger">Speaker test failed: ${e.message}</div>`;
    }
}

// Record from selected mic and play back once, used by inline onclick handler.
async function recordAndPlaybackMic(sessionId = 'global', durationMs = 5000) {
    const inputSel = document.getElementById(`audioInputSelect-${sessionId}`) || document.getElementById('audioInputSelect-global');
    const meterEl = document.getElementById(`micMeter-${sessionId}`) || document.getElementById('micMeter-global');
    const playbackEl = document.getElementById(`micTestPlayback-${sessionId}`) || document.getElementById('micTestPlayback-global');
    const statusEl = document.getElementById(`micTestStatus-${sessionId}`) || document.getElementById('micTestStatus-global');

    const selectedInputId = inputSel ? (inputSel.value || 'default') : 'default';
    let stream;
    let mediaRecorder;
    let rafId = null;
    const chunks = [];

    try {
        if (statusEl) statusEl.innerHTML = '<div class="alert alert-info">Recording microphone test...</div>';
        if (meterEl) meterEl.style.display = 'block';
        if (playbackEl) playbackEl.innerHTML = '';

        const constraints = {
            audio: selectedInputId && selectedInputId !== 'default'
                ? { deviceId: { exact: selectedInputId } }
                : true
        };

        stream = await navigator.mediaDevices.getUserMedia(constraints);

        // Lightweight level meter visualization for the existing meter bar.
        try {
            const ctx = new(window.AudioContext || window.webkitAudioContext)();
            const source = ctx.createMediaStreamSource(stream);
            const analyser = ctx.createAnalyser();
            analyser.fftSize = 256;
            source.connect(analyser);
            const data = new Uint8Array(analyser.frequencyBinCount);

            const tick = () => {
                analyser.getByteFrequencyData(data);
                const avg = data.reduce((a, b) => a + b, 0) / data.length;
                if (meterEl) {
                    meterEl.style.background = `linear-gradient(90deg, #28a745 ${Math.min(100, avg / 2.2)}%, #eee ${Math.min(100, avg / 2.2)}%)`;
                }
                rafId = requestAnimationFrame(tick);
            };
            tick();
        } catch (_) { }

        mediaRecorder = new MediaRecorder(stream);
        mediaRecorder.ondataavailable = (e) => {
            if (e.data && e.data.size > 0) chunks.push(e.data);
        };

        mediaRecorder.onstop = () => {
            try { stream.getTracks().forEach(t => t.stop()); } catch (_) { }
            if (rafId) cancelAnimationFrame(rafId);
            if (meterEl) meterEl.style.display = 'none';

            const blob = new Blob(chunks, { type: mediaRecorder.mimeType || 'audio/webm' });
            const url = URL.createObjectURL(blob);
            if (playbackEl) {
                playbackEl.innerHTML = `<audio controls src="${url}" style="width:100%;"></audio>`;
            }
            if (statusEl) statusEl.innerHTML = '<div class="alert alert-success">Mic test complete. Playback ready.</div>';
        };

        mediaRecorder.start();
        setTimeout(() => {
            if (mediaRecorder && mediaRecorder.state !== 'inactive') {
                mediaRecorder.stop();
            }
        }, Math.max(1000, durationMs));
    } catch (e) {
        console.warn('recordAndPlaybackMic failed', e);
        try { if (stream) stream.getTracks().forEach(t => t.stop()); } catch (_) { }
        if (rafId) cancelAnimationFrame(rafId);
        if (meterEl) meterEl.style.display = 'none';
        if (statusEl) statusEl.innerHTML = `<div class="alert alert-danger">Mic test failed: ${e.message}</div>`;
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // Test Microphone Button
    // Global microphone test button (if present) uses recordAndPlaybackMic('global',...)

    const toggleMicBtnElement = document.getElementById('toggleMicBtn');
    if (toggleMicBtnElement) {
        toggleMicBtnElement.addEventListener('click', function (e) {
            e.preventDefault();
            const btn = this;

            // Use WebPhone to toggle microphone
            if (webPhone && webPhone.isCallActive()) {
                isMicMuted = webPhone.toggleMicrophone();
                console.log('Microphone toggled, new state:', isMicMuted);
            } else {
                alert('No active call');
                return;
            }

            if (isMicMuted) {
                btn.textContent = '🎤 Unmute Microphone';
                document.getElementById('micStatus').textContent = '🔇 Muted';
                btn.classList.remove('btn-warning');
                btn.classList.add('btn-danger');
            } else {
                btn.textContent = '🎤 Mute Microphone';
                document.getElementById('micStatus').textContent = '✅ Active';
                btn.classList.remove('btn-danger');
                btn.classList.add('btn-warning');
            }
        });
    }

    // Hold Call functionality is now handled in the dialer modal (dialerHoldBtn) and Active Calls table
    // Legacy holdCallBtn code removed - use holdCallFromDialer() and renderActiveCalls() instead

    // Recording controls
    const startRecordingBtn = document.getElementById('startRecordingBtn');
    const pauseRecordingBtn = document.getElementById('pauseRecordingBtn');
    const resumeRecordingBtn = document.getElementById('resumeRecordingBtn');
    const stopRecordingBtn = document.getElementById('stopRecordingBtn');

    if (startRecordingBtn) {
        startRecordingBtn.addEventListener('click', async function () {
            try {
                const ok = await webPhone.startRecording();
                if (!ok) {
                    alert('Recording not supported or not allowed for this account.');
                    return;
                }
                startRecordingBtn.style.display = 'none';
                pauseRecordingBtn.style.display = 'inline-block';
                stopRecordingBtn.style.display = 'inline-block';
                document.getElementById('recordingStatus').textContent = '🔴 Recording';
            } catch (error) {
                alert('Failed to start recording: ' + error.message);
            }
        });
    }

    if (pauseRecordingBtn) {
        pauseRecordingBtn.addEventListener('click', async function () {
            try {
                const ok = await webPhone.pauseRecording();
                if (!ok) {
                    alert('Pause recording is not allowed on this account.');
                    return;
                }
                pauseRecordingBtn.style.display = 'none';
                resumeRecordingBtn.style.display = 'inline-block';
                document.getElementById('recordingStatus').textContent = '⏸️ Recording Paused';
            } catch (error) {
                alert('Failed to pause recording: ' + error.message);
            }
        });
    }

    if (resumeRecordingBtn) {
        resumeRecordingBtn.addEventListener('click', async function () {
            try {
                const ok = await webPhone.resumeRecording();
                if (!ok) {
                    alert('Resume recording is not allowed on this account.');
                    return;
                }
                resumeRecordingBtn.style.display = 'none';
                pauseRecordingBtn.style.display = 'inline-block';
                document.getElementById('recordingStatus').textContent = '🔴 Recording';
            } catch (error) {
                alert('Failed to resume recording: ' + error.message);
            }
        });
    }

    if (stopRecordingBtn) {
        stopRecordingBtn.addEventListener('click', async function () {
            try {
                await webPhone.stopRecording();
                startRecordingBtn.style.display = 'inline-block';
                pauseRecordingBtn.style.display = 'none';
                resumeRecordingBtn.style.display = 'none';
                stopRecordingBtn.style.display = 'none';
                document.getElementById('recordingStatus').textContent = 'Not recording';
                alert('Recording stopped');
            } catch (error) {
                alert('Failed to stop recording: ' + error.message);
            }
        });
    }
});
// ===== END: RECORDING AND AUDIO CONTROLS =====

console.log('✅ R-Dialer  audio and recording controls loaded');
