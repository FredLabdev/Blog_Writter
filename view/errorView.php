<?php 
    session_start();
    $title = 'Erreurs';
    if ($_SESSION['group_id'] == 1) {
        $template = 'backend';
    } else {
        $template = 'frontend';
    }
    ob_start();
?>

<p class="alert">
    <?php echo 'Erreur ! ' . $errorMessage; ?>
</p>

<?php $backend = ob_get_clean(); ?>

<?php require('view/frontend/template.php'); ?>
