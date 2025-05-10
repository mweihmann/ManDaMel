document.addEventListener('DOMContentLoaded', () => {
    loadProducts();
});

function loadProducts() {
    fetch('http://localhost:5000/api/product_edit.php')
        .then(res => res.json())
        .then(products => {
            const container = document.getElementById('admin-edit-products');
            if (!container) return;
            container.innerHTML = `
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Beschreibung</th>
                            <th>Preis</th>
                            <th>Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${products.map(product => `
                            <tr>
                                <td>${product.name}</td>
                                <td>${product.description}</td>
                                <td>$${Number(product.price).toFixed(2)}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="editProduct(${product.id})">Bearbeiten</button>
                                    <button class="btn btn-sm btn-danger" onclick="deactivateProduct(${product.id})">Deaktivieren</button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
        })
        .catch(err => {
            console.error('Fehler beim Laden der Produkte:', err);
        });
}

function editProduct(id) {
    alert('Produkt bearbeiten: ' + id);
}

function deactivateProduct(id) {
    if (!confirm('Produkt wirklich deaktivieren?')) return;

    fetch('http://localhost:5000/api/deactivate_product.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ id })
    })
    .then(res => res.json())
    .then(response => {
        if (response.status === 'success') {
            loadProducts(); // Liste neu laden
        } else {
            alert('Fehler beim Deaktivieren');
        }
    })
    .catch(err => {
        console.error('Fehler beim Deaktivieren:', err);
    });
}
