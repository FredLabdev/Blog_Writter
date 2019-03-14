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
        //                     Accessible sans ouverture de session 
        //**************************************************************************************
        //**************************************************************************************
        //                         de postView / postsListView             
        //**************************************************************************************

            // Lister les billets (sans indice de page), 
        if ($_GET['action'] == 'listPosts') {
            listPosts(1, "", "");
        }

            // Lister les billets (avec un indice de page),     
        else if ($_GET['action'] == 'pagePosts') {
            if (isset($_GET['page']) && isset($_GET['page']) > 0) {
                $page = getCleanParameter($_GET['page']);
                listPosts($page, "", "");
            } else {
                throw new Exception('Aucun identifiant de page de billets envoyé');
            }
        }

        //**************************************************************************************
        //                                de loginView                
        //**************************************************************************************

            // Formulaire de connexion,
        else if ($_GET['action'] == 'login') {
            $pseudo_connect = getCleanParameter($_POST['pseudo_connect']);
            $password_connect = getCleanParameter($_POST['password_connect']);
            if (!empty($pseudo_connect) && !empty($password_connect)) {
                loginControl($pseudo_connect, $password_connect);
            } else { 
                loginControl("","");
            }
        } 

            // Formulaire de création de compte,
        else if ($_GET['action'] == 'newMember') {
            $name = getCleanParameter($_POST['name']);
            $first_name = getCleanParameter($_POST['first_name']);
            $pseudo = getCleanParameter($_POST['pseudo']);
            $email = getCleanParameter($_POST['email']);
            $email_confirm = getCleanParameter($_POST['email_confirm']);
            $password = getCleanParameter($_POST['password']);
            $password_confirm = getCleanParameter($_POST['password_confirm']);
            if(!empty($name) && !empty($first_name) && !empty($pseudo) && !empty($email) && !empty($email_confirm) && !empty($password) && !empty($password_confirm)) {
                newMember($name, $first_name, $pseudo, $email, $email_confirm, $password, $password_confirm);
            } else {
                newMember("","","","","","","","");
            }
        } 
        
        //**************************************************************************************
        //             toute autre action accessible uniquement au cours d'une session             
        //**************************************************************************************

        else if (!empty($_SESSION['pseudo']) && !empty($_SESSION['password'])) {
            
            //**************************************************************************************
            //                          de postView / postsListView             
            //**************************************************************************************


                // Détailler un billet,    
            if ($_GET['action'] == 'post') {
                if (isset($_GET['billet']) && $_GET['billet'] > 0) {
                    $billet = getCleanParameter($_GET['billet']);
                    post($billet, "", "");
                } else {
                    throw new Exception('Aucun identifiant de page de billets envoyé');
                }
            }

                // Ajouter un billet,     
            else if ($_GET['action'] == 'addPost') {
                $titre = getCleanParameter($_POST['titre']);
                $newPostHTML = $_POST['newPostHTML'];
                $newPostPlainText = getCleanParameter($_POST['newPostPlainText']); 
                $postBefore = getCleanParameter($_POST['postBefore']);               
                if(!empty($titre) && !empty($newPostHTML)) {
                    if (!empty($postBefore)) {
                        newPost($titre, $newPostHTML, $newPostPlainText, $postBefore);
                    } else {
                        newPost($titre, $newPostHTML, $newPostPlainText, "");
                    }
                } else {
                    newPost("","","","");
                }
            }

                // modifier un billet   
            else if ($_GET['action'] == 'postModif') {  
                $postId = getCleanParameter($_POST['postId']);
                $titre = getCleanParameter($_POST['titre']);
                $modifPostHTML = getCleanParameter($_POST['modifPostHTML']);
                $modifPostPlainText = getCleanParameter($_POST['modifPostPlainText']);
                if ($postId) {
                    if(!empty($titre) && !empty($modifPostHTML)) {
                        modifPost($postId, $titre, $modifPostHTML, $modifPostPlainText);
                    } else if (empty($titre)) {
                        post($postId, "",  'Attention : Titre vide !');
                    } else if (empty($modifPostHTML) && empty($modifPostPlainText)) {
                        post($postId, "",  'Attention : Billet vide !');
                    } 
                } else {
                    post($postId, "",  'Erreur : Aucun billet sélectionné', "");
                }
            }

            // supprimer un billet,   
            else if ($_GET['action'] == 'postDelete') {
                $postId = getCleanParameter($_GET['postId']);
                if ($postId) {
                    postErase($postId);
                } else {
                    post($postId, "",  'Erreur : Aucun billet sélectionné', "");
                }
            }

                // Ajouter un commentaire,     
            else if ($_GET['action'] == 'addComment') {
                $postId = getCleanParameter($_POST['postId']);
                $nv_comment = getCleanParameter($_POST['nv_comment']);
                if ($postId) {
                    addCommentRequest($postId, $_SESSION['pseudo'], $nv_comment);
                } else {
                    throw new Exception('Aucun identifiant de billet envoyé');
                }
            }

                // Modifier un commentaire,     
            else if ($_GET['action'] == 'modifComment') {
                $postId = getCleanParameter($_POST['postId']);
                $modifCommentId = getCleanParameter($_POST['modifCommentId']);
                $modifComment = getCleanParameter($_POST['modifComment']);
                if ($postId) {
                    modifCommentRequest($postId, $_SESSION['pseudo'], $modifCommentId, $modifComment);
                } else {
                    throw new Exception('Aucun identifiant de billet envoyé');
                }
            }

                       // signaler un commentaire,   
            else if ($_GET['action'] == 'signalComment') {
                $postId = getCleanParameter($_POST['postId']);
                $signal_comment = getCleanParameter($_POST['signal_comment']);
                $signal_commentId = getCleanParameter($_POST['signal_commentId']);
               if ($postId) {
                    commentSignal($postId, $signal_comment, $signal_commentId, $_SESSION['pseudo']);  
                } else {
                    throw new Exception('Aucun identifiant de billet envoyé');
                }
            }

                // Effacer un commentaire,     
            else if ($_GET['action'] == 'deleteComment') {
                $postId = getCleanParameter($_POST['postId']);
                $delete_comment = getCleanParameter($_POST['delete_comment']);
                if ($_POST['postId']) {
                    commentErase($_POST['postId'], $delete_comment);  
                } else {
                    commentErase("", $delete_comment); 
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
                $member = getCleanParameter($_POST['member']);
                $delete_comment = getCleanParameter($_POST['delete_comment']);
                if (isset($member)) {
                    if (!empty($member)) {
                        memberDetail("", "", $member, 'backend');
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
                    $member_modif = getCleanParameter($_POST['member_modif']);
                    if(isset($_POST['block_comment'])) { // Interdir/autoriser de commenter 
                        $block_comment = getCleanParameter($_POST['block_comment']);
                        memberBloqComment($member_modif, $block_comment, 'backend');   
                    } else if(isset($_POST['moderator'])) { // Interdir/autoriser à être modérateur 
                        $moderator = getCleanParameter($_POST['moderator']);                       
                        memberModerator($member_modif, $moderator, 'backend');   
                    } else {
                        memberDetail("",  'Veuillez sélectionner une action', "", 'backend');
                    }

                   // Son propre compte (FRONTEND),   
                } else if ($_POST['personal_modif']) { 
                    $personal_modif = getCleanParameter($_POST['personal_modif']);
                    $champ = getCleanParameter($_POST['champ']);
                    $modif_champ = getCleanParameter($_POST['modif_champ']);
                    $modif_champ_confirm = getCleanParameter($_POST['modif_champ_confirm']);
                    if(!empty($champ) && !empty($modif_champ) && !empty($modif_champ_confirm)) {
                        if ($champ == 1) { // Modification du mail
                            newMail($personal_modif, $modif_champ, $modif_champ_confirm);
                        } else if ($champ == 2) { // Modification du mot de passe     
                            newPassword($personal_modif, $modif_champ, $modif_champ_confirm);
                        }
                    } else if(empty($champ) && empty($modif_champ) && empty($modif_champ_confirm)) {
                            memberDetail("",  'Veuillez sélectionner un champ et une action', $_SESSION['id'], "");
                    } else if (empty($champ)) {
                            memberDetail("",  'Veuillez selectionner un champ', $_SESSION['id'], "");
                    } else if (empty($modif_champ)) {
                            memberDetail("",  'Veuillez rentrer une nouvelle valeure au champ', $_SESSION['id'], "");
                    } else if (empty($modif_champ_confirm)) {
                            memberDetail("",  'Veuillez confirmer cette nouvelle valeure', $_SESSION['id'], "");
                    } 
                }
            }
            

               // Supprimer un compte member, 
            else if ($_GET['action'] == 'memberDelete') {
                if ($_GET['memberErase']) {
                    $memberErase = getCleanParameter($_GET['memberErase']);
                    memberDelete($memberErase, 'backend');
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
            //                             de connexion      
            //**************************************************************************************

                // Demande de connexion à une session   
            else if ($_GET['action'] == 'connexion') {
                require('view/frontend/loginView.php');
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
            require('view/frontend/loginView.php');
        }
        
    }

    //**************************************************************************************
    //       Sinon si le routeur ne recoit aucune action dans l'URL (entrée sur site)             
    //**************************************************************************************

            // Soit on récupère un cookie autorisé d'un précédent login_ok,    
    else if ($_COOKIE['password']) {
        $pseudo = getCleanParameter($_COOKIE['pseudo']);
        $password = getCleanParameter($_COOKIE['password']);
        loginAvailable($pseudo, $password);
    }
            // Soit on dirige vers la page d'accueil en libre accés, 
    else {
        header('Location: index.php?action=listPosts'); 
    }

//**************************************************************************************
//                   Redirection des erreurs vers page errorView             
//**************************************************************************************

} catch(Exception $e) {
    $errorMessage = $e->getMessage();
    require('view/errorView.php');
}

//**************************************************************************************
//                      Fonction d'évitement de la faille XSS             
//**************************************************************************************

function getCleanParameter($parameter){
    $trimmedParameter = trim($parameter);
    $cleanedParameter = nl2br(htmlspecialchars($trimmedParameter));
    return $cleanedParameter;
}
