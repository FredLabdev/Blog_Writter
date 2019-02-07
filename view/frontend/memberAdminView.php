<?php 
    session_start();
    $title = 'Membres';
    $template = 'frontend'; 
    ob_start(); 
?>

<p>===========================================================</p>
<!-- Confirm connect -->

<h3>
    Bienvenue sur l' administration de votre compte !
</h3>
<br />
<p>===========================================================</p>

<h3>
    Détail de votre compte :
</h3>
<p class="success">
    <?php echo $message_success; ?>
</p>
<?php
        foreach($memberDetails as $dataMember) { // Détail du member sélectionné
            echo 'Date de création : ' . $dataMember['creation_date'] . '<br>';
            echo 'Nom : ' . $dataMember['name'] . '<br>';
            echo 'Prénom : ' . $dataMember['first_name'] . '<br>';  
            echo 'Pseudo : ' . $dataMember['pseudo'] . '<br>';  
            echo 'Mail : ' . $dataMember['email'] . '<br>';  
        }
    ?>

<br />
<p>===========================================================</p>

<h3>
    Modifier votre adresse mail, votre mot de passe :
</h3>

<form novalidate method="post" action="index.php?action=memberModif" id="form_modif" name="modif">
    <input type="hidden" name="member_modif" value="<?php echo $dataMember['id']; ?>" />
    <label>Sélectionnez le champ à modifier : </label>
    <select id="champ" name="champ">
        <option value=""></option>
        <option value="1">e-mail</option>
        <option value="2">Mot de passe</option>
    </select><br>
    <label for="modif_champ">Nouveau contenu du champ : </label>
    <input id="modif_champ" type="email" name="modif_champ" />
    <span class="error" id="error1" aria-live="polite">
        <?php echo $message_error; ?>
    </span><br>
    <label for="modif_champ_confirm">Confirmez ce nouveau contenu : </label>
    <input id="modif_champ_confirm" type="email" name="modif_champ_confirm" />
    <span class="error" id="error2" aria-live="polite">
        <?php echo $message_error; ?>
    </span><br>
    <input id="bouton_envoi" type="submit" value="Appliquer" name="remplacer" />

</form>

<br />
<p>===========================================================</p>


<h3>
    Pour supprimer votre compte, cliquez ici :
</h3>

<form name="delete">
    <input type="hidden" name="member_modif" value="<?php echo $dataMember['id']; ?>" />
    <a href="#" onClick="var memberId = document.forms.delete.member_modif.value;
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

<?php $frontend = ob_get_clean(); ?>

<?php require('view/frontend/template.php'); ?>
