<?php 
  
namespace FredLab\tp4_blog_ecrivain\Model;

require_once("model/Manager.php");

class PostManager extends Manager { // se situe dans le namespace

    // private $postTitle;
    // private $postContentHTML;
    // private $postExtract;
    // private $postBefore;
    // private $postId;
    // private $newPostTitle;
    // private $newPostContentHTML;
    // private $postExtract;

    public function getPostsCount() {
        $db = $this->dbConnect();
        $req = $db->query('SELECT COUNT(id) AS nbre_posts FROM posts');
        $postsCount = $req->fetch();
        $req->closeCursor();
        return $postsCount;
    }

    public function getPosts() {
        $db = $this->dbConnect();
        $posts = $db->query('SELECT chapter_title, creation_date FROM posts ORDER BY creation_date DESC');
        $postsList = array(); 
        while ($post = $posts->fetch()) {
            $postsList[] = $post; // on créer un tableau regroupant les posts
        }
        return $postsList;
    }

    public function getPostsBy5($offset) {
        $db = $this->dbConnect();
        $getPostsBy5 = $db->prepare('SELECT id, chapter_title, chapter_content, chapter_extract, DATE_FORMAT(creation_date, \'%d/%m/%Y à %Hh%imin%ss\') AS date FROM posts ORDER BY creation_date DESC LIMIT 5 OFFSET :idmax'); // OFFSET selon indice page
        $getPostsBy5->bindValue(':idmax', $offset, \PDO::PARAM_INT);
        $getPostsBy5->execute();
        $postsBy5 = array(); 
        while ($post = $getPostsBy5->fetch()) {
            $postsBy5[] = $post; // on créer un tableau regroupant les 5 posts
        }
        return $postsBy5;
    }

    public function getPost($postId) {
        $db = $this->dbConnect();
        $getPostDetail = $db->prepare('SELECT id, chapter_title, chapter_content, DATE_FORMAT(creation_date, \'%d/%m/%%Hh%imin%ss\') AS creation_date_fr FROM posts WHERE id = ?');
        $getPostDetail->execute(array($postId));
        $postDetails = array(); 
        while ($postDetail = $getPostDetail->fetch()) {
            $postDetails[] = $postDetail; // on créer un tableau regroupant les donnees des members
        }
        return $postDetails;
    }
    
    public function getAllPosts() {
        $db = $this->dbConnect();
        $posts = $db->query('SELECT chapter_title, chapter_content FROM posts ORDER BY creation_date');
        $postsAll = array(); 
        while ($post = $posts->fetch()) {
            $postsAll[] = $post; // on créer un tableau regroupant les posts
        }
        return $postsAll;
    }
    
//**************************************************************************************
//                        Model backend PostManager           
//**************************************************************************************
    
    public function addPost($postTitle, $postContentHTML, $postExtract, $postBefore) {            
        $db = $this->dbConnect();
        $req = $db->prepare('INSERT INTO posts(creation_date, chapter_title, chapter_content, chapter_extract) VALUES(NOW(), :titre, :contenu, :extract)');
        $req->execute(array(
            'titre' => $postTitle,
            'contenu' => $postContentHTML,
            'extract' => $postExtract
        ));
        $req->closeCursor();
    }
 
    public function changePostTitle($postId, $newPostTitle) {
        $db = $this->dbConnect();
        $modifTitle = $db->prepare('UPDATE posts SET chapter_title = :nvtitre WHERE id = :idnum');
        $modifTitle->execute(array(
            'nvtitre' => $newPostTitle,
            'idnum' => $postId
        )); 
    }

    public function changePostContent($postId, $newPostContentHTML, $postExtract) {
        $db = $this->dbConnect();
        $modifContent = $db->prepare('UPDATE posts SET chapter_content = :nvcontenu, chapter_extract = :nvextract WHERE id = :idnum');
        $modifContent->execute(array(
            'nvcontenu' => $newPostContentHTML,
            'nvextract' => $postExtract,
            'idnum' => $postId
        )); 
    }

    public function deletePost($postId) {  
        $db = $this->dbConnect();
        $req = $db->prepare('DELETE FROM posts WHERE id = :idnum');
        $req->execute(array(
            'idnum' => $postId
        ));  
        $req->closeCursor();
    }
    
}
