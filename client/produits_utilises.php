<?php
session_start();
require_once("../backend/db.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Produits Utilisés - XaviStore</title>
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="css/product.css">
  <link rel="stylesheet" href="css/sidebar.css">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body>
  <?php include("sidebar.php"); ?>

  <section class="products-section">
    <div class="container">
      <h1>Produits Occasion</h1>
      <div class="products-grid">
        <?php
        $stmt = $pdo->prepare("SELECT * FROM produits WHERE categorie = 'utilise' AND etat != 'indisponible'");
        $stmt->execute();
        $produits = $stmt->fetchAll();

        foreach ($produits as $produit): ?>
          <div class="product-card used">
            <img src="../uploads/<?= htmlspecialchars($produit['image']) ?>" alt="<?= htmlspecialchars($produit['nom']) ?>" class="product-image">
            <div class="product-details">
              <h3 class="product-name"><?= htmlspecialchars($produit['nom']) ?></h3>
              <p class="product-price"><?= number_format($produit['prix'], 2, ',', ' ') ?> TND</p>
              <a href="product_detail.php?id=<?= $produit['id'] ?>" class="btn btn-secondary">Voir Détails</a>
              <button class="btn btn-primary" onclick="addToCart(<?= $produit['id'] ?>)">Ajouter au Panier</button>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>



  <script>
    const userLoggedIn = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;

    function addToCart(productId) {
      if (!userLoggedIn) {
        window.location.href = "login.php";
        return;
      }

      fetch('../backend/ajouter_au_panier.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'produit_id=' + encodeURIComponent(productId)
      })
      .then(response => response.text())
      .then(data => {
        alert("Produit ajouté au panier !");
      })
      .catch(error => {
        console.error('Erreur :', error);
      });
    }
  </script>
  <script src="js/client.js"></script>
</body>
</html>
