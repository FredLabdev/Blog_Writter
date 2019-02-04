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

<h3 class=success>
    Bienvenue
    <?php 
        echo $_SESSION['first_name'] . ' !';
    ?>
</h3>

<p>===========================================================</p>
<!-- Liste des posts -->

<ul>
    <?php
        echo '<h3>' . 'Liste des ' . $postsCount['nbre_posts'] . ' posts publiés à ce jour :' . '</h3>';
        $compteur = $postsCount['nbre_posts'];
        while ($postList = $posts->fetch()) {
            echo '<li style="color: red;">' . ' N° ' . $compteur . ' : ' . $postList['chapter_title'] . '</li>';
            $compteur--;
        }
    ?>
</ul>

<p>===========================================================</p>
<!-- Les pages par groupe de 5 posts -->

<p>Page
    <?php // Boucle affichant le bon nombre de liens vers d'autres pages, par groupe de 5 posts 
        for ($page=1, $pages_max=($postsCount['nbre_posts']/5)+($postsCount['nbre_posts']%5); $page<=$pages_max; $page++) {
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
    while ($post = $postsBy5->fetch()) {
?>

<h3 class="news">
    <?php echo $post['chapter_title'] . ' : ' . ' le '. $post['date']; ?>
</h3>
<p>
    <?php echo $post['chapter_extract']; ?>
</p>
<a href="index.php?action=post&amp;billet=<?php echo $post['id']; ?>">Voir plus</a>
<p>===========================================================</p>

<?php

}
?>

<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>
