<!-- Footer-->
<footer class="py-5 bg-dark">
    <div class="container">
        <p class="m-0 text-center text-white">Copyright &copy; ManDaMel Webshop 2025</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../js/scripts.js"></script>
<script src="../js/product_edit.js"></script>

    <?php
        $currentPage = basename($_SERVER['PHP_SELF']);
        if ($currentPage === 'register.php') {
            echo '<script src="/js/register.js"></script>';
        } elseif ($currentPage === 'my_account.php') {
            echo '<script src="/js/my_account.js"></script>';
        } elseif ($currentPage === 'login.php') {
            echo '<script src="/js/login.js"></script>';
        } elseif ($currentPage === 'index.php') {
            echo '<script src="/js/products.js"></script>';
        } elseif ($currentPage === 'product_edit.php') {
            echo '<script src="/js/product_edit.js"></script>';
        } elseif ($currentPage === 'manage_accounts.php') {
            echo '<script src="/js/manage_accounts.js"></script>';
        }
        
    ?>
</html>
