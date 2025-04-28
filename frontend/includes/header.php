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

    <!-- Sidebar für Warenkorb -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="cartSidebar" aria-labelledby="cartSidebarLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="cartSidebarLabel">Your Cart</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div id="cartContents"></div>
        <hr>
        <div class="d-flex justify-content-between align-items-center">
            <strong>Total:</strong>
            <span id="cartTotal" class="fw-bold">$0.00</span>
        </div>
        <div class="mt-3 text-end">
            <button id="clearCartBtn" class="btn btn-link text-danger p-0" title="Clear cart">
                <i class="bi bi-trash" style="font-size: 1.3rem;"></i>
            </button>
        </div>
    </div>
</div>


    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">

        <div class="container px-4 px-lg-5">
            <!--  <a class="navbar-brand" href="#!">ManDaMel</a>  Text oder doch Logo?-->
            <a href="../pages/index.php">
                <img src="../images/logo.png" alt="Logo" class="logo-img">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">Categories</a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#!">All Products</a></li>
                            <li>
                                <hr class="dropdown-divider"/>
                            </li>
                            <li><a class="dropdown-item" href="#!">Popular Items</a></li>
                            <li><a class="dropdown-item" href="#!">New Arrivals</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="#!">Bestsellers</a></li>
                    <li class="nav-item"><a class="nav-link" href="#!">Software</a></li>

                    

                    <!-- Search -->
                    <li class="nav-item">
                        <form class="d-flex ms-3" role="search">
                            <input class="form-control me-2 search-input" type="search"
                                placeholder="What are you looking for?" aria-label="Search">
                            <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                        </form>
                    </li>
                </ul>
                
                <form class="d-flex">
                    <!-- Shows cart icon with item count - offcanvas -->
                    <button class="btn btn-outline-dark position-relative" type="button" data-bs-toggle="offcanvas" data-bs-target="#cartSidebar" aria-controls="cartSidebar">
                        <i class="bi-cart-fill me-1"></i>
                        Cart
                        <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark">
                            0
                        </span>
                    </button>
                </form>

                <div class="dropdown ms-3">
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