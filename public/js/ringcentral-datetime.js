/**
 * R-Dialer  Date/Time Formatting (shared by calls + messages)
 * Edit RC_DATE_FORMAT below to change output across the UI.
 */
(function () {
    const DEFAULTS = {
        // Keep null to use browser local PC time across all tabs.
        offsetHours: null,
        use24Hour: true,
        yesterdayLabel: 'YDAY',
        showWeekdayForLast7Days: true,
        daysShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
    };

    const config = window.RC_DATE_FORMAT = Object.assign({}, DEFAULTS, window.RC_DATE_FORMAT || {});

    function parseBrowserLocalDate(dateText) {
        const raw = String(dateText || '').trim();
        if (!raw) return null;

        // Normalize compact offsets like +0000 -> +00:00, then let Date parse with timezone awareness.
        const normalized = raw.replace(/([+-]\d{2})(\d{2})$/, '$1:$2');
        const parsed = new Date(normalized);
        if (Number.isNaN(parsed.getTime())) return null;
        return parsed;
    }

    function coerceDate(value) {
        if (!value) return null;
        if (value instanceof Date) return new Date(value.getTime());
        if (typeof value === 'string') {
            const localParsed = parseBrowserLocalDate(value);
            if (localParsed) return localParsed;
        }
        const d = new Date(value);
        if (Number.isNaN(d.getTime())) return null;
        return d;
    }

    function applyOffset(dateObj) {
        if (!dateObj) return null;
        if (typeof config.offsetHours !== 'number') return dateObj;
        return new Date(dateObj.getTime() + (config.offsetHours * 60 * 60 * 1000));
    }

    function formatTime(dateObj) {
        if (!dateObj) return '';
        const hours = dateObj.getHours();
        const minutes = dateObj.getMinutes();
        if (config.use24Hour) {
            return String(hours).padStart(2, '0') + ':' + String(minutes).padStart(2, '0');
        }
        const h12 = hours % 12 || 12;
        const ampm = hours >= 12 ? 'PM' : 'AM';
        return h12 + ':' + String(minutes).padStart(2, '0') + ' ' + ampm;
    }

    function getDayShort(dateObj) {
        return config.daysShort[dateObj.getDay()];
    }

    function getMonthShort(dateObj) {
        return config.monthsShort[dateObj.getMonth()];
    }

    function formatMessageTime(creationTime) {
        const src = coerceDate(creationTime);
        if (!src) return '';

        const msgTime = applyOffset(src);
        const nowTime = applyOffset(new Date());

        const msgDateOnly = new Date(msgTime.getFullYear(), msgTime.getMonth(), msgTime.getDate());
        const todayOnly = new Date(nowTime.getFullYear(), nowTime.getMonth(), nowTime.getDate());
        const yesterday = new Date(todayOnly);
        yesterday.setDate(yesterday.getDate() - 1);

        if (msgDateOnly.getTime() === todayOnly.getTime()) {
            return formatTime(msgTime);
        }

        if (msgDateOnly.getTime() === yesterday.getTime()) {
            return config.yesterdayLabel || 'YDAY';
        }

        const weekAgo = new Date(todayOnly);
        weekAgo.setDate(weekAgo.getDate() - 7);
        if (config.showWeekdayForLast7Days && msgDateOnly > weekAgo && msgDateOnly < todayOnly) {
            return getDayShort(msgDateOnly);
        }

        const day = msgTime.getDate();
        const month = getMonthShort(msgTime);
        const year = msgTime.getFullYear();
        const currentYear = nowTime.getFullYear();
        return year !== currentYear ? month + ' ' + day + ', ' + year : month + ' ' + day;
    }

    function formatCallTime(startTime) {
        return formatMessageTime(startTime);
    }

    function formatLocalDateTime(value, options) {
        const src = coerceDate(value);
        if (!src) return '';
        const dt = applyOffset(src);
        return dt.toLocaleString(undefined, Object.assign({
            year: 'numeric',
            month: 'short',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        }, options || {}));
    }

    window.rcFormatMessageTime = formatMessageTime;
    window.rcFormatCallTime = formatCallTime;
    window.rcGetDayShort = getDayShort;
    window.rcGetMonthShort = getMonthShort;
    window.rcFormatTimeOnly = formatTime;
    window.rcFormatLocalDateTime = formatLocalDateTime;
})();
