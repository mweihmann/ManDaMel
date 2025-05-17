<?php include 'includes/header.php'; ?>

<div class="container py-4">
    <h2 class="mb-4">🎟️ Voucher Management</h2>

    <!-- Button zum Erstellen eines neuen Gutscheins -->
    <div class="mb-3">
        <button id="create-btn" class="btn btn-success">➕ Create New Voucher</button>
    </div>

    <!-- Hier wird die Liste der Gutscheine dynamisch per JS eingefügt -->
    <div id="voucher-list"></div>

    <!-- Modal-Fenster für Gutscheinbearbeitung -->
    <div class="modal fade" id="voucherModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" id="voucher-form">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Voucher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="voucher-id">

                    <!-- Eingabefeld für Gutscheincode -->
                    <div class="mb-3">
                        <label for="voucher-code" class="form-label">Code</label>
                        <input type="text" id="voucher-code" class="form-control" name="voucher-code" required>
                        <button type="button" class="btn btn-sm btn-outline-secondary mt-1" id="generate-code-btn">Generate Voucher Code</button>
                    </div>

                    <!-- Eingabefeld für Gutscheinwert -->
                    <div class="mb-3">
                        <label for="voucher-value" class="form-label">Value (€)</label>
                        <input type="number" id="voucher-value" class="form-control" required min="0">
                    </div>

                    <!-- Eingabefeld für Ablaufdatum -->
                    <div class="mb-3">
                        <label for="voucher-expiry" class="form-label">Valid till</label>
                        <input type="datetime-local" id="voucher-expiry" class="form-control" required>
                    </div>
                </div>

                <!-- Modal-Fenster Footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">💾 Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abort</button>
                </div>

            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>