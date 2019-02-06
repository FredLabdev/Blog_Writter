<?php

require_once('model/backend/CommentManager.php');
require_once('model/backend/MemberManager.php');
require_once('model/backend/PostManager.php');

//**************************************************************************************
// Controller backend PostManager (+backend CommentManager) (+Controller frontend PostManager)          
//**************************************************************************************

function postExtract($text) {
    $max=250;
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
        $postManager = new \FredLab\tp4_blog_ecrivain\Model\Backend\PostManager();
        $postManager->addPost($postTitle, $postContent, $postExtract, $postBefore); 
    } else {
        $postManager->addPost($postTitle, $postContent, $postExtract, "");     
    }
    $message_success =  'Votre billet ' . $postTitle . ' a bien été publié ci-dessus';
    listPosts(1, $message_success, "");
}

function newPostTitle($postId, $newPostTitle) {
    $postManager = new \FredLab\tp4_blog_ecrivain\Model\Backend\PostManager();
    $postManager->postModifTitle($postId, $newPostTitle);
    $message_success =  'Le titre de l\'épisode ' . $postId . ' a bien été modifié ci-dessous !';
    post($postId, $message_success, "");
}

function newPostContent($postId, $newPostContent) {
    $postExtract = postExtract($newPostContent);
    $postManager = new \FredLab\tp4_blog_ecrivain\Model\Backend\PostManager();
    $postManager->postModifContent($postId, $newPostContent, $postExtract);
    $message_success =  'Le contenu de l\'épisode ' . $postId . ' a bien été modifié ci-dessous !';
    post($postId, $message_success, "");
}
    
function postErase($postId) {
    $postManager = new \FredLab\tp4_blog_ecrivain\Model\Backend\PostManager();
    $postManager->deletePost($postId);     
    $commentManager = new \FredLab\tp4_blog_ecrivain\Model\Backend\CommentManager();
    $commentManager->deleteComments($postId);     
    $message_success =  'Le billet '. $postId . ' et ses ommentaires ont bien été supprimés !';
    listPosts(1, $message_success);
}

//**************************************************************************************
//        Controller backend CommentManager (+Controller frontend PostManager)                  
//**************************************************************************************

function allowComment($postId, $member, $newComment) {
    $commentManager = new \FredLab\tp4_blog_ecrivain\Model\Backend\CommentManager();
    $allowComment = $commentManager->permitComments($member);
    $message_error = "";
    if($allowComment['block_comment'] == 1) {
        $message_error =  'Désolé vous n\'êtes pas autorisé à poster des comments';
    } else {
        $commentManager->addComment($postId, $member, $newComment);     
        $message_success =  'Votre commentaire a bien été publié ci-dessous';
    }
    post($postId, $message_success, $message_error);
}

function commentErase($postId, $commentId) {
    $commentManager = new \FredLab\tp4_blog_ecrivain\Model\Backend\CommentManager();
    $commentManager->deleteComment($commentId);     
    $message_success =  'Le comment '. $commentId . ' a bien été Supprimé !';
    post($postId, $message_success, "");
}

//**************************************************************************************
//         Controller backend MemberManager (+Controller frontend PostManager)              
//**************************************************************************************

function contactBloqComment($contactId, $blockId) {
    $memberManager = new \FredLab\tp4_blog_ecrivain\Model\Backend\MemberManager();
    $memberManager->bloqContactComment($contactId, $blockId);
    if ($blockId == 1) {
        $message_success =  'Le contact a bien été bloqué et ne pourra plus commenter !';
    } else {
        $message_success =  'Le contact a bien été débloqué et pourra de nouveau commenter !';
    }
    contactDetail($message_success, "", $contactId);
}
