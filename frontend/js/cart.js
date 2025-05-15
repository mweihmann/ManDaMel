function getJwtToken() {
    return localStorage.getItem('jwt') || sessionStorage.getItem('jwt') || null;
}

document.addEventListener('DOMContentLoaded', () => {
    attachCartHandlers();
    loadCartCount();
});

function attachCartHandlers() {
    document.body.addEventListener('click', async (e) => {
        if (e.target.classList.contains('add-to-cart')) {
            const productId = e.target.dataset.productId;
            await addToCart(productId);
        } else if (e.target.classList.contains('remove-from-cart')) {
            const productId = e.target.dataset.productId;
            await updateQuantity(productId, 0);
        } else if (e.target.classList.contains('increase-qty')) {
            const productId = e.target.dataset.productId;
            await adjustQuantity(productId, 1);
        } else if (e.target.classList.contains('decrease-qty')) {
            const productId = e.target.dataset.productId;
            await adjustQuantity(productId, -1);
        }
    });

    document.querySelectorAll('[data-draggable-product]').forEach(elem => {
        elem.setAttribute('draggable', true);
        elem.addEventListener('dragstart', (e) => {
            e.dataTransfer.setData('productId', e.target.dataset.productId);
        });
    });

    const cartIcon = document.getElementById('cart-icon-dropzone');
    if (cartIcon) {
        cartIcon.addEventListener('dragover', e => e.preventDefault());
        cartIcon.addEventListener('drop', async (e) => {
            e.preventDefault();
            const productId = e.dataTransfer.getData('productId');
            if (productId) await addToCart(productId);
        });
    }
}

async function addToCart(productId, quantity = 1) {
    try {
        const jwt = getJwtToken();
        const res = await fetch('http://localhost:5000/api/cart_add.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                ...(jwt && { 'Authorization': `Bearer ${jwt}` })
            },
            body: JSON.stringify({ product_id: productId, quantity }),
            credentials: 'include' // for guest users
        });
        const data = await res.json();
        if (data.status === 'success') {
            updateCartBadge(data.count || 1);
            await loadCartCount();
            await renderCartSidebar();
        } else {
            alert(data.message || 'Fehler beim Hinzufügen zum Warenkorb.');
        }
    } catch (err) {
        console.error('Warenkorb Fehler:', err);
    }
}

async function updateQuantity(productId, quantity) {
    const jwt = getJwtToken();
    const res = await fetch('http://localhost:5000/api/cart_update.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            ...(jwt && { 'Authorization': `Bearer ${jwt}` })
        },
        body: JSON.stringify({ product_id: productId, quantity }),
        credentials: 'include'
    });
    const data = await res.json();
    if (data.status === 'success') {
        updateCartBadge(data.count);
        renderCartSidebar();
    }
}

async function adjustQuantity(productId, diff) {
    const jwt = getJwtToken();
    const res = await fetch('http://localhost:5000/api/cart_get.php', {
        headers: {
            ...(jwt && { 'Authorization': `Bearer ${jwt}` })
        },
        credentials: 'include' // for guest users
    });
    const data = await res.json();
    const item = data.items.find(i => i.id == productId);
    if (item) {
        await updateQuantity(productId, item.quantity + diff);
    }
}

function updateCartBadge(count) {
    const badge = document.querySelector('.bi-cart-fill + .badge');
    if (badge) badge.textContent = count;
}

async function loadCartCount() {
    try {
        const jwt = getJwtToken();
        const res = await fetch('http://localhost:5000/api/cart_get.php', {
            headers: {
                ...(jwt && { 'Authorization': `Bearer ${jwt}` })
            },
            credentials: 'include'
        });
        const data = await res.json();
        if (data.status === 'success') {
            const items = data.items || [];
            updateCartBadge(items.reduce((sum, item) => sum + parseInt(item.quantity), 0));
            renderCartSidebar(data.items);
        } else {
            updateCartBadge(0);
        }
    } catch (err) {
        console.error('Fehler beim Laden des Warenkorbs:', err);
    }
}

async function renderCartSidebar(items = null) {
    try {
        const jwt = getJwtToken();
        let total = 0;
        
        if (!items) {
            const res = await fetch('http://localhost:5000/api/cart_get.php', {
                headers: {
                    ...(jwt && { 'Authorization': `Bearer ${jwt}` })
                },
                credentials: 'include'
            });
            const data = await res.json();
            items = data.items || [];
            total = data.total || 0;
        } else {
            total = items.reduce((sum, item) => sum + item.quantity * parseFloat(item.price), 0);
        }

        const container = document.getElementById('cartSidebarBody');
        if (!container) return;

        if (items.length === 0) {
            container.innerHTML = '<p class="text-muted">Dein Warenkorb ist leer.</p>';
            return;
        }

        let html = '<ul class="list-group mb-3">';
        items.forEach(item => {
            html += `
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div>
                        <strong>${item.name}</strong><br>
                        <small>${item.price} €</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <button class="btn btn-sm btn-outline-secondary decrease-qty me-1" data-product-id="${item.id}">-</button>
                        <span>${item.quantity}</span>
                        <button class="btn btn-sm btn-outline-secondary increase-qty ms-1" data-product-id="${item.id}">+</button>
                        <button class="btn btn-sm btn-outline-danger ms-3 remove-from-cart" data-product-id="${item.id}">✕</button>
                    </div>
                </li>
            `;
        });
        html += '</ul>';

        html += `
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <strong>Gesamt:</strong>
                    <span>${total.toFixed(2)} €</span>
                </div>
            `;

        if (jwt) {
            container.innerHTML = html + '<a href="checkout.php" class="btn btn-primary w-100">Zur Kasse</a>';
        } else {
            container.innerHTML = html + `
                <p class="text-center text-muted mt-3">
                    Please <a href="login.php" class="fw-bold">login</a>, to checkout.
                </p>
            `;
        }
        
    } catch (err) {
        console.error('Error showing the cart:', err);
    }
}

window.submitCheckout = async function () {
    const selectedMethod = document.querySelector('[name="payment_method"]:checked')?.value;
    const voucherCode = document.getElementById('voucher_code')?.value;

    if (!selectedMethod && !voucherCode) {
        alert('Bitte Zahlungsmethode oder Gutschein wählen.');
        return;
    }

    const payload = {
        payment_method: selectedMethod || null,
        voucher_code: voucherCode || null
    };

    try {
        const jwt = getJwtToken();
        const res = await fetch('http://localhost:5000/api/checkout.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                ...(jwt && { 'Authorization': `Bearer ${jwt}` })
            },
            body: JSON.stringify(payload)
        });

        const data = await res.json();
        if (data.status === 'success') {
            alert('Bestellung erfolgreich abgeschlossen!');
            updateCartBadge(0);
            window.location.href = 'my_orders.php';
        } else {
            alert(data.message || 'Fehler beim Checkout.');
        }
    } catch (err) {
        console.error('Checkout Fehler:', err);
    }
};
