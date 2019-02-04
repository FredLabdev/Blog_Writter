<?php
require('model.php');

//**************************************************************************************
//                           Fonctions pour le login                    
//**************************************************************************************

function loginControl($pseudo, $password) {
    $login_error = 'Erreur : pseudo et/ou mot de passe errone(s) !';
    $dbPassword = (getPasswordFromPseudo($pseudo))['password']; // on récupère le password de la db si pseudo ok
    $isPasswordCorrect = password_verify($password, $dbPassword); 
    if ($dbPassword) {
        if ($isPasswordCorrect) {
            if (isset($_POST['login_auto'])) {
                setcookie('pseudo', $pseudo, time() + 365*24*3600, null, null, false, true);
                setcookie('password', $dbPassword, time() + 365*24*3600, null, null, false, true);
            }
            loginAvailable($pseudo, $dbPassword);
        } else {
            require('login.php');
        }
    } else {
        require('login.php');
    }
}

function loginAvailable($pseudo, $password) {
                // 1 - Récupération de ses données   
    $memberData = getMemberData($pseudo, $password);
                // 2 - Ouverture de session   
    sessionStart($memberData);
                // 3 - re-direction vers accueil front ou backend
    homePageDirect($memberData['pseudo'], $memberData['group_id']);
}

function sessionStart($memberData) {
    session_start();
    $_SESSION['id'] = $memberData['id'];
    $_SESSION['name'] = $memberData['name'];
    $_SESSION['first_name'] = $memberData['first_name'];
    $_SESSION['pseudo'] = $memberData['pseudo'];
    $_SESSION['password'] = $memberData['password'];    
    $_SESSION['group_id'] = $memberData['group_id'];    
} 

function homePageDirect($pseudo, $group) {
    if ($_SESSION['group_id']) {
        header('Location: index.php?action=listPosts'); 
    }  
    else if ($group !== 1) {
        header('Location: index.php?action=listPosts');
    }  
}

function pseudoControl($pseudo, $message_error) {
    $pseudoIdem = getPseudoIdem($pseudo);
    if ($pseudoIdem['pseudo_idem'] == 0) {
    } else {
        $message_error .=  utf8_encode('Désolé, ce pseudo existe déjà !');
    }
    return $message_error;
}

function mailControl($mail, $mailConfirm, $message_error) {
    if (preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $mail)) {
        $mailIdem = getMailIdem($mail);
        if ($mailIdem['email_idem'] == 0) {
            if ($mailConfirm == $mail) {
            } else {
                $message_error .=  utf8_encode('Attention vos 2 adresses mail sont différentes !');
            } 
        } else {
            $message_error .=  utf8_encode('Désolé cette adresse mail existe déjà !');
        }     
    } else {
        $message_error .=  utf8_encode('Désolé le format d\'adresse mail n\'est pas valide.');
    }    
    return $message_error;
}

function passwordControl($password, $passwordConfirm, $message_error) {
    if (preg_match("#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}$#", $password)) {
        if ($passwordConfirm == $password) {
            $getAllPassword = getAllPassword();
            while ($allPassword = $getAllPassword->fetch()) {
                $isPasswordExist = password_verify($password, $allPassword['password']);
                if (!$isPasswordExist) {   
                } else {
                    $message_error .=  utf8_encode('Désolé ce mot de passe existe déjà !');
                }
            }
        } else {
            $message_error .=  utf8_encode('Attention vos mots de passes ne sont pas identiques !');
        }   
    } else {
        $message_error .=  utf8_encode('Désolé votre mot de passe doit être composé de minimum 8 caractères'  . '<br>' . 'dont 1 Majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial !');
    }    
    return $message_error;
}

function newMember($createName, $createFirstName, $createPseudo, $createMail, $mailConfirm, $createPassword, $passwordConfirm) {
    $message_error = '';
    $message_error = pseudoControl($createPseudo, $message_error);
    $message_error = mailControl($createMail, $mailConfirm, $message_error);
    $message_error = passwordControl($createPassword, $passwordConfirm, $message_error);    
    if ($message_error == '') { // Si tout ok on creer le nouveau membre,
        memberCreate($createName, $createFirstName, $createPseudo, $createMail, $createPassword);
        $account_success =  utf8_encode('Bonjour, votre compte est bien créé !' . '<br>' . 'Accédez au site en vous connectant ci-dessus.');
        loginControl($createPseudo, $createPassword); // et on démmarre sa session
    } else {
        require('login.php'); // retour au login avec affichage des erreurs
    }
}

//**************************************************************************************
//                        Fonctions pour l'afichage des contacts             
//**************************************************************************************

function contactsHome($message_success, $message_error, $contactDetail) {
    $contactsCount = getContactsCount();
    $contactsByGroup = getContactsByGroup();
    $contactsByName = getContactsByName();
    $message_success;
    $message_error;
    $contactDetail;
    if ($_SESSION['group_id'] == 1) {
        require('contacts_view.php');
    } else {
        require('contact_view.php');
    }      
}

function contactDetail($message_success, $message_error, $contactId) {
    $contactDetail = getContactDetail($contactId);
    contactsHome($message_success, $message_error, $contactDetail);
}

//**************************************************************************************
//                        Fonctions pour la modification des contacts             
//**************************************************************************************

function contactDelete($contactId) {
    deleteContact($contactId);
    $message_success =  utf8_encode('Le compte a bien été supprimé...');
    contactDetail($message_success, "", $contactId);
}

function contactBloqComment($contactId, $blockId) {
    bloqContactComment($contactId, $blockId);
    if ($blockId == 1) {
        $message_success =  utf8_encode('Le contact a bien été bloqué et ne pourra plus commenter !');
    } else {
        $message_success =  utf8_encode('Le contact a bien été débloqué et pourra de nouveau commenter !');
    }
    
    contactDetail($message_success, "", $contactId);
}

function newPseudo($contactId, $newPseudo) {
    $message_error = '';
    $message_error = pseudoControl($newPseudo, $message_error);
    if ($message_error == '') { // Si tout ok on creer le nouveau membre,
        contactModifPseudo($contactId, $newPseudo); // et on démmarre sa session
    } else {
        require('contact_view.php'); // retour au login avec affichage des erreurs
    }
}

function contactModifPseudo($contactId, $newPseudo) {
    modifPseudo($contactId, $newPseudo);
    $message_success =  utf8_encode('La modification du pseudo du contact a bien été enrégistrée !');
    contactDetail($message_success, "", $contactId);
}

function newMail($contactId, $newMail, $mailConfirm) {
    $message_error = '';
    $message_error = mailControl($newMail, $mailConfirm, $message_error);
    if ($message_error == '') { // Si tout ok on creer le nouveau membre,
        contactModifMail($contactId, $newMail); // et on démmarre sa session
    } else {
        contactDetail("", $message_error, $contactId);
    }
}

function contactModifMail($contactId, $newMail) {
    modifMail($contactId, $newMail);
    $message_success =  utf8_encode('La modification de l\'email du contact a bien été enrégistrée !');
    contactDetail($message_success, "", $contactId);
}

function newPassword($contactId, $newPassword, $passwordConfirm) {
    $message_error = '';
    $message_error = passwordControl($newPassword, $passwordConfirm, $message_error);
    if ($message_error == '') { // Si tout ok on creer le nouveau membre,
        contactModifPassword($contactId, $newPassword); // et on démmarre sa session
    } else {
        contactDetail("", $message_error, $contactId);
    }
}

function contactModifPassword($contactId, $newPassword) {
    modifPassword($contactId, $newPassword);
    $message_success =  utf8_encode('La modification du mot de passe du contact a bien été enrégistrée !');
    contactDetail($message_success, "", $contactId);
}

//**************************************************************************************
//                Fonctions pour l'afichage d'un billet et ses commentaires                  
//**************************************************************************************

function listPosts($page, $message_success) {
    $postsCount = getPostsCount();
    $posts = getPosts();
    $offset = ($page-1)*5;  
    $postsBy5 = getPostsBy5($offset);
    $billet_max = $postsCount['nbre_posts']-($offset);
    $message_success;
    if ($billet_max <= 5) {
        $billet_min = 1;
    } else {
        $billet_min = $billet_max-4;
    }
    require('home_view.php');
}

function commentsByPost($page) {
    $commentsCount = getCommentsCount($postId);
}

function post($postId, $message_success, $message_error) {
    $post = getPost($postId);
    $message_success;
    $message_error;
    $comments = getComments($postId);
    require('post_view.php');
}

function postExtract($text) {
    $max=20;
    if (strlen($text) > $max) { // vérifie que texte plus long que max extrait
        // récupère 1er espace après $max pour éviter de couper un mot en plein milieu
        $space = strpos($text,' ',$max);
        //récupère l'extrait jusqu'à l'espace préalablement cherché auquel on ajoute "..."
        $postExtract = substr($text,0,$space).'...';
    }
    return $postExtract;
}

function newPost($postTitle, $postContent, $postBefore) {
    $postExtract = postExtract($postContent);
    if(!empty($postBefore)) {
        addPost($postTitle, $postContent, $postExtract, $postBefore); 
    } else {
        addPost($postTitle, $postContent, $postExtract, "");     
    }
    $message_success =  utf8_encode('Votre billet ' . $postTitle . ' a bien été publié ci-dessus');
    listPosts(1, $message_success, "");
}

function newPostTitle($postId, $newPostTitle) {
    postModifTitle($postId, $newPostTitle);
    $message_success =  utf8_encode('Le titre de l\'épisode ' . $postId . ' a bien été modifié ci-dessous !');
    post($postId, $message_success, "");
}

function newPostContent($postId, $newPostContent) {
    postModifContent($postId, $newPostContent);
    $message_success =  utf8_encode('Le contenu de l\'épisode ' . $postId . ' a bien été modifié ci-dessous !');
    post($postId, $message_success, "");
}
    
function postErase($postId) {
    deletePost($postId);     
    $message_success =  utf8_encode('Le billet '. $postId . ' a bien été Supprimé !');
    listPosts(1, $message_success);
}

function allowComment($postId, $member, $newComment) {
    $allowComment = permitComments($member);
    $message_error = "";
    if($allowComment['block_comment'] == 1) {
        $message_error =  utf8_encode('Désolé vous n\'êtes pas autorisé à poster des comments');
    } else {
        addComment($postId, $member, $newComment);     
        $message_success =  utf8_encode('Votre commentaire a bien été publié ci-dessous');
    }
    post($postId, $message_success, $message_error);
}

function commentErase($postId, $commentId) {
    deleteComment($commentId);     
    $message_success =  utf8_encode('Le comment '. $commentId . ' a bien été Supprimé !');
    post($postId, $message_success, "");
}

//**************************************************************************************
//                              Fonction pour la deconnexion                  
//**************************************************************************************

function sessionEnd() {
    $login_error = utf8_encode('Vous êtes bien déconnecté.' . '<br>' . 'A bientôt ' . $_SESSION['first_name']);
    require('login.php');
    $_SESSION = array(); // Suppression des variables de session et de la session
    session_destroy();
    setcookie('pseudo', ''); // Suppression des cookies de connexion automatique
    setcookie('password', '');
}
