<?php
    ini_set("display_errors", 1);
    error_reporting(E_ALL);

    header("Content-type: text/html; charset=UTF-8");
    session_start();

    $bdd = mysqli_connect('localhost', 'root', '', 'db-exercices');
    // table client crée directement sur PhpMyAdmin

    $msg = "";

    // Si on clique sur le bouton de déconnexion
    if (isset($_POST["btn_logout"])) {
        echo "test";
        unset($_SESSION["login"]); // On détruit la session login
    } else if (isset($_POST["btn_login"])) { // On appuie sur le bouton de connexion
        if (isset($_COOKIE["client"]["pwd"]) && $_POST['pwd']==$_COOKIE['client']['pwd']) {
            $pwd = "tata";
        } else {
            $pwd = "toto";
        }
        
        $data = mysqli_query($bdd, "SELECT * FROM `td1-client`;");
        var_dump($data);
        if ($data && mysqli_fetch_row($data)) {
            $_SESSION["login"] = $_POST["login"];
            $msg = "Connexion ok";
        }

    } else if (isset($_POST["btn_register"])) { // On appuie sur le bouton d'inscription
        if ($_POST["input_mail"] == "" || $_POST["input_password"] || $_POST["input_password_confirm"]) {
            $msg = "Formulaire d'inscription incomplet";
        } else if ($_POST["input_password"] != $_POST["input_password_confirm"]) {
            $msg = "Les mot de passe sont différents !";
        } else {
            $data = mysqli_query($bdd, "SELECT * FROM `td1-client` WHERE mail=".$_POST["input_mail"].";");
            if (mysqli_num_rows($data) == 1) {
                $msg = "Utilisateur déjà en base";
            } else {
                $password_hash = password_hash($_POST["input_password"], PASSWORD_BCRYPT);
                mysqli_query($bdd, "INSERT INTO `td1-client` (mail, password) VALUES (".$_POST['input_mail'].",".$password_hash.");");
                $msg = "Inscription faite";
            }
        }
        echo $msg;
    } else { // Contexte hors traitement
        if (isset($_SESSION["login"])) {
            $msg = "Bienvenue";
        } else {
            $msg = "au revoir";
        }
    }

    echo $msg;

    // TODO : mettre le cookie et la session...
?>

<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Guillaume Hostache">
        <meta name="description" content="M2 CIM - Programmation Javascript/Php avancée">

        <link rel="stylesheet" href="./style.css"/>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

	    <script type="text/javascript" src="./script.js"></script>
        <title>TD1 - Exercices</title>
    </head>

    <body>
        <div class="login-box">
            <div class="lb-header">
                <a href="#" class="active" id="login-box-link">Connexion</a>
                <a href="#" id="signup-box-link">Inscription</a>
            </div>
            
            <form class="email-login" method="post">
                <div class="u-form-group">
                    <input name="input_mail" type="email" placeholder="Votre adresse mail"/>
                </div>
                <div class="u-form-group">
                    <input name="input_password" type="password" placeholder="Password"/>
                </div>
                <div class="u-form-group">
                    <button name="btn_login">Se connecter</button>
                </div>
            </form>

            <form class="email-signup" method="post">
                <div class="u-form-group">
                    <input name="input_mail" type="email" placeholder="Votre adresse mail"/>
                </div>
                <div class="u-form-group">
                    <input name="input_password" type="password" placeholder="Password"/>
                </div>
                <div class="u-form-group">
                    <input name="input_password_confirm" type="password" placeholder="Confirm Password"/>
                </div>
                <div class="u-form-group">
                    <button name="btn_register">S'inscrire</button>
                </div>
            </form>
        </div>
    </body>
</html>