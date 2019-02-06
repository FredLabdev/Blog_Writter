<?php 
  
namespace FredLab\tp4_blog_ecrivain\Model\Frontend;

require_once("model/frontend/Manager.php");

class CommentManager extends Manager { // se situe dans le namespace

//**************************************************************************************
//                        Model frontend CommentManager           
//**************************************************************************************

    public function getCommentsCount($postId) {
        $db = dbConnect();
        $commentsCount = $db->prepare('SELECT COUNT(post_id) AS nbre_comment FROM comments WHERE post_id = ?');
        $commentsCount->execute(array($postId));    
        return $commentsCount;
    }

    public function getComments($postId) {
        $db = dbConnect();
        $comments = $db->prepare('SELECT id, author, comment, DATE_FORMAT(comment_date, \'%d/%m/%Y à %Hh%imin%ss\')comment_date_fr FROM comments WHERE post_id = ? ORDER BY comment_date LIMIT 0, 5');
        $comments->execute(array($postId));    
        return $comments;
    }
    
}
