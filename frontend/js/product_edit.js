document.addEventListener('DOMContentLoaded', () => {
    loadProducts();
});

function loadProducts() {
    fetch('http://localhost:5000/api/product_edit.php')
        .then(res => res.json())
        .then(products => {
            const container = document.getElementById('admin-edit-products');
            if (!container) return;

            if (products.length === 0) {
                container.innerHTML = '<p class="text-muted">Keine Produkte vorhanden.</p>';
                return;
            }

            let html = `<div class="accordion" id="productAccordion">`;

            products.forEach((product, index) => {
                const collapseId = `collapseProduct${index}`;
                const headingId = `headingProduct${index}`;

                html += `
                <div class="accordion-item">
                    <h2 class="accordion-header" id="${headingId}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#${collapseId}">
                            🛒 ${product.name}
                        </button>
                    </h2>
                    <div id="${collapseId}" class="accordion-collapse collapse" data-bs-parent="#productAccordion">
                        <div class="accordion-body">
                            <form data-id="${product.id}" onsubmit="editProduct(${product.id}); return false;" enctype="multipart/form-data">
                                <div class="mb-2"><label>Name</label><input name="name" class="form-control" value="${product.name}"></div>
                                <div class="mb-2"><label>Beschreibung</label><textarea name="description" class="form-control">${product.description}</textarea></div>
                                <div class="mb-2"><label>Preis (€)</label><input name="price" class="form-control" value="${product.price}"></div>
                                <div class="mb-2"><label>Rating (1-5)</label><input name="rating" class="form-control" value="${product.rating}"></div>
                                <div class="mb-2"><label>Kategorie-ID</label><input name="category_id" class="form-control" value="${product.category_id}"></div>
                                <div class="mb-2"><label>Datei (.zip, .pdf)</label><input name="file" type="file" class="form-control"></div>
                                <div class="mb-2"><label>Bild</label><input name="image" type="file" class="form-control"></div>
                                <div class="mb-2">
                                    <label>Status</label>
                                    <select name="active" class="form-select">
                                        <option value="1" ${product.active == 1 ? 'selected' : ''}>Aktiv</option>
                                        <option value="0" ${product.active == 0 ? 'selected' : ''}>Inaktiv</option>
                                    </select>
                                </div>
                                <button class="btn btn-success mt-2">💾 Speichern</button>
                            </form>
                        </div>
                    </div>
                </div>`;
            });

            html += `</div>`;
            container.innerHTML = html;
        });
}

function updateProduct(event, id) {
    event.preventDefault();
    const form = event.target;

    const data = {
        id,
        name: form.name.value,
        description: form.description.value,
        price: parseFloat(form.price.value),
        rating: parseInt(form.rating.value),
        category_id: parseInt(form.category_id.value)
    };

    fetch('http://localhost:5000/api/update_product.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(result => {
        if (result.status === 'success') {
            alert('Produkt aktualisiert.');
            loadProducts();
        } else {
            alert('Fehler beim Speichern.');
        }
    });
}

function editProduct(id) {
    const section = document.querySelector(`form[data-id="${id}"]`);
    if (!section) return;

    const formData = new FormData(section);
    formData.append('id', id);
    formData.append('active', section.active.value);

    fetch('http://localhost:5000/api/update_product.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(response => {
        if (response.status === 'success') {
            alert('Produkt gespeichert.');
            loadProducts();
        } else {
            alert('Fehler beim Speichern.');
        }
    })
    .catch(() => alert('Fehler beim Speichern.'));
}

