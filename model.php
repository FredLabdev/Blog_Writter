<?php 
    session_start();

//**************************************************************************************
//                          Connexion à la base de données                         
//**************************************************************************************

function dbConnect() {
    try {
        $db = new PDO('mysql:host=localhost;dbname=forteroche', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    catch(Exception $e) {
        die('Erreur : '.$e->getMessage());
    }
    return $db;
}
    
//**************************************************************************************
//                           Fonctions pour le login                    
//**************************************************************************************
    
function getMemberData($pseudo, $dbPassword) {
    $db = dbConnect();
    $req = $db->prepare('SELECT * FROM contacts WHERE pseudo = :pseudo AND password = :password');
    $req->execute(array(
        'pseudo' => $pseudo,
        'password' => $dbPassword
    ));
    $memberData = $req->fetch();
    return $memberData;
}

function getPasswordFromPseudo($pseudo) {
    $db = dbConnect();
    $req = $db->prepare('SELECT password FROM contacts WHERE pseudo = ?');
    $req->execute(array($pseudo));
    $passwordFromPseudo = $req->fetch();
    return $passwordFromPseudo;
}

function getPseudoIdem($pseudo) {
    $db = dbConnect();
    $getPseudoIdem = $db->prepare('SELECT COUNT(pseudo) AS pseudo_idem FROM contacts WHERE pseudo = :pseudo');
    $getPseudoIdem->execute(array('pseudo' => $pseudo));
    $pseudoIdem = $getPseudoIdem->fetch();
    return $pseudoIdem;
}

function getMailIdem($mail) {
    $db = dbConnect();
    $getMailIdem = $db->prepare('SELECT COUNT(email) AS email_idem FROM contacts WHERE email = :email');
    $getMailIdem->execute(array('email' => $mail));
    $mailIdem = $getMailIdem->fetch();
    return $mailIdem;
}

function getAllPassword() {
    $db = dbConnect();
    $getAllPassword = $db->query('SELECT password FROM contacts');
    return $getAllPassword;
}
  
function memberCreate($createName, $createFirstName, $createPseudo, $createMail, $createPassword) {
    $db = dbConnect();
    $memberCreate = $db->prepare('INSERT INTO contacts(name, first_name, pseudo, email, password, creation_date) VALUES(:name, :first_name, :pseudo, :email, :password, NOW())');
    $memberCreate->execute(array(
        'name' => $createName,
        'first_name' => $createFirstName,
        'pseudo' => $createPseudo,
        'email' => $createMail,
        'password' => password_hash($createPassword, PASSWORD_DEFAULT)
    ));
}

//**************************************************************************************
//                       Fonctions pour l'afichage des contacts                  
//**************************************************************************************

function getContactsCount() {
    $db = dbConnect();
    $getContactsCount = $db->query('SELECT COUNT(*) AS nbre_contacts FROM contacts');
    $contactsCount = $getContactsCount->fetch();
    return $contactsCount;
}

function getContactsByGroup() {
    $db = dbConnect();
    $getContactsByGroup = $db->query('SELECT c.name AS name_contact, c.first_name AS first_name_contact, g.grade AS grade_groupe FROM groups AS g INNER JOIN contacts AS c ON c.group_id = g.id ORDER BY group_id, name');        
    $contactsByGroup = array(); 
    while ($contactByGroup = $getContactsByGroup->fetch()) {
        $contactsByGroup[] = $contactByGroup; // on créer un tableau regroupant les contacts
    }
    return $contactsByGroup;
}

function getContactsByName() {
    $db = dbConnect();
    $getContactsByName = $db->query('SELECT id, UPPER(name) AS name_maj, LOWER(first_name) AS first_name_min FROM contacts ORDER BY name'); 
    $contactsByName = array(); 
    while ($contactByName = $getContactsByName->fetch()) {
        $contactsByName[] = $contactByName; // on créer un tableau regroupant les contacts
    }
    return $contactsByName;
}

function getContactDetail($contactId) {
    $db = dbConnect();
    $getContactDetail = $db->prepare('SELECT * FROM contacts WHERE id = ?');
    $getContactDetail->execute(array($contactId));          
    $contactDetail = array(); 
    while ($dataContact = $getContactDetail->fetch()) {
        $contactDetail[] = $dataContact; // on créer un tableau regroupant les donnees des contacts
    }
    return $contactDetail;
}

function deleteContact($contactId) {
    $db = dbConnect();
    $deleteContact = $db->prepare('DELETE FROM contacts WHERE id = :idnum');
    $deleteContact->execute(array(
        'idnum' => $contactId
    )); 
}

function bloqContactComment($contactId, $blockId) {
    $db = dbConnect();
    $bloqContactComment = $db->prepare('UPDATE contacts SET block_comment = :blockId WHERE id = :idnum');
    $bloqContactComment->execute(array(
        'blockId' => $blockId,
        'idnum' => $contactId
    )); 
}

function modifPseudo($contactId, $dataContact) {
    $db = dbConnect();
    $modifPseudo = $db->prepare('UPDATE contacts SET pseudo = :nvpseudo WHERE id = :idnum');
    $modifPseudo->execute(array(
        'nvpseudo' => $dataContact,
        'idnum' => $contactId
    )); 
}

function modifMail($contactId, $dataContact) {
    $db = dbConnect();
    $modifMail = $db->prepare('UPDATE contacts SET email = :nvemail WHERE id = :idnum');
    $modifMail->execute(array(
        'nvemail' => $dataContact,
        'idnum' => $contactId
    )); 
}

function modifPassword($contactId, $dataContact) {
    $db = dbConnect();
    $modifPassword = $db->prepare('UPDATE contacts SET password = :newpassword WHERE id = :idnum');
    $modifPassword->execute(array(
        'newpassword' => password_hash($dataContact, PASSWORD_DEFAULT),
        'idnum' => $contactId
    )); 
}




//**************************************************************************************
//                Fonctions pour l'afichage d'un billet et ses commentaires                  
//**************************************************************************************

function getPostsCount() {
    $db = dbConnect();
    $req = $db->query('SELECT COUNT(id) AS nbre_posts FROM posts');
    $postsCount = $req->fetch();
    $req->closeCursor();
    return $postsCount;
}

function getPosts() {
    $db = dbConnect();
    $posts = $db->query('SELECT chapter_title, creation_date FROM posts ORDER BY creation_date DESC');
    return $posts;
}

function getPostsBy5($offset) {
    $db = dbConnect();
    $postsBy5 = $db->prepare('SELECT id, chapter_title, chapter_content, chapter_extract, DATE_FORMAT(creation_date, \'%d/%m/%Y à %Hh%imin%ss\') AS date FROM posts ORDER BY creation_date DESC LIMIT 5 OFFSET :idmax'); // OFFSET selon indice page
    $postsBy5->bindValue(':idmax', $offset, PDO::PARAM_INT);
    $postsBy5->execute();
    return $postsBy5;
}

function getCommentsCount($postId) {
    $db = dbConnect();
    $commentsCount = $db->prepare('SELECT COUNT(post_id) AS nbre_comment FROM comments WHERE post_id = ?');
    $commentsCount->execute(array($postId));    
    return $commentsCount;
}

function getPost($postId) {
    $db = dbConnect();
    $req = $db->prepare('SELECT id, chapter_title, chapter_content, DATE_FORMAT(creation_date, \'%d/%m/%%Hh%imin%ss\') AS creation_date_fr FROM posts WHERE id = ?');
    $req->execute(array($postId));
    $post = $req->fetch();
    $req->closeCursor();
    return $post;
}

function addPost($postTitle, $postContent, $postExtract, $postBefore) {            
    $db = dbConnect();
    $req = $db->prepare('INSERT INTO posts(creation_date, chapter_title, chapter_content, chapter_extract) VALUES(NOW(), :titre, :contenu, :extract)');
    $req->execute(array(
        'titre' => $postTitle,
        'contenu' => $postContent,
        'extract' => $postExtract
    ));
    $req->closeCursor();
}
 
function postModifTitle($postId, $newPostTitle) {
    $db = dbConnect();
    $modifTitle = $db->prepare('UPDATE posts SET chapter_title = :nvtitre WHERE id = :idnum');
    $modifTitle->execute(array(
        'nvtitre' => $newPostTitle,
        'idnum' => $postId
    )); 
}

function postModifContent($postId, $newPostContent) {
    $db = dbConnect();
    $modifContent = $db->prepare('UPDATE posts SET chapter_content = :nvcontenu WHERE id = :idnum');
    $modifContent->execute(array(
        'nvcontenu' => $newPostContent,
        'idnum' => $postId
    )); 
}

function deletePost($postId) {  
    $db = dbConnect();
    $req = $db->prepare('DELETE FROM posts WHERE id = :idnum');
    $req->execute(array(
        'idnum' => $postId
    ));  
    $req->closeCursor();
}
    
function getComments($postId) {
    $db = dbConnect();
    $comments = $db->prepare('SELECT id, author, comment, DATE_FORMAT(comment_date, \'%d/%m/%Y à %Hh%imin%ss\')comment_date_fr FROM comments WHERE post_id = ? ORDER BY comment_date LIMIT 0, 5');
    $comments->execute(array($postId));    
    return $comments;
}

function permitComments($member) {
    $db = dbConnect();
    $req = $db->prepare('SELECT block_comment FROM contacts WHERE pseudo = ?');
    $req->execute(array($member));
    $allowComment = $req->fetch();
    $req->closeCursor();
    return $allowComment;
}

function addComment($postId, $author, $comment) {            
    $db = dbConnect();
    $req = $db->prepare('INSERT INTO comments(post_id, author, comment, comment_date) VALUES(:post_id, :author, :comment, NOW())');
    $req->execute(array(
        'post_id' => $postId,
        'author' => $author,
        'comment' => $comment
    ));
    $req->closeCursor();
}
    
function deleteComment($commentId) {  
    $db = dbConnect();
    $req = $db->prepare('DELETE FROM comments WHERE id = :idnum');
    $req->execute(array(
        'idnum' => $commentId
    ));  
    $req->closeCursor();
}
