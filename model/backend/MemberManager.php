<?php 
  
namespace FredLab\tp4_blog_ecrivain\Model\Backend;

require_once("model/backend/Manager.php");

class MemberManager extends Manager { // se situe dans le namespace

//**************************************************************************************
//                        Model backend MemberManager           
//**************************************************************************************

    public function bloqContactComment($contactId, $blockId) {
        $db = dbConnect();
        $bloqContactComment = $db->prepare('UPDATE contacts SET block_comment = :blockId WHERE id = :idnum');
        $bloqContactComment->execute(array(
            'blockId' => $blockId,
            'idnum' => $contactId
        )); 
    }
    
}