<?php   

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
//                   Fonction de Login membre automatique via cookies                       
//**************************************************************************************

function memberConnect($pseudo, $dbPassword) {
    $db = dbConnect();
    $req = $db->prepare('SELECT * FROM contacts WHERE pseudo = :pseudo AND password = :password');
    $req->execute(array(
        'pseudo' => $pseudo,
        'password' => $dbPassword
    ));
    $memberData = $req->fetch();
    return $memberData;
}
       
//**************************************************************************************
//                   Fonction de Login membre automatique via cookies                       
//**************************************************************************************

function pseudoControl($pseudo) {
    $db = dbConnect();
    $req = $db->prepare('SELECT password FROM contacts WHERE pseudo = ?');
    $req->execute(array($pseudo));
    $pseudoValid = $req->fetch();
    return $pseudoValid;
}
        
//**************************************************************************************
//            Fonction de vérification d'un formulaire de création de compte                     
//**************************************************************************************

function newMember() {
    $db = dbConnect();
    if(isset($_POST['name']) AND isset($_POST['first_name']) AND isset($_POST['pseudo']) AND isset($_POST['email']) AND isset($_POST['password']) AND isset($_POST['password_confirm'])) {
    
        $account_error = ''; // On défini une variable regroupant les erreurs
    
        // Pseudo: vérification pas déjà existant dans la db 
    
        $_POST['pseudo'] = htmlspecialchars($_POST['pseudo']);
        $req1 = $db->prepare('SELECT COUNT(pseudo) AS pseudo_idem FROM contacts WHERE pseudo = :pseudo');
        $req1->execute(array('pseudo' => htmlspecialchars($_POST['pseudo'])));
        $data1 = $req1->fetch();
        $req1->closeCursor(); // Termine le traitement de la requête 1
        if ($data1['pseudo_idem'] == 0) {
        } else {
            $account_error .= '<p class="alert">' . 'Désolé, ce pseudo existe déjà !' . '</p>';
        }
            
        // Adresse email: vérification format, 2 saisies idem, et pas déjà existante dans la db 
            
        $_POST['email'] = htmlspecialchars($_POST['email']);
        if (preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $_POST['email'])) {
            $req2 = $db->prepare('SELECT COUNT(email) AS email_idem FROM contacts WHERE email = :email');
            $req2->execute(array('email' => htmlspecialchars($_POST['email'])));
            $data2 = $req2->fetch();
            $req2->closeCursor(); // Termine le traitement de la requête 2
            if ($data2['email_idem'] == 0) {
                if ($_POST['email_confirm'] == $_POST['email']) {
                } else {
                    $account_error .= '<p class="alert">' . 'Attention vos 2 adresses mail sont différentes !' . '</p>';
                } 
            } else {
                $account_error .= '<p class="alert">' . 'Désolé cette adresse mail existe déjà !' . '</p>';
            }   
        } else {
            $account_error .= '<p class="alert">' . 'Désolé le format d\'adresse mail n\'est pas valide.' . '</p>';
        }
            
        // Mot de passe: vérification format, 2 saisies idem, et pas déjà existant dans la db 
            
        $_POST['password'] = htmlspecialchars($_POST['password']);
        $_POST['password_confirm'] = htmlspecialchars($_POST['password_confirm']);
        if (preg_match("#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}$#", $_POST['password'])) {
            if ($_POST['password_confirm'] == $_POST['password']) { 
                $req3 = $db->query('SELECT password FROM contacts');
                while ($data3 = $req3->fetch()) {
                    $isPasswordExist = password_verify($_POST['password'], $data3['password']);
                    if (!$isPasswordExist) {   
                    } else {
                        $account_error .= '<p class="alert">' . 'Désolé ce mot de passe existe déjà !' . '</p>';
                    }
                }
                $req3->closeCursor(); // Termine le traitement de la requête 3
            } else {
                $account_error .= '<p class="alert">' . 'Attention vos mots de passes ne sont pas identiques !' . '</p>';
            }   
        } else {
            $account_error .= '<p class="alert">' . 'Désolé votre mot de passe doit être composé de minimum 8 caractères'  . '<br>' . 'dont 1 Majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial !' . '</p>';
        }             
            
        // Fin, si tout ok (variable d'erreurs restée à 0),
            
        if ($account_error == '') {
            memberCreate($db); //  on appelle la fonction de création d'un nouveau membre    
            $account_error = '<p class="success">' . 'Bonjour ' . $_POST['first_name'] . ' ' . $_POST['name'] . ', votre compte est bien créé !' . '<br>' . 'Accédez au site en vous connectant ci-dessus.' . '</p>';
            return $account_error;
        } else {
            return $account_error;
        }
    }
}

//**************************************************************************************
//                       Fonction de création d'un nouveau membre                  
//**************************************************************************************

function memberCreate() {
    $db = dbConnect();
    $req = $db->prepare('INSERT INTO contacts(name, first_name, pseudo, email, password, creation_date) VALUES(:name, :first_name, :pseudo, :email, :password, NOW())');
    $req->execute(array(
        'name' => htmlspecialchars($_POST['name']),
        'first_name' => htmlspecialchars($_POST['first_name']),
        'pseudo' => htmlspecialchars($_POST['pseudo']),
        'email' => htmlspecialchars($_POST['email']),
        'password' => password_hash($_POST['password'], PASSWORD_DEFAULT)
    ));
    $req->closeCursor(); // Termine le traitement de la requête
}
