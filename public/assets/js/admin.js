(function () {
    'use strict';

    /* ===================================================================
     * 1. Modale de confirmation custom
     *    Cible : <form data-confirm="<message>">.
     *    HTML construit par DOM API (pas d'innerHTML) — robuste contre toute injection.
     * =================================================================== */

    function el(tag, attrs, text) {
        var node = document.createElement(tag);
        if (attrs) {
            Object.keys(attrs).forEach(function (key) {
                if (key === 'class') { node.className = attrs[key]; return; }
                node.setAttribute(key, attrs[key]);
            });
        }
        if (text != null) node.textContent = text;
        return node;
    }

    function buildConfirmDom(message) {
        var overlay = el('div', {
            'class': 'modal-confirm',
            'role': 'alertdialog',
            'aria-modal': 'true',
            'aria-labelledby': 'modal-confirm-title',
            'aria-describedby': 'modal-confirm-message'
        });

        var card = el('div', { 'class': 'modal-confirm__card', 'role': 'document' });
        var icon = el('span', { 'class': 'modal-confirm__icon', 'aria-hidden': 'true' }, '!');
        var title = el('h2', { 'class': 'modal-confirm__title', 'id': 'modal-confirm-title' }, 'Confirmation requise');
        var msg = el('p', { 'class': 'modal-confirm__message', 'id': 'modal-confirm-message' }, message);

        var actions = el('div', { 'class': 'modal-confirm__actions' });
        var cancelBtn = el('button', { 'type': 'button', 'class': 'modal-confirm__cancel', 'data-modal-cancel': '' }, 'Annuler');
        var confirmBtn = el('button', { 'type': 'button', 'class': 'modal-confirm__confirm', 'data-modal-confirm': '' }, 'Confirmer');
        actions.appendChild(cancelBtn);
        actions.appendChild(confirmBtn);

        card.appendChild(icon);
        card.appendChild(title);
        card.appendChild(msg);
        card.appendChild(actions);
        overlay.appendChild(card);

        return { overlay: overlay, cancelBtn: cancelBtn, confirmBtn: confirmBtn };
    }

    function openConfirm(message) {
        return new Promise(function (resolve) {
            var dom = buildConfirmDom(message);
            var overlay = dom.overlay;
            var cancelBtn = dom.cancelBtn;
            var confirmBtn = dom.confirmBtn;

            document.body.appendChild(overlay);
            document.body.style.overflow = 'hidden';

            var lastFocus = document.activeElement;
            confirmBtn.focus();

            function close(result) {
                overlay.classList.add('is-leaving');
                document.removeEventListener('keydown', onKey);
                window.setTimeout(function () {
                    overlay.remove();
                    document.body.style.overflow = '';
                    if (lastFocus && typeof lastFocus.focus === 'function') {
                        lastFocus.focus();
                    }
                    resolve(result);
                }, 200);
            }

            function onKey(event) {
                if (event.key === 'Escape') { event.preventDefault(); close(false); return; }
                if (event.key === 'Enter' && document.activeElement !== cancelBtn) {
                    event.preventDefault(); close(true); return;
                }
                if (event.key === 'Tab') {
                    var focusables = [cancelBtn, confirmBtn];
                    var idx = focusables.indexOf(document.activeElement);
                    if (idx === -1) {
                        event.preventDefault();
                        focusables[0].focus();
                        return;
                    }
                    var nextIdx = event.shiftKey
                        ? (idx === 0 ? focusables.length - 1 : idx - 1)
                        : (idx === focusables.length - 1 ? 0 : idx + 1);
                    event.preventDefault();
                    focusables[nextIdx].focus();
                }
            }

            cancelBtn.addEventListener('click', function () { close(false); });
            confirmBtn.addEventListener('click', function () { close(true); });
            overlay.addEventListener('click', function (e) { if (e.target === overlay) close(false); });
            document.addEventListener('keydown', onKey);
        });
    }

    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        var confirmed = false;
        form.addEventListener('submit', function (event) {
            if (confirmed) return;
            event.preventDefault();
            var message = form.getAttribute('data-confirm') || 'Confirmer cette action ?';
            openConfirm(message).then(function (ok) {
                if (ok) {
                    confirmed = true;
                    form.submit();
                }
            });
        });
    });

    /* ===================================================================
     * 2. Recherche live + pagination côté client sur les tableaux admin
     *    Cible : <table data-table="recipes|admins" data-page-size="10">
     *    + lignes <tr data-search="<haystack>">.
     * =================================================================== */

    document.querySelectorAll('table[data-table]').forEach(function (table) {
        var pageSize = parseInt(table.getAttribute('data-page-size') || '10', 10);
        var rows = Array.prototype.slice.call(table.querySelectorAll('tbody > tr'));
        if (rows.length === 0) return;

        var key = table.getAttribute('data-table');
        var toolbar = document.querySelector('[data-table-toolbar="' + key + '"]');
        if (!toolbar) return;

        var searchInput = toolbar.querySelector('[data-table-search]');
        var prevBtn = toolbar.querySelector('[data-table-prev]');
        var nextBtn = toolbar.querySelector('[data-table-next]');
        var indicator = toolbar.querySelector('[data-table-indicator]');
        var emptyState = table.parentNode.querySelector('[data-table-empty]');

        var page = 0;
        var visibleRows = rows.slice();

        function normalize(value) {
            return (value || '').toLowerCase().normalize('NFD').replace(/\p{Diacritic}/gu, '');
        }

        function applyFilter() {
            var query = normalize(searchInput ? searchInput.value.trim() : '');
            visibleRows = rows.filter(function (row) {
                if (query === '') return true;
                return normalize(row.getAttribute('data-search') || row.textContent).includes(query);
            });
            page = 0;
            render();
        }

        function render() {
            var totalPages = Math.max(1, Math.ceil(visibleRows.length / pageSize));
            if (page > totalPages - 1) page = totalPages - 1;

            var start = page * pageSize;
            var end = start + pageSize;

            rows.forEach(function (row) { row.style.display = 'none'; });
            visibleRows.slice(start, end).forEach(function (row) { row.style.display = ''; });

            if (indicator) {
                if (visibleRows.length === 0) {
                    indicator.textContent = '0 résultat';
                } else {
                    indicator.textContent = (start + 1) + '–' + Math.min(end, visibleRows.length) + ' sur ' + visibleRows.length;
                }
            }
            if (prevBtn) prevBtn.disabled = page === 0;
            if (nextBtn) nextBtn.disabled = page >= totalPages - 1;
            if (emptyState) emptyState.hidden = visibleRows.length !== 0;
        }

        if (searchInput) searchInput.addEventListener('input', applyFilter);
        if (prevBtn) prevBtn.addEventListener('click', function () { if (page > 0) { page--; render(); } });
        if (nextBtn) nextBtn.addEventListener('click', function () { page++; render(); });

        render();
    });
})();
