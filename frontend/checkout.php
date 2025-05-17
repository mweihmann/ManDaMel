<?php include 'includes/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <h2 class="mb-4">🛒 Checkout</h2>

            <!-- Bereich für Warenkorb-Artikel (wird per JS befüllt) -->
            <div id="checkout-items" class="mb-4">
                <div class="text-muted">Loading cart...</div>
            </div>

            <!-- Gesamtsumme -->
            <div id="checkout-total" class="fs-5 fw-bold mb-4"></div>

            <!-- Checkout-Formular -->
            <form id="checkout-form" class="border p-4 rounded bg-light shadow-sm">

                <!-- Eingabefeld für optionalen Gutscheincode -->
                <div class="mb-3">
                    <label for="voucher_code" class="form-label">🎟️ Voucher Code (optional)</label>
                    <input type="text" id="voucher_code" name="voucher_code" class="form-control" placeholder="z. B. SAVE10">
                    <div id="voucher-info"></div>
                </div>

                <!-- Auswahl der Zahlungsmethode (per JS eingefügt) -->
                <div id="payment-method-group" class="mb-3">
                    <label class="form-label">💳 Select Payment Method</label>
                    <!-- Wird dynamisch per JavaScript geladen -->

                    <!-- <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" value="creditcard" id="payCard">
                        <label class="form-check-label" for="payCard">Kreditkarte</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" value="iban" id="payIban">
                        <label class="form-check-label" for="payIban">IBAN</label>
                    </div> -->
                </div>

                
                <button type="submit" class="btn btn-primary w-100">✅ Buy Now</button>
                <div id="checkout-message" class="mt-3"></div>

            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>