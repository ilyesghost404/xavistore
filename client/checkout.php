<?php
session_start();
require_once("../backend/db.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Proceed with the checkout process...
?>
