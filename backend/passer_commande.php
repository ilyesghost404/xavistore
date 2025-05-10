<?php
session_start();
require_once("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../client/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$adresse = $_POST['adresse'] ?? '';
$telephone = $_POST['telephone'] ?? '';

if (empty($adresse) || empty($telephone)) {
    echo "Adresse et téléphone requis.";
    exit;
}

// Get user name
$stmt = $pdo->prepare("SELECT nom FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
$nom_client = $user['nom'] ?? 'Client inconnu';

// Get products from cart
$stmt = $pdo->prepare("SELECT p.nom, pa.quantite, p.prix FROM panier pa JOIN produits p ON pa.produit_id = p.id WHERE pa.user_id = ?");
$stmt->execute([$user_id]);
$items = $stmt->fetchAll();

if (!$items) {
    echo "Votre panier est vide.";
    exit;
}

// Format order details
$total = 0;
$produits_text = "";
foreach ($items as $item) {
    $ligne = $item['nom'] . " x" . $item['quantite'] . " (" . $item['prix'] . " TND)";
    $produits_text .= $ligne . "\n";
    $total += $item['quantite'] * $item['prix'];
}

// Insert into commande table
$stmt = $pdo->prepare("INSERT INTO commande (user_id, nom_client, adresse, telephone, produits, total) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->execute([$user_id, $nom_client, $adresse, $telephone, $produits_text, $total]);

// Clear cart
$stmt = $pdo->prepare("DELETE FROM panier WHERE user_id = ?");
$stmt->execute([$user_id]);

header("Location: ../client/panier.php?commande=success");
exit;
?>
