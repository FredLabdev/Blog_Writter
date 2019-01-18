<?php session_start(); ?>
<?php $title = 'Mon_compte'; ?>
<?php $template = 'frontend'; ?>
<?php ob_start(); ?>

<?php   

    // connexion à la base de données   

    try { // connexion à la base de données 
        $db = new PDO('mysql:host=localhost;dbname=forteroche', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    catch(Exception $e) {
        die('Erreur : '.$e->getMessage());
    }

//*******************************************************************************//
//                Récupère-t-on un formulaire de  modification ?                                   
//*******************************************************************************//

    // si tout le contact à supprimer
    if(isset($_POST['delete'])) {
        echo
                    '<script>
                        function valid_confirm() {
                            if (confirm("Voulez-vous vraiment apporter ces modifications ?")) {
                                document.location.href = "frontend_admin_compte2.php?delete=all";
                                alert("Votre profil a bien été supprimé... Désolé de vous voir partir.");
                                return true;
                                
                            } else {
                                alert("Je me disais aussi...");
                                return false;
                            }
                        }
                        valid_confirm();
                    </script>';
                
    // Sinon si modification d'un champ seulement    
    } else if(isset($_POST['champ']) AND isset($_POST['modif_champ']) AND isset($_POST['modif_champ_confirm']) AND isset($_POST['remplacer'])) {
                
        $invalid = 0; // On défini une variable comptant les erreurs
                
        // Si modif du champ email
        if ($_POST['champ'] == 1) {
            // Adresse email: vérification format, 2 saisies idem, et pas déjà existante dans la db 
            $_POST['modif_champ'] = htmlspecialchars($_POST['modif_champ']);
            $_POST['modif_champ_confirm'] = htmlspecialchars($_POST['modif_champ_confirm']);
            if (preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $_POST['modif_champ'])) {
                $req2 = $db->prepare('SELECT COUNT(email) AS email_idem FROM contacts WHERE email = :email');
                $req2->execute(array('email' => htmlspecialchars($_POST['modif_champ'])));
                $data2 = $req2->fetch();
                $req2->closeCursor(); // Termine le traitement de la requête 2
                if ($data2['email_idem'] == 0) {
                    if ($_POST['modif_champ_confirm'] == $_POST['modif_champ']) {
                    } else {
                        $message_mails_diff = 'Attention vos 2 adresses mail sont différentes !';
                        $invalid += 1;
                    } 
                } else {
                    $message_mail_exist = 'Désolé cette adresse mail existe déjà !';
                    $invalid += 1;
                }   
            } else {
                $message_mail_invalid = 'Désolé le format de votre adresse mail n\'est pas valide !';
                $invalid += 1;
            }
                    
            // Fin, si tout ok (variable d'erreurs restée à 0): Insertion du nouveau mail dans la db    
            if ($invalid == 0) {  
                echo
                    '<script>
                        function valid_confirm() {
                            if (confirm("Voulez-vous vraiment apporter ces modifications ?")) {
                                document.location.href = "frontend_admin_compte2.php?nv_email=' . $_POST['modif_champ'] .'";
                                alert("Votre nouvelle adresse email a bien été prise en compte.");
                                return true;
                                
                            } else {
                                alert("Je me disais aussi...");
                                return false;
                            }
                        }
                        valid_confirm();
                    </script>';
            }
        
        // Si modif du champ mot de passe            
        } else if ($_POST['champ'] == 2) {
            // Mot de passe: vérification format, 2 saisies idem, et pas déjà existant dans la db 
            $_POST['modif_champ'] = htmlspecialchars($_POST['modif_champ']);
            $_POST['modif_champ_confirm'] = htmlspecialchars($_POST['modif_champ_confirm']);
            if (preg_match("#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}$#", $_POST['modif_champ'])) {
                if ($_POST['modif_champ_confirm'] == $_POST['modif_champ']) { 
                    $req3 = $db->query('SELECT password FROM contacts');
                    while ($data3 = $req3->fetch()) {
                        $isPasswordExist = password_verify($_POST['modif_champ'], $data3['password']);
                        if (!$isPasswordExist) {  
                            $pass_hache = password_hash($_POST['modif_champ'], PASSWORD_DEFAULT); // si ok => Hachage du mot de passe
                        } else {
                            $message_mp_exist = ' Désolé ce mot de passe existe déjà !';
                            $invalid += 1;
                        }
                    }
                    $req3->closeCursor(); // Termine le traitement de la requête 3
                } else {
                    $message_mp_diff = ' Désolé vos mots de passes ne sont pas identiques !';
                    $invalid += 1;
                }   
            } else {
                $message_mp_invalid =  ' Désolé votre mot de passe doit être composé de minimum 8 caractères'  . '<br>' . 'dont 1 Majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial !';
                $invalid += 1;
            }  
                    
            // Fin, si tout ok (variable d'erreurs restée à 0): Insertion du nouveau mot de passe dans la db    
            if ($invalid == 0) {
                echo
                    '<script>
                        function valid_confirm() {
                            if (confirm("Voulez-vous vraiment apporter ces modifications ?")) {
                                document.location.href = "frontend_admin_compte2.php?nv_mp=' . $pass_hache .'";
                                alert("Votre nouveau mot de passe a bien été pris en compte.");
                                return true;
                                
                            } else {
                                alert("Je me disais aussi...");
                                return false;
                            }
                        }
                        valid_confirm();
                    </script>';
            }
        }
    };

//*******************************************************************************//
//                  Détail du compte selon pseudo session                                   
//*******************************************************************************//

    if (isset($_SESSION['pseudo'])) {
        $req = $db->prepare('SELECT * FROM contacts WHERE pseudo = :monpseudo');
        $req->execute(array(
            'monpseudo' => $_SESSION['pseudo']     
        ));  
        $data = $req->fetch();
            $date = '<p>' . 'Date de création : ' . $data['creation_date'] . '<br>';
            $name = '<p>' . 'Nom : ' . $data['name'] . '<br>';
            $first_name = '<p>' . 'Prénom : ' . $data['first_name'] . '<br>';  
            $pseudo = '<p>' . 'Pseudo : ' . $data['pseudo'] . '<br>';  
            $mail = '<p>' . 'Mail : ' . $data['email'] . '<br>';  
        $req->closeCursor(); // Termine le traitement de la requête
    }

?>

<br />
<p>===========================================================</p>
<!-- Confirm connect -->

<h3>
    Bienvenue sur l' administration de votre compte !
</h3>

<p>
    Nous sommes le :
    <?php echo date('d/m/Y') . '<br>';
        	if(isset($_SESSION['pseudo'])) {
            	echo ' Bonjour ' . $_SESSION['first_name'];
        	} else {
            	echo 'Erreur nom ou prénom visiteur';
        	}
        ?>
</p>

<br />
<p>===========================================================</p>

<!-- Liste des posts -->

<h3>
    Les données de votre compte :
</h3>
<?php
        echo $date;
        echo $name;
        echo $first_name;  
        echo $pseudo;  
        echo $mail;  
        echo $mot_passe;  
    ?>

<br />
<p>===========================================================</p>

<!-- Modification d'un champ -->

<h3>
    Modifier votre adresse mail, votre mot de passe, ou vous décsincrire :
</h3>

<form novalidate method="post" action="frontend_admin_compte.php" id="form_modif">
    <p>
        <label>Sélectionnez le champ à modifier : </label>
        <select id="champ" name="champ">
            <option value="1">e-mail</option>
            <option value="2">Mot de passe</option>
        </select><br>
        <label for="modif_champ">Nouveau contenu du champ : </label>
        <input id="modif_champ" type="email" name="modif_champ" />
        <span class="error" id="error1" aria-live="polite">
            <?php
                    if($message_mail_invalid) {
                        echo $message_mail_invalid;
                    }
                    else if ($message_mail_exist) {
                        echo $message_mail_exist;
                    }
                    else if ($message_mails_diff) {
                        echo $message_mails_diff;
                    } 
                    else if($message_mp_invalid) {
                        echo $message_mp_invalid;
                    }
                    else if ($message_mp_diff) {
                        echo $message_mp_diff;
                    }
                    else if ($message_mp_existf) {
                        echo $message_mp_exist;
                    }
                ?>
        </span><br>
        <label for="modif_champ_confirm">Confirmez ce nouveau contenu : </label>
        <input id="modif_champ_confirm" type="email" name="modif_champ_confirm" />
        <span class="error" id="error2" aria-live="polite">
            <?php
                    if ($message_mails_diff) {
                        echo $message_mails_diff;
                    }
                ?>
        </span><br>
        <label>Vous désinscrire et supprimer votre profil ?</label>
        <input type="checkbox" name="delete" /><br>
        <input type="hidden" id="variableAPasser" value="<?php echo $invalid ?>" />
        <input id="bouton_envoi" type="submit" value="Appliquer" name="remplacer" />
    </p>
</form>

<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>
