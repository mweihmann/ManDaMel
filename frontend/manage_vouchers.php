<?php include 'includes/header.php'; ?>

<div class="container py-4">
    <h2 class="mb-4">🎟️ Gutscheinverwaltung</h2>

    <div class="mb-3">
        <button id="create-btn" class="btn btn-success">➕ Neuen Gutschein erstellen</button>
    </div>

    <div id="voucher-list"></div>

    <!-- Modal -->
    <div class="modal fade" id="voucherModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" id="voucher-form">
                <div class="modal-header">
                    <h5 class="modal-title">Gutschein bearbeiten</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="voucher-id">
                    <div class="mb-3">
                        <label for="voucher-code" class="form-label">Code</label>
                        <input type="text" id="voucher-code" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="voucher-value" class="form-label">Wert (€)</label>
                        <input type="number" id="voucher-value" class="form-control" required min="0">
                    </div>
                    <div class="mb-3">
                        <label for="voucher-expiry" class="form-label">Gültig bis</label>
                        <input type="datetime-local" id="voucher-expiry" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">💾 Speichern</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>