<?php 
    session_start();
    $title = 'Accueil';
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
    <?php echo ' ' . $_SESSION['first_name'];?>, nous sommes le :
    <?php echo date('d/m/Y') . '.<br>';?>
    Bienvenue dans l'administration de votre site !
</h3>
<p>===========================================================</p>
<!-- Liste des posts -->

<p class="success">
    <?php echo $message_success; ?>
</p>
<ul>
    <?php
        echo '<h3>' . 'Liste des ' . $postsCount['nbre_posts'] . ' posts publiés à ce jour :' . '</h3>';
        $compteur = $postsCount['nbre_posts'];
        foreach($postsList as $post) {
            echo '<li style="color: red;">' . ' N° ' . $compteur . ' : ' . $post['chapter_title'] . '</li>';
            $compteur--;
        }
    ?>
</ul>

<?php $all1 = ob_get_clean(); ?>
<?php ob_start();?>
<p>===========================================================</p>
<!-- Ajouter un post -->

<h3>
    Ajouter un nouveau billet :
</h3>
<form action="index.php?action=addPost" method="post">
    <p>
        <label>Titre du billet : </label><br>
        <input type="text" name="titre" />
        <label>Contenu du billet : </label><br>
        <textarea name="contenu" rows="8" cols="45"></textarea>
        <label>L'insérer après un billet particulier : </label>
        <select name="postBefore">
            <option value=""></option>
            <?php
            foreach($postsList as $postBefore) {
               echo '<option value="' . $postBefore['id'] . '">' . $postBefore['chapter_title'] . '</option>';
            }
        ?>
        </select>
    </p>
    <input type="submit" value="Publier votre billet" />
</form>

<?php $backend = ob_get_clean(); ?>
<?php ob_start();?>
<p>===========================================================</p>
<!-- Les pages par groupe de 5 posts -->

<p>Page
    <?php 
        for ($page=1, $pages_max; $page<=$pages_max; $page++) {
            echo '<a href="index.php?action=pagePosts&amp;page=' . $page .'">' . $page . '</a>' . ' ';
        }
    ?>
</p>

<h3>
    <?php    // Récupération des indices de posts max et min de chaque page
        if ($billet_max == $postsCount['nbre_posts']) {
            echo 'Les 5 derniers posts du n° ';
        } else {
            echo 'posts du n° ';
        }
        echo $billet_max . ' au n° ' . $billet_min;
    ?>
</h3>

<p>===========================================================</p>
<!-- Les détails de la page sélectionnée -->

<?php    
    foreach($postsBy5 as $postBy5) {
?>

<h3 class="news">
    <?php echo $postBy5['chapter_title'] . ' : ' . ' le '. $postBy5['date']; ?>
</h3>
<p>
    <?php echo $postBy5['chapter_extract']; ?>
</p>
<a href="index.php?action=post&amp;billet=<?php echo $postBy5['id']; ?>">Voir plus</a>
<p>===========================================================</p>

<?php

}
?>

<?php $all2 = ob_get_clean(); ?>

<?php require('view/frontend/template.php'); ?>
