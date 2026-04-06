(function () {
    'use strict';

    var table = document.getElementById('bnm-entries-table');
    var search = document.getElementById('bnm-entries-search');
    if (!table || !search) return;

    var tbody = table.tBodies[0];
    var rows = Array.prototype.slice.call(tbody.rows);
    var noResults = document.querySelector('.bnm-no-results');
    var countEl = document.querySelector('.bnm-entries-count');
    var totalCount = rows.length;

    // --- Search ---
    search.addEventListener('input', function () {
        var term = this.value.toLowerCase();
        var visible = 0;
        rows.forEach(function (row) {
            var text = row.textContent.toLowerCase();
            var show = text.indexOf(term) !== -1;
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        if (noResults) noResults.style.display = visible === 0 ? '' : 'none';
        if (countEl) countEl.textContent = visible + ' / ' + totalCount;
    });

    // --- Sort ---
    var headers = table.querySelectorAll('th.bnm-sortable');
    headers.forEach(function (th, colIndex) {
        th.style.cursor = 'pointer';
        th.addEventListener('click', function () {
            var dir = th.getAttribute('data-sort-dir');
            var newDir = dir === 'asc' ? 'desc' : 'asc';

            // Reset all icons
            headers.forEach(function (h) {
                h.removeAttribute('data-sort-dir');
                var icon = h.querySelector('.bnm-sort-icon');
                if (icon) icon.textContent = '';
            });

            th.setAttribute('data-sort-dir', newDir);
            var icon = th.querySelector('.bnm-sort-icon');
            if (icon) icon.textContent = newDir === 'asc' ? '\u25B2' : '\u25BC';

            var sortType = th.getAttribute('data-sort');

            rows.sort(function (a, b) {
                var aVal, bVal;
                if (sortType === 'date') {
                    aVal = a.getAttribute('data-date') || '';
                    bVal = b.getAttribute('data-date') || '';
                } else {
                    aVal = (a.cells[colIndex] ? a.cells[colIndex].textContent : '').toLowerCase();
                    bVal = (b.cells[colIndex] ? b.cells[colIndex].textContent : '').toLowerCase();
                }
                if (aVal < bVal) return newDir === 'asc' ? -1 : 1;
                if (aVal > bVal) return newDir === 'asc' ? 1 : -1;
                return 0;
            });

            rows.forEach(function (row) {
                tbody.appendChild(row);
            });
        });
    });
})();
