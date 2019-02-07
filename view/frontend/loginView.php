<?php 
$title = 'Login';
$template = 'frontend';
$noMenu = 'no_menu';
ob_start(); 
?>

<p class="success">
    <?php 
        if($message_success) {
            echo $message_success;
        } else {
        echo '<br>';
        }
    ?>
</p>
<p>===========================================================</p>
<h3>
    Veuillez vous identifier :
</h3>
<form method="post" action="index.php?action=login">
    <p>
        <label>Votre pseudo : <input type="text" name="pseudo_connect" /></label><br>
        <label>Votre mot de passe : <input type="password" name="password_connect" /></label><br>
        <label>Prochaine connexion automatique ?<input type="checkbox" name="login_auto" /></label><br>
    </p>
    <input type="submit" value="valider" name="login" /><br>

</form>
<p class="alert">
    <?php 
        if($login_error) {
            echo $login_error;
        } else {
        echo '<br>';
        }
    ?>
</p>

<p>===========================================================</p>
<h3>
    Création de compte :
</h3>
<form method="post" action="index.php?action=newMember">
    <p>
        <label>Votre nom : <input type="text" name="name" /></label><br>
        <label>Votre prenom : <input type="text" name="first_name" /></label><br>
        <label>Votre pseudo : <input type="text" name="pseudo"></label><br>
        <label>Votre e-mail : <input type="text" name="email" /></label><br>
        <label>Confirmez votre e-mail : <input type="text" name="email_confirm" /></label><br>
        <label>Créez un mot de passe : <input type="password" name="password" /></label><br>
        <label>Confirmez votre mot de passe : <input type="password" name="password_confirm" /></label><br>
    </p>
    <input type="submit" value="valider" name="newMember" /><br>
</form>
<p class="alert">
    <?php 
        if($message_error) {
            echo $message_error;
        } else {
        echo '<br>';
        }
    ?>
</p>

<?php $all1 = ob_get_clean(); ?>

<?php require('view/frontend/template.php'); ?>
