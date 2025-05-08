document.addEventListener('DOMContentLoaded', () => {
    fetchProducts();
});

function fetchProducts() {
    fetch('http://localhost:5000/api/product_data.php')
        .then(res => res.json())
        .then(products => {
            const productList = document.getElementById('product-list');
            productList.innerHTML = '';

            if (products.length === 0) {
                productList.innerHTML = '<p class="text-center text-muted">Keine Produkte gefunden.</p>';
                return;
            }

            products.forEach(product => {
                const col = document.createElement('div');
                col.className = 'col mb-5';

                // Dynamisches Bild setzen
                let productImage = 'https://dummyimage.com/450x300/dee2e6/6c757d.jpg'; // Standardbild
                if (product.image && product.image.trim() !== '') {
                    productImage = `http://localhost:5000/uploads/images/${product.image}`;
                }


                col.innerHTML = `
                    <div class="card h-100">
                        ${product.rating >= 5 ? '<div class="badge bg-dark text-white position-absolute" style="top: 0.5rem; right: 0.5rem">Top</div>' : ''}
                       <div class="ratio ratio-4x3">
                            <img src="${productImage}" alt="${escapeHtml(product.name)}" class="card-img-top object-fit-cover">
                            </div>
                        <div class="card-body p-4">
                            <div class="text-center">
                                <h5 class="fw-bolder">${escapeHtml(product.name)}</h5>
                                ${renderStars(product.rating)}
                                <div class="text-muted mb-1">${escapeHtml(product.description)}</div>
                                <div class="fw-bold mt-2">
                                    $${Number(product.price).toFixed(2)}
                                </div>
                            </div>
                        </div>
                        <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                            <div class="text-center">
                                <a class="btn btn-outline-dark mt-auto" href="product.php?id=${product.id}">
                                    Details ansehen
                                </a>
                            </div>
                        </div>
                    </div>
                `;
                productList.appendChild(col);
            });
        })
        .catch(err => {
            console.error("Fehler beim Laden der Produkte:", err);
            document.getElementById('product-list').innerHTML =
                '<div class="text-danger text-center">Fehler beim Laden der Produkte.</div>';
        });
}

function renderStars(rating) {
    let stars = '';
    for (let i = 0; i < rating; i++) {
        stars += '<div class="bi-star-fill"></div>';
    }
    return `<div class="d-flex justify-content-center small text-warning mb-2">${stars}</div>`;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
