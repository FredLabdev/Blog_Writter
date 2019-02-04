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
            $login_error =  utf8_encode('Erreur : tous les champs ne sont pas remplis !');
            require('login.php');
        }
    } 

        // Formulaire de création de compte,
    else if ($_GET['action'] == 'newMember') {
        if(!empty($_POST['name']) && !empty($_POST['first_name']) && !empty($_POST['pseudo']) && !empty($_POST['email']) && !empty($_POST['email_confirm']) && !empty($_POST['password']) && !empty($_POST['password_confirm'])) {
            newMember(htmlspecialchars($_POST['name']), htmlspecialchars($_POST['first_name']), htmlspecialchars($_POST['pseudo']), htmlspecialchars($_POST['email']), htmlspecialchars($_POST['email_confirm']), htmlspecialchars($_POST['password']), htmlspecialchars($_POST['password_confirm']));
        } else {
            $account_error =  utf8_encode('Erreur : Veuillez renseigner tous les champs');
            require('login.php');
        }
    } 

    //**************************************************************************************
    //                     Actions pour le post_view              
    //**************************************************************************************
     
        // Lister les billets, 
    else if ($_GET['action'] == 'listPosts') {
        listPosts(1, "");
    }

        // Indice de page de billets (par groupe de 5),     
    else if ($_GET['action'] == 'pagePosts') {
        if (isset($_GET['page']) > 0) {
            listPosts($_GET['page'], "");
        } else {
            echo  utf8_encode('Erreur : aucun identifiant de page de billets envoyé');
        }
    }
     
        // Détailler un billet,    
    else if ($_GET['action'] == 'post') {
        if (isset($_GET['billet']) && $_GET['billet'] > 0) {
            post($_GET['billet'], "", "");
        } else {
            echo  utf8_encode('Erreur : aucun identifiant de billet envoyé');
        }
    }
     
        // Ajouter un billet,     
    else if ($_GET['action'] == 'addPost') {
        if(!empty($_POST['titre']) AND !empty($_POST['contenu'])) {
            if (!empty($_POST['postBefore'])) {
                newPost(htmlspecialchars($_POST['titre']), htmlspecialchars($_POST['contenu']), htmlspecialchars($_POST['postBefore']));
            } else {
                newPost(htmlspecialchars($_POST['titre']), htmlspecialchars($_POST['contenu']), "");
            }
        } else {
            $post_error =  utf8_encode('Erreur : Veuillez renseigner tous les champs');
            require('home_view.php');
        }
    }
    
        // modifier un billet   
    else if ($_GET['action'] == 'postModif') {  
        if ($_POST['postId']) {
            if(!empty($_POST['champ']) AND !empty($_POST['modif_champ'])) {
                if ($_POST['champ'] == 1) {
                    newPostTitle($_POST['postId'], htmlspecialchars($_POST['modif_champ']));
                } else if ($_POST['champ'] == 2) {
                    newPostContent($_POST['postId'], htmlspecialchars($_POST['modif_champ']));
                } else {
                    echo  utf8_encode('Erreur : Aucun champ à modifier');
                }
            } else if (empty($_POST['champ'])) {
                post($_POST['postId'], "",  utf8_encode('Erreur : Veuillez selectionner un champ'));
            } else if (empty($_POST['modif_champ'])) {
                post($_POST['postId'], "",  utf8_encode('Erreur : Veuillez rentrer une nouvelle valeure au champ'));
            } 
        } else {
            post($_POST['postId'], "",  utf8_encode('Erreur : Aucun billet sélectionné'), "");
        }
    }
    
       // supprimer un billet   
    else if ($_GET['action'] == 'postDelete') {
        if ($_GET['postId']) {
            postErase($_GET['postId']);
        } else {
            post($_GET['postId'], "",  utf8_encode('Erreur : Aucun billet sélectionné'), "");
        }
    }
     
        // Ajouter un commentaire,     
    else if ($_GET['action'] == 'addComment') {
        if (isset($_GET['billet']) && $_GET['billet'] > 0) {
            allowComment($_GET['billet'], $_SESSION['pseudo'], $_POST['nv_comment']);
        } else {
            echo  utf8_encode('Erreur : aucun identifiant de billet envoyé');
        }
    }
     
        // Effacer un commentaire,     
    else if ($_GET['action'] == 'deleteComment') {
        if (isset($_GET['billet']) && $_GET['billet'] > 0) {
            commentErase($_GET['billet'], $_POST['delete_comment']);  
        } else {
            echo  utf8_encode('Erreur : aucun identifiant de billet envoyé');
        }
    }
    
    //**************************************************************************************
    //                     Actions pour le contacts_view (Admin)              
    //**************************************************************************************
    
        // Affichage des contacts,    
    else if ($_GET['action'] == 'contactDetail') {
        if ($_SESSION['group_id'] == 1) { // => demande detail d'un compte lambda depuis le backend
            if (isset($_POST['valider'])) {
                if (!empty($_POST['contact'])) {
                    contactDetail("", "", $_POST['contact']);
                } else {
                    contactDetail("",  utf8_encode('Erreur : Veuillez sélectionner un contact'), "");
                }
            } else {
                contactDetail("", "", "");
            }
        } else { // => demande detail de son propre compte par un membre
            contactDetail("", "", $_SESSION['id']);
        }
    }
    
        // modifier un contact   
    else if ($_GET['action'] == 'contactModif') {  
        if ($_POST['contact_modif']) {
            if(isset($_POST['bloquage'])) { // Backend : interdir de commenter 
                contactBloqComment($_POST['contact_modif'], $_POST['bloquage']);   
            } else if(!empty($_POST['champ']) AND !empty($_POST['modif_champ']) AND !empty($_POST['modif_champ_confirm'])) {
                if ($_POST['champ'] == 1) { // Frontend : modification du mail
                    newMail($_POST['contact_modif'], htmlspecialchars($_POST['modif_champ']), htmlspecialchars($_POST['modif_champ_confirm']));
                } else if ($_POST['champ'] == 2) { // Frontend : Modification du mot de passe     
                    newPassword($_POST['contact_modif'], htmlspecialchars($_POST['modif_champ']), htmlspecialchars($_POST['modif_champ_confirm']));
                } else {
                    echo  utf8_encode('Erreur : Aucun champ à modifier');
                }
            } else if (empty($_POST['champ'])) {
                contactDetail("",  utf8_encode('Erreur : Veuillez selectionner un champ'), $_SESSION['id']);
            } else if (empty($_POST['modif_champ'])) {
                contactDetail("",  utf8_encode('Erreur : Veuillez rentrer une nouvelle valeure au champ'), $_SESSION['id']);
            } else if (empty($_POST['modif_champ_confirm'])) {
                contactDetail("",  utf8_encode('Erreur : Veuillez confirmer cette nouvelle valeure'), $_SESSION['id']);
            }
        } else {
            contactDetail("",  utf8_encode('Erreur : Veuillez sélectionner un contact'), "");
        }
    }
    
       // supprimer un contact   
    else if ($_GET['action'] == 'contactDelete') {
        if ($_GET['contactErase']) {
            contactDelete($_GET['contactErase']);
        } else {
            echo  utf8_encode('Erreur : aucun contact selectionné');
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
