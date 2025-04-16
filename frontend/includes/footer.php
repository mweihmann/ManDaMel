    <!-- Footer-->
    <footer class="py-5 bg-dark">
        <div class="container">
            <p class="m-0 text-center text-white">Copyright &copy; Your Website 2023</p>
        </div>
    </footer>


    <!-- Bootstrap Javascript-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Javascript -->
    <!-- <script src="js/scripts.js"></script> -->

    <!-- Load js for register, login + jquery-->
    <?php
        $currentPage = basename($_SERVER['PHP_SELF']);
        if ($currentPage === 'register.php') {
            echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>';
            echo '<script src="/js/register.js"></script>';
        } elseif ($currentPage === 'login.php') {
            echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>';
            echo '<script src="/js/login.js"></script>';
        }
    ?>

</html>