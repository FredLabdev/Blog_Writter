<?php
require('model/frontend.php');

//**************************************************************************************
//**************************************************************************************
//**************************************************************************************
//************************************ FRONTEND ****************************************                 
//**************************************************************************************
//**************************************************************************************
//**************************************************************************************

//**************************************************************************************
//                              Fonctions pour le login                
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
            require('view/frontend/loginView.php');
        }
    } else {
        require('view/frontend/loginView.php');
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
        $message_error .=  'Ce pseudo existe déjà !' . '<br>';
    }
    return $message_error;
}

function mailControl($mail, $mailConfirm, $message_error) {
    if (preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $mail)) {
        $mailIdem = getMailIdem($mail);
        if ($mailIdem['email_idem'] == 0) {
            if ($mailConfirm == $mail) {
            } else {
                $message_error .=  'Vos 2 adresses mail sont différentes !' . '<br>';
            } 
        } else {
            $message_error .=  'Cette adresse mail existe déjà !' . '<br>';
        }     
    } else {
        $message_error .=  'Le format d\'adresse mail n\'est pas valide.' . '<br>';
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
                    $message_error .=  'Ce mot de passe existe déjà !' . '<br>';
                }
            }
        } else {
            $message_error .=  'Vos mots de passes ne sont pas identiques !' . '<br>';
        }   
    } else {
        $message_error .=  'Votre mot de passe doit être composé de minimum 8 caractères'  . '<br>' . 'dont 1 Majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial !' . '<br>';
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
        loginControl($createPseudo, $createPassword); // et on démmarre sa session
    } else {
        require('view/frontend/loginView.php'); // retour au login avec affichage des erreurs
    }
}

//**************************************************************************************
//                      Fonctions pour l'afichage des billets                
//**************************************************************************************

function listPosts($page, $message_success) {
    $postsCount = getPostsCount();
    $postsList = getPosts();
    $pages_max = getPagesMax($postsCount);
    if ($page <= $pages_max) {
        $offset = ($page-1)*5;  
        $postsBy5 = getPostsBy5($offset);
        $billet_max = $postsCount['nbre_posts']-($offset);
        $message_success;
        if ($billet_max <= 5) {
            $billet_min = 1;
        } else {
            $billet_min = $billet_max-4;
        }
        require('view/frontend/postsListView.php');
    } else {
        throw new Exception('Mauvais indice de page envoyé');
    }
}

function getPagesMax($postsCount) {
    if ($postsCount['nbre_posts']%5 == 0) { // 5 billets par page
        $pages_max = $postsCount['nbre_posts']/5;
    } else {
        $pages_max = ($postsCount['nbre_posts']/5)+1;
    }
    return $pages_max;
}

function post($postId, $message_success, $message_error) {
    $postDetails = getPost($postId);
    $message_success;
    $message_error;
    $comments = getComments($postId);
    require('view/frontend/postView.php');
}

//**************************************************************************************
//                   Fonctions pour l'afichage des commentaires                  
//**************************************************************************************

function commentsByPost($page) {
    $commentsCount = getCommentsCount($postId);
}

//**************************************************************************************
//                       Fonctions pour la gestion des membres             
//**************************************************************************************

function contactsHome($message_success, $message_error, $contactDetails) {
    $contactsCount = getContactsCount();
    $contactsByGroup = getContactsByGroup();
    $contactsByName = getContactsByName();
    $message_success;
    $message_error;
    $contactDetails;
    if ($_SESSION['group_id'] == 1) {
        require('view/backend/membersAdminView.php');
    } else {
        require('view/frontend/memberAdminView.php');
    }      
}

function contactDetail($message_success, $message_error, $contactId) {
    $contactDetails = getContactDetail($contactId);
    contactsHome($message_success, $message_error, $contactDetails);
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

function contactDelete($contactId) {
    deleteContact($contactId);
    $message_success =  utf8_encode('Ce compte a bien été supprimé...');
    if ($_SESSION['group_id'] == 1) {
        contactDetail($message_success, "", $contactId);
    } else {
        require('view/frontend/loginView.php');
    }   
}

//**************************************************************************************
//                              Fonction pour la deconnexion                  
//**************************************************************************************

function sessionEnd() {
    $login_error = 'Vous êtes bien déconnecté.' . '<br>' . 'A bientôt ' . $_SESSION['first_name'];
    require('view/frontend/loginView.php');
    $_SESSION = array(); // Suppression des variables de session et de la session
    session_destroy();
    setcookie('pseudo', ''); // Suppression des cookies de connexion automatique
    setcookie('password', '');
}
