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
    Liste des members classés par catégorie :
</h3>
<p>
    <?php echo 'Nombre d\'abonnés à votre blog à ce jour: ' . $membersCount['nbre_members'] ?>
</p>
<p>
    <?php 
        foreach($membersByGroup as $member) {
            echo $member['grade_groupe'] . ' : ' . $member['name_member'] . ' ' . $member['first_name_member'] . '<br>';
        }
    ?>
</p>
<br />
<p>===========================================================</p>

<!-- Voir les champs d'un membre -->

<h3>
    Editer un member :
</h3>
<p class="success">
    <?php echo $message_success; ?>
</p>
<p class="alert">
    <?php echo $message_error; ?>
</p>
<form method="post" action="index.php?action=memberDetail">
    <label>Sélectionnez un member : </label><select name="member">
        <option value=""></option>
        <?php
            foreach($membersByName as $member) {
               echo '<option value="' . $member['id'] . '">' . $member['name_maj'] . ' ' . $member['first_name_min'] . '</option>';
            }
        ?>
    </select>
    <input type="submit" value="Valider" name="valider" /><br>
    <?php
        foreach($memberDetails as $dataMember) { // Détail du member sélectionné
            echo 'Date de création : ' . $dataMember['creation_date'] . '<br>';
            echo 'Nom : ' . $dataMember['name'] . '<br>';
            echo 'Prénom : ' . $dataMember['first_name'] . '<br>';  
            echo 'Pseudo : ' . $dataMember['pseudo'] . '<br>';  
            echo 'Mail : ' . $dataMember['email'] . '<br>';  
            if($dataMember['block_comment'] == 0) {
                echo 'Commentaires autorisés : oui';
            } else {
                echo 'Commentaires autorisés : non';
            };  
        }
    ?>
</form>
<p>.......................................................</p>
<form method="post" action="index.php?action=memberModif">
    <input type="hidden" name="member_modif" value="<?php echo $dataMember['id']; ?>" />
    <label>
        <?php if($dataMember['block_comment'] == 0) {
                echo 'Bloquer ses commentaires';
            } else {
                echo 'Réautoriser ses commentaires';
            };
        ?>
    </label>
    <input type="checkbox" name="bloquage" value="<?php if($dataMember['block_comment'] == 0){echo '1';}else{echo '0';};?>" /><br>
    <input type="submit" value="Appliquer" name="remplacer" />
</form>

<br />
<p>===========================================================</p>

<h3>
    Pour supprimer ce compte, cliquez ici :
</h3>

<form name="delete">
    <input type="hidden" name="member_modif" value="<?php echo $dataMember['id']; ?>" />
    <a href="#" onClick="var memberId = document.forms.delete.member_modif.value;
        function valid_confirm(member) {
            if (confirm('Voulez-vous vraiment désinscrire ce membre ?')) {
                var url = 'index.php?action=memberDelete&memberErase=' + member;
                document.location.href = url;
                return true;
            } else {
                alert('Je me disais aussi...');
                return false;
            }
        }
        valid_confirm(memberId);"> Désincrire ce membre </a>
</form>

<?php $backend = ob_get_clean(); ?>

<?php require('view/frontend/template.php'); ?>
