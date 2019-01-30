<?php
require('controller.php');

//**************************************************************************************
//                       Si le routeur recoit une action dans l'URL              
//**************************************************************************************

if (isset($_GET['action'])) {

    //**************************************************************************************
    //                              Actions de login                
    //**************************************************************************************

        // Formulaire de connexion,

    if ($_GET['action'] == 'login') {
        if (!empty($_POST['pseudo_connect']) && !empty($_POST['password_connect'])) {
            loginControl(htmlspecialchars($_POST['pseudo_connect']), htmlspecialchars($_POST['password_connect']));
        }
        else {
            $login_error = '<p class="alert">' . 'Erreur : tous les champs ne sont pas remplis !' . '</p>';
            require('login.php');
        }
    } 

        // Formulaire de création de compte,

    else if ($_GET['action'] == 'newMember') {
        accountControl();
    } 

    //**************************************************************************************
    //                     Actions pour le post_view              
    //**************************************************************************************
     
        // Lister les billets, 
     
    else if ($_GET['action'] == 'listPosts') {
        listPosts(1);
    }

        // Indice de page de billets (par groupe de 5), 
     
    else if ($_GET['action'] == 'pagePosts') {
        if (isset($_GET['page']) > 0) {
            listPosts($_GET['page']);
        } else {
            echo 'Erreur : aucun identifiant de page de billets envoyé';
        }
    }
     
        // Détailler un billet, 
     
    else if ($_GET['action'] == 'post') {
        if (isset($_GET['billet']) && $_GET['billet'] > 0) {
            post($_GET['billet']);
        } else {
            echo 'Erreur : aucun identifiant de billet envoyé';
        }
    }
     
        // Ajouter un commentaire, 
     
    else if ($_GET['action'] == 'addComment') {
        if (isset($_GET['billet']) && $_GET['billet'] > 0) {
            allowComment($_GET['billet'], $_SESSION['pseudo'], $_POST['nv_comment']);
        } else {
            echo 'Erreur : aucun identifiant de billet envoyé';
        }
    }
     
        // Effacer un commentaire, 
     
    else if ($_GET['action'] == 'deleteComment') {
        if (isset($_GET['billet']) && $_GET['billet'] > 0) {
            commentErase($_GET['billet'], $_POST['delete_comment']);  
        } else {
            echo 'Erreur : aucun identifiant de billet envoyé';
        }
    }
    
    //**************************************************************************************
    //                     Actions pour le page Contacts (Admin)              
    //**************************************************************************************
     
        // Lister les contacts, 
     
    else if ($_GET['action'] == 'contacts') {
        contactsHome();
    } 
    
        // Détailler un contact, 
     
    else if ($_GET['action'] == 'contactDetail') {
        if (isset($_GET['billet']) && $_GET['billet'] > 0) {
            post($_GET['billet']);
        } else {
            echo 'Erreur : aucun contact selectionné';
        }
    }

} 

//**************************************************************************************
//                        Sinon si aucune action (1ère connexion)             
//**************************************************************************************
        
        // Soit on récupère un cookie autorisé d'un précédent login_ok, 
    
else if ($_COOKIE['password']) {
    loginAvailable(htmlspecialchars($_COOKIE['pseudo']), htmlspecialchars($_COOKIE['password']));
}
        // Soit on dirige vers la page de connexion, 
    
else {
    require('login.php');
}
