<?php 
    
//**************************************************************************************
//**************************************************************************************
//**************************************************************************************
//************************************* BACKEND ****************************************                 
//**************************************************************************************
//**************************************************************************************
//**************************************************************************************


//**************************************************************************************
//                        Model backend CommentManager           
//**************************************************************************************

function permitComments($member) {
    $db = dbConnect();
    $req = $db->prepare('SELECT block_comment FROM contacts WHERE pseudo = ?');
    $req->execute(array($member));
    $allowComment = $req->fetch();
    $req->closeCursor();
    return $allowComment;
}

function addComment($postId, $author, $comment) {            
    $db = dbConnect();
    $req = $db->prepare('INSERT INTO comments(post_id, author, comment, comment_date) VALUES(:post_id, :author, :comment, NOW())');
    $req->execute(array(
        'post_id' => $postId,
        'author' => $author,
        'comment' => $comment
    ));
    $req->closeCursor();
}
    
function deleteComment($commentId) {  
    $db = dbConnect();
    $req = $db->prepare('DELETE FROM comments WHERE id = :idnum');
    $req->execute(array(
        'idnum' => $commentId
    ));  
    $req->closeCursor();
}
    
function deleteComments($postId) {  
    $db = dbConnect();
    $req = $db->prepare('DELETE FROM comments WHERE post_id = :postidnum');
    $req->execute(array(
        'postidnum' => $postId
    ));  
    $req->closeCursor();
}
    
//**************************************************************************************
//                        Model
//**************************************************************************************




//**************************************************************************************
//                        Model backend PostManager           
//**************************************************************************************

function addPost($postTitle, $postContent, $postExtract, $postBefore) {            
    $db = dbConnect();
    $req = $db->prepare('INSERT INTO posts(creation_date, chapter_title, chapter_content, chapter_extract) VALUES(NOW(), :titre, :contenu, :extract)');
    $req->execute(array(
        'titre' => $postTitle,
        'contenu' => $postContent,
        'extract' => $postExtract
    ));
    $req->closeCursor();
}
 
function postModifTitle($postId, $newPostTitle) {
    $db = dbConnect();
    $modifTitle = $db->prepare('UPDATE posts SET chapter_title = :nvtitre WHERE id = :idnum');
    $modifTitle->execute(array(
        'nvtitre' => $newPostTitle,
        'idnum' => $postId
    )); 
}

function postModifContent($postId, $newPostContent, $postExtract) {
    $db = dbConnect();
    $modifContent = $db->prepare('UPDATE posts SET chapter_content = :nvcontenu, chapter_extract = :nvextract WHERE id = :idnum');
    $modifContent->execute(array(
        'nvcontenu' => $newPostContent,
        'nvextract' => $postExtract,
        'idnum' => $postId
    )); 
}

function deletePost($postId) {  
    $db = dbConnect();
    $req = $db->prepare('DELETE FROM posts WHERE id = :idnum');
    $req->execute(array(
        'idnum' => $postId
    ));  
    $req->closeCursor();
}

//**************************************************************************************
//                        Model backend MemberManager           
//**************************************************************************************

function bloqContactComment($contactId, $blockId) {
    $db = dbConnect();
    $bloqContactComment = $db->prepare('UPDATE contacts SET block_comment = :blockId WHERE id = :idnum');
    $bloqContactComment->execute(array(
        'blockId' => $blockId,
        'idnum' => $contactId
    )); 
}
