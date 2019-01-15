<?php   

//**************************************************************************************
//                          Connexion à la base de données                         
//**************************************************************************************

function connectDataBase() {
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=forteroche', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    catch(Exception $e) {
        die('Erreur : '.$e->getMessage());
    }
    return $bdd;
}
    
//**************************************************************************************
//                          Fonction de contrôle du login                       
//**************************************************************************************

function loginControl($bdd) {
    $login_error = 0; // On défini une variable comptant les erreurs
    $req = $bdd->prepare('SELECT * FROM contacts WHERE pseudo = ?');
    $req->execute(array(htmlspecialchars($_POST['pseudo_connect']))); 
    if ($donnees = $req->fetch()) {
        // On vérifie que le mot de passe saisi est égal au mot de passe crypté de la bdd,
        $isPasswordCorrect = password_verify(htmlspecialchars($_POST['mot_passe_connect']), $donnees['mot_passe']);
        if ($isPasswordCorrect) {
        } else {
            $login_error += 1;
        }
    } else {
        $login_error += 1;
    }
    $req->closeCursor();

    if ($login_error == 0) { // si aucune erreur
        // si l'option a été cochée, on stocke des cookies pour les prochaines connexions
        if (isset($_POST['login_auto'])) {
            setcookie('pseudo', $donnees['pseudo'], time() + 365*24*3600, null, null, false, true);
            setcookie('mot_passe', $donnees['mot_passe'], time() + 365*24*3600, null, null, false, true);
        }
        // On passe ses données en argument à la fonction de démarrage de session
        sessionStart($donnees);
    } else {
        $login_error = '<p class="alert">' . 'pseudo et/ou mot de passe erroné(s)' . '</p>';
        return $login_error;
    }
}

//**************************************************************************************
//             Fonction de contrôle de cookies de conexion automatique                       
//**************************************************************************************

function cookieControl($bdd, $mot_passe) {
    $req = $bdd->prepare('SELECT * FROM contacts WHERE mot_passe = ?');
    $req->execute(array($mot_passe));
    if ($donnees = $req->fetch()) { 
        sessionStart($donnees);
    } $req->closeCursor(); // Termine le traitement de la requête
}
        
//**************************************************************************************
//                       Fonction d'ouverture de session                  
//**************************************************************************************

function sessionStart($donnees) {
    // on démarre la session, et on stocke les paramètres utiles aux autres pages
    session_start();
    $_SESSION['nom'] = $donnees['nom'];
    $_SESSION['prenom'] = $donnees['prenom'];
    $_SESSION['pseudo'] = $donnees['pseudo'];
    $_SESSION['mot_passe'] = $donnees['mot_passe'];     
    // Si son pseudo est "admin", on le dirige vers l'accueil backend,
    if ((htmlspecialchars($donnees['pseudo']) == 'admin') AND ($donnees['id_groupe'] == 1)) {
        header('Location: backend_accueil.php');
    }
    // sinon on le dirige vers l'accueil frontend.   
    else if ($donnees['id_groupe'] !== 1) {  // sinon => on dirigera vers l'interface client front-end accueil
        header('Location: frontend_accueil.php');
    }  
} 

//**************************************************************************************
//            Fonction de vérification d'un formulaire de création de compte                     
//**************************************************************************************

function newMember($bdd) {
    if(isset($_POST['nom']) AND isset($_POST['prenom']) AND isset($_POST['pseudo']) AND isset($_POST['email']) AND isset($_POST['mot_passe']) AND isset($_POST['mot_passe_confirm'])) {
    
        $account_error = ''; // On défini une variable regroupant les erreurs
    
        // Pseudo: vérification pas déjà existant dans la bdd 
    
        $_POST['pseudo'] = htmlspecialchars($_POST['pseudo']);
        $req1 = $bdd->prepare('SELECT COUNT(pseudo) AS pseudo_idem FROM contacts WHERE pseudo = :pseudo');
        $req1->execute(array('pseudo' => htmlspecialchars($_POST['pseudo'])));
        $donnees1 = $req1->fetch();
        $req1->closeCursor(); // Termine le traitement de la requête 1
        if ($donnees1['pseudo_idem'] == 0) {
        } else {
            $account_error .= '<p class="alert">' . 'Désolé, ce pseudo existe déjà !' . '</p>';
        }
            
        // Adresse email: vérification format, 2 saisies idem, et pas déjà existante dans la bdd 
            
        $_POST['email'] = htmlspecialchars($_POST['email']);
        if (preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $_POST['email'])) {
            $req2 = $bdd->prepare('SELECT COUNT(email) AS email_idem FROM contacts WHERE email = :email');
            $req2->execute(array('email' => htmlspecialchars($_POST['email'])));
            $donnees2 = $req2->fetch();
            $req2->closeCursor(); // Termine le traitement de la requête 2
            if ($donnees2['email_idem'] == 0) {
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
            
        // Mot de passe: vérification format, 2 saisies idem, et pas déjà existant dans la bdd 
            
        $_POST['mot_passe'] = htmlspecialchars($_POST['mot_passe']);
        $_POST['mot_passe_confirm'] = htmlspecialchars($_POST['mot_passe_confirm']);
        if (preg_match("#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}$#", $_POST['mot_passe'])) {
            if ($_POST['mot_passe_confirm'] == $_POST['mot_passe']) { 
                $reponse3 = $bdd->query('SELECT mot_passe FROM contacts');
                while ($donnees3 = $reponse3->fetch()) {
                    $isPasswordExist = password_verify($_POST['mot_passe'], $donnees3['mot_passe']);
                    if (!$isPasswordExist) {   
                    } else {
                        $account_error .= '<p class="alert">' . 'Désolé ce mot de passe existe déjà !' . '</p>';
                    }
                }
                $reponse3->closeCursor(); // Termine le traitement de la requête 3
            } else {
                $account_error .= '<p class="alert">' . 'Attention vos mots de passes ne sont pas identiques !' . '</p>';
            }   
        } else {
            $account_error .= '<p class="alert">' . 'Désolé votre mot de passe doit être composé de minimum 8 caractères'  . '<br>' . 'dont 1 Majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial !' . '</p>';
        }             
            
        // Fin, si tout ok (variable d'erreurs restée à 0),
            
        if ($account_error == '') {
            memberCreate($bdd); //  on appelle la fonction de création d'un nouveau membre    
            $account_error = '<p class="success">' . 'Bonjour ' . $_POST['prenom'] . ' ' . $_POST['nom'] . ', votre compte est bien créé !' . '<br>' . 'Accédez au site en vous connectant ci-dessus.' . '</p>';
            return $account_error;
        } else {
            return $account_error;
        }
    }
}

//**************************************************************************************
//                       Fonction de création d'un nouveau membre                  
//**************************************************************************************

function memberCreate($bdd) {
    $req = $bdd->prepare('INSERT INTO contacts(nom, prenom, pseudo, email, mot_passe, date_creation) VALUES(:nom, :prenom, :pseudo, :email, :mot_passe, NOW())');
    $req->execute(array(
        'nom' => htmlspecialchars($_POST['nom']),
        'prenom' => htmlspecialchars($_POST['prenom']),
        'pseudo' => htmlspecialchars($_POST['pseudo']),
        'email' => htmlspecialchars($_POST['email']),
        'mot_passe' => password_hash($_POST['mot_passe'], PASSWORD_DEFAULT)
    ));
    $req->closeCursor(); // Termine le traitement de la requête
} 

?>
