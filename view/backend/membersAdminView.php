<?php 
    session_start();
    $title = 'Membres';
    $template = 'backend';
    ob_start(); 
?>

<p>===========================================================</p>
<!-- Confirm connect -->

<h3>
    Bienvenue sur l' administration de tous les membres !
</h3>
<br />
<p>===========================================================</p>

<!-- Liste des membres par catégorie -->

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

<!-- Voir les champs d'un membre -->

<h3>
    Editer un contact :
</h3>
<p class="success">
    <?php echo $message_success; ?>
</p>
<p class="alert">
    <?php echo $message_error; ?>
</p>
<form method="post" action="index.php?action=contactDetail">
    <label>Sélectionnez un contact : </label><select name="contact">
        <option value=""></option>
        <?php
            foreach($contactsByName as $contact) {
               echo '<option value="' . $contact['id'] . '">' . $contact['name_maj'] . ' ' . $contact['first_name_min'] . '</option>';
            }
        ?>
    </select>
    <input type="submit" value="Valider" name="valider" /><br>
    <?php
        foreach($contactDetail as $dataContact) { // Détail du contact sélectionné
            echo 'Date de création : ' . $dataContact['creation_date'] . '<br>';
            echo 'Nom : ' . $dataContact['name'] . '<br>';
            echo 'Prénom : ' . $dataContact['first_name'] . '<br>';  
            echo 'Pseudo : ' . $dataContact['pseudo'] . '<br>';  
            echo 'Mail : ' . $dataContact['email'] . '<br>';  
            echo 'Mot de passe : ' . $dataContact['password'] . '<br>';  
            if($dataContact['block_comment'] == 0) {
                echo 'Commentaires autorisés : oui';
            } else {
                echo 'Commentaires autorisés : non';
            };  
        }
    ?>
</form>
<p>.......................................................</p>
<form method="post" action="index.php?action=contactModif">
    <input type="hidden" name="contact_modif" value="<?php echo $dataContact['id']; ?>" />
    <label>
        <?php if($dataContact['block_comment'] == 0) {
                echo 'Bloquer ses commentaires';
            } else {
                echo 'Réautoriser ses commentaires';
            };
        ?>
    </label>
    <input type="checkbox" name="bloquage" value="<?php if($dataContact['block_comment'] == 0){echo '1';}else{echo '0';};?>" /><br>
    <input type="submit" value="Appliquer" name="remplacer" />
</form>

<br />
<p>===========================================================</p>

<h3>
    Pour supprimer ce compte, cliquez ici :
</h3>

<form name="delete">
    <input type="hidden" name="contact_modif" value="<?php echo $dataContact['id']; ?>" />
    <a href="#" onClick="var contactId = document.forms.delete.contact_modif.value;
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
        valid_confirm(contactId);"> Désincrire ce membre </a>
</form>

<?php $content = ob_get_clean(); ?>

<?php require('view/frontend/template.php'); ?>
