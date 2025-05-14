<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ManDaMel</title>

    <!-- Favicon -->
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>

<body>

<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container px-4 px-lg-5 d-flex align-items-center justify-content-between">
        <!-- Logo -->
        <a href="index.php" class="d-flex align-items-center">
            <img src="http://localhost:5000/uploads/images/logo.png" alt="Logo" class="logo-img me-3" />
        </a>

        <!-- Suche -->
        <form class="d-flex flex-grow-1 me-4" role="search">
            <input id="product-search" class="form-control me-2 search-input" type="search"
                placeholder="What are you looking for?" aria-label="Search">
            <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
        </form>
        <!-- Cart & User -->
        <div class="d-flex align-items-center">
            <form class="d-flex me-3">
                <!-- <button class="btn btn-outline-dark" type="submit">
                    <i class="bi-cart-fill me-1"></i>
                    Cart
                    <span class="badge bg-dark text-white ms-1 rounded-pill">0</span>
                </button> -->
                <button class="btn btn-outline-dark" type="button" data-bs-toggle="offcanvas" data-bs-target="#cartSidebar">
                    <i class="bi-cart-fill me-1"></i>
                    Cart
                    <span class="badge bg-dark text-white ms-1 rounded-pill">0</span>
                </button>
            </form>
            <div class="dropdown">
                <button class="btn btn-outline-dark dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown" id="user-dropdown-menu">
                    <!-- Filled by JS depending on cookie -->
                </ul>
            </div>
        </div>
    </div>
</nav>

<!-- Offcanvas Cart Sidebar -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="cartSidebar" aria-labelledby="cartSidebarLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="cartSidebarLabel">Cart</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Schließen"></button>
    </div>
    <div class="offcanvas-body" id="cartSidebarBody">
        <!-- JS füllt Inhalte dynamisch -->
    </div>
</div>


