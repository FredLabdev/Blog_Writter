<?php
require('controller.php');

//**************************************************************************************
//                              Routeur pour le login                
//**************************************************************************************

// Si on récupère un cookie autorisé d'un précédent login_ok, 
if ($_COOKIE['password']) {
    getMemberData(htmlspecialchars($_COOKIE['pseudo']), htmlspecialchars($_COOKIE['password'])); // on appele la récupération de données d'un membre.
}

// Sinon si on récupère un formulaire de connexion,
else if (isset($_POST['login'])) {
    if (!empty($_POST['pseudo_connect']) && !empty($_POST['password_connect'])) {
        loginControl(htmlspecialchars($_POST['pseudo_connect']), htmlspecialchars($_POST['password_connect'])); // on appele le Contrôle de validité du login.
    }
    else {
        $login_error = '<p class="alert">' . 'Erreur : tous les champs ne sont pas remplis !' . '</p>';
        require('login.php');
    }
} 

// Sinon si on récupère un formulaire de création de compte,
else if (isset($_POST['newMember'])) {
    accountControl(); // on appele le Contrôle de validité du compte.
} 

// Sinon on affiche la page de connexion
else {
    require('login.php');
}

//**************************************************************************************
//                     Routeur pour le backend_comment_billet_admin              
//**************************************************************************************
