<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin/login.php");
    exit();
}

require_once("db.php");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Get the current product
    $stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
    $stmt->execute([$id]);
    $produit = $stmt->fetch();

    if ($produit) {
        $quantite = $produit['quantite'];

        // Insert the sale record
        $insertVente = $pdo->prepare("INSERT INTO ventes (produit_id, date_vente) VALUES (?, NOW())");
        $insertVente->execute([$id]);
        
        // Decrement quantity and mark as unavailable if zero
        $nouvelle_quantite = $quantite - 1;
        if ($nouvelle_quantite <= 0) {
            // Mark as 'indisponible' but keep it in the database (it will still be visible in the admin panel with some quantity)
            $update = $pdo->prepare("UPDATE produits SET quantite = 0, etat = 'indisponible' WHERE id = ?");
            $update->execute([$id]);
        } else {
            $update = $pdo->prepare("UPDATE produits SET quantite = ? WHERE id = ?");
            $update->execute([$nouvelle_quantite, $id]);
        }

        // Redirect to the correct page based on category
        $redirect = ($produit['categorie'] === 'utilise') ? "produits_utilises.php" : "produits_neufs.php";
        header("Location: ../admin/$redirect");
        exit();
    } else {
        echo "Produit non trouvé.";
    }
} else {
    echo "ID non spécifié.";
}
?>
