<?php 
    session_start();
    $title = 'Publication';
    if ($_SESSION['group_id'] == 1) {
        $template = 'backend';
    } else {
        $template = 'frontend';
    }
    ob_start(); 
?>

<br />
<p>===========================================================</p>
<!-- Confirm connect -->
<h3>
    Bonjour
    <?= ' ' . $_SESSION['first_name'];?>, nous sommes le :
    <?= date('d/m/Y') . '.<br>';?>
    Bienvenue sur la publication du roman !
</h3>
<p>===========================================================</p>
<!-- Tout le roman -->

<p class="success">
    <?= $message_success; ?>
</p>
<p class="alert">
    <?= $message_error; ?>
</p>

<p id="roman"></p>
<input id="AllPostsLength" value="<?= count($postsAll); ?>" type="hidden" />
<?php   
    foreach($postsAll as $post) {
?>
<h1 class="news">
    <?= $post['chapter_title']; ?>
</h1>
<input type="hidden" class="eachPostHTML" name="postAllHTML" value="<?= $post['chapter_content']; ?>" />
<p class="eachPostPlace"></p>
<?php
 }
?>
<script>
    var roman = document.getElementsByClassName("eachPostPlace");
    var AllPostsLength = document.getElementById('AllPostsLength').value;
    var AllPostsHTML = new Array;
    AllPostsHTMLOrigin = document.getElementsByClassName('eachPostHTML');
    for (var i = 0, c = AllPostsLength; i < c; i++) {
        AllPostsHTML.push(AllPostsHTMLOrigin[i].value);
        var newPostHTML = AllPostsHTML[i];
        roman[i].insertAdjacentHTML('afterend', newPostHTML);
    }

</script>

<?php $all1 = ob_get_clean(); ?>

<?php require('view/frontend/template.php'); ?>
