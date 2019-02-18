<?php 
    session_start();
    $title = 'Forteroche/Erreurs';
    if ($_SESSION['group_id'] == 1) {
        $template = 'backend';
    } else {
        $template = 'frontend';
    }
    ob_start();
?>

<div class="container error-view red">
    <p class="alert">
        <?php echo 'Erreur ! ' . $errorMessage; ?>
    </p>
</div>

<?php $all1 = ob_get_clean(); ?>

<?php require('view/frontend/template.php'); ?>
