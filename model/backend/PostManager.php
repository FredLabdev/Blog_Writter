<?php 
  
namespace FredLab\tp4_blog_ecrivain\Model\Backend;

require_once("model/backend/Manager.php");

class PostManager extends Manager { // se situe dans le namespace

//**************************************************************************************
//                        Model backend PostManager           
//**************************************************************************************

    // private $postTitle;
    // private $postContent;
    // private $postExtract;
    // private $postBefore;
    // private $postId;
    // private $newPostTitle;
    // private $newPostContent;
    // private $postExtract;

    public function addPost($postTitle, $postContent, $postExtract, $postBefore) {            
        $db = dbConnect();
        $req = $db->prepare('INSERT INTO posts(creation_date, chapter_title, chapter_content, chapter_extract) VALUES(NOW(), :titre, :contenu, :extract)');
        $req->execute(array(
            'titre' => $postTitle,
            'contenu' => $postContent,
            'extract' => $postExtract
        ));
        $req->closeCursor();
    }
 
    public function postModifTitle($postId, $newPostTitle) {
        $db = dbConnect();
        $modifTitle = $db->prepare('UPDATE posts SET chapter_title = :nvtitre WHERE id = :idnum');
        $modifTitle->execute(array(
            'nvtitre' => $newPostTitle,
            'idnum' => $postId
        )); 
    }

    public function postModifContent($postId, $newPostContent, $postExtract) {
        $db = dbConnect();
        $modifContent = $db->prepare('UPDATE posts SET chapter_content = :nvcontenu, chapter_extract = :nvextract WHERE id = :idnum');
        $modifContent->execute(array(
            'nvcontenu' => $newPostContent,
            'nvextract' => $postExtract,
            'idnum' => $postId
        )); 
    }

    public function deletePost($postId) {  
        $db = dbConnect();
        $req = $db->prepare('DELETE FROM posts WHERE id = :idnum');
        $req->execute(array(
            'idnum' => $postId
        ));  
        $req->closeCursor();
    }

}
