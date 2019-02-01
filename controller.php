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
    $_SESSION['name'] = $memberData['name'];
    $_SESSION['first_name'] = $memberData['first_name'];
    $_SESSION['pseudo'] = $memberData['pseudo'];
    $_SESSION['password'] = $memberData['password'];    
    $_SESSION['group_id'] = $memberData['group_id'];    
} 

function homePageDirect($pseudo, $group) {
    if ((htmlspecialchars($pseudo == 'admin')) AND ($group == 1)) {
        header('Location: index.php?action=listPosts'); 
    }  
    else if ($group !== 1) {
        header('Location: index.php?action=listPosts');
    }  
}

function pseudoControl($pseudo, $account_error) {
    $pseudoIdem = getPseudoIdem($pseudo);
    if ($pseudoIdem['pseudo_idem'] == 0) {
    } else {
        $account_error .= 'Désolé, ce pseudo existe déjà !';
    }
    return $account_error;
}

function mailControl($mail, $mailConfirm, $account_error) {
    if (preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $mail)) {
        $mailIdem = getMailIdem($mail);
        if ($mailIdem['email_idem'] == 0) {
            if ($mailConfirm == $mail) {
            } else {
                $account_error .= 'Attention vos 2 adresses mail sont différentes !';
            } 
        } else {
            $account_error .= 'Désolé cette adresse mail existe déjà !';
        }     
    } else {
        $account_error .= 'Désolé le format d\'adresse mail n\'est pas valide.';
    }    
    return $account_error;
}

function passwordControl($password, $passwordConfirm, $account_error) {
    if (preg_match("#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}$#", $password)) {
        if ($passwordConfirm == $password) {
            $getAllPassword = getAllPassword();
            while ($allPassword = $getAllPassword->fetch()) {
                $isPasswordExist = password_verify($password, $allPassword['password']);
                if (!$isPasswordExist) {   
                } else {
                    $account_error .= 'Désolé ce mot de passe existe déjà !';
                }
            }
        } else {
            $account_error .= 'Attention vos mots de passes ne sont pas identiques !';
        }   
    } else {
        $account_error .= 'Désolé votre mot de passe doit être composé de minimum 8 caractères'  . '<br>' . 'dont 1 Majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial !';
    }    
    return $account_error;
}

function newMember($createName, $createFirstName, $createPseudo, $createMail, $mailConfirm, $createPassword, $passwordConfirm) {
    $account_error = '';
    $account_error = pseudoControl($createPseudo, $account_error);
    $account_error = mailControl($createMail, $mailConfirm, $account_error);
    $account_error = passwordControl($createPassword, $passwordConfirm, $account_error);    
    if ($account_error == '') { // Si tout ok on creer le nouveau membre,
        memberCreate($createName, $createFirstName, $createPseudo, $createMail, $createPassword);
        $account_success = 'Bonjour, votre compte est bien créé !' . '<br>' . 'Accédez au site en vous connectant ci-dessus.';
        loginControl($createPseudo, $createPassword); // et on démmarre sa session
    } else {
        require('login.php'); // retour au login avec affichage des erreurs
    }
}

//**************************************************************************************
//                        Fonctions pour l'afichage des contacts             
//**************************************************************************************

function contactsHome($message, $contactDetail) {
    $contactsCount = getContactsCount();
    $contactsByGroup = getContactsByGroup();
    $contactsByName = getContactsByName();
    $message;
    $contactDetail;
    require('contacts_view.php');
}

function contactDetail($contactId) {
    $contactDetail = getContactDetail($contactId);
    $commentModif = 'Voici le détail de ce contact :';
    contactsHome($commentModif, $contactDetail);
}

function contactDelete($contactId) {
    deleteContact($contactId);
    $commentModif = 'Le contact a bien été Supprimé !';
    contactsHome($commentModif, "");
}

function contactBloqComment($contactId) {
    bloqContactComment($contactId);
    $commentModif = 'Le contact a bien été bloqué et ne pourra plus commenter !';
    contactsHome($commentModif, "");
}

function contactModifPseudo($contactId, $contactEntryNewData) {
    modifPseudo($contactId, $contactEntryNewData);
    $commentModif = 'La modification du pseudo du contact a bien été enrégistrée !';
    contactsHome($commentModif, "");
}

function contactModifMail($contactId, $contactEntryNewData) {
    modifMail($contactId, $contactEntryNewData);
    $commentModif = 'La modification de l\'email du contact a bien été enrégistrée !';
    contactsHome($commentModif, "");
}

function contactModifPassword($contactId, $contactEntryNewData) {
    modifPassword($contactId, $contactEntryNewData);
    $commentModif = 'La modification du mot de passe du contact a bien été enrégistrée !';
    contactsHome($commentModif, "");
}

//**************************************************************************************
//                Fonctions pour l'afichage d'un billet et ses commentaires                  
//**************************************************************************************

function listPosts($page) {
    $postsCount = getPostsCount();
    $posts = getPosts();
    $offset = ($page-1)*5;  
    $postsBy5 = getPostsBy5($offset);
    $billet_max = $postsCount['nbre_posts']-($offset);
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

function post($postId) {
    $post = getPost($postId);
    if(!$post) {
        $postError = '<p class="alert">' . 'Ce billet n\'existe pas !' . '</p>';
    } 
    $comments = getComments($postId);
    require('post_view.php');
}

function allowComment($postId, $member, $newComment) {
    $allowComment = permitComments($member);
    if($allowComment['block_comment'] == 1) {
        $commentError = '<p class="alert">Désolé vous n\'êtes pas autorisé à poster des comments</p>';
    } else {
        addComment($postId, $member, $newComment);     
        $commentSuccess = '<p class="success">' . 'Votre commentaire a bien été publié ci-dessous' . '</p>';
    }
    post($postId);
}

function commentErase($postId, $commentId) {
    deleteComment($commentId);     
    $commentErase = '<p class="success">' . 'Le comment '. $commentId . ' a bien été Supprimé !' . '</p>';
    post($postId);
}

//**************************************************************************************
//                              Fonction pour la deconnexion                  
//**************************************************************************************

function sessionEnd() {
    $login_error = '<p class="alert">' . 'Vous êtes bien déconnecté.' . '<br>' . 'A bientôt ' . $_SESSION['first_name'] . '</p>';
    require('login.php');
    $_SESSION = array(); // Suppression des variables de session et de la session
    session_destroy();
    setcookie('pseudo', ''); // Suppression des cookies de connexion automatique
    setcookie('password', '');
}
