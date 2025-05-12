document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('product-search');
    const filterForm = document.getElementById("filterForm");
    const searchForm = document.querySelector('form[role="search"]');

    // Suchformular-Absenden verhindern
    if (searchForm) {
        searchForm.addEventListener('submit', (e) => {
            e.preventDefault();
        });
    }

    // Live-Suche aktivieren
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            const query = searchInput.value.trim();
            const formData = new FormData(filterForm);
            fetchProducts(query, formData);
        });
    }

    // Filterformular verarbeiten
    if (filterForm) {
        filterForm.addEventListener("submit", (e) => {
            e.preventDefault();
            const formData = new FormData(filterForm);
            const query = searchInput?.value.trim() || '';
            fetchProducts(query, formData);
        });
    }

    // Initialer Abruf aller Produkte
    fetchProducts();
});

function fetchProducts(query = '', formData = null) {
    let url = `http://localhost:5000/api/product_data.php?search=${encodeURIComponent(query)}`;


    if (formData) {
        const params = new URLSearchParams();
        for (const [key, value] of formData.entries()) {
            if (value !== '') {
                params.append(key, value);
            }
        }
        url += '&' + params.toString();
    }

    fetch(url)
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
                let productImage;
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
                                    ${Number(product.price).toLocaleString('de-DE', { style: 'currency', currency: 'EUR' })}
                                </div>
                            </div>
                        </div>
                        <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                            <div class="text-center">
                                <a class="btn btn-outline-dark mt-auto" href="product.php?id=${product.id}">
                                    Show details
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

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function renderStars(rating) {
    let stars = '';
    for (let i = 0; i < rating; i++) {
        stars += '<div class="bi-star-fill"></div>';
    }
    return `<div class="d-flex justify-content-center small text-warning mb-2">${stars}</div>`;
}
