<?php
session_start();

if (isset($_GET['commande']) && $_GET['commande'] == 'success') {
    echo "<p class='success-msg'>Commande envoyée avec succès !</p>";
}

require_once("../backend/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT p.id, p.nom, p.image, p.prix FROM panier pa JOIN produits p ON pa.produit_id = p.id WHERE pa.user_id = ?");
$stmt->execute([$userId]);
$produits = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mon Panier - XaviStore</title>
  <link rel="stylesheet" href="css/panier.css">
  <link rel="stylesheet" href="css/Sidebar.css">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body>
  <?php include("sidebar.php"); ?>

  <main class="panier-container">
    <h1>Mon Panier</h1>

    <?php if (count($produits) > 0): ?>
      <div class="panier-grid">
        <?php foreach ($produits as $produit): ?>
          <div class="panier-item">
            <img src="../uploads/<?= htmlspecialchars($produit['image']) ?>" alt="<?= htmlspecialchars($produit['nom']) ?>">
            <div class="panier-details">
              <h3><?= htmlspecialchars($produit['nom']) ?></h3>
              <p><?= number_format($produit['prix'], 2, ',', ' ') ?> TND</p>

            </div>
          </div>
        <?php endforeach; ?>
        <form action="../backend/passer_commande.php" method="POST" class="commande-form">
            <h2>Informations de Livraison</h2>
            
            <!-- Input for address -->
            <input type="text" name="adresse" placeholder="Adresse complète" required>

            <!-- Input for phone number -->
            <input type="text" name="telephone" placeholder="Numéro de téléphone" required>

            <!-- Submit button to confirm the order -->
            <button type="submit" class="btn btn-success">Passer la commande</button>
            <button class="remove-btn" onclick="removeFromCart(<?= $produit['id'] ?>)">
                <i class="ri-delete-bin-6-line"></i> Retirer la commande
              </button>
        </form>


      </div>
    <?php else: ?>
      <p class="empty-msg">Votre panier est vide.</p>
    <?php endif; ?>
  </main>

  <script>
    function removeFromCart(productId) {
      fetch('remove_from_cart.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'produit_id=' + encodeURIComponent(productId)
      })
      .then(response => response.text())
      .then(data => {
        console.log(data);
        location.reload();
      })
      .catch(error => console.error('Erreur:', error));
    }
  </script>
  <script src="js/client.js"></script>
</body>
</html>
