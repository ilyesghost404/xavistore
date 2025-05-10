<?php
session_start();
require_once("../backend/db.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user orders
$stmt = $pdo->prepare("SELECT * FROM commande WHERE user_id = ? ORDER BY date_commande DESC");
$stmt->execute([$user_id]);
$commandes = $stmt->fetchAll();

// Handle cancel button
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_id'])) {
    $cancel_id = $_POST['cancel_id'];
    $cancel_stmt = $pdo->prepare("UPDATE commande SET statut = 'cancelled' WHERE id = ? AND user_id = ? AND statut = 'pending'");
    $cancel_stmt->execute([$cancel_id, $user_id]);
    header("Location: mon_commande.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Commandes</title>
    <link rel="stylesheet" href="css/Styles.css">
    <link rel="stylesheet" href="css/mon_commande.css">
    <link rel="stylesheet" href="css/Sidebar.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">

</head>
<body>
    <?php include("sidebar.php"); ?>

    <div class="commande-container">
        <h1>Mes Commandes</h1>

        <?php if (count($commandes) > 0): ?>
            <?php foreach ($commandes as $cmd): ?>
                <div class="commande-card">
                    <h3>Commande #<?= htmlspecialchars($cmd['id']) ?></h3>
                    <p><strong>Produits:</strong> <?= htmlspecialchars($cmd['produits']) ?></p>
                    <p><strong>Adresse:</strong> <?= htmlspecialchars($cmd['adresse']) ?></p>
                    <p><strong>Téléphone:</strong> <?= htmlspecialchars($cmd['telephone']) ?></p>
                    <p><strong>Total:</strong> €<?= number_format($cmd['total'], 2) ?></p>
                    <p><strong>Date:</strong> <?= date("d/m/Y H:i", strtotime($cmd['date_commande'])) ?></p>
                    <p><strong>Statut:</strong> 
                        <span class="statut <?= htmlspecialchars($cmd['statut']) ?>">
                            <?= htmlspecialchars($cmd['statut']) ?>
                        </span>
                    </p>
                    <?php if ($cmd['statut'] === 'pending'): ?>
                        <form method="POST">
                            <input type="hidden" name="cancel_id" value="<?= $cmd['id'] ?>">
                            <button type="submit" class="btn-cancel">Annuler la commande</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune commande trouvée.</p>
        <?php endif; ?>
    </div>
    <script src="js/client.js"></script>
</body>
</html>
