<?php 
  
namespace FredLab\tp4_blog_ecrivain\Model;

require_once("model/Manager.php");

class MemberManager extends Manager { // se situe dans le namespace

//**************************************************************************************
//                              Model MemberManager           
//**************************************************************************************
 
    public function getMembersCount() {
        $db = $this->dbConnect();
        $getMembersCount = $db->query('SELECT COUNT(*) AS nbre_members FROM members WHERE group_id = 2 OR group_id = 3');
        $membersCount = $getMembersCount->fetch();
        return $membersCount;
    }

    public function getMembersByGroup() {
        $db = $this->dbConnect();
        $getMembersByGroup = $db->query('SELECT c.name AS name_member, c.first_name AS first_name_member, g.grade AS grade_groupe FROM groups AS g INNER JOIN members AS c ON c.group_id = g.id WHERE group_id = 1 OR group_id = 2 ORDER BY group_id, name');        
        $membersByGroup = array(); 
        while ($memberByGroup = $getMembersByGroup->fetch()) {
            $membersByGroup[] = $memberByGroup; // on créer un tableau regroupant les members
        }
        return $membersByGroup;
    }

    public function getMembersByName() {
        $db = $this->dbConnect();
        $getMembersByName = $db->query('SELECT id, UPPER(name) AS name_maj, LOWER(first_name) AS first_name_min FROM members WHERE group_id = 2 OR group_id = 3 ORDER BY name'); 
        $membersByName = array(); 
        while ($memberByName = $getMembersByName->fetch()) {
            $membersByName[] = $memberByName; // on créer un tableau regroupant les members
        }
        return $membersByName;
    }

    public function getMemberDetail($memberId) {
        $db = $this->dbConnect();
        $getMemberDetail = $db->prepare('SELECT *, DATE_FORMAT(creation_date, \'%d/%m/%Y\')creation_date_fr FROM members WHERE id = ?');
        $getMemberDetail->execute(array($memberId));          
        $memberDetails = array(); 
        while ($memberDetail = $getMemberDetail->fetch()) {
            $memberDetails[] = $memberDetail; // on créer un tableau regroupant les donnees des members
        }
        return $memberDetails;
    }

    public function changeMemberMail($memberId, $dataMember) {
        $db = $this->dbConnect();
        $changeMemberMail = $db->prepare('UPDATE members SET email = :nvemail WHERE id = :idnum');
        $changeMemberMail->execute(array(
            'nvemail' => $dataMember,
            'idnum' => $memberId
        )); 
    }

    public function changeMemberPassword($memberId, $dataMember) {
        $db = $this->dbConnect();
        $changeMemberPassword = $db->prepare('UPDATE members SET password = :newpassword WHERE id = :idnum');
        $changeMemberPassword->execute(array(
            'newpassword' => password_hash($dataMember, PASSWORD_DEFAULT),
            'idnum' => $memberId
        )); 
    }

    public function changeMemberNoComment($memberId, $blockId) {
        $db = $this->dbConnect();
        $changeMemberNoComment = $db->prepare('UPDATE members SET block_comment = :blockId WHERE id = :idnum');
        $changeMemberNoComment->execute(array(
            'blockId' => $blockId,
            'idnum' => $memberId
        )); 
    }

    public function changeMemberGroup($memberId, $moderatorId) {
        $db = $this->dbConnect();
        $changeMemberGroup = $db->prepare('UPDATE members SET group_id = :groupId WHERE id = :idnum');
        $changeMemberGroup->execute(array(
            'groupId' => $moderatorId,
            'idnum' => $memberId
        )); 
    }
    
    public function deleteMember($memberId) {
        $db = $this->dbConnect();
        $deleteMember = $db->prepare('DELETE FROM members WHERE id = :idnum');
        $deleteMember->execute(array(
            'idnum' => $memberId
        )); 
    }
    
}
