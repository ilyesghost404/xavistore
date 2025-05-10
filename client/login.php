<?php
require_once("../backend/db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle form submission (user login)
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check if the email exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        header("Location: index.php");  // Redirect to home page after login
        exit();
    } else {
        $error_message = "Email or password is incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - XaviStore</title>
    <link rel="stylesheet" href="css/Auth.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <h2>Connexion</h2>
            <?php if (isset($error_message)) : ?>
                <div class="error-message"><?= $error_message ?></div>
            <?php endif; ?>
            <form action="login.php" method="POST">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required class="input-field">
                </div>
                <div class="input-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required class="input-field">
                </div>
                <button type="submit" class="btn btn-primary">Se connecter</button>
            </form>
            <p class="auth-footer">Pas encore de compte ? <a href="signup.php">Créer un compte</a></p>
        </div>
    </div>
</body>
</html>
