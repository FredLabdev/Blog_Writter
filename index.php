<?php
session_start();
require('controller/frontend.php');
require('controller/backend.php');

try {

    //**************************************************************************************
    //                       Si le routeur recoit une action dans l'URL              
    //**************************************************************************************

    if (isset($_GET['action'])) {
        

        //**************************************************************************************
        //                                de loginView                
        //**************************************************************************************

            // Formulaire de connexion,
        if ($_GET['action'] == 'login') {
            if (!empty($_POST['pseudo_connect']) && !empty($_POST['password_connect'])) {
                loginControl(htmlspecialchars($_POST['pseudo_connect']), htmlspecialchars($_POST['password_connect']));
            } else { 
                loginControl("","");
            }
        } 

            // Formulaire de création de compte,
        else if ($_GET['action'] == 'newMember') {
            if(!empty($_POST['name']) && !empty($_POST['first_name']) && !empty($_POST['pseudo']) && !empty($_POST['email']) && !empty($_POST['email_confirm']) && !empty($_POST['password']) && !empty($_POST['password_confirm'])) {
                newMember(htmlspecialchars($_POST['name']), htmlspecialchars($_POST['first_name']), htmlspecialchars($_POST['pseudo']), htmlspecialchars($_POST['email']), htmlspecialchars($_POST['email_confirm']), htmlspecialchars($_POST['password']), htmlspecialchars($_POST['password_confirm']));
            } else {
                newMember("","","","","","","","");
            }
        } 
        
        //**************************************************************************************
        //                           toute autre action au cours d'une session             
        //**************************************************************************************

        else if (!empty($_SESSION['pseudo']) && !empty($_SESSION['password'])) {
            
            //**************************************************************************************
            //                            de postView / postsListView             
            //**************************************************************************************

                // Lister les billets (sans indice de page), 
            if ($_GET['action'] == 'listPosts') {
                listPosts(1, "", "");
            }

                // Lister les billets (avec un indice de page),     
            else if ($_GET['action'] == 'pagePosts') {
                if (isset($_GET['page']) && isset($_GET['page']) > 0) {
                    listPosts($_GET['page'], "", "");
                } else {
                    throw new Exception('Aucun identifiant de page de billets envoyé');
                }
            }

                // Détailler un billet,    
            else if ($_GET['action'] == 'post') {
                if (isset($_GET['billet']) && $_GET['billet'] > 0) {
                    post($_GET['billet'], "", "");
                } else {
                    throw new Exception('Aucun identifiant de page de billets envoyé');
                }
            }

                // Ajouter un billet,     
            else if ($_GET['action'] == 'addPost') {
                if(!empty($_POST['titre']) && !empty($_POST['newPostHTML'])) {
                    if (!empty($_POST['postBefore'])) {
                        newPost($_POST['titre'], $_POST['newPostHTML'], $_POST['newPostPlainText'], $_POST['postBefore']);
                    } else {
                        newPost($_POST['titre'], $_POST['newPostHTML'], $_POST['newPostPlainText'], "");
                    }
                } else {
                    newPost("","","","");
                }
            }

                // modifier un billet   
            else if ($_GET['action'] == 'postModif') {  
                if ($_POST['postId']) {
                    if(!empty($_POST['titre']) && !empty($_POST['modifPostHTML'])) {
                        modifPost($_POST['postId'], $_POST['titre'], $_POST['modifPostHTML'], $_POST['modifPostPlainText']);
                    } else if (empty($_POST['titre'])) {
                        post($_POST['postId'], "",  utf8_encode('Attention : Titre vide !'));
                    } else if (empty($_POST['modifPostHTML']) && empty($_POST['modifPostPlainText'])) {
                        post($_POST['postId'], "",  utf8_encode('Attention : Billet vide !'));
                    } 
                } else {
                    post($_POST['postId'], "",  utf8_encode('Erreur : Aucun billet sélectionné'), "");
                }
            }

            // supprimer un billet,   
            else if ($_GET['action'] == 'postDelete') {
                if ($_GET['postId']) {
                    postErase($_GET['postId']);
                } else {
                    post($_GET['postId'], "",  utf8_encode('Erreur : Aucun billet sélectionné'), "");
                }
            }

                // Ajouter un commentaire,     
            else if ($_GET['action'] == 'addComment') {
                if ($_POST['postId']) {
                    addCommentRequest($_POST['postId'], $_SESSION['pseudo'], $_POST['nv_comment']);
                } else {
                    throw new Exception('Aucun identifiant de billet envoyé');
                }
            }

                // Modifier un commentaire,     
            else if ($_GET['action'] == 'modifComment') {
                if ($_POST['postId']) {
                    modifCommentRequest($_POST['postId'], $_SESSION['pseudo'], $_POST['modifCommentId'], $_POST['modifComment']);
                } else {
                    throw new Exception('Aucun identifiant de billet envoyé');
                }
            }

                       // signaler un commentaire,   
            else if ($_GET['action'] == 'signalComment') {
               if ($_POST['postId']) {
                    commentSignal($_POST['postId'], $_POST['signal_comment'], $_POST['signal_commentId'], $_SESSION['pseudo']);  
                } else {
                    throw new Exception('Aucun identifiant de billet envoyé');
                }
            }

                // Effacer un commentaire,     
            else if ($_GET['action'] == 'deleteComment') {
                if ($_POST['postId']) {
                    commentErase($_POST['postId'], $_POST['delete_comment']);  
                } else {
                    commentErase("", $_POST['delete_comment']); 
                }
            }

                // Voir l'intégralité du roman, 
            else if ($_GET['action'] == 'publishing') {
                readAllPosts();
            }

            //**************************************************************************************
            //                       de memberAdminView / membersAdminView          
            //**************************************************************************************

                // Aficher detail d'un compte lambda depuis le backend   
            else if ($_GET['action'] == 'membersDetail') {
                if (isset($_POST['member'])) {
                    if (!empty($_POST['member'])) {
                        memberDetail("", "", $_POST['member'], 'backend');
                    } else {
                         memberDetail("",  'Veuillez sélectionner un membre', "", 'backend');
                    }
                } else {
                    memberDetail("", "", "", 'backend');
                }
             }

                // Afficher detail de son propre compte par un membre   
            else if ($_GET['action'] == 'memberDetail') {
                    memberDetail("", "", $_SESSION['id'], "");
            }

                // Modifier un compte membre  
            else if ($_GET['action'] == 'memberModif') {  

                    // Un compte tiers (BACKEND),
                if ($_POST['member_modif']) {
                    if(isset($_POST['block_comment'])) { // Interdir/autoriser de commenter 
                        memberBloqComment($_POST['member_modif'], $_POST['block_comment'], 'backend');   
                    } else if(isset($_POST['moderator'])) { // Interdir/autoriser à être modérateur 
                        memberModerator($_POST['member_modif'], $_POST['moderator'], 'backend');   
                    } else {
                        memberDetail("",  'Veuillez sélectionner une action', "", 'backend');
                    }

                   // Son propre compte (FRONTEND),   
                } else if ($_POST['personal_modif']) { 
                    if(!empty($_POST['champ']) && !empty($_POST['modif_champ']) && !empty($_POST['modif_champ_confirm'])) {
                        if ($_POST['champ'] == 1) { // Modification du mail
                            newMail($_POST['personal_modif'], htmlspecialchars($_POST['modif_champ']), htmlspecialchars($_POST['modif_champ_confirm']));
                        } else if ($_POST['champ'] == 2) { // Modification du mot de passe     
                            newPassword($_POST['personal_modif'], htmlspecialchars($_POST['modif_champ']), htmlspecialchars($_POST['modif_champ_confirm']));
                        }
                    } else if(empty($_POST['champ']) && empty($_POST['modif_champ']) && empty($_POST['modif_champ_confirm'])) {
                            memberDetail("",  'Veuillez sélectionner un champ et une action', $_SESSION['id'], "");
                    } else if (empty($_POST['champ'])) {
                            memberDetail("",  utf8_encode('Veuillez selectionner un champ'), $_SESSION['id'], "");
                    } else if (empty($_POST['modif_champ'])) {
                            memberDetail("",  utf8_encode('Veuillez rentrer une nouvelle valeure au champ'), $_SESSION['id'], "");
                    } else if (empty($_POST['modif_champ_confirm'])) {
                            memberDetail("",  utf8_encode('Veuillez confirmer cette nouvelle valeure'), $_SESSION['id'], "");
                    } 
                }
            }

               // Supprimer un compte member, 
            else if ($_GET['action'] == 'memberDelete') {
                if ($_GET['memberErase']) {
                    memberDelete($_GET['memberErase'], 'backend');
                } else {
                    throw new Exception('Aucun membre selectionné');
                }
            }


            //**************************************************************************************
            //                                  de contactView            
            //**************************************************************************************

                // Acces à la page du formulaire de Contact, 
            else if ($_GET['action'] == 'contact') {
                require('view/frontend/contactView.php');
            }   
                // Réception d'un formulaire de Contact,     
            else if($_GET['action'] == 'contactForm') {
                $errorMessage = 'Désolé, cette fonction est encore en travaux...';  
                require('view/errorView.php');
            }

            //**************************************************************************************
            //                           de la deconnexion (javascript)            
            //**************************************************************************************

                // Deconnecter une session   
            else if ($_GET['action'] == 'deconnexion') {
                sessionEnd();
            } 
            
        //**************************************************************************************
        //                        Si action url sans connexion préalable            
        //**************************************************************************************

        } else {
            throw new Exception('Vous n\'êtes pas encore autorisé à accéder au site. Veuillez vous connecter');
        }
        
    }

    //**************************************************************************************
    //       Sinon si le routeur ne recoit aucune action dans l'URL (entrée sur site)             
    //**************************************************************************************

            // Soit on récupère un cookie autorisé d'un précédent login_ok,    
    else if ($_COOKIE['password']) {
        loginAvailable(htmlspecialchars($_COOKIE['pseudo']), htmlspecialchars($_COOKIE['password']));
    }
            // Soit on dirige vers la page de connexion, 
    else {
        require('view/frontend/loginView.php');
    }

//**************************************************************************************
//                   Redirection des erreurs vers page errorView             
//**************************************************************************************

} catch(Exception $e) {
    $errorMessage = $e->getMessage();
    require('view/errorView.php');
}
