<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require_once("../backend/db.php");

$today = date('Y-m-d');
$stmt = $pdo->prepare("
  SELECT 
    COUNT(*) AS total_vendus, 
    SUM(p.prix) AS total_revenu 
  FROM ventes v
  JOIN produits p ON v.produit_id = p.id
  WHERE DATE(v.date_vente) = ?
");
$stmt->execute([$today]);
$data = $stmt->fetch();


$ventes_aujourdhui = $data['total_vendus'];
$revenu_aujourdhui = $data['total_revenu'] ?? 0;

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <meta charset="UTF-8">
  <title>Dashboard Admin - XaviStore</title>
  <link rel="stylesheet" href="css/dashboard.css">
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="css/sidebar.css">
  <link rel="icon" type="png" href="assets/xavistore.png">

  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body>
    <?php include("loading.php"); ?>
 

    
  <?php include("sidebar.php"); ?>



<div class="dashboard">
<div class="stats-cards">
  <div class="card">
    <i class="ri-shopping-bag-line card-icon"></i>
    <div>
      <p class="card-title">Produits vendus aujourd'hui</p>
      <h3><?= $ventes_aujourdhui ?></h3>
    </div>
  </div>

  <div class="card">
    <i class="ri-coins-line card-icon"></i>
    <div>
      <p class="card-title">Revenu aujourd'hui (TND)</p>
      <h3><?= number_format($revenu_aujourdhui, 2, ',', ' ') ?> TND</h3>
    </div>
  </div>

</div>
</div>

<script src="js/Admin.js">
</script>






</body>
</html>
