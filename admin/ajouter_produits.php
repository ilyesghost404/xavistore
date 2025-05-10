<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="UTF-8">
  <link rel="stylesheet" href="css/ap.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/sidebar.css">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

  <title>Admin - XaviStore</title>
</head>
<body>
  <?php include("sidebar.php"); ?>

  <div class="content">
    <h2>➕ Ajouter un produit</h2>

    <?php if (isset($_GET['success'])): ?>
        <p class="success-message">✅ Produit ajouté avec succès !</p>
    <?php endif; ?>

    <form action="../backend/ajouter_produits.php" method="POST" enctype="multipart/form-data">
        <label>Nom du produit:</label>
        <input type="text" name="nom" required>

        <label>Prix (TND):</label>
        <input type="number" name="prix" required>

        <label>Description:</label>
        <textarea name="description" required></textarea>

        <label>Catégorie:</label>
        <select name="categorie" required>
            <option value="neuf">Neuf</option>
            <option value="utilise">Occasion</option>
        </select>


        <!-- Quantity Input (Visible only if 'Neuf' is selected) -->
        <div id="quantite-input" >
            <label>Quantité:</label>
            <input type="number" name="quantite" required>
        </div>

        <label>Image:</label>
        <input type="file" name="image" accept="image/*" required>

        <button type="submit">Ajouter</button>
    </form>
  </div>

<script src="js/admin.js"></script>
</body>
</html>
