<?php 
  
namespace FredLab\tp4_blog_ecrivain\Model\Frontend;

require_once("model/frontend/Manager.php");

class PostManager extends Manager { // se situe dans le namespace

//**************************************************************************************
//                        Model frontend PostManager           
//**************************************************************************************

    public function getPostsCount() {
        $db = dbConnect();
        $req = $db->query('SELECT COUNT(id) AS nbre_posts FROM posts');
        $postsCount = $req->fetch();
        $req->closeCursor();
        return $postsCount;
    }

    public function getPosts() {
        $db = dbConnect();
        $posts = $db->query('SELECT chapter_title, creation_date FROM posts ORDER BY creation_date DESC');
        $postsList = array(); 
        while ($post = $posts->fetch()) {
            $postsList[] = $post; // on créer un tableau regroupant les posts
        }
        return $postsList;
    }

    public function getPostsBy5($offset) {
        $db = dbConnect();
        $postsBy5 = $db->prepare('SELECT id, chapter_title, chapter_content, chapter_extract, DATE_FORMAT(creation_date, \'%d/%m/%Y à %Hh%imin%ss\') AS date FROM posts ORDER BY creation_date DESC LIMIT 5 OFFSET :idmax'); // OFFSET selon indice page
        $postsBy5->bindValue(':idmax', $offset, PDO::PARAM_INT);
        $postsBy5->execute();
        return $postsBy5;
    }

    public function getPost($postId) {
        $db = dbConnect();
        $getPostDetail = $db->prepare('SELECT id, chapter_title, chapter_content, DATE_FORMAT(creation_date, \'%d/%m/%%Hh%imin%ss\') AS creation_date_fr FROM posts WHERE id = ?');
        $getPostDetail->execute(array($postId));
        $postDetails = array(); 
        while ($postDetail = $getPostDetail->fetch()) {
            $postDetails[] = $postDetail; // on créer un tableau regroupant les donnees des contacts
        }
        return $postDetails;
    }
  
}
