<?php
// Include database connection
require_once 'config.php';

try {
    // Fetch products from the database
    $stmt = $pdo->query("SELECT name, price FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ManDaMel Shop</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-DQvkBjpPgn7RC31MCQoOeC9TI2kdqa4+BSgNMNj8v77fdC77Kj5zpWFTJaaAoMbC" crossorigin="anonymous">
   <!-- Bootstrap Icons-->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <div class="row">
           <div class="col-xl-3 col-md-4 col-sm-6">

            <div class="card" style="width:18rem;">
              <img src="images/schedule_1.jpg" class="card-img-top" alt="...">
              <div class="card-body">
                <h5 class="card-title">Schedule I</h5>
                <h6 class="card-subtitle mb-2 text-muted ">
                    <img src="icons/steam_icon.png" alt="Steam_Logo" style="width:16px; height:16px; margin-left:5px;">
                    Steam <strong>&bull;</strong> Schlüssel <strong>&bull;</strong> Global </h6>
                <p class="card-text">19,50€ <span class="float-end">
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-half"></i>
                </span></p>
              </div>
            </div>

           </div>
           <div class="col-xl-3 col-md-4 col-sm-6">
            col-2
           </div>
           <div class="col-xl-3 col-md-4 col-sm-6">
            col-3
           </div>
           <div class="col-xl-3 col-md-4 col-sm-6">
            col-4
           </div>
        </div>
    </div>
    
    <h2>Available Products</h2>
    <ul>
        <?php foreach ($products as $product): ?>
            <li><?= htmlspecialchars($product['name']) ?> - $<?= number_format($product['price'], 2) ?></li>
        <?php endforeach; ?>
    </ul>

</body>
</html>