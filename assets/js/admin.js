document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.btn-delete').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            if (!window.confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });

    const searchInput = document.getElementById('table-search');

    if (searchInput) {
        searchInput.addEventListener('keyup', function () {
            const term = this.value.toLowerCase();
            const rows = document.querySelectorAll('.admin-table-wrapper tbody tr');

            rows.forEach(function (row) {
                const text = Array.from(row.querySelectorAll('td'))
                    .map(td => td.textContent)
                    .join(' ')
                    .toLowerCase();

                row.style.display = text.includes(term) ? '' : 'none';
            });
        });
    }

    const currentURL = window.location.href;

    document.querySelectorAll('.sidebar-nav a').forEach(function (link) {
        if (currentURL.includes(link.getAttribute('href'))) {
            link.classList.add('active');
        }
    });

    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar       = document.querySelector('.admin-sidebar');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.toggle('sidebar-open');
        });
    }

});