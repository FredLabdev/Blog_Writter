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
        createAccount();
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
    //                     Actions pour le contacts_view (Admin)              
    //**************************************************************************************
     
        // Lister les contacts,   
    else if ($_GET['action'] == 'contacts') {
        contactsHome("", "");
    } 
    
        // Détailler un contact,    
    else if ($_GET['action'] == 'contactDetail') {
        if (isset($_POST['contact']) AND isset($_POST['valider'])) {
            contactDetail($_POST['contact']);
        } else {
            echo 'Erreur : aucun contact selectionné';
        }
    }
    
        // modifier un contact   
    else if ($_GET['action'] == 'contactModif') {
        if (isset($_POST['contact-modif'])) {
            if(isset($_POST['bloquage'])) { // Lui interdir de commenter 
                contactBloqComment($_POST['contact-modif']);   
            } else if(isset($_POST['champ']) AND isset($_POST['modif_champ'])) { // Modification du pseudo
                if ($_POST['champ'] == 1) {
                    contactModifPseudo($_POST['contact-modif'], $_POST['modif_champ']); // Modification du mail       
                } else if ($_POST['champ'] == 2) {
                    contactModifMail($_POST['contact-modif'], $_POST['modif_champ']); // Modification du mot de passe     
                } else if ($_POST['champ'] == 3) {
                    contactModifPassword($_POST['modif_champ'], $_POST['contact-modif']);
                } else {
                    echo 'Erreur : Aucun champ à modifier';
                }
            } else if (!isset($_POST['champ'])) {
                echo 'Erreur : Veuillez selectionner un champ';
            } else if (!isset($_POST['modif_champ'])) {
                echo 'Erreur : Veuillez rentrer une nouvelle valeure au chanmp';
            } else {
                echo 'Erreur : aucune modification selectionnée';
            }
        } else {
        echo 'Erreur : aucun contact selectionné';
        }
    }
    
       // supprimer un contact   
    else if ($_GET['action'] == 'contactDelete') {
        if ($_GET['contactErase']) {
            contactDelete($_GET['contactErase']);
        } else {
            echo 'Erreur : aucun contact selectionné';
        }
    }
    
    //**************************************************************************************
    //                     Actions pour la deconnexion             
    //**************************************************************************************
     
        // supprimer un contact   
    else if ($_GET['action'] == 'deconnexion') {
            sessionEnd();
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
