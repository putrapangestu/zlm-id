function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content || '';
}

function addToCompare(laptopId) {
    fetch('/compare/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken()
        },
        body: JSON.stringify({ laptop_id: laptopId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            updateFloatingCompare(data.count);
            updateCompareButtons();
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(() => showToast('Gagal terhubung ke server', 'error'));
}

function removeFromCompare(laptopId) {
    fetch(`/compare/remove/${laptopId}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': getCsrfToken() }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            updateFloatingCompare(data.count);
            updateCompareButtons();
            if (window.location.pathname === '/compare') location.reload();
        }
    })
    .catch(() => showToast('Gagal terhubung ke server', 'error'));
}

function clearCompare() {
    fetch('/compare/clear', {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': getCsrfToken() }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            updateFloatingCompare(0);
            updateCompareButtons();
            if (window.location.pathname === '/compare') location.reload();
        }
    })
    .catch(() => showToast('Gagal terhubung ke server', 'error'));
}

function updateFloatingCompare(count) {
    const widget = document.getElementById('floating-compare');
    const badge = document.getElementById('compare-badge');
    const countText = document.getElementById('compare-count');

    if (!widget) return;

    if (count > 0) {
        widget.classList.remove('hidden');
        if (badge) badge.textContent = count;
        if (countText) countText.textContent = count;
    } else {
        widget.classList.add('hidden');
    }
}

function updateCompareButtons() {
    fetch('/compare/ids', {
        headers: { 'X-CSRF-TOKEN': getCsrfToken() }
    })
    .then(res => res.json())
    .then(data => {
        document.querySelectorAll('[data-compare-btn]').forEach(btn => {
            const id = btn.dataset.laptopId;
            if (data.ids.includes(id)) {
                btn.classList.add('bg-blue-50', 'text-blue-600', 'border-blue-200');
            } else {
                btn.classList.remove('bg-blue-50', 'text-blue-600', 'border-blue-200');
            }
        });
    });
}

function showToast(message, type = 'info') {
    const colors = {
        success: 'bg-emerald-500',
        error: 'bg-red-500',
        info: 'bg-blue-500'
    };
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-[100] ${colors[type] || colors.info} text-white px-5 py-3 rounded-xl text-sm font-medium shadow-lg transition-all duration-300`;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

document.addEventListener('DOMContentLoaded', () => {
    updateCompareButtons();
    localStorage.removeItem('laptopsToCompare');
});
