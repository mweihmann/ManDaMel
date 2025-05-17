document.addEventListener('DOMContentLoaded', () => {
    // JWT Token abrufen
    const token = localStorage.getItem('jwt') || sessionStorage.getItem('jwt');
    if (!token) return location.href = 'login.php';

    // DOM-Elemente abrufen
    const list = document.getElementById('voucher-list');
    const modal = new bootstrap.Modal(document.getElementById('voucherModal'));
    const form = document.getElementById('voucher-form');

    // Gutscheincode generieren
    document.getElementById('generate-code-btn')?.addEventListener('click', () => {
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let code = '';
        for (let i = 0; i < 5; i++) {
            code += characters[Math.floor(Math.random() * characters.length)];
        }
        form['voucher-code'].value = code;
    });

    // Gutscheine laden
    function loadVouchers() {
        fetch('http://localhost:5000/api/manage_vouchers.php', {
            headers: { 'Authorization': `Bearer ${token}` }
        })
            .then(res => res.json())
            .then(data => {
                list.innerHTML = '<ul class="list-group">';
                data.forEach(v => {

                    let remaining = parseFloat(v.remaining_value ?? v.value ?? 0).toFixed(2);
                    let total = parseFloat(v.value ?? 0).toFixed(2);
                    let badge = '';
                    
                    if (v.is_used) badge = '<span class="badge bg-secondary ms-2">Used</span>';
                    else if (v.expired) badge = '<span class="badge bg-warning text-dark ms-2">Expired</span>';

                    // Einzelnen Gutschein anzeigen
                    list.innerHTML += `
                        <li class="list-group-item d-flex justify-content-between align-items-center ${v.is_used || v.expired ? 'opacity-75' : ''}">
                            <div>
                                <strong>${v.code}</strong> — ${remaining} Euro remaining / ${total} Euro ${badge}
                                <br><small>valid until: ${new Date(v.expires_at).toLocaleString()}</small>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-outline-primary me-2 edit-btn" data-id="${v.id}" data-code="${v.code}" data-value="${v.value}" data-expires="${v.expires_at}" ${v.is_used ? 'disabled' : ''}>
                                    Edit
                                </button>
                                ${(!v.is_used && !v.expired) ? `
                                    <button class="btn btn-sm btn-outline-warning use-btn" data-id="${v.id}">
                                        Mark as used
                                    </button>` : ''}
                            </div>
                        </li>`;
                });
                list.innerHTML += '</ul>';
            });
    }

    // Neuer Gutschein – Modal öffnen
    document.getElementById('create-btn').addEventListener('click', () => {
        form.reset();
        form['voucher-id'].value = '';
        modal.show();
    });

    // Gutschein speichern (neu oder bearbeiten)
    form.addEventListener('submit', async e => {
        e.preventDefault();
        const id = form['voucher-id'].value;
        const data = {
            id,
            code: form['voucher-code'].value,
            value: parseFloat(form['voucher-value'].value),
            expires_at: form['voucher-expiry'].value
        };

        const method = id ? 'PUT' : 'POST'; // PUT = bearbeiten, POST = neu

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

    // Bearbeiten oder als benutzt markieren
    list.addEventListener('click', async e => {
        // Bearbeiten
        if (e.target.classList.contains('edit-btn')) {
            form['voucher-id'].value = e.target.dataset.id;
            form['voucher-code'].value = e.target.dataset.code;
            form['voucher-value'].value = e.target.dataset.value;
            form['voucher-expiry'].value = e.target.dataset.expires.replace(' ', 'T');
            modal.show();
        }

        // Als benutzt markieren
        if (e.target.classList.contains('use-btn')) {
            const id = e.target.dataset.id;
            if (confirm('Do you really want to mark the voucher as used?')) {
                await fetch('http://localhost:5000/api/manage_vouchers.php', {
                    method: 'PATCH',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id })
                });
                loadVouchers();
            }
        }
    });

    // Initiale Gutscheine laden
    loadVouchers();
});
