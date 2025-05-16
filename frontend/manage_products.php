<?php include 'includes/header.php'; ?>

<h1 class="text-center my-4">Product Dashboard</h1>

<!-- Produktliste -->
<section class="container mb-5">
    <h2>Products</h2>
    <div id="admin-product-list" class="row"></div>
</section>

<!-- Produkt hinzufügen -->
<section class="container mb-5">
    <div class="accordion" id="addProductAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingAdd">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdd" aria-expanded="true" aria-controls="collapseAdd">
                    ➕ Add New Product
                </button>
            </h2>
            <div id="collapseAdd" class="accordion-collapse collapse show" aria-labelledby="headingAdd" data-bs-parent="#addProductAccordion">
                <div class="accordion-body">

                    <!-- Produktformular mit Dateiupload -->
                    <form id="add-product-form" enctype="multipart/form-data">

                        <!-- Produktname -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <!-- Beschreibung -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" required></textarea>
                        </div>

                        <!-- Bewertung (1-5) -->
                        <div class="mb-3">
                            <label for="rating" class="form-label">Rating (1-5)</label>
                            <input type="number" class="form-control" id="rating" name="rating" min="1" max="5" required>
                        </div>

                        <!-- Preis -->
                        <div class="mb-3">
                            <label for="price" class="form-label">Price (€)</label>
                            <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                        </div>

                        <!-- Kategorie -->
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="1">eBooks</option>
                                <option value="2">Software</option>
                                <option value="3">Courses</option>
                            </select>
                        </div>

                        <!-- Tags -->
                        <div class="mb-3">
                            <label for="file" class="form-label">File (e.g. PDF, ZIP)</label>
                            <input type="file" class="form-control" id="file" name="file" required>
                        </div>

                        <!-- Bild -->
                        <div class="mb-3">
                            <label for="image" class="form-label">Product Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" required onchange="previewImage(event)">
                            <img id="preview" class="mt-3" style="max-height: 150px; display: none;" />
                        </div>
                        <button type="submit" class="btn btn-primary">Add Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Bestehende Produkte verwalten -->
<section class="container mb-5">
    <h2>Manage Products</h2>
    <div id="admin-edit-products">
        <!-- Produkttabelle wird hier später dynamisch per JS eingefügt -->
    </div>
</section>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = () => {
            const img = document.getElementById('preview');
            img.src = reader.result;
            img.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

<!-- <script src="../js/admin.js"></script> -->

<?php include 'includes/footer.php'; ?>