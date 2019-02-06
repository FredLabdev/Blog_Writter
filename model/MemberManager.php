<?php 
  
namespace FredLab\tp4_blog_ecrivain\Model;

require_once("model/Manager.php");

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
    
//**************************************************************************************
//                        Model frontend MemberManager           
//**************************************************************************************

    public function getContactsCount() {
        $db = dbConnect();
        $getContactsCount = $db->query('SELECT COUNT(*) AS nbre_contacts FROM contacts');
        $contactsCount = $getContactsCount->fetch();
        return $contactsCount;
    }

    public function getContactsByGroup() {
        $db = dbConnect();
        $getContactsByGroup = $db->query('SELECT c.name AS name_contact, c.first_name AS first_name_contact, g.grade AS grade_groupe FROM groups AS g INNER JOIN contacts AS c ON c.group_id = g.id ORDER BY group_id, name');        
        $contactsByGroup = array(); 
        while ($contactByGroup = $getContactsByGroup->fetch()) {
            $contactsByGroup[] = $contactByGroup; // on créer un tableau regroupant les contacts
        }
        return $contactsByGroup;
    }

    public function getContactsByName() {
        $db = dbConnect();
        $getContactsByName = $db->query('SELECT id, UPPER(name) AS name_maj, LOWER(first_name) AS first_name_min FROM contacts ORDER BY name'); 
        $contactsByName = array(); 
        while ($contactByName = $getContactsByName->fetch()) {
            $contactsByName[] = $contactByName; // on créer un tableau regroupant les contacts
        }
        return $contactsByName;
    }

    public function getContactDetail($contactId) {
        $db = dbConnect();
        $getContactDetail = $db->prepare('SELECT * FROM contacts WHERE id = ?');
        $getContactDetail->execute(array($contactId));          
        $contactDetails = array(); 
        while ($contactDetail = $getContactDetail->fetch()) {
            $contactDetails[] = $contactDetail; // on créer un tableau regroupant les donnees des contacts
        }
        return $contactDetails;
    }

    public function deleteContact($contactId) {
        $db = dbConnect();
        $deleteContact = $db->prepare('DELETE FROM contacts WHERE id = :idnum');
        $deleteContact->execute(array(
            'idnum' => $contactId
        )); 
    }

    public function modifPseudo($contactId, $dataContact) {
        $db = dbConnect();
        $modifPseudo = $db->prepare('UPDATE contacts SET pseudo = :nvpseudo WHERE id = :idnum');
        $modifPseudo->execute(array(
            'nvpseudo' => $dataContact,
            'idnum' => $contactId
        )); 
    }

    public function modifMail($contactId, $dataContact) {
        $db = dbConnect();
        $modifMail = $db->prepare('UPDATE contacts SET email = :nvemail WHERE id = :idnum');
        $modifMail->execute(array(
            'nvemail' => $dataContact,
            'idnum' => $contactId
        )); 
    }

    public function modifPassword($contactId, $dataContact) {
        $db = dbConnect();
        $modifPassword = $db->prepare('UPDATE contacts SET password = :newpassword WHERE id = :idnum');
        $modifPassword->execute(array(
            'newpassword' => password_hash($dataContact, PASSWORD_DEFAULT),
            'idnum' => $contactId
        )); 
    }
    
}
