<!--****************************************************************************************************************-->
<!--                                                  PHP                                                          -->
<!--****************************************************************************************************************-->

<?php
require('model.php');

// Si on récupère un cookie autorisé d'un précédent login_ok, 
// on se connecte à la Base de données,
// on les envoie en arguments à la fonction de contrôle de cookie,
// qui appelera ou non la fonction d'ouverture de session...
if ($_COOKIE['password']) {
    $db = connectDataBase();
    cookieControl($db, htmlspecialchars($_COOKIE['password']));
}

// Si on récupère un formulaire de connexion,
// on se connecte à la Base de données,
// on l'envoie en argument à la fonction de contrôle de login,
// qui appelera ou non la fonction d'ouverture de session...
if (isset($_POST['login'])) {
    $db = connectDataBase();
    $login_error = loginControl($db);
} 

// Si on récupère un formulaire de création de compte,
// on se connecte à la Base de données,
// on l'envoie en argument à la fonction de contrôle d'un nouveau membre,
if (isset($_POST['newMember'])) {
    $db = connectDataBase();
    $account_error = newMember($db);
} 

// Sinon on affiche la page de connexion
    require('login.php');
