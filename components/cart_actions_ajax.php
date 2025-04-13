<?php
include 'connect.php';
session_start();

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? '';

if (!$user_id) {
    echo json_encode(['message' => 'Please login first.']);
    exit;
}

if (isset($_POST['delete'])) {
    $cart_id = $_POST['cart_id'];
    $delete = $conn->prepare("DELETE FROM `cart` WHERE id = ? AND user_id = ?");
    $delete->execute([$cart_id, $user_id]);
    echo json_encode(['message' => 'Item deleted from cart!']);
    exit;
}

if (isset($_POST['delete_all'])) {
    $delete_all = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
    $delete_all->execute([$user_id]);
    echo json_encode(['message' => 'All items deleted from cart!']);
    exit;
}

if (isset($_POST['update_qty'])) {
    $cart_id = $_POST['cart_id'];
    $qty = filter_var($_POST['qty'], FILTER_SANITIZE_NUMBER_INT);
    $update = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ? AND user_id = ?");
    $update->execute([$qty, $cart_id, $user_id]);
    echo json_encode(['message' => 'Quantity updated!']);
    exit;
}

echo json_encode($response);
