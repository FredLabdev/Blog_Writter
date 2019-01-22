<?php
require('model.php');

//**************************************************************************************
//                   Fonction de Contrôle du formulaire de login                  
//**************************************************************************************

function loginControl($pseudo, $password) {
    $login_error = '<p class="alert">' . 'Erreur : pseudo et/ou mot de passe errone(s) !' . '</p>';
    $dbPassword = (pseudoControl($pseudo))['password']; // on récupère le password de la db si pseudo ok
    $isPasswordCorrect = password_verify($password, $dbPassword); 
    if ($dbPassword) {
        if ($isPasswordCorrect) {
            if (isset($_POST['login_auto'])) {
                setcookie('pseudo', $pseudo, time() + 365*24*3600, null, null, false, true);
                setcookie('password', $dbPassword, time() + 365*24*3600, null, null, false, true);
            }
            getMemberData($pseudo, $dbPassword);
        } else {
            require('login.php');
        }
    } else {
        require('login.php');
    }
}

//**************************************************************************************
//              Fonction de recuperation des donnees d'un membre connecte                
//**************************************************************************************

function getMemberData($pseudo, $password) {
    $memberData = memberConnect($pseudo, $password);
    sessionStart($memberData);  // on passe ses donnees en argument pour démarrer la session
    homePageDirect($memberData['pseudo'], $memberData['group_id']); // puis on le dirige vers sa page d'accueil selon type administrateur ou membre
}

//**************************************************************************************
//                       Fonction d'ouverture de session                  
//**************************************************************************************

function sessionStart($memberData) {
    // on démarre la session, et on stocke les paramètres utiles aux autres pages
    session_start();
    $_SESSION['name'] = $memberData['name'];
    $_SESSION['first_name'] = $memberData['first_name'];
    $_SESSION['pseudo'] = $memberData['pseudo'];
    $_SESSION['password'] = $memberData['password'];    
} 

//**************************************************************************************
//                  Fonction de re-direction vers accueil front ou backend                  
//**************************************************************************************

function homePageDirect($pseudo, $group) {
    if ((htmlspecialchars($pseudo == 'admin')) AND ($group == 1)) {
        header('Location: backend_accueil.php'); // Soit on le dirige vers l'accueil backend,
    }  
    else if ($group !== 1) { // soit vers l'accueil frontend. 
        header('Location: frontend_accueil.php');
    }  
}

//**************************************************************************************
//                Fonction de Controle du formulaire d'un nouveau membre                
//**************************************************************************************

function createAccount() {
    $account_error = newMember();
    require('login.php');
}
