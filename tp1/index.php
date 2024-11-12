<?php
    //ini_set("display_errors", 1);
    //error_reporting(E_ALL);

    header("Content-type: text/html; charset=UTF-8");
    session_start();

    $bdd_cnx = mysqli_connect('localhost', 'root', '', 'db-exercices');
    // table client crée directement sur PhpMyAdmin
    mysqli_query($bdd_cnx, "CREATE TABLE IF NOT EXISTS `td1-client` (id INT UNSIGNED AUTO_INCREMENT NOT NULL, mail VARCHAR(64) NOT NULL, password VARCHAR(64) NOT NULL, PRIMARY KEY(id))") or die ("Erreur de création de table");

    $msg = "";
    $meta = "";

    // Traitement formulaire de connexion
    if (isset($_POST['loginButton'])) {
      if (isset($_COOKIE['client']['email']) && isset($_COOKIE['client']['password'])) {
        $mdp = "'" . $_POST['loginPassword'] . "'";
      } else {
        $mdp = "MD5('" . $_POST['loginPassword'] . "')";
      }

      $res = mysqli_query($bdd_cnx, "SELECT * FROM `td1-client` WHERE mail='" . $_POST['loginEmail'] . "' AND password=$mdp");
      if ($res && mysqli_fetch_row($res)) {
        $_SESSION['identifie'] = $_POST['loginEmail'];
        $msg = "Bienvenue à vous, " . $_POST['loginEmail'];
      } else {
        $msg = 'Désolé, identifiants de connexion incorrects';
      }

      // Traitement formulaire d'inscription
    } else if (isset($_POST['registerButton'])) {
      // Sécurité : les champs doivent être saisies !
      if (empty($_POST['registerEmail']) || empty($_POST['registerPassword']) || empty($_POST['registerConfirmPassword'])) {
        $msg = "Le Formulaire est incomplet";
      } else if ($_POST['registerPassword'] != $_POST['registerConfirmPassword']) {
        $msg = "Les deux mots de passe sont différents !";
      } else {
        // On vérifie que l'utilisateur n'est pas déjà en base...
        $res = mysqli_query($bdd_cnx, "SELECT * FROM `td1-client` WHERE mail='" . $_POST['registerEmail'] ."'");
        if ($res && mysqli_fetch_row($res)) {
          $msg = 'Login déjà présent dans la base !';
        } else {
          // Inscription 
          mysqli_query($bdd_cnx, "INSERT INTO `td1-client` (mail, password) VALUES ('" . $_POST['registerEmail'] . "', MD5('" . $_POST['registerPassword'] . "'))") or die ("Erreur d'insertion");
          $msg = 'Vous voilà inscrit.e !';

          // Gestion du cookie
          if (isset($_POST['rememberMe'])) {
            setcookie("client[email]", $_POST['registerEmail'], time() + 3600*24*365); // 1 année
            setcookie("client[password]", md5($_POST['registerPassword']), time() + 3600*24*365);
            $meta = "<meta http-equiv=\"refresh\" content=\"1;url=".$_SERVER['PHP_SELF']."\"/>";
          }
        }
      }

      // Traitement déconnexion
    } else if (isset($_POST['logoutButton'])) {
      unset($_SESSION['identifie']);
    }
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <?php echo "$meta" ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Guillaume Hostache">
    <meta name="description" content="M2 CIM - Programmation Javascript/Php avancée">

    <title>TP1 - Exercices</title>
    <link rel="icon" href="./favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./style.css">
  </head>

  <body>
    <?php 
      if (isset($_SESSION['identifie'])) {
        ?>

        <div class="container mt-5">
          <div class="alert alert-success text-center">
            Bienvenue, <?php echo htmlspecialchars($_SESSION['identifie']); ?> ! Vous êtes connecté.e.
          </div>

          <div class="text-center mt-4">
            <a href="./user_profile.php" class="btn btn-primary">Voir le profil</a>
            <form method="post" style="display:inline;">
              <button type="submit" name="logoutButton" class="btn btn-danger">Déconnexion</button>
            </form>
          </div>
        </div>
    
    <?php
      } else {
        
        if (isset($_COOKIE['client']['email'])) {
          $valLog = $_COOKIE['client']['email'];
          $valMdp = $_COOKIE['client']['password'];
        } else {
          
          $valLog = '';
          $valMdp = '';
        }
        ?>
        <div class="container">
      <ul class="nav nav-tabs" id="loginRegisterTab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab" aria-controls="login" aria-selected="true">Connexion</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button" role="tab" aria-controls="register" aria-selected="false">Inscription</button>
        </li>
      </ul>

      <div class="tab-content" id="loginRegisterTabContent">
        <?php if (!empty($msg)) { ?>
          <div class="alert alert-danger text-center">
            <?php echo $msg; ?>
          </div>
        <?php } ?>
        <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
          <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
            <div class="mb-3">
              <label for="loginEmail" class="form-label">Adresse Mail</label>
              <input type="email" class="form-control" name="loginEmail" value="<?php echo $valLog ?>"required>
            </div>
            <div class="mb-3">
              <label for="loginPassword" class="form-label">Mot de passe</label>
              <input type="password" class="form-control" name="loginPassword" value="<?php echo $valMdp ?>" required>
            </div>
            <button type="submit" class="btn btn-primary w-100" name="loginButton">Se connecter</button>
          </form>
        </div>
        
        <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="register-tab">
          <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
            <div class="mb-3">
              <label for="registerEmail" class="form-label">Adresse Mail</label>
              <input type="email" class="form-control" name="registerEmail" required>
            </div>
            <div class="mb-3">
              <label for="registerPassword" class="form-label">Mot de passe</label>
              <input type="password" class="form-control" name="registerPassword" required>
            </div>
            <div class="mb-3">
              <label for="registerConfirmPassword" class="form-label">Confirmez le mot de passe</label>
              <input type="password" class="form-control" name="registerConfirmPassword" required>
            </div>
            <div class="form-check">
              <input type="checkbox" class="form-check-input" name="rememberMe" id="rememberMe">
              <label class="form-check-label" for="rememberMe">Se souvenir de moi</label>
            </div>
            <button type="submit" class="btn btn-success w-100 mt-3" name="registerButton">S'inscrire</button>
          </form>
        </div>

      </div>
    </div>
    <?php
      }
      ?>
  </body>
</html>
