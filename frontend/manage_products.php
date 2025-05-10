<?php include 'includes/header.php'; ?>

<h1 class="text-center my-4">Product Dashboard</h1>

<!-- Produktliste -->
<section class="container mb-5">
    <h2>Produkte</h2>
    <div id="admin-product-list" class="row"></div>
</section>

<!-- Produkt hinzufügen -->
<section class="container mb-5">
    <h2>Neues Produkt hinzufügen</h2>
    <form id="add-product-form" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Produktname</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Beschreibung</label>
            <textarea class="form-control" id="description" name="description" required></textarea>
        </div>

        <div class="mb-3">
            <label for="rating" class="form-label">Bewertung (1-5)</label>
            <input type="number" class="form-control" id="rating" name="rating" min="1" max="5" required>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Preis (€)</label>
            <input type="number" class="form-control" id="price" name="price" step="0.01" required>
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Kategorie</label>
            <select class="form-select" id="category_id" name="category_id" required>
                <option value="1">eBooks</option>
                <option value="2">Software</option>
                <option value="3">Courses</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="file" class="form-label">Datei (z.B. PDF, ZIP)</label>
            <input type="file" class="form-control" id="file" name="file" required>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Produktbild</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*" required onchange="previewImage(event)">
            <img id="preview" class="mt-3" style="max-height: 150px; display: none;" />
        </div>

        <button type="submit" class="btn btn-primary">Produkt hinzufügen</button>
    </form>
</section>

<!-- Bestehende Produkte verwalten -->
<section class="container mb-5">
    <h2>Produkte verwalten</h2>
    <div id="admin-edit-products">
        <!-- Hier wird später die Produkttabelle dynamisch eingefügt -->
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

<script src="../js/admin.js"></script>

<?php include 'includes/footer.php'; ?>
