<?php
include 'connect.php';
session_start();

$response = [];

if (!isset($_SESSION['user_id'])) {
   $response['message'] = 'Please log in to add items!';
   echo json_encode($response);
   exit;
}

$user_id = $_SESSION['user_id'];
$pid = $_POST['pid'] ?? '';
$name = $_POST['name'] ?? '';
$price = $_POST['price'] ?? '';
$qty = $_POST['qty'] ?? 1;
$image = $_POST['image'] ?? '';

$check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ? AND pid = ?");
$check_cart->execute([$user_id, $pid]);

if ($check_cart->rowCount() > 0) {
   $response['message'] = 'Product already in cart!';
} else {
   $add_cart = $conn->prepare("INSERT INTO `cart` (user_id, pid, name, price, quantity, image) VALUES (?, ?, ?, ?, ?, ?)");
   $add_cart->execute([$user_id, $pid, $name, $price, $qty, $image]);
   $response['message'] = 'Added to cart!';
}

echo json_encode($response);
?>
