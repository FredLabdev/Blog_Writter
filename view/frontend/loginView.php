<?php 
$title = 'Forteroche/Login';
$template = 'frontend';
ob_start(); 
?>

<div class="loginView">
    <div class="login">
        <h1 class="login-brand">
            <div class="col-xs-12">Pour voyager sur l'ensemble du site,</div>
            <div class="col-xs-12">Validez votre billet pour l'Alaska !</div>
        </h1>
        <h2 class="login-title">
            Billet déjà prit ?<br>Embarquez :
        </h2>
        <form class="login-form" method="post" action="index.php?action=login">
            <p>
                <label>Votre pseudo : <input type="text" name="pseudo_connect" placeholder="pseudo" /></label>
                <label>Votre mot de passe : <input type="password" name="password_connect" placeholder="password" /></label>
                <label>Aller-retour (connexion automatique) ?<input type="checkbox" name="login_auto" /></label>
            </p>
            <button type="button submit" class="btn btn-outline-light btn-lg col-xs-12" name="login">Alaska</button>
        </form>
        <div class="row col-xs-12 text-center">
            <?php if($login_error) { ?>
            <span class="alert alert-danger col-xs-4 offset-4">
                <?= $login_error; ?>
            </span>
            <?php } ?>
        </div>
    </div>

    <div class="newMember">
        <h2 class="member-title">
            Un billet pour l'Alaska ?
        </h2>
        <form class="login-form" method="post" action="index.php?action=newMember">
            <p>
                <label>Votre nom : <input type="text" name="name" placeholder="last name" /></label>
                <label>Votre prenom : <input type="text" name="first_name" placeholder="first name" /></label>
                <label>Votre pseudo : <input type="text" name="pseudo" placeholder="pseudo"></label>
                <label>Votre e-mail : <input type="text" name="email" placeholder="e-mail" /></label>
                <label>Confirmez votre e-mail : <input type="text" name="email_confirm" placeholder="confirm e-mail" /></label>
                <label>Créez un mot de passe : <input type="password" name="password" placeholder="Pass&word (inclu: Maj et caract.special)" /></label>
                <label>Confirmez votre mot de passe : <input type="password" name="password_confirm" placeholder="confirm Pass&word" /></label>
            </p>
            <button type="button submit" class="btn btn-outline-light btn-lg col-xs-12" name="login">Check-In</button>
        </form>
        <div class="row col-xs-12 text-center">
            <?php if($message_error) { ?>
            <span class="alert alert-danger col-xs-4 offset-4">
                <?= $message_error; ?>
            </span>
            <?php } ?>
        </div>
    </div>
</div>

<?php $all1 = ob_get_clean(); ?>

<?php require('view/frontend/template.php'); ?>
