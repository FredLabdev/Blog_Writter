<?php 
  
namespace FredLab\tp4_blog_ecrivain\Model;

require_once("model/Manager.php");

class LoginManager extends Manager { // se situe dans le namespace

//**************************************************************************************
//                                  Model loginManager           
//**************************************************************************************

    public function getPseudoIdem($pseudo) {
        $db = $this->dbConnect();
        $getPseudoIdem = $db->prepare('SELECT COUNT(pseudo) AS pseudo_idem FROM members WHERE pseudo = :pseudo');
        $getPseudoIdem->execute(array('pseudo' => $pseudo));
        $pseudoIdem = $getPseudoIdem->fetch();
        return $pseudoIdem;
    }

    public function getMailIdem($mail) {
        $db = $this->dbConnect();
        $getMailIdem = $db->prepare('SELECT COUNT(email) AS email_idem FROM members WHERE email = :email');
        $getMailIdem->execute(array('email' => $mail));
        $mailIdem = $getMailIdem->fetch();
        return $mailIdem;
    }

    public function getPasswordFromPseudo($pseudo) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT password FROM members WHERE pseudo = ?');
        $req->execute(array($pseudo));
        $passwordFromPseudo = $req->fetch();
        return $passwordFromPseudo;
    }
    
    public function getAllPassword() {
        $db = $this->dbConnect();
        $getAllPassword = $db->query('SELECT password FROM members');
        return $getAllPassword;
    }

    public function getMemberData($pseudo, $dbPassword) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT * FROM members WHERE pseudo = :pseudo AND password = :password');
        $req->execute(array(
            'pseudo' => $pseudo,
            'password' => $dbPassword
        ));
        $memberData = $req->fetch();
        return $memberData;
    }

    public function createMember($createName, $createFirstName, $createPseudo, $createMail, $createPassword) {
        $db = $this->dbConnect();
        $createMember = $db->prepare('INSERT INTO members(name, first_name, pseudo, email, password, creation_date) VALUES(:name, :first_name, :pseudo, :email, :password, NOW())');
        $createMember->execute(array(
            'name' => $createName,
            'first_name' => $createFirstName,
            'pseudo' => $createPseudo,
            'email' => $createMail,
            'password' => password_hash($createPassword, PASSWORD_DEFAULT)
        ));
    }

}
