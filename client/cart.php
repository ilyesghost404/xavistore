<?php
session_start();
require_once("db.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the user's cart items (if logged in)
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();

// Display cart items
foreach ($cart_items as $item) {
    // Display each item in the cart
}
?>
