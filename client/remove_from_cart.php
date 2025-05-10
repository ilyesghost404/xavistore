<?php
session_start();
require_once("../backend/db.php");

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo "Non autorisé";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['produit_id'])) {
    $produitId = intval($_POST['produit_id']);
    $userId = $_SESSION['user_id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM panier WHERE produit_id = :produit_id AND user_id = :user_id");
        $stmt->execute([
            ':produit_id' => $produitId,
            ':user_id' => $userId
        ]);

        echo "Produit retiré du panier";
    } catch (PDOException $e) {
        http_response_code(500);
        echo "Erreur serveur : " . $e->getMessage();
    }
} else {
    http_response_code(400);
    echo "Requête invalide";
}
