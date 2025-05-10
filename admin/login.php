<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion Admin</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  
  <div class="admin">
  
  <form method="POST" action="../backend/login_admin.php" class="form">
        
    <div class="login-content">
        
        <div class="text">
            <h2> Xavi Store</h2>
            <div>
                <label>ID Admin:</label><br>
                <input type="text" name="admin_id" placeholder="Admin ID" required><br><br>
                <button type="submit">Se connecter</button>
            </div>
        </div>
        <div class="image" >
            <img src="assets/login.png" alt="login">
        </div>
    </div>
    
  </form>
  </div>

  
  <?php if (isset($_GET['error'])): ?>
    <p style="color:red;">ID invalide. Réessayez.</p>
  <?php endif; ?>
</body>
</html>
