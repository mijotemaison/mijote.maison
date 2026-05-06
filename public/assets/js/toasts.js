(function () {
    'use strict';

    var DISMISS_MS = 4500;
    var EXIT_MS = 280;

    function dismiss(toast) {
        if (toast.classList.contains('is-leaving')) return;
        toast.classList.add('is-leaving');
        window.setTimeout(function () { toast.remove(); }, EXIT_MS);
    }

    function setupToast(toast) {
        var closeBtn = toast.querySelector('.flash-toast-close');
        if (closeBtn) closeBtn.addEventListener('click', function () { dismiss(toast); });

        var timer = window.setTimeout(function () { dismiss(toast); }, DISMISS_MS);

        toast.addEventListener('mouseenter', function () { window.clearTimeout(timer); });
        toast.addEventListener('mouseleave', function () {
            timer = window.setTimeout(function () { dismiss(toast); }, DISMISS_MS / 2);
        });
    }

    document.querySelectorAll('.flash-stack .flash-toast').forEach(setupToast);

    document.addEventListener('keydown', function (event) {
        if (event.key !== 'Escape') return;
        var toasts = document.querySelectorAll('.flash-stack .flash-toast:not(.is-leaving)');
        if (toasts.length === 0) return;
        dismiss(toasts[toasts.length - 1]);
    });
})();
