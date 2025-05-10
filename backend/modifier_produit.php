<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin/login.php");
    exit();
}

require_once("db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $nom = $_POST['nom'];
    $prix = $_POST['prix'];
    $description = $_POST['description'];
    $categorie = $_POST['categorie'];
    $quantite = $_POST['quantite'];
    $image = $_FILES['image']['name'] ? $_FILES['image']['name'] : null;

    // Handle file upload (optional, if image is updated)
    if ($image) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($image);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    }

    // Update product details in the database
    $sql = "UPDATE produits SET nom = ?, prix = ?, description = ?, categorie = ?, quantite = ?";
    $params = [$nom, $prix, $description, $categorie, $quantite];

    // If image was updated, add to query
    if ($image) {
        $sql .= ", image = ?";
        $params[] = $image;
    }

    $sql .= " WHERE id = ?";
    $params[] = $id;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Redirect to the product page with success message
    header("Location: ../admin/produits_neufs.php?success=true");
    exit();
}
?>
