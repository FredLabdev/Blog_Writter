<?php 
$title = 'Forteroche/Exit';
$template = 'frontend';
$noMenu = 'no_menu';
ob_start(); 
?>

<div class="container-fluid exit">
    <a href="index.php" class="btn btn-outline-light btn-lg exit-text" name="login">Retour en Alaska ?</a>
    <h2 class="exit-text">
        <?= $message_success ?>
    </h2>
</div>

<?php $all1 = ob_get_clean(); ?>

<?php require('view/frontend/template.php'); ?>
