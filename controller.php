<?php
require('model.php');

//**************************************************************************************
//                           Fonctions pour le login                    
//**************************************************************************************

            // Fonction de contrôle d'un formulaire de login

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
            loginAvailable($pseudo, $dbPassword);
        } else {
            require('login.php');
        }
    } else {
        require('login.php');
    }
}

            // Fonction déclenchant le proccessus de connexion (1-2-3): 

function loginAvailable($pseudo, $password) {
                // 1 - Récupération de ses données   
    $memberData = getMemberData($pseudo, $password);
                // 2 - Ouverture de session   
    sessionStart($memberData);
                // 3 - re-direction vers accueil front ou backend
    homePageDirect($memberData['pseudo'], $memberData['group_id']);
}

            // 2 - Fonction d'ouverture de session   

function sessionStart($memberData) {
    // on démarre la session, et on stocke les paramètres utiles aux autres pages
    session_start();
    $_SESSION['name'] = $memberData['name'];
    $_SESSION['first_name'] = $memberData['first_name'];
    $_SESSION['pseudo'] = $memberData['pseudo'];
    $_SESSION['password'] = $memberData['password'];    
} 

            // 3 - Fonction de re-direction vers accueil front ou backend

function homePageDirect($pseudo, $group) {
    if ((htmlspecialchars($pseudo == 'admin')) AND ($group == 1)) {
        header('Location: index.php?action=listPosts'); // Soit on le dirige vers l'accueil backend,
    }  
    else if ($group !== 1) { // soit vers l'accueil frontend. 
        header('Location: frontend_accueil.php');
    }  
}

            // Fonction de Controle du formulaire d'un nouveau membre

function createAccount() {
    $account_error = newMember();
    require('login.php');
}

//**************************************************************************************
//                Fonctions pour l'afichage d'un billet et ses commentaires                  
//**************************************************************************************

            // Liste de tous les billets

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
    require('backend_accueil.php');
}

            // Nbre de commentaires d'un billet

function commentsByPost($page) {
    $commentsCount = getCommentsCount($postId);
}

            // Détail d'un billet et ses commentaires

function post($postId) {
    $post = getPost($postId);
    if(!$post) {
        $postError = '<p class="alert">' . 'Ce billet n\'existe pas !' . '</p>';
    } 
    $comments = getComments($postId);
    require('backend_comment_billet_admin.php');
}

            // Autorisation et ajout d'un commentaire

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

          // Fonction supprimant un commentaire

function commentErase($postId, $commentId) {
    deleteComment($commentId);     
    $commentErase = '<p class="success">' . 'Le comment '. $commentId . ' a bien été Supprimé !' . '</p>';
    post($postId);
}
