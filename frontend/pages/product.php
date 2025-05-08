<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <div id="product-detail-container" class="row justify-content-center">
        <!-- Dynamische Produktdetails werden hier eingefügt -->
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const productId = params.get('id');

    if (!productId) {
        document.getElementById('product-detail-container').innerHTML =
            '<p class="text-danger text-center">Produkt nicht gefunden.</p>';
        return;
    }

    fetch(`http://localhost:5000/api/product_detail.php?id=${productId}`)
        .then(response => response.json())
        .then(product => {
            const container = document.getElementById('product-detail-container');
            container.innerHTML = `
                <div class="col-md-8">
                    <div class="card mb-4">
                        <img src="http://localhost:5000/uploads/images/${product.image}" class="card-img-top" alt="${escapeHtml(product.name)}" style="object-fit: cover; max-height: 400px;">
                        <div class="card-body">
                            <h2 class="card-title">${escapeHtml(product.name)}</h2>
                            <div class="mb-2">${renderStars(product.rating)}</div>
                            <p class="card-text">${escapeHtml(product.description)}</p>
                            <h4 class="text-success">$${Number(product.price).toFixed(2)}</h4>
                            <div class="mt-4">
                                <label for="quantity" class="form-label">Menge:</label>
                                <input type="number" id="quantity" class="form-control w-25 d-inline-block" value="1" min="1" />
                                <button class="btn btn-primary ms-3">In den Warenkorb</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        })
        .catch(error => {
            console.error("Fehler beim Laden der Produktdaten:", error);
            document.getElementById('product-detail-container').innerHTML =
                '<p class="text-danger text-center">Produktdetails konnten nicht geladen werden.</p>';
        });
});

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function renderStars(rating) {
    let stars = '';
    for (let i = 0; i < 5; i++) {
        stars += `<i class="bi ${i < rating ? 'bi-star-fill text-warning' : 'bi-star text-muted'} me-1"></i>`;
    }
    return stars;
}
</script>

<?php include '../includes/footer.php'; ?>
