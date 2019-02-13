<?php 
    session_start();
    $title = 'Forteroche/Membres';
    if ($_SESSION['group_id'] == 1) {
        $template = 'backend';
    } else {
        $template = 'frontend';
    }
    ob_start(); 
?>

<br />
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
    <?= 'Nombre d\'abonnés au blog à ce jour (hors administrateurs) : ' . $membersCount['nbre_members'] ?>
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
    <?= $message_success; ?>
</p>
<p class="alert">
    <?= $message_error; ?>
</p>
<form id="MemberForm" method="post" action="index.php?action=membersDetail">
    <label>Sélectionnez un member : </label><select name="member">
        <option name="choix" value=""></option>
        <?php
            foreach($membersByName as $member) {
               echo '<option value="' . $member['id'] . '">' . $member['name_maj'] . ' ' . $member['first_name_min'] . '</option>';
            }
        ?>
    </select>
    <input type="submit" value="Valider" />

    <?php
        foreach($memberDetails as $dataMember) { // Détail du member sélectionné
            echo 'Date de création : ' . $dataMember['creation_date'] . '<br>';
            echo 'Nom : ' . $dataMember['name'] . '<br>';
            echo 'Prénom : ' . $dataMember['first_name'] . '<br>';  
            echo 'Pseudo : ' . $dataMember['pseudo'] . '<br>';  
            echo 'Mail : ' . $dataMember['email'] . '<br>';  
            if($dataMember['block_comment'] == 0) {
                echo 'Commentaires autorisés : oui' . '<br>';  
            } else {
                echo 'Commentaires autorisés : non' . '<br>';  
            }; 
            if($dataMember['group_id'] == 2) {
                echo 'Ce membre est un modérateur';
            };  
        }
    ?>
</form>
<?php
        if ($memberDetails) {
    ?>

<p>.......................................................</p>
<form id="modifMember" method="post" action="index.php?action=memberModif">
    <input type="hidden" name="member_modif" value="<?php echo $dataMember['id']; ?>" />
    <label>
        <?php 
            if($dataMember['block_comment'] == 0) {
                echo 'Bloquer ses commentaires';
            } else {
                echo 'Réautoriser ses commentaires';
            };
        ?>
    </label>
    <input type="checkbox" name="block_comment" value="<?php if($dataMember['block_comment'] == 0){echo '1';}else{echo '0';};?>" /><br>
    <label>
        <?php 
            if ($_SESSION['group_id'] == 1) {
                if ($dataMember['group_id'] != 2) {
                    echo 'Donner un pouvoir de modérateur de commentaires';
                } else {
                    echo 'Retirer son pouvoir de modérateur de commentaires';
                }
        ?>
    </label>
    <input type="checkbox" name="moderator" value="<?php if($dataMember['group_id'] == 3){echo '2';}else{echo '3';};?>" /><br>
    <?php 
            }
    ?>
    <input type="submit" value="Appliquer" name="remplacer" />
</form>

<br />
<p>===========================================================</p>

<?php 
    if ($_SESSION['group_id'] == 1) {
?>

<form id="deleteMember" name="delete">
    <label>Pour supprimer ce compte, cliquez ici :</label>
    <input type="hidden" name="member_modif" value="<?= $dataMember['id']; ?>" />
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
<?php 
    }
    }
?>

<?php 
    if ($_SESSION['group_id'] == 1) {
        $backend = ob_get_clean();
    } else {
        $frontend = ob_get_clean();
    }
?>

<?php require('view/frontend/template.php'); ?>
