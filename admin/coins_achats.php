<?php
require_once("../backend/db.php");

// Handle status update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'], $_POST['status'])) {
    $stmt = $pdo->prepare("UPDATE coin_orders SET status = ? WHERE id = ?");
    $stmt->execute([$_POST['status'], $_POST['order_id']]);
    header("Location: coins_achats.php?updated=1");
    exit;
}

// Fetch all orders
$stmt = $pdo->query("SELECT * FROM coin_orders ORDER BY created_at DESC");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commandes de Coins - Admin</title>
      <link rel="stylesheet" href="css/style.css">
      <link rel="stylesheet" href="css/coins.css">
      <link rel="stylesheet" href="css/sidebar.css">
        <link rel="icon" type="png" href="assets/xavistore.png">

  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    
</head>
<body>
  <?php include("sidebar.php"); ?>
  <?php include("loading.php"); ?>

<div class="coin-content">
  <h2>Commandes de Coins</h2>

<table>
<thead>
    <tr>
        <th>#</th>
        <th>Jeu</th>
        <th>Coins</th>
        <th>Adresse email</th>
        <th>Téléphone</th>
        <th>Mot de passe</th>
        <th>Prix (TND)</th>
        <th>Image Paiement</th>
        <th>Statut</th>
        <th>Créé le</th>
        <th>Action</th>
    </tr>
</thead>

    <tbody>
        <?php if (count($orders) === 0): ?>
            <tr><td colspan="10">Aucune commande trouvée.</td></tr>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= $order['id'] ?></td>
                    <td><?= htmlspecialchars($order['game_slug']) ?></td>
                    <td><?= $order['coins'] ?></td>
                    <td><?= htmlspecialchars($order['username']) ?></td>
                    <td><?= htmlspecialchars($order['phone']) ?></td>
                    <td><?= htmlspecialchars($order['password']) ?></td>


                    <td><?= $order['price_tnd'] ?> TND</td>
                                        <td>
                        <a href="../client/uploads/<?= $order['payment_image'] ?>" target="_blank">
                            <img src="../client/uploads/<?= $order['payment_image'] ?>" class="payment-img" alt="Paiement">
                        </a>
                    </td>
                    <td><?= ucfirst($order['status']) ?></td>
                    <td><?= $order['created_at'] ?></td>
                    <td>
                        <form method="POST" class="update-form">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <select name="status" class="status-select">
                                <option value="en_attente" <?= $order['status'] === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                                <option value="confirmee" <?= $order['status'] === 'confirmee' ? 'selected' : '' ?>>Confirmée</option>
                                <option value="expediee" <?= $order['status'] === 'expediee' ? 'selected' : '' ?>>Expédiée</option>
                                <option value="annulee" <?= $order['status'] === 'annulee' ? 'selected' : '' ?>>Annulée</option>
                                <option value="terminee" <?= $order['status'] === 'terminee' ? 'selected' : '' ?>>Terminée</option>
                            </select>
                            <button type="submit">Mettre à jour</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
</div>



<script src="js/admin.js"></script>
</body>
</html>
