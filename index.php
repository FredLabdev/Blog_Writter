<!--****************************************************************************************************************-->
<!--                                                  PHP                                                          -->
<!--****************************************************************************************************************-->

<?php
require('modele.php');

// Si on récupère un cookie autorisé d'un précédent login_ok, 
// on se connecte à la Base de données,
// on les envoie en arguments à la fonction de contrôle de cookie,
// qui appelera ou non la fonction d'ouverture de session...
if ($_COOKIE['mot_passe']) {
    $bdd = connectDataBase();
    cookieControl($bdd, htmlspecialchars($_COOKIE['mot_passe']));
}

// Si on récupère un formulaire de connexion,
// on se connecte à la Base de données,
// on l'envoie en argument à la fonction de contrôle de login,
// qui appelera ou non la fonction d'ouverture de session...
if (isset($_POST['login'])) {
    $bdd = connectDataBase();
    $login_error = loginControl($bdd);
} 

// Si on récupère un formulaire de création de compte,
// on se connecte à la Base de données,
// on l'envoie en argument à la fonction de contrôle d'un nouveau membre,
if (isset($_POST['newMember'])) {
    $bdd = connectDataBase();
    $account_error = newMember($bdd);
} 

// Sinon on affiche la page de connexion
    require('login.php');

?>
