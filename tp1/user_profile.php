<?php
  header("Content-type: text/html;charset=utf-8");
  session_start(); 
  // Comme setcookie, doit être appelée avant tout contenu (mais appel possible après un autre header)
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Guillaume Hostache">
    <meta name="description" content="M2 CIM - Programmation Javascript/Php avancée">
    <title>TP1 - Exercices</title>
    <link rel="icon" href="./favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>

  <body>
    <?php
      if (isset($_SESSION['identifie'])) {
        ?>
        <div class="container mt-5">
      <h3 class="text-center">Profil de l'utilisateur</h3>
      <div class="card mt-4">
        <div class="card-body">
          <p><strong>Email :</strong> <?php echo htmlspecialchars($_SESSION['identifie']); ?></p>
        </div>
      </div>
      <div class="text-center mt-4">
        <a href="./index.php" class="btn btn-secondary">Retour à l'accueil</a>
      </div>
    </div>
        <?php 
      } else {
?><div class="container mt-5">
      <h3 class="text-center">Accès refusé </h3>
      
      <div class="text-center mt-4">
        <a href="./index.php" class="btn btn-danger">Redirection</a>
      </div>
    </div><?php 

      }
    ?>
    
  </body>
</html>
