<?php
session_start();
require_once("../backend/db.php");
require_once("../backend/send_email.php");

// Redirect if not admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Handle status change and send email
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    // Update status in the database
    $update_stmt = $pdo->prepare("UPDATE commande SET statut = ? WHERE id = ?");
    $update_stmt->execute([$new_status, $order_id]);

    // Fetch user and order info
    $stmt = $pdo->prepare("
        SELECT c.*, u.nom AS client_nom, u.prenom AS client_prenom, u.email
        FROM commande c
        JOIN users u ON c.user_id = u.id
        WHERE c.id = ?
    ");
    $stmt->execute([$order_id]);
    $commande = $stmt->fetch();

    if ($commande) {
        $client_email = $commande['email'];
        $client_name = $commande['client_prenom'] . ' ' . $commande['client_nom'];
        $commande_id = $commande['id'];
        $produits = $commande['produits'];

        // Email content based on order status
        switch ($new_status) {
            case 'pending':
                $subject = "Commande en attente - XaviStore";
                $body = "<p>Bonjour $client_name,<br>Votre commande #$commande_id est en attente de traitement.</p>";
                break;
            case 'shipped':
                $subject = "Commande expédiée - XaviStore";
                $body = "<p>Bonjour $client_name,<br>Votre commande #$commande_id a été expédiée.</p>";
                break;
            case 'completed':
                $subject = "Commande livrée - XaviStore";
                $body = "<p>Bonjour $client_name,<br>Votre commande #$commande_id a été livrée. Merci pour votre confiance !</p>";
                break;
            case 'cancelled':
                $subject = "Commande annulée - XaviStore";
                $body = "<p>Bonjour $client_name,<br>Votre commande #$commande_id a été annulée. Si c'est une erreur, contactez-nous.</p>";
                break;
        }

        // Send the email to the client
        sendMailToClient('hmidilyes4442@gmail.com', $client_name, $subject, $body);
    }

    // Redirect to avoid form resubmission
    header("Location: commandes.php");
    exit;
}

// Fetch all orders from the database
$stmt = $pdo->prepare("
    SELECT c.*, u.nom AS client_nom, u.prenom AS client_prenom, u.email
    FROM commande c
    JOIN users u ON c.user_id = u.id
    ORDER BY c.date_commande DESC
");
$stmt->execute();
$commandes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commandes - XaviStore Admin</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/commande.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body>
    <?php include("sidebar.php"); ?>
    <?php include("loading.php"); ?>


    <div class="commandes-container">
        <h1>Commandes Reçues</h1>

        <?php if (is_array($commandes) && count($commandes) > 0): ?>
            <div class="cards-container">
                <?php foreach ($commandes as $cmd): ?>
                    <div class="order-card">
                        <div class="order-image">
                            <img src="../uploads/<?= htmlspecialchars($cmd['image']) ?>" alt="Image produit">
                        </div>
                        <div class="order-info">
                            <h3>Commande ID: <?= htmlspecialchars($cmd['id']) ?></h3>
                            <p><strong>Client:</strong> <?= htmlspecialchars($cmd['client_nom']) ?> <?= htmlspecialchars($cmd['client_prenom']) ?></p>
                            <p><strong>Email:</strong> <?= htmlspecialchars($cmd['email']) ?></p>
                            <p><strong>Adresse:</strong> <?= htmlspecialchars($cmd['adresse']) ?></p>
                            <p><strong>Téléphone:</strong> <?= htmlspecialchars($cmd['telephone']) ?></p>
                            <p><strong>Produits:</strong> <?= htmlspecialchars($cmd['produits']) ?></p>
                            <p><strong>Total:</strong> €<?= number_format($cmd['total'], 2) ?></p>
                            <p><strong>Date:</strong> <?= date("d/m/Y H:i", strtotime($cmd['date_commande'])) ?></p>
                            <p><strong>Statut:</strong> <?= htmlspecialchars($cmd['statut']) ?></p>

                            <!-- Change status form -->
                            <form method="POST" action="commandes.php" class="status-form">
                                <input type="hidden" name="order_id" value="<?= htmlspecialchars($cmd['id']) ?>">
                                <select name="status">
                                    <option value="pending" <?= $cmd['statut'] == 'pending' ? 'selected' : '' ?>>En attente</option>
                                    <option value="shipped" <?= $cmd['statut'] == 'shipped' ? 'selected' : '' ?>>Expédiée</option>
                                    <option value="completed" <?= $cmd['statut'] == 'completed' ? 'selected' : '' ?>>Livrée</option>
                                    <option value="cancelled" <?= $cmd['statut'] == 'cancelled' ? 'selected' : '' ?>>Annulée</option>
                                </select>
                                <button type="submit" name="change_status" class="status-btn">Mettre à jour</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-message">Aucune commande trouvée.</div>
        <?php endif; ?>
    </div>


<script src="js/admin.js"></script>
</body>
</html>
