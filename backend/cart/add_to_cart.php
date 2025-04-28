<?php
session_start();


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


$data = json_decode(file_get_contents("php://input"), true);


$product = [
    'name' => $data['name'],
    'price' => $data['price'],
    'quantity' => $data['quantity']
];


$_SESSION['cart'][] = $product;


echo json_encode([
    'status' => 'success',
    'cartItemCount' => count($_SESSION['cart'])
]);
?>
