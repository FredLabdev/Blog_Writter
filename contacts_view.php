<?php 
    session_start();
    $title = 'Membres';
    if ($_SESSION['group_id'] == 1) {
        $template = 'backend';
    } else {
        $template = 'frontend';
    }       
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
    Editer un contact :
</h3>

<form method="post" action="index.php?action=contactDetail">
    <label>Sélectionnez un contact : </label><select name="contact">
        <option value=""></option>
        <?php
            foreach($contactsByName as $contact) {
               echo '<option value="' . $contact['id'] . '">' . $contact['name_maj'] . ' ' . $contact['first_name_min'] . '</option>';
            }
        ?>
    </select>
    <input type="submit" value="valider" name="valider" /><br>
    <p class="success">
        <?php
            if ($message) {
                echo $message;
            }
        ?>
    </p>
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

<h3>
    Modifier un contact :
</h3>

<form method="post" action="index.php?action=contactModif">
    <label>Sélectionnez un contact : </label>
    <select name="contact-modif">
        <option value=""></option>
        <?php
           foreach($contactsByName as $contact) {
               echo '<option value="' . $contact['id'] . '">' . $contact['name_maj'] . ' ' . $contact['first_name_min'] . '</option>';
            }
        ?>
    </select><br> <!-- Sélection du champ à modifier -->
    <label>Bloquer ses comments</label><input type="checkbox" name="bloquage" /><br>
    <label>Sélectionnez le champ à modifier : </label>
    <select name="champ">
        <option value=""></option>
        <option value="1">Pseudo</option>
        <option value="2">e-mail</option>
        <option value="3">Mot de passe</option>
    </select><br>
    <label>Nouveau contenu du champ : </label><input type="text" name="modif_champ" />
    <input type="submit" value="Appliquer" name="remplacer" />
</form>

<br />
<p>===========================================================</p>


<h3>
    Supprimer un contact :
</h3>

<form name="delete">
    <label>Sélectionnez un contact : </label>
    <select name="contactErase">
        <option value=""></option>
        <?php
           foreach($contactsByName as $contact) {
               echo '<option value="' . $contact['id'] . '">' . $contact['id'] . $contact['name_maj'] . ' ' . $contact['first_name_min'] . '</option>';
            }
        ?>
    </select><br> <!-- Sélection du champ à modifier -->
    <a href="#" onClick="var contactId = document.forms.delete.contactErase.options[document.forms.delete.contactErase.options.selectedIndex].value;
        function valid_confirm(contact) {
            if (confirm('Voulez-vous vraiment apporter ces modifications ?')) {
                var url = 'index.php?action=contactDelete&contactErase=' + contact;
                document.location.href = url;
                return true;
            } else {
                alert('Je me disais aussi...');
                return false;
            }
        }
        valid_confirm(contactId);"> Supprimer </a>
</form>

<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>
