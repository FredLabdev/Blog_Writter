<?php 
    session_start();
    $title = 'Forteroche/Publication';
    if ($_SESSION['group_id'] == 1) {
        $template = 'backend';
    } else {
        $template = 'frontend';
    }
    ob_start(); 
?>

<!-- TOUTES LES PUBLICATIONS -->

<div id="publishing" class="container-fluid">
    <div class="row col-12 text-center">
        <?php if($message_success) { ?>
        <span class="alert alert-success col-4 offset-4">
            <?= $message_success; ?>
        </span>
        <?php } else if($message_error) { ?>
        <span class="alert alert-danger col-4 offset-4">
            <?= $message_error; ?>
        </span>
        <?php } ?>
    </div>
    <div class="publishing-text">
        <p id="roman"></p>
        <input id="AllPostsLength" value="<?= count($postsAll); ?>" type="hidden" />
        <?php   
    foreach($postsAll as $post) {
    ?>
        <h2>
            <?= $post['chapter_title']; ?>
        </h2>
        <p>
            <?= $post['chapter_content']; ?>
        </p>
        <?php
    }
    ?>
    </div>
</div>

<?php $all1 = ob_get_clean(); ?>

<?php require('view/frontend/template.php'); ?>
