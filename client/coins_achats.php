<?php
// Load games and prices
$games = [
    'efootball' => [
        'name' => 'eFootball',
        'image' => 'efootball_icon.png',
        'coins' => [
            ['amount' => 100, 'price_usd' => 1.19],
            ['amount' => 300, 'price_usd' => 2.69],
            ['amount' => 550, 'price_usd' => 4.79],
            ['amount' => 750, 'price_usd' => 6.49],
            ['amount' => 1040, 'price_usd' => 8.79],
            ['amount' => 2130, 'price_usd' => 17.99],
            ['amount' => 3250, 'price_usd' => 26.99],
            ['amount' => 5700, 'price_usd' => 43.99],
            ['amount' => 12800, 'price_usd' => 93.99],
        ]
    ],
    'free_fire' => [
        'name' => 'Free Fire',
        'image' => 'free_fire.png',
        'coins' => [
            ['amount' => 100, 'price_usd' => 1.10],
            ['amount' => 310, 'price_usd' => 3.10],
            ['amount' => 520, 'price_usd' => 5.10],
            ['amount' => 1060, 'price_usd' => 10.10],
            ['amount' => 2180, 'price_usd' => 20.10],
            ['amount' => 5600, 'price_usd' => 50.10],
        ]
    ]
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commander des Coins - XaviStore</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/acheter_coins.css">
    <link rel="stylesheet" href="css/sidebar.css">
      <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
</head>
<body>
 <?php include("sidebar.php"); ?>
 <div class="coin-content">

 
<h2>Commander des Coins</h2>

<form action="../backend/process_coin_purchase.php" method="POST" enctype="multipart/form-data">
    <label for="game_slug">Jeu</label>
    <select name="game_slug" id="game_slug" required>
        <option value="">-- Choisissez un jeu --</option>
        <?php foreach ($games as $slug => $game): ?>
            <option value="<?= $slug ?>"><?= $game['name'] ?></option>
        <?php endforeach; ?>
    </select>

    <label for="coins">Quantité de coins</label>
    <select name="coins" id="coins" required></select>

    <label for="username">Nom d'utilisateur du jeu</label>
    <input type="text" name="username" id="username" required>

    <label for="password">Mot de passe</label>
    <input type="password" name="password" id="password" required>

    <label for="phone">Numéro de téléphone</label>
    <input type="text" name="phone" id="phone" required>

    <label for="payment_image">Preuve de paiement</label>
    <input type="file" name="payment_image" id="payment_image" accept="image/*" required>

    <button type="submit" name="acheter">Envoyer la commande</button>
</form>
</div>
<script>
    const gameData = <?= json_encode($games) ?>;
    const gameSelect = document.getElementById('game_slug');
    const coinSelect = document.getElementById('coins');

    gameSelect.addEventListener('change', function () {
        const slug = this.value;
        coinSelect.innerHTML = '<option value="">-- Sélectionnez une quantité --</option>';

        if (gameData[slug]) {
            gameData[slug].coins.forEach(coin => {
                const tnd = (coin.price_usd * 3.6).toFixed(2);
                const option = document.createElement('option');
                option.value = coin.amount;
                option.textContent = `${coin.amount} pièces - ${tnd} TND`;
                coinSelect.appendChild(option);
            });
        }
    });
</script>
 <script src="js/client.js"></script>
</body>
</html>
