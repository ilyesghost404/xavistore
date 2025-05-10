<?php
session_start();
include("db.php");

if (isset($_POST['admin_id'])) {
    $admin_id = trim($_POST['admin_id']);

    $stmt = $pdo->prepare("SELECT * FROM admin WHERE id = ?");
    $stmt->execute([$admin_id]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['admin_id'] = $admin_id;
        header("Location: ../admin/index.php");
        exit();
    } else {
        header("Location: ../admin/login.php?error=1");
        exit();
    }
} else {
    header("Location: ../admin/login.php?error=1");
    exit();
}
