<?php
require_once("../backend/db.php");

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    // Validation checks
    if (empty($nom) || empty($prenom) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = "Tous les champs doivent être remplis!";
    } elseif ($password !== $confirm_password) {
        $error_message = "Les mots de passe ne correspondent pas!";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        // Prepare the query to insert into the database
        $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, email, password) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nom, $prenom, $email, $hashed_password]);
        
        $success_message = "Inscription réussie! Vous pouvez maintenant vous connecter.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscription - XaviStore</title>
  <link rel="stylesheet" href="css/Auth.css">
</head>
<body>

  <div class="auth-container">
    <div class="auth-card">
      <h2>Créer un Compte</h2>

      <?php if (isset($error_message)): ?>
        <div class="error-message"><?= $error_message ?></div>
      <?php endif; ?>

      <?php if (isset($success_message)): ?>
        <div class="success-message"><?= $success_message ?></div>
      <?php endif; ?>

      <form action="signup.php" method="POST">
        <!-- Nom Field -->
        <div class="input-group">
          <label for="nom">Nom</label>
          <input type="text" name="nom" id="nom" class="input-field" value="<?= isset($nom) ? htmlspecialchars($nom) : '' ?>" required>
        </div>

        <!-- Prenom Field -->
        <div class="input-group">
          <label for="prenom">Prénom</label>
          <input type="text" name="prenom" id="prenom" class="input-field" value="<?= isset($prenom) ? htmlspecialchars($prenom) : '' ?>" required>
        </div>

        <!-- Email Field -->
        <div class="input-group">
          <label for="email">Email</label>
          <input type="email" name="email" id="email" class="input-field" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required>
        </div>

        <!-- Password Field -->
        <div class="input-group">
          <label for="password">Mot de passe</label>
          <input type="password" name="password" id="password" class="input-field" required>
        </div>

        <!-- Confirm Password Field -->
        <div class="input-group">
          <label for="confirm_password">Confirmer le mot de passe</label>
          <input type="password" name="confirm_password" id="confirm_password" class="input-field" required>
        </div>

        <button type="submit" class="btn">S'inscrire</button>
      </form>

      <div class="auth-footer">
        <p>Vous avez déjà un compte? <a href="login.php">Connectez-vous ici</a></p>
      </div>
    </div>
  </div>

</body>
</html>
