{{-- #10 (Batch 5): DevTools / Inspect guard.
     Deliberate DevTools shortcuts (F12, Ctrl+Shift+I/J/C, Ctrl+U): 1st press = warning,
     2nd press = freeze the account (self) + reload (existing freeze block then locks the UI).
     Right-click is blocked (context menu) but does NOT escalate to a freeze, so normal use
     isn't punished. Note: client-side devtools detection is best-effort by nature. --}}
@auth
<script>
(function () {
    var attempts = 0, frozen = false;

    function freezeNow() {
        if (frozen) return;
        frozen = true;
        fetch("{{ route('security.devtools_freeze') }}", {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        }).then(function () {
            alert('Your account has been frozen due to repeated unauthorized Developer Tools access. Please contact your administrator.');
            window.location.reload();
        }).catch(function () { window.location.reload(); });
    }

    function inspectAttempt() {
        if (frozen) return;
        attempts++;
        if (attempts === 1) {
            alert('⚠️ Developer Tools / Inspect Element is not permitted on this portal.\n\nIf you try again, your account will be FROZEN and you will be locked out. Contact admin if you need access.');
        } else {
            freezeNow();
        }
    }

    document.addEventListener('keydown', function (e) {
        var k = e.keyCode || e.which;
        var isDevtools =
            k === 123 ||                                        // F12
            (e.ctrlKey && e.shiftKey && (k === 73 || k === 74 || k === 67)) || // Ctrl+Shift+I/J/C
            (e.ctrlKey && k === 85);                            // Ctrl+U (view source)
        if (isDevtools) {
            e.preventDefault();
            inspectAttempt();
            return false;
        }
    });

    // Block right-click (a common route to Inspect) but do NOT escalate to a freeze.
    document.addEventListener('contextmenu', function (e) { e.preventDefault(); return false; });
})();
</script>
@endauth
