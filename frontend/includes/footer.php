<!-- Footer-->
<footer class="py-5 bg-dark">
    <div class="container">
        <p class="m-0 text-center text-white">Copyright &copy; ManDaMel Webshop 2025</p>
    </div>
</footer>


<!-- Bootstrap & jQuery -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script src="../js/scripts.js"></script>
<script src="../js/cart.js"></script>

<?php
// Ermittelt die aktuelle Seite
$currentPage = basename($_SERVER['PHP_SELF']);

 // Lädt seitenbezogene JavaScript-Dateien
if ($currentPage === 'register.php') {
    echo '<script src="/js/register.js"></script>';         // Registrierung
} elseif ($currentPage === 'my_account.php') {
    echo '<script src="/js/my_account.js"></script>';       // Benutzerkonto
} elseif ($currentPage === 'login.php') {
    echo '<script src="/js/login.js"></script>';            // Login
} elseif ($currentPage === 'index.php') {
    echo '<script src="/js/products.js"></script>';         // Produktübersicht
} elseif ($currentPage === 'manage_products.php') {
    echo '<script src="/js/manage_products.js"></script>';  // Produktverwaltung (Admin)
} elseif ($currentPage === 'manage_accounts.php') {
    echo '<script src="/js/manage_accounts.js"></script>';  // Benutzerverwaltung (Admin)
} elseif ($currentPage === 'checkout.php') {
    echo '<script src="/js/checkout.js"></script>';         // Checkout-Prozess
} elseif ($currentPage === 'manage_vouchers.php') {
    echo '<script src="/js/manage_vouchers.js"></script>';  // Gutscheinverwaltung (Admin)
}
?>

</html>