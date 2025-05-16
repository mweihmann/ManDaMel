<?php include 'includes/header.php'; ?>

<!-- Filterformular -->
<section class="py-3 bg-light">
    <div class="container">
        <form id="filterForm" class="row g-3">

            <!-- Sortieroptionen -->
            <div class="col-12 col-md-4">
                <select name="sort_by" class="form-select">
                    <option value="">Sort by</option>
                    <option value="price_asc">Price: Low to High</option>
                    <option value="price_desc">Price: High to Low</option>
                    <option value="rating_asc">Rating: Low to High</option>
                    <option value="rating_desc">Rating: High to Low</option>
                </select>
            </div>

            <!-- Kategorieauswahl -->
            <div class="col-12 col-md-3">
                <select name="category_id" class="form-select">
                    <option value="">All Categories</option>
                    <option value="1">eBooks</option>
                    <option value="2">Software</option>
                    <option value="3">Courses</option>
                </select>
            </div>

            <!-- Bewertungsauswahl -->
            <div class="col-12 col-md-3">
                <select name="rating" class="form-select">
                    <option value="">Select Rating</option>
                    <option value="5">★★★★★</option>
                    <option value="4">★★★★☆</option>
                    <option value="3">★★★☆☆</option>
                    <option value="2">★★☆☆☆</option>
                    <option value="1">★☆☆☆☆</option>
                </select>
            </div>

            <!-- Filter-Button -->
            <div class="col-12 col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
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

<!-- JavaScript für Produktsuche -->
<script src="../js/products.js"></script>

<?php include 'includes/footer.php'; ?>