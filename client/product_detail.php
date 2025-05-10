<?php
session_start();
require_once("../backend/db.php");

// Fetch the product details using the id from the URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Get product details from the database
    $stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
    $stmt->execute([$id]);
    $produit = $stmt->fetch();

    if (!$produit) {
        echo "Produit non trouvé.";
        exit();
    }
} else {
    echo "ID du produit non spécifié.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Détail du Produit - XaviStore</title>
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="css/Product_details.css">
  <link rel="stylesheet" href="css/Sidebar.css">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body>
  <?php include("sidebar.php"); ?>

  <section class="product-detail-section">
    <div class="container">
      <div class="product-detail">
        <div class="product-image">
          <img src="../uploads/<?= htmlspecialchars($produit['image']) ?>" alt="<?= htmlspecialchars($produit['nom']) ?>" />
        </div>
        <div class="product-info">
          <h1 class="product-name"><?= htmlspecialchars($produit['nom']) ?></h1>
          <p class="product-price"><?= number_format($produit['prix'], 2, ',', ' ') ?> TND</p>
          <p class="product-description"><?= nl2br(htmlspecialchars($produit['description'])) ?></p>

          <?php if (isset($_SESSION['user_id'])): ?>
            <form action="../backend/ajouter_au_panier.php" method="POST">

              <input type="hidden" name="produit_id" value="<?= $produit['id'] ?>" />
              <button type="submit" class="btn btn-primary">Ajouter au Panier</button>
            </form>
          <?php else: ?>
            <a href="login.php" class="btn btn-warning">Connectez-vous pour acheter</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>

  <footer>
    <p>&copy; 2025 XaviStore. Tous droits réservés.</p>
  </footer>
  <script>
  function addToCart(productId) {
      if (sessionStorage.getItem('userLoggedIn') === null) {
          alert("Veuillez vous connecter pour ajouter un produit au panier !");
          window.location.href = "login.php";
      } else {
          fetch('../backend/ajouter_au_panier.php', {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/x-www-form-urlencoded',
              },
              body: 'produit_id=' + encodeURIComponent(productId)
          })
          .then(response => response.text())
          .then(data => {
              alert("Produit ajouté au panier !");
          })
          .catch(error => console.error('Erreur:', error));
      }
  }
</script>
<script src="js/client.js"></script>
</body>
</html>
