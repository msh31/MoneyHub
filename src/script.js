function resetForm() {
    document.getElementById('transaction-form').reset();
}

function filterTransactions() {
    const filterValue = document.getElementById('filterType').value;
    const rows = document.querySelectorAll('#transactionsTable tbody tr');
    
    rows.forEach(row => {
        const type = row.getAttribute('data-type');
        if (filterValue === 'all' || type === filterValue) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

document.getElementById('transaction-form').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');

    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Saving...';
    submitBtn.disabled = true;
});