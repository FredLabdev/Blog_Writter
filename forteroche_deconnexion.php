<?php 
session_start();
$title = 'Deconnexion';
$template = 'frontend';
$menu = 'no_menu';
ob_start();

    echo 'Vous êtes bien déconnecté.' . '<br>';
    echo 'A bientôt ' . $_SESSION['first_name'] . '<br>';
    echo '<a href="index.php">Retour à l\'accueil</a>';

    // Suppression des variables de session et de la session
    $_SESSION = array();
    session_destroy();

    // Suppression des cookies de connexion automatique
    setcookie('pseudo', '');
    setcookie('password', '');

$content = ob_get_clean();

require('template.php');
