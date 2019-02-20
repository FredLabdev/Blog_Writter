<?php 
    session_start();
    $title = 'Forteroche/Compte';
    if ($_SESSION['group_id'] == 1) {
        $template = 'backend';
    } else {
        $template = 'frontend';
    }
    ob_start(); 
?>

<div id="member" class="white">

    <!-- DETAIL DU COMPTE PERSO -->

    <div class="row col-12 text-center">
        <?php if($message_success) { ?>
        <span class="alert alert-success col-4 offset-4">
            <?= $message_success; ?>
        </span>
        <?php } ?>
    </div>
    <div class="member-text offset-lg-3">
        <?php
        foreach($memberDetails as $dataMember) { // Détail du member sélectionné
            echo 'Date de création : ' . $dataMember['creation_date_fr'] . '<br>';
            echo 'Nom : ' . $dataMember['name'] . '<br>';
            echo 'Prénom : ' . $dataMember['first_name'] . '<br>';  
            echo 'Pseudo : ' . $dataMember['pseudo'] . '<br>';  
            echo 'Mail : ' . $dataMember['email'] . '<br>';  
        }
        ?>
    </div>

    <!-- MODIFICATIONS POSSIBLES -->

    <div class="white">

        <form novalidate method="post" action="index.php?action=memberModif" id="form_modif" name="modif">
            <input type="hidden" name="personal_modif" value="<?php echo $dataMember['id']; ?>" />
            <label class="member-text2 d-flex flex-lg-row flex-sm-column">Sélectionnez le champ à modifier :</label>
            <fieldset>
                <select id="champ" class="member-input" name="champ">
                    <option value=""></option>
                    <option value="1">e-mail</option>
                    <option value="2">Mot de passe</option>
                </select>
            </fieldset><br>
            <label class="member-text2" for="modif_champ">Nouveau contenu du champ :</label>
            <fieldset>
                <input id="modif_champ" class="member-input" name="modif_champ" /><br>
                <span class="error" id="error1" aria-live="polite">
                    <?= $message_error; ?>
                </span>
            </fieldset>
            <br>
            <label class="member-text2" for="modif_champ_confirm">Confirmez ce nouveau contenu :</label>
            <fieldset>
                <input id="modif_champ_confirm" class="member-input" name="modif_champ_confirm" /><br>
                <span class="error" id="error2" aria-live="polite">
                    <?= $message_error; ?>
                </span>
            </fieldset>
            <br>
            <button id="bouton_envoi" class="btn btn-light btn-lg blue offset-4" type="submit" name="remplacer">Appliquer</button>
        </form>
    </div>

</div>

<!-- SUPPRESSION DU COMPTE -->

<div class="delete-member" class="container-fluid white">

    <form class="deleteMember" name="delete">
        <label>Pour supprimer ce compte, cliquez ici :</label>
        <input type="hidden" name="personal_modif" value="<?php echo $dataMember['id']; ?>" />
        <a href="#" class="delete offset-2" onClick="var memberId = document.forms.delete.personal_modif.value;
        function valid_confirm(member) {
            if (confirm('Voulez-vous vraiment vous désinscrire définitivement ?')) {
                var url = 'index.php?action=memberDelete&memberErase=' + member;
                document.location.href = url;
                return true;
            } else {
                alert('Je me disais aussi...');
                return false;
            }
        }
        valid_confirm(memberId);"> Désincription </a>
    </form>

</div>

<?php 
    if ($_SESSION['group_id'] == 1) {
        $backend = ob_get_clean();
    } else {
        $frontend = ob_get_clean();
    }
?>

<?php require('view/frontend/template.php'); ?>
