<?php
require('model.php');

//**************************************************************************************
//                           Fonctions pour le login                    
//**************************************************************************************

function loginControl($pseudo, $password) {
    $login_error = '<p class="alert">' . 'Erreur : pseudo et/ou mot de passe errone(s) !' . '</p>';
    $dbPassword = (getPasswordFromPseudo($pseudo))['password']; // on r�cup�re le password de la db si pseudo ok
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
                // 1 - R�cup�ration de ses donn�es   
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


function newMember($createName, $createFirstName, $createPseudo, $createMail, $mailConfirm, $createPassword, $passwordConfirm) {
    $account_error = ''; // On d�fini une variable regroupant les erreurs
    $pseudoIdem = pseudoControl($createPseudo);
    if ($pseudoIdem['pseudo_idem'] == 0) {
    } else {
        $account_error .= 'D�sol�, ce pseudo existe d�j� !';
    }
    // Adresse email: v�rification format, 2 saisies idem, et pas d�j� existante dans la db
    if (preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $createMail)) {
        $mailIdem = mailControl($createMail);
        if ($mailIdem['email_idem'] == 0) {
            if ($mailConfirm == $createMail) {
            } else {
                $account_error .= 'Attention vos 2 adresses mail sont diff�rentes !';
            } 
        } else {
            $account_error .= 'D�sol� cette adresse mail existe d�j� !';
        }     
    } else {
        $account_error .= 'D�sol� le format d\'adresse mail n\'est pas valide.';
    }    
    // Mot de passe: v�rification format, 2 saisies idem, et pas d�j� existant dans la db 
    if (preg_match("#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}$#", $createPassword)) {
        if ($passwordConfirm == $createPassword) {
            $getAllPassword = getAllPassword();
            while ($allPassword = $getAllPassword->fetch()) {
                $isPasswordExist = password_verify($createPassword, $allPassword['password']);
                if (!$isPasswordExist) {   
                } else {
                    $account_error .= 'D�sol� ce mot de passe existe d�j� !';
                }
            }
        } else {
                $account_error .= 'Attention vos mots de passes ne sont pas identiques !';
        }   
    } else {
        $account_error .= 'D�sol� votre mot de passe doit �tre compos� de minimum 8 caract�res'  . '<br>' . 'dont 1 Majuscule, 1 minuscule, 1 chiffre et 1 caract�re sp�cial !';
    }    
    
    if ($account_error == '') { // Fin, si tout ok (variable d'erreurs rest�e � 0),
        memberCreate($createName, $createFirstName, $createPseudo, $createMail, $createPassword);
        $account_success = 'Bonjour, votre compte est bien cr�� !' . '<br>' . 'Acc�dez au site en vous connectant ci-dessus.';
        loginControl($createPseudo, $createPassword);
    } else {
        require('login.php');
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
    $commentModif = 'Voici le d�tail de ce contact :';
    contactsHome($commentModif, $contactDetail);
}

function contactDelete($contactId) {
    deleteContact($contactId);
    $commentModif = 'Le contact a bien �t� Supprim� !';
    contactsHome($commentModif, "");
}

function contactBloqComment($contactId) {
    bloqContactComment($contactId);
    $commentModif = 'Le contact a bien �t� bloqu� et ne pourra plus commenter !';
    contactsHome($commentModif, "");
}

function contactModifPseudo($contactId, $contactEntryNewData) {
    modifPseudo($contactId, $contactEntryNewData);
    $commentModif = 'La modification du pseudo du contact a bien �t� enr�gistr�e !';
    contactsHome($commentModif, "");
}

function contactModifMail($contactId, $contactEntryNewData) {
    modifMail($contactId, $contactEntryNewData);
    $commentModif = 'La modification de l\'email du contact a bien �t� enr�gistr�e !';
    contactsHome($commentModif, "");
}

function contactModifPassword($contactId, $contactEntryNewData) {
    modifPassword($contactId, $contactEntryNewData);
    $commentModif = 'La modification du mot de passe du contact a bien �t� enr�gistr�e !';
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
        $commentError = '<p class="alert">D�sol� vous n\'�tes pas autoris� � poster des comments</p>';
    } else {
        addComment($postId, $member, $newComment);     
        $commentSuccess = '<p class="success">' . 'Votre commentaire a bien �t� publi� ci-dessous' . '</p>';
    }
    post($postId);
}

function commentErase($postId, $commentId) {
    deleteComment($commentId);     
    $commentErase = '<p class="success">' . 'Le comment '. $commentId . ' a bien �t� Supprim� !' . '</p>';
    post($postId);
}

//**************************************************************************************
//                              Fonction pour la deconnexion                  
//**************************************************************************************

function sessionEnd() {
    $login_error = '<p class="alert">' . 'Vous �tes bien d�connect�.' . '<br>' . 'A bient�t ' . $_SESSION['first_name'] . '</p>';
    require('login.php');
    $_SESSION = array(); // Suppression des variables de session et de la session
    session_destroy();
    setcookie('pseudo', ''); // Suppression des cookies de connexion automatique
    setcookie('password', '');
}
