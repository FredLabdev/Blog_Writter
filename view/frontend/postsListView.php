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
    <?= ' ' . $_SESSION['first_name'];?>, nous sommes le :
    <?= date('d/m/Y') . '.<br>';?>
    Bienvenue dans l'administration de votre site !
</h3>
<p>===========================================================</p>
<!-- Liste des posts -->

<p class="success">
    <?= $message_success; ?>
</p>
<p class="alert">
    <?= $message_error; ?>
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
    Saisissez et mettez en forme votre nouveau billet ici :
</h3>
<input type="button" value="G" style="font-weight:bold;" onclick="commande('bold');" />
<input type="button" value="I" style="font-style:italic;" onclick="commande('italic');" />
<input type="button" value="S" style="text-decoration:underline;" onclick="commande('underline');" />
<input type="button" value="Lien" onclick="commande('createLink');" />
<input type="button" value="Retirer lien" onclick="commande('unlink');" />
<input type="button" value="Image" onclick="commande('insertImage');" />
<select onchange="commande('heading', this.value); this.selectedIndex = 0;">
    <option value="">Titre</option>
    <option value="h1">Titre 1</option>
    <option value="h2">Titre 2</option>
    <option value="h3">Titre 3</option>
    <option value="h4">Titre 4</option>
    <option value="h5">Titre 5</option>
    <option value="h6">Titre 6</option>
</select>
<input type="button" value="effacer" onclick="commande('delete');" />
<div id="newPostInForm" contentEditable></div>

<form action="index.php?action=addPost" method="post">
    <p>
        <label>Titre du billet : </label><br>
        <input type="text" name="titre" />
        <textarea id="newPostPlainText" name="newPostPlainText"></textarea>
        <textarea id="newPostHTML" name="newPostHTML"></textarea>
        <!--Remettre textarea pour fonctionne post -->
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
    <input type="submit" value="Publier votre billet" onclick="getNewPostInForm();" />
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
    <?= $postBy5['chapter_title'] . ' : ' . ' le '. $postBy5['date']; ?>
</h3>
<p>
    <?= $postBy5['chapter_extract'] . ' : ' . ' le '. $postBy5['date']; ?>
</p>
<a href="index.php?action=post&amp;billet=<?= $postBy5['id']; ?>"> Voir le billet complet ! </a>

<p>===========================================================</p>

<?php
 }
?>

<?php $all2 = ob_get_clean(); ?>

<?php require('view/frontend/template.php'); ?>
