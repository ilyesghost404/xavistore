<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin/login.php");
    exit();
}

require_once("db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prix = $_POST['prix'];
    $description = $_POST['description'];
    $categorie = $_POST['categorie'];
    $quantite = ($categorie === 'neuf') ? intval($_POST['quantite']) : 1;
    $image = $_FILES['image']['name'];

    // Upload the image
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($image);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

    // Check if product with same name and category already exists
    $stmt = $pdo->prepare("SELECT * FROM produits WHERE nom = ? AND categorie = ? AND etat = 'disponible'");
    $stmt->execute([$nom, $categorie]);
    $existing = $stmt->fetch();

    if ($existing) {
        // Update quantity of existing product
        $newQuantite = $existing['quantite'] + $quantite;
        $update = $pdo->prepare("UPDATE produits SET quantite = ?, prix = ?, description = ?, image = ? WHERE id = ?");
        $update->execute([$newQuantite, $prix, $description, $image, $existing['id']]);
    } else {
        // Insert as new product
        $stmt = $pdo->prepare("INSERT INTO produits (nom, prix, description, categorie, quantite, image, etat) 
                               VALUES (?, ?, ?, ?, ?, ?, 'disponible')");
        $stmt->execute([$nom, $prix, $description, $categorie, $quantite, $image]);
    }

    header("Location: ../admin/ajouter_produits.php?success=true");
    exit();
}

?>
