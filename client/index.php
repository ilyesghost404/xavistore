<?php
session_start();
require_once("../backend/db.php");

// Fetch some featured products or latest products
$stmt = $pdo->prepare("SELECT * FROM produits WHERE etat = 'disponible' ORDER BY id DESC LIMIT 6");
$stmt->execute();
$produits = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Xavi Store</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/Style.css">
  <link rel="stylesheet" href="css/sidebar.css">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
</head>
<body>
  <?php include("sidebar.php"); ?>

  <div class="main-content">
  <div class="main-text">
    <h1 class="main-title">Bienvenue chez XAVISTORE</h1>
    <p class="main-subtitle">Les meilleurs produits pour tous vos besoins</p>
    
    <div class="button-group">
      <a href="produits_neufs.php" class="btn btn-primary">Voir les Produits Neufs</a>
      <a href="produits_utilises.php" class="btn btn-secondary">Voir les Produits Utilisés</a>
    </div>
  </div>
  <div class="main-pic">
    <img src="assets/home-client.png" alt="XaviStore Image">
  </div>
</div>

<script>
  const userLoggedIn = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;
</script>
<script src="js/client.js"></script>


</body>
</html>
