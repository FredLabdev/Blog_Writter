<?php 
$title = 'Forteroche/Contact';
    if ($_SESSION['group_id'] == 1) {
        $template = 'backend';
    } else {
        $template = 'frontend';
    }
ob_start(); 
?>

<div class="container-fluid white">
    <div class="contact-view white">
        <h2 class="member-title">
            Demandez-nous ce que vous voulez !
        </h2>
        <form method="post" action="index.php?action=newMember">
<div class="form-group">
                <label>Votre nom : </label>
                <input class="contact form-control col-3" type="text" name="name" value="<?= $_SESSION['name'] ?>" />
                <label>Votre prenom : </label>
                <input class="contact form-control col-3" type="text" name="first_name" value="<?= $_SESSION['first_name'] ?>" />
                <label>Votre message : </label>
                <textarea class="form-control col-6 contact" rows="15"></textarea>
            </div>
            <button type="button submit" class="btn btn-outline-light btn-lg" name="login">Envoyer <i class="fas fa-space-shuttle"></i></button>
        </form>

        <div class="row col-12 text-center">
            <?php if($message_error) { ?>
            <span class="alert alert-danger col-4 offset-4">
                <?= $message_error; ?>
            </span>
            <?php } ?>
        </div>
    </div>
</div>

<?php $all1 = ob_get_clean(); ?>

<?php require('view/frontend/template.php'); ?>
