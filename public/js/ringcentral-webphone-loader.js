/**
 * R-Dialer  WebPhone SDK Loader
 * Dynamically loads the R-Dialer WebPhone SDK bundle
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

function loadRcWebPhoneBundle() {
    return new Promise((resolve, reject) => {
        const localSrc = '/js/ringcentral-web-phone.min.js';
        const cdnSrc = 'https://unpkg.com/ringcentral-web-phone/dist/ringcentral-web-phone.min.js';
        // Avoid duplicate loads
        if (document.querySelector('script[data-rc-webphone="1"]')) {
            resolve('already-loaded');
            return;
        }
        const s = document.createElement('script');
        s.dataset.rcWebphone = '1';
        s.src = localSrc;
        s.onload = () => { rcLog('Loaded local web-phone'); resolve('local'); };
        s.onerror = () => {
            console.warn('Local web-phone not found, loading CDN...');
            const s2 = document.createElement('script');
            s2.dataset.rcWebphone = '1';
            s2.src = cdnSrc;
            s2.onload = () => { rcLog('Loaded web-phone from CDN'); resolve('cdn'); };
            s2.onerror = (e) => { console.error('Failed to load web-phone from CDN'); reject(e); };
            document.head.appendChild(s2);
        };
        document.head.appendChild(s);
    });
}
