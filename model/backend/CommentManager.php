<?php 
  
namespace FredLab\tp4_blog_ecrivain\Model\Backend;

require_once("model/backend/Manager.php");

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
    
}

