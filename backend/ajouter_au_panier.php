<?php
session_start();

// Ensure the user is logged in before proceeding
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  // Redirect to login if not logged in
    exit();
}

require_once("db.php");

if (isset($_POST['produit_id'])) {
    $produit_id = intval($_POST['produit_id']);
    $user_id = $_SESSION['user_id'];

    // Check if the product already exists in the user's cart
    $stmt = $pdo->prepare("SELECT * FROM panier WHERE user_id = ? AND produit_id = ?");
    $stmt->execute([$user_id, $produit_id]);
    $cart_item = $stmt->fetch();

    if ($cart_item) {
        // If the product is already in the cart, update the quantity
        $stmt = $pdo->prepare("UPDATE panier SET quantite = quantite + 1 WHERE user_id = ? AND produit_id = ?");
        $stmt->execute([$user_id, $produit_id]);
    } else {
        // If the product is not in the cart, add it with a quantity of 1
        $stmt = $pdo->prepare("INSERT INTO panier (user_id, produit_id, quantite) VALUES (?, ?, 1)");
        $stmt->execute([$user_id, $produit_id]);
    }

    // Redirect the user to the cart page
    header("Location: ../client/panier.php");
    exit();    
} else {
    echo "Produit non spécifié.";
}
?>
