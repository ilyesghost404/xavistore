<?php
$host = "localhost";
$dbname = "xavistore";
$user = "root";
$pass = ""; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
