<?php $title = 'Login'; ?>
<?php $template = 'frontend'; ?>
<?php $menu = 'no_menu'; ?>
<?php ob_start(); ?>

<p>===========================================================</p>
<!-- envoie formulaire connexion en méthode POST -->

<h3>
    <?php 
            if ($login_error) {
                echo $login_error;
            } else {
                echo '<p>' . 'Veuillez vous identifier :' . '</p>';
            }
        ?>
</h3>

<form method="post" action="index.php">
    <p>
        <label>Votre pseudo : <input type="text" name="pseudo_connect" /></label><br>
        <label>Votre mot de passe : <input type="password" name="password_connect" /></label><br>
        <label>Prochaine connexion automatique ?<input type="checkbox" name="login_auto" /></label><br>
    </p>
    <input type="submit" value="valider" name="login" /><br>

</form>

<h3>
    <?php 
            if ($account_error) {
                echo $account_error;
            } else {
                echo '<p>' . 'Création de compte :' . '</p>';
            }
        ?>
</h3>

<form method="post" action="index.php">
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

<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>
