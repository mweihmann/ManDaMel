<?php
session_start();

$cart = $_SESSION['cart'] ?? [];


echo json_encode($cart);
?>