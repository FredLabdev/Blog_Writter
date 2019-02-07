<?php 
  
namespace FredLab\tp4_blog_ecrivain\Model;

require_once("model/Manager.php");

class LoginManager extends Manager { // se situe dans le namespace

//**************************************************************************************
//                        Model frontend loginManager           
//**************************************************************************************

    public function getMemberData($pseudo, $dbPassword) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT * FROM contacts WHERE pseudo = :pseudo AND password = :password');
        $req->execute(array(
            'pseudo' => $pseudo,
            'password' => $dbPassword
        ));
        $memberData = $req->fetch();
        return $memberData;
    }

    public function getPasswordFromPseudo($pseudo) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT password FROM contacts WHERE pseudo = ?');
        $req->execute(array($pseudo));
        $passwordFromPseudo = $req->fetch();
        return $passwordFromPseudo;
    }

    public function getPseudoIdem($pseudo) {
        $db = $this->dbConnect();
        $getPseudoIdem = $db->prepare('SELECT COUNT(pseudo) AS pseudo_idem FROM contacts WHERE pseudo = :pseudo');
        $getPseudoIdem->execute(array('pseudo' => $pseudo));
        $pseudoIdem = $getPseudoIdem->fetch();
        return $pseudoIdem;
    }

    public function getMailIdem($mail) {
        $db = $this->dbConnect();
        $getMailIdem = $db->prepare('SELECT COUNT(email) AS email_idem FROM contacts WHERE email = :email');
        $getMailIdem->execute(array('email' => $mail));
        $mailIdem = $getMailIdem->fetch();
        return $mailIdem;
    }

    public function getAllPassword() {
        $db = $this->dbConnect();
        $getAllPassword = $db->query('SELECT password FROM contacts');
        return $getAllPassword;
    }

    public function memberCreate($createName, $createFirstName, $createPseudo, $createMail, $createPassword) {
        $db = $this->dbConnect();
        $memberCreate = $db->prepare('INSERT INTO contacts(name, first_name, pseudo, email, password, creation_date) VALUES(:name, :first_name, :pseudo, :email, :password, NOW())');
        $memberCreate->execute(array(
            'name' => $createName,
            'first_name' => $createFirstName,
            'pseudo' => $createPseudo,
            'email' => $createMail,
            'password' => password_hash($createPassword, PASSWORD_DEFAULT)
        ));
    }

}
