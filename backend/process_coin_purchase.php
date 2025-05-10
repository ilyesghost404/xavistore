<?php
require_once("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['acheter'])) {
    $game_slug = $_POST['game_slug'];
    $coin_amount = $_POST['coins'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $payment_image = $_FILES['payment_image']['name'];

    // Prices definition (you can move this to a table later)
    $prices_usd = [
        'efootball' => [
            100 => 1.19, 300 => 2.69, 550 => 4.79, 750 => 6.49,
            1040 => 8.79, 2130 => 17.99, 3250 => 26.99,
            5700 => 43.99, 12800 => 93.99
        ],
        'free_fire' => [
            100 => 1.10, 310 => 3.10, 520 => 5.10,
            1060 => 10.10, 2180 => 20.10, 5600 => 50.10
        ]
    ];

    $price_usd = $prices_usd[$game_slug][$coin_amount] ?? 0;
    $price_tnd = round($price_usd * 3.6, 2);

    // Handle upload
    $upload_dir = "../client/uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $target_path = $upload_dir . basename($payment_image);
    if (move_uploaded_file($_FILES['payment_image']['tmp_name'], $target_path)) {
        // Insert into DB
        $stmt = $pdo->prepare("INSERT INTO coin_orders 
            (game_slug, coins, username, password, phone, payment_image, price_tnd, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, 'en_attente', NOW())");
        $stmt->execute([
            $game_slug,
            $coin_amount,
            $username,
            $password,
            $phone,
            $payment_image,
            $price_tnd
        ]);

        header("Location: ../client/coins_achats.php?success=1");
        exit;
    } else {
        echo "Erreur lors du téléchargement de l'image de paiement.";
    }
}
?>
