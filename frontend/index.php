<?php include 'includes/header.php'; ?>

<!-- Filterformular -->
<section class="py-3 bg-light">
    <div class="container">
        <form id="filterForm" class="row g-3">
            <div class="col-md-4">
                <select name="sort_by" class="form-select">
                    <option value="">Sortieren nach</option>
                    <option value="price_asc">Preis aufsteigend</option>
                    <option value="price_desc">Preis absteigend</option>
                    <option value="rating_asc">Bewertung aufsteigend</option>
                    <option value="rating_desc">Bewertung absteigend</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="category_id" class="form-select">
                    <option value="">Alle Kategorien</option>
                    <option value="1">eBooks</option>
                    <option value="2">Software</option>
                    <option value="3">Kurse</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="rating" class="form-select">
                    <option value="">Bewertung auswählen</option>
                    <option value="5">★★★★★</option>
                    <option value="4">★★★★☆</option>
                    <option value="3">★★★☆☆</option>
                    <option value="2">★★☆☆☆</option>
                    <option value="1">★☆☆☆☆</option>
                </select>
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filtern</button>
            </div>
        </form>
    </div>
</section>

<!-- Produktliste -->
<section class="py-5">
    <div class="container px-4 px-lg-5 mt-5">
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center" id="product-list">
            <!-- Produkte werden per JS eingefügt -->
        </div>
    </div>
</section>

<!-- JS -->
<script src="../js/products.js"></script>

<?php include 'includes/footer.php'; ?>
