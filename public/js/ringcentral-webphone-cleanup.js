/**
 * R-Dialer  WebPhone Cleanup Handlers
 * Handles cleanup and disposal of WebPhone instances to prevent SIP slot exhaustion
 */

(function initRcLogger() {
    if (window.rcLog) return;
    window.rcLogLevel = window.rcLogLevel || 'brief';
    window.rcLog = function () {
        if (window.rcLogLevel === 'verbose') {
            window.console.log.apply(console, arguments);
        }
    };
    window.rcInfo = function () {
        if (window.rcLogLevel !== 'silent') {
            console.info.apply(console, arguments);
        }
    };
})();

let webPhoneDisposed = false;

// Dispose WebPhone using pagehide event (more reliable than unload)
// This prevents the 5-instance limit from being exhausted as tabs are closed/reopened
function disposeWebPhoneInstance() {
    if (webPhoneDisposed) {
        rcLog('ℹ️ WebPhone already disposed, skipping');
        return;
    }
    
    webPhoneDisposed = true;
    
    try {
        rcLog('🧹 Page unload/navigation detected - disposing WebPhone instance to free SIP slot...');
        
        if (webPhone && typeof webPhone.dispose === 'function') {
            rcLog('📡 Sending dispose request to WebPhone...');
            // Call dispose - browser will complete the operation even after page navigation
            webPhone.dispose().then(() => {
                rcLog('✅ WebPhone SDK disposed successfully - SIP slot freed');
            }).catch(err => {
                console.warn('⚠️ Warning: WebPhone dispose encountered error:', err?.message || err);
            });
        } else {
            rcLog('ℹ️ WebPhone not available for disposal');
        }
    } catch (err) {
        console.warn('⚠️ Error in dispose handler:', err?.message || err);
    }
}

// Warn when user tries to leave during an active call. Do not end the call here:
// if the user cancels the browser prompt, the call must keep running.
window.addEventListener('beforeunload', function (e) {
    try {
        if (webPhone && typeof webPhone.isCallActive === 'function' && webPhone.isCallActive()) {
            rcLog('Active call detected during beforeunload - prompting user before leaving...');

            // Show warning to user. Chrome will display its own generic text.
            const msg = 'A call is active. Leaving this page may disconnect the call.';
            e.preventDefault();
            e.returnValue = msg;
            return msg;
        }
    } catch (err) {
        console.error('Error in beforeunload handler:', err);
    }
});

// Use pagehide event (fires before unload, more reliable in modern browsers)
window.addEventListener('pagehide', function() {
    rcLog('📱 pagehide event fired');
    disposeWebPhoneInstance();
});

// Fallback: unload event (for older browsers)
window.addEventListener('unload', function() {
    rcLog('📱 unload event fired');
    disposeWebPhoneInstance();
});

// Additional safety: also trigger on visibility change to hidden
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        rcLog('📱 Document hidden');
        // Don't dispose here - just log visibility change
    }
});

// Manual test function for debugging disposal
window.testDispose = async function() {
    rcLog('🧪 Testing WebPhone disposal...');
    rcLog('webPhone object exists:', !!webPhone);
    rcLog('webPhone.dispose exists:', !!(webPhone && typeof webPhone.dispose === 'function'));
    
    if (webPhone && typeof webPhone.dispose === 'function') {
        rcLog('🚀 Calling webPhone.dispose()...');
        try {
            await webPhone.dispose();
            rcLog('✅ Disposal completed successfully');
        } catch (err) {
            console.error('❌ Disposal failed:', err);
        }
    } else {
        console.error('❌ Cannot dispose - webPhone not available');
    }
};

rcLog('✅ WebPhone disposal handlers registered. You can test with: window.testDispose()');
