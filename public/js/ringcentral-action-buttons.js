(function () {
    const loadingClass = 'rc-action-btn-loading';

    function getDefaultLabel(button) {
        if (!button) return '';
        return button.getAttribute('aria-label')
            || button.getAttribute('title')
            || button.textContent.trim()
            || 'Working';
    }

    function setStatus(target, text, statusClass) {
        if (!target || !text) return;
        target.textContent = text;
        target.classList.remove('text-muted', 'text-success', 'text-danger', 'text-warning');
        target.classList.add(statusClass || 'text-muted');
    }

    function setActionButtonLoading(buttonOrId, options) {
        const button = typeof buttonOrId === 'string'
            ? document.getElementById(buttonOrId)
            : buttonOrId;
        if (!button) return function () { };

        const opts = options || {};
        if (button.dataset.rcActionLoading === '1') {
            return function () { };
        }

        const original = {
            html: button.innerHTML,
            disabled: button.disabled,
            title: button.getAttribute('title'),
            ariaLabel: button.getAttribute('aria-label'),
            minWidth: button.style.minWidth
        };
        const label = opts.label || getDefaultLabel(button);
        const loadingText = opts.loadingText || 'Working...';
        const statusTarget = opts.statusTarget
            ? (typeof opts.statusTarget === 'string' ? document.querySelector(opts.statusTarget) : opts.statusTarget)
            : null;

        button.dataset.rcActionLoading = '1';
        button.dataset.rcActionOriginalLabel = label;
        button.classList.add(loadingClass);
        button.classList.toggle('rc-action-preserve-content', !!opts.preserveContent);
        if (!button.style.minWidth && button.offsetWidth > 0) {
            button.style.minWidth = button.offsetWidth + 'px';
        }
        if (!opts.keepEnabled) {
            button.disabled = true;
        }
        button.setAttribute('aria-busy', 'true');
        button.setAttribute('aria-label', loadingText);
        button.setAttribute('title', loadingText);

        const spinner = '<span class="rc-action-spinner" aria-hidden="true"></span>';
        if (!opts.preserveContent) {
            button.innerHTML = opts.iconOnly
                ? spinner
                : spinner + '<span class="rc-action-loading-text">' + loadingText + '</span>';
        }

        setStatus(statusTarget, opts.statusText || loadingText, opts.statusClass || 'text-muted');

        return function restoreActionButton(restoreOptions) {
            const restoreOpts = restoreOptions || {};
            if (restoreOpts.statusText) {
                setStatus(statusTarget, restoreOpts.statusText, restoreOpts.statusClass || 'text-muted');
            }
            if (!opts.preserveContent) {
                button.innerHTML = restoreOpts.html || original.html;
            }
            button.disabled = Object.prototype.hasOwnProperty.call(restoreOpts, 'disabled')
                ? !!restoreOpts.disabled
                : original.disabled;
            button.style.minWidth = original.minWidth;
            if (original.title === null) button.removeAttribute('title');
            else button.setAttribute('title', original.title);
            if (original.ariaLabel === null) button.removeAttribute('aria-label');
            else button.setAttribute('aria-label', original.ariaLabel);
            button.removeAttribute('aria-busy');
            button.classList.remove(loadingClass);
            button.classList.remove('rc-action-preserve-content');
            delete button.dataset.rcActionLoading;
            delete button.dataset.rcActionOriginalLabel;
        };
    }

    window.rcSetActionButtonLoading = setActionButtonLoading;
})();
