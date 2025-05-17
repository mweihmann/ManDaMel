document.addEventListener('DOMContentLoaded', () => {
    // Lade Warenkorb beim Laden der Seite
    loadCheckoutCart();

    // Gutschein checken, wenn sich eingabefeld ändert
    const voucherInput = document.getElementById('voucher_code');
    voucherInput?.addEventListener('input', async function () {
        const code = this.value.trim();
        const token = localStorage.getItem('jwt') || sessionStorage.getItem('jwt');
        const infoBox = document.getElementById('voucher-info');

        if (!code) {
            infoBox.innerHTML = '';
            return;
        }

        try {
            const res = await fetch('http://localhost:5000/api/validate_voucher.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify({ voucher_code: code })
            });

            const data = await res.json();

            if (data.status === 'success') {
                const discount = parseFloat(data.discount ?? 0);
                const totalAfter = parseFloat(data.total_after ?? 0);
                const remainingValue = parseFloat(data.remaining_value ?? 0);
                const remainingAfter = parseFloat(data.remaining_after ?? (remainingValue - discount));

                infoBox.innerHTML = `
                    <div class="alert alert-success mt-2">
                        Voucher valid: <strong>${discount.toFixed(2)} €</strong> discount<br>
                        New total price: <strong>${totalAfter.toFixed(2)} €</strong><br>
                        Remaining voucher value after checkout: <strong>${remainingAfter.toFixed(2)} €</strong><br>
                        Original voucher value: <strong>${remainingValue.toFixed(2)} €</strong>
                    </div>
                `;
            } else {
                infoBox.innerHTML = `<div class="alert alert-danger mt-2">${data.message}</div>`;
            }
        } catch (err) {
            infoBox.innerHTML = `<div class="alert alert-danger mt-2">Fehler bei der Gutscheinprüfung</div>`;
        }
    });
    
    
    

    // Wenn das Formular existiert, auf Absenden reagieren
    const form = document.getElementById('checkout-form');
    form?.addEventListener('submit', async (e) => {
        e.preventDefault();

        const token = localStorage.getItem('jwt') || sessionStorage.getItem('jwt');
        if (!token) return alert('Bitte zuerst einloggen.');


        // Eingaben aus dem Formular lesen
        const voucher_code = document.getElementById('voucher_code')?.value.trim() || null;
        const payment_method = document.querySelector('input[name="payment_method"]:checked')?.value || null;

        // Mindestens eine Eingabe erforderlich
        if (!voucher_code && !payment_method) {
            document.getElementById('checkout-message').innerHTML = '<div class="text-danger">Bitte Gutschein oder Zahlungsmethode wählen.</div>';
            return;
        }

        try {
            // API-Aufruf an checkout.php
            const res = await fetch('http://localhost:5000/api/checkout.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify({ voucher_code, payment_method })
            });

            if (!res.ok) {
                const text = await res.text();
                throw new Error(`HTTP ${res.status}: ${text}`);
            }

            const data = await res.json();


            // Erfolgsmeldung bei erfolgreichem Checkout
            if (data.status === 'success') {
                document.getElementById('checkout-message').innerHTML =
                    `<div class="alert alert-success">✅ Bestellung erfolgreich abgeschlossen!<br>Bestellnummer: <strong>#${data.order_id}</strong><br>Weiterleitung in Kürze...</div>`;
                setTimeout(() => {
                    window.location.href = 'my_account.php'; // Weiterleitung zur Bestellübersicht
                }, 2500);
            } else {
                document.getElementById('checkout-message').innerHTML =
                    `<div class="alert alert-danger">${data.message || 'Fehler beim Checkout.'}</div>`;
            }

        } catch (err) {
            console.error('Checkout Fehler:', err);
            document.getElementById('checkout-message').innerHTML =
                `<div class="alert alert-danger">Serverfehler beim Checkout.</div>`;
        }
    });
});


// Warenkorb laden und anzeigen
async function loadCheckoutCart() {
    const token = localStorage.getItem('jwt') || sessionStorage.getItem('jwt');
    try {
        const res = await fetch('http://localhost:5000/api/cart_get.php', {
            headers: {
                ...(token && { 'Authorization': `Bearer ${token}` })
            },
            credentials: 'include' // Cookies bei Bedarf mitsenden
        });

        const data = await res.json();
        const container = document.getElementById('checkout-items');
        const totalContainer = document.getElementById('checkout-total');


        // Wenn Warenkorb leer ist
        if (!data.items || data.items.length === 0) {
            container.innerHTML = '<p class="text-muted">Dein Warenkorb ist leer.</p>';
            totalContainer.innerHTML = '';
            document.getElementById('checkout-form').style.display = 'none';
            return;
        }

        // Produkte auflisten
        let html = '<ul class="list-group">';
        data.items.forEach(item => {
            html += `
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    ${item.name} <span>${item.quantity} × ${parseFloat(item.price).toFixed(2)} €</span>
                </li>
            `;
        });
        html += '</ul>';

        container.innerHTML = html;
        totalContainer.innerHTML = `<strong>Gesamtsumme: ${parseFloat(data.total).toFixed(2)} €</strong>`;
    } catch (err) {
        console.error('Fehler beim Laden des Warenkorbs:', err);
        document.getElementById('checkout-items').innerHTML =
            '<p class="text-danger">Fehler beim Laden des Warenkorbs.</p>';
    }
    // Zahlungsmethoden nachladen
    loadSavedPaymentMethods(token);
}

// Gespeicherte Zahlungsmethoden laden
async function loadSavedPaymentMethods(token) {
    const methodGroup = document.getElementById('payment-method-group');
    if (!methodGroup) return;

    try {
        const res = await fetch('http://localhost:5000/api/get_payment_methods.php', {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });

        const data = await res.json();
        // Wenn keine Methoden vorhanden sind
        if (!data.success || !data.methods.length) {
            methodGroup.innerHTML = '<p class="text-muted">Keine Zahlungsmethoden gespeichert.</p>';
            return;
        }

        methodGroup.innerHTML = ''; // Vorherigen Inhalt löschen

        // Zahlungsmethoden anzeigen
        data.methods.forEach((method, index) => {
            const label = method.method === 'creditcard'
                ? `Kreditkarte •••• ${method.cc_last}`
                : method.method === 'iban'
                    ? `IBAN ${method.iban.slice(0, 6)}...`
                    : `Gutschein ${method.voucher_code}`;

            methodGroup.innerHTML += `
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment_method" 
                        value="${method.method}" id="pm${index}">
                    <label class="form-check-label" for="pm${index}">${label}</label>
                </div>
            `;
        });
    } catch (err) {
        console.error('Fehler beim Laden der Zahlungsmethoden:', err);
    }
}


