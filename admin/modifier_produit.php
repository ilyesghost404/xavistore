<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once("../backend/db.php");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Get product details
    $stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
    $stmt->execute([$id]);
    $produit = $stmt->fetch();

    if (!$produit) {
        echo "Produit non trouvé.";
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modifier Produit</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/ajouter_produits.css">
  <link rel="stylesheet" href="css/sidebar.css">
</head>
<body>
  <?php include("sidebar.php"); ?>

  <div class="content">
    <h2>Modifier un produit</h2>

    <?php if (isset($_GET['success'])): ?>
        <p class="success-message">✅ Produit modifié avec succès !</p>
    <?php endif; ?>

    <form action="../backend/modifier_produit.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $produit['id'] ?>">

        <label>Nom du produit:</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($produit['nom']) ?>" required>

        <label>Prix (€):</label>
        <input type="number" name="prix" value="<?= htmlspecialchars($produit['prix']) ?>" required>

        <label>Description:</label>
        <textarea name="description" required><?= htmlspecialchars($produit['description']) ?></textarea>

        <label>Catégorie:</label>
        <select name="categorie" required>
            <option value="neuf" <?= $produit['categorie'] == 'neuf' ? 'selected' : '' ?>>Neuf</option>
            <option value="utilise" <?= $produit['categorie'] == 'utilise' ? 'selected' : '' ?>>Utilisé</option>
        </select>

        <label>Quantité:</label>
        <input type="number" name="quantite" value="<?= htmlspecialchars($produit['quantite']) ?>" required>

        <label>Image:</label>
        <?php if ($produit['image']): ?>
            <img src="../uploads/<?= htmlspecialchars($produit['image']) ?>" alt="Image" width="100">
        <?php endif; ?>
        <input type="file" name="image" accept="image/*">


        <button type="submit">Modifier</button>
    </form>
  </div>

<script src="js/admin.js"></script>
</body>
</html>
