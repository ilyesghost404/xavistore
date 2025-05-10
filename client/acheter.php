<?php
session_start();
require_once("../backend/db.php");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Fetch product details
    $stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ? AND etat = 'disponible'");
    $stmt->execute([$id]);
    $produit = $stmt->fetch();

    if (!$produit) {
        echo "Produit non disponible.";
        exit();
    }

    // Here you can handle the logic to add to the cart or directly proceed with the purchase
    // For now, let's simulate a direct purchase
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Process the purchase (add sale record, update stock)
        $stmt = $pdo->prepare("INSERT INTO ventes (produit_id, date_vente) VALUES (?, NOW())");
        $stmt->execute([$id]);

        // Decrement the quantity of the product
        $quantite = $produit['quantite'] - 1;
        if ($quantite <= 0) {
            // Soft-delete if quantity is 0
            $update = $pdo->prepare("UPDATE produits SET quantite = 0, etat = 'indisponible' WHERE id = ?");
            $update->execute([$id]);
        } else {
            $update = $pdo->prepare("UPDATE produits SET quantite = ? WHERE id = ?");
            $update->execute([$quantite, $id]);
        }

        // Redirect or display success message
        header("Location: confirmation.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Achat du Produit</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <div class="product-details">
    <h2>Confirmer votre achat</h2>
    <div class="product-item">
      <img src="../uploads/<?= htmlspecialchars($produit['image']) ?>" alt="<?= htmlspecialchars($produit['nom']) ?>" width="150">
      <h3><?= htmlspecialchars($produit['nom']) ?></h3>
      <p><?= htmlspecialchars($produit['description']) ?></p>
      <p>Prix: €<?= htmlspecialchars($produit['prix']) ?></p>
      <form method="POST">
        <button type="submit" class="btn btn-buy">Acheter</button>
      </form>
    </div>
  </div>
  <script src="js/client.js"></script>
</body>
</html>
