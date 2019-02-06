<?php 
  
namespace FredLab\tp4_blog_ecrivain\Model;

require_once("model/Manager.php");

class CommentManager extends Manager { // se situe dans le namespace

//**************************************************************************************
//                        Model backend CommentManager           
//**************************************************************************************

    public function permitComments($member) {
        $db = dbConnect();
        $req = $db->prepare('SELECT block_comment FROM contacts WHERE pseudo = ?');
        $req->execute(array($member));
        $allowComment = $req->fetch();
        $req->closeCursor();
        return $allowComment;
    }

    public function addComment($postId, $author, $comment) {            
        $db = dbConnect();
        $req = $db->prepare('INSERT INTO comments(post_id, author, comment, comment_date) VALUES(:post_id, :author, :comment, NOW())');
        $req->execute(array(
            'post_id' => $postId,
            'author' => $author,
            'comment' => $comment
        ));
        $req->closeCursor();
    }
    
    public function deleteComment($commentId) {  
        $db = dbConnect();
        $req = $db->prepare('DELETE FROM comments WHERE id = :idnum');
        $req->execute(array(
            'idnum' => $commentId
        ));  
        $req->closeCursor();
    }
    
    public function deleteComments($postId) {  
        $db = dbConnect();
        $req = $db->prepare('DELETE FROM comments WHERE post_id = :postidnum');
        $req->execute(array(
            'postidnum' => $postId
        ));  
        $req->closeCursor();
    }

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
