<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once("../backend/db.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Produits Neufs</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
  <link rel="stylesheet" href="css/neuf.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/sidebar.css">
</head>
<body>
  <?php include("sidebar.php"); ?>
  <?php include("loading.php"); ?>


  <div class="neufs-content">

    <table class="products-table">
      <thead>
        <tr>
          <th>Image</th>
          <th>Nom</th>
          <th>Prix (TND)</th>
          <th>Description</th>
          <th>Quantité</th> <!-- Added Quantity Column -->
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
                  // In produits_utilises.php or produits_neufs.php
                  $stmt = $pdo->prepare("SELECT * FROM produits WHERE categorie = ? AND quantite > 0");
                  $stmt->execute(['utilise']);  // Use 'neuf' for the other category
                  $produits = $stmt->fetchAll();



        foreach ($produits as $produit): ?>
          <tr>
            <td><img src="../uploads/<?= htmlspecialchars($produit['image']) ?>" width="60" alt="image"></td>
            <td><?= htmlspecialchars($produit['nom']) ?></td>
            <td><?= htmlspecialchars($produit['prix']) ?></td>
            <td><?= htmlspecialchars($produit['description']) ?></td>
            <td><?= htmlspecialchars($produit['quantite']) ?></td> <!-- Display Quantity -->
            <td>
                <a href="modifier_produit.php?id=<?= $produit['id'] ?>" class="btn btn-edit">Modifier</a>
                <a href="../backend/vendre_produit.php?id=<?= $produit['id'] ?>" class="btn btn-sell">Vendre</a>
            </td>

          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

<script src="js/admin.js"></script>
</body>
</html>
