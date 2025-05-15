document.addEventListener('DOMContentLoaded', () => {
    const token = localStorage.getItem('jwt') || sessionStorage.getItem('jwt');
    if (!token) return location.href = 'login.php';

    const list = document.getElementById('voucher-list');
    const modal = new bootstrap.Modal(document.getElementById('voucherModal'));
    const form = document.getElementById('voucher-form');

    function loadVouchers() {
        fetch('http://localhost:5000/api/manage_vouchers.php', {
            headers: { 'Authorization': `Bearer ${token}` }
        })
            .then(res => res.json())
            .then(data => {
                list.innerHTML = '<ul class="list-group">';
                data.forEach(v => {
                    list.innerHTML += `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>${v.code}</strong> — ${parseFloat(v.value).toFixed(2)} €
                                <br><small>gültig bis: ${new Date(v.expires_at).toLocaleString()}</small>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-outline-primary me-2 edit-btn" data-id="${v.id}" data-code="${v.code}" data-value="${v.value}" data-expires="${v.expires_at}">Bearbeiten</button>
                                <button class="btn btn-sm btn-outline-danger delete-btn" data-id="${v.id}">Löschen</button>
                            </div>
                        </li>`;
                });
                list.innerHTML += '</ul>';
            });
    }

    document.getElementById('create-btn').addEventListener('click', () => {
        form.reset();
        form['voucher-id'].value = '';
        modal.show();
    });

    form.addEventListener('submit', async e => {
        e.preventDefault();
        const id = form['voucher-id'].value;
        const data = {
            id,
            code: form['voucher-code'].value,
            value: parseFloat(form['voucher-value'].value),
            expires_at: form['voucher-expiry'].value
        };

        const method = id ? 'PUT' : 'POST';

        await fetch('http://localhost:5000/api/manage_vouchers.php', {
            method,
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        modal.hide();
        loadVouchers();
    });

    list.addEventListener('click', async e => {
        if (e.target.classList.contains('edit-btn')) {
            form['voucher-id'].value = e.target.dataset.id;
            form['voucher-code'].value = e.target.dataset.code;
            form['voucher-value'].value = e.target.dataset.value;
            form['voucher-expiry'].value = e.target.dataset.expires.replace(' ', 'T');
            modal.show();
        }

        if (e.target.classList.contains('delete-btn')) {
            const id = e.target.dataset.id;
            if (confirm('Diesen Gutschein wirklich löschen?')) {
                await fetch('http://localhost:5000/api/manage_vouchers.php', {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id=${id}`
                });
                loadVouchers();
            }
        }
    });

    loadVouchers();
});
