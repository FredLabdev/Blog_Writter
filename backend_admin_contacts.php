<?php 
    session_start();
    $title = 'Membres';
    if ($_SESSION['group_id'] == 1) {
        $template = 'backend';
    } else {
        $template = 'frontend';
    }
    
 // ++++++++++++++++++++++ model +++++++++++++++++++++++++

            // Connexion à la dataBase

        function dbConnect() {
            try {
                $db = new PDO('mysql:host=localhost;dbname=forteroche', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            }
            catch(Exception $e) {
                die('Erreur : '.$e->getMessage());
            }
            return $db;
        }

            // Comptage des contacts

        function getContactsCount() {
            $db = dbConnect();
            $getContactsCount = $db->query('SELECT COUNT(*) AS nbre_contacts FROM contacts');
            $contactsCount = $getContactsCount->fetch();
            return $contactsCount;
        }


           // Récupération des contacts par classés par catégorie puis nom

        function getContactsByGroup() {
            $db = dbConnect();
            $getContactsByGroup = $db->query('SELECT c.name AS name_contact, c.first_name AS first_name_contact, g.grade AS grade_groupe FROM groups AS g INNER JOIN contacts AS c ON c.group_id = g.id ORDER BY group_id, name');        
            $contactsByGroup = array(); 
            while ($contactByGroup = $getContactsByGroup->fetch()) {
                $contactsByGroup[] = $contactByGroup; // on créer un tableau regroupant les contacts
            }
            return $contactsByGroup;
        }

           // Récupération des contacts par classés par nom

        function getContactsByName() {
            $db = dbConnect();
            $getContactsByName = $db->query('SELECT *, UPPER(name) AS name_maj, LOWER(first_name) AS first_name_min FROM contacts ORDER BY name'); 
            $contactsByName = array(); 
            while ($contactByName = $getContactsByName->fetch()) {
                $contactsByName[] = $contactByName; // on créer un tableau regroupant les contacts
            }
            return $contactsByName;
        }

           // Récupération des contacts par classés par nom

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

            // Suppression d'un contact

        function deleteContact($contactId) {
            $db = dbConnect();
            $deleteContact = $db->prepare('DELETE FROM contacts WHERE id = :idnum');
            $deleteContact->execute(array(
                'idnum' => $contactId
            )); 
        }

            // Interdiction à un contact de commenter

        function bloqContactComment($contactId) {
            $db = dbConnect();
            $bloqContactComment = $db->prepare('UPDATE contacts SET block_comment = 1 WHERE id = :idnum');
            $bloqContactComment->execute(array(
                'idnum' => $contactId
            )); 
        }

            // Modification du pseudo

        function modifPseudo($dataContact, $contactId) {
            $db = dbConnect();
            $modifPseudo = $db->prepare('UPDATE contacts SET pseudo = :nvpseudo WHERE id = :idnum');
            $modifPseudo->execute(array(
                'nvpseudo' => $dataContact,
                'idnum' => $contactId
            )); 
        }

            // Modification du mail

        function modifMail($dataContact, $contactId) {
            $db = dbConnect();
            $modifMail = $db->prepare('UPDATE contacts SET email = :nvemail WHERE id = :idnum');
            $modifMail->execute(array(
                'nvemail' => $dataContact,
                'idnum' => $contactId
            )); 
        }

            // Modification du mot de passe 

        function modifPassword($dataContact, $contactId) {
            $db = dbConnect();
            $modifPassword = $db->prepare('UPDATE contacts SET password = :newpassword WHERE id = :idnum');
            $modifPassword->execute(array(
                'newpassword' => $dataContact,
                'idnum' => $contactId
            )); 
        }


// ++++++++++++++++++++++ controller +++++++++++++++++++++++++

            // Modification du mot de passe 

      
             // Comptage des contacts
            $contactsCount = getContactsCount();
            // Liste des contacts par groupe puis nom
            $contactsByGroup = getContactsByGroup();
            // Liste des contacts par nom
            $contactsByName = getContactsByName();            
        

            // Récupération du détail d'un contact

            if (isset($_POST['contact']) AND isset($_POST['valider'])) {
                $contactDetail = getContactDetail($_POST['contact']);
            }

            // Modifications d'un contact

                // Suppression d'un contact
            if(isset($_POST['champ']) AND isset($_POST['delete'])) {
                deleteContact($_POST['contact-modif']);
                $commentModif = 'Le contact a bien été Supprimé !';
        
                // Interdiction à un contact de commenter    
            } else if(isset($_POST['bloquage']) AND isset($_POST['contact-modif']) AND isset($_POST['remplacer'])) {
                bloqContactComment($_POST['contact-modif']);
                $commentModif = 'Ce contact ne pourra plus poster de comments !';

                // Modification d'une donnee d'un contact  
            } else if(isset($_POST['champ']) AND isset($_POST['contact-modif']) AND isset($_POST['modif_champ']) AND       isset($_POST['remplacer'])) {
                
                // Modification du pseudo          
                if ($_POST['champ'] == 1) {
                    modifPseudo($_POST['modif_champ'], $_POST['contact-modif']);
                    $commentModif = 'La modification du pseudo du contact a bien été enrégistrée !';

                // Modification du mail          
                } else if ($_POST['champ'] == 2) {
                    modifMail($_POST['modif_champ'], $_POST['contact-modif']);
                    $commentModif = 'La modification de l\'email du contact a bien été enrégistrée !';
                    
                 // Modification du mot de passe    
                } else if ($_POST['champ'] == 3) {
                    modifPassword($_POST['modif_champ'], $_POST['contact-modif']);
                    $commentModif = 'La modification du mot de passe du contact a bien été enrégistrée !';
                }
            };
        

ob_start(); 
?>

<p>===========================================================</p>
<!-- Confirm connect -->

<h3>
    Bienvenue sur l' administration de vos contacts !
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

<!-- Liste des contacts par catégorie -->

<h3>
    Liste des contacts classés par catégorie :
</h3>
<p>
    <?php echo 'Nombre d\'abonnés à votre blog à ce jour: ' . $contactsCount['nbre_contacts'] ?>
</p>
<p>
    <?php 
        foreach($contactsByGroup as $contact) {
            echo $contact['grade_groupe'] . ' : ' . $contact['name_contact'] . ' ' . $contact['first_name_contact'] . '<br>';
        }
    ?>
</p>
<br />
<p>===========================================================</p>

<!-- Voir les champs d'un contact -->

<h3>
    Voir les champs d'un contact :
</h3>

<!-- Messages d'action sur les contacts -->

<p class="success">
    <?php
        if ($commentModif) {
            echo $commentModif;
        }
    ?>
</p>

<form method="post" action="backend_admin_contacts.php">
    <label>Sélectionnez un contact : </label><select name="contact">
        <?php
            foreach($contactsByName as $contact) {
               echo '<option value="' . $contact['id'] . '">' . $contact['name_maj'] . ' ' . $contact['first_name_min'] . '</option>';
            }
        ?>
    </select>
    <input type="submit" value="valider" name="valider" /><br>

    <?php
        foreach($contactDetail as $dataContact) { // Détail du contact sélectionné
            echo 'Date de création : ' . $dataContact['creation_date'] . '<br>';
            echo 'Nom : ' . $dataContact['name'] . '<br>';
            echo 'Prénom : ' . $dataContact['first_name'] . '<br>';  
            echo 'Pseudo : ' . $dataContact['pseudo'] . '<br>';  
            echo 'Mail : ' . $dataContact['email'] . '<br>';  
            echo 'Mot de passe : ' . $dataContact['password'] . '<br>';  
        }
    ?>
</form>

<br />
<p>===========================================================</p>

<!-- Modification du champs d'un contact -->

<h3>
    Modifier le champ d'un contact :
</h3>

<form method="post" action="backend_admin_contacts.php">
    <label>Sélectionnez un contact : </label><select name="contact-modif">
        <?php
           foreach($contactsByName as $contact) {
               echo '<option value="' . $contact['id'] . '">' . $contact['name_maj'] . ' ' . $contact['first_name_min'] . '</option>';
            }
        ?>
    </select><br> <!-- Sélection du champ à modifier -->
    <label>Supprimer tout le contact ?</label><input type="checkbox" name="delete" /><br>
    <label>Bloquer ses comments</label><input type="checkbox" name="bloquage" /><br>
    <label>Sélectionnez le champ à modifier : </label><select name="champ">
        <option value="1">Pseudo</option>
        <option value="2">e-mail</option>
        <option value="3">Mot de passe</option>
    </select><br>
    <label>Nouveau contenu du champ : </label><input type="text" name="modif_champ" />
    <input type="submit" value="Appliquer" name="remplacer" />
</form>

<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>
