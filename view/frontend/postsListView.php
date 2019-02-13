<?php 
    session_start();
    $title = 'Forteroche/Accueil';
    if ($_SESSION['group_id'] == 1) {
        $template = 'backend';
    } else {
        $template = 'frontend';
    }
    $bg = 'postsView';
    ob_start(); 
?>
<div class="container-fluid header">
    <h5 class="col-lg-4 offset-7">
        Bonjour <strong>
            <?= $_SESSION['first_name']; ?>
        </strong> , prenez donc
    </h5>
    <h3 class="title col-lg-6 offset-6">
        Un billet simple pour l'Alaska !
    </h3>

    <h2 class="index-title">Index des
        <?= $postsCount['nbre_posts'] ?> extraits publiés à ce jour
    </h2>

    <ul class="index offset-1 col-lg-10">
        <?php
        $compteur = $postsCount['nbre_posts']-($postsCount['nbre_posts']-1);
        foreach($postsList as $post) {
            echo '<li>' . ' N° ' . $compteur . ' : ' . $post['chapter_title'] . '</li>';
            $compteur++;
        }
    ?>
    </ul>

    <span class="success">
        <?= $message_success; ?>
    </span>
    <span class="alert">
        <?= $message_error; ?>
    </span>
</div>
<?php $all1 = ob_get_clean(); ?>
<?php ob_start();?>

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
<div class="container-fluid bg-PostsView">
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <li class="page-item">
                <a class="page-link" href="index.php?action=pagePosts&amp;page=<?php if($_GET['page'] < $pages_max){echo $_GET['page']+1;} else {echo $pages_max;} ?>">Prev</a>
                <?php 
                for ($index=1, $pages_max; $index <= $pages_max; $index++) {
            ?>
            <li class="page-item">
                <a class="page-link" href="index.php?action=pagePosts&amp;page=<?= $pages_max+1-$index ?>">
                    <?= $index ?></a>
                <?php
                }
            ?>
            <li class="page-item">
                <a class="page-link" href="index.php?action=pagePosts&amp;page=<?php if($_GET['page'] > 1){echo $_GET['page']-1;} else {echo 1;} ?>">Next</a>
        </ul>
    </nav>

    <h3>
        <?php    // Récupération des indices de posts max et min de chaque page
        if ($billet_max == $postsCount['nbre_posts']) {
            echo 'Les 5 derniers posts du n° ';
        } else {
            echo 'Billets du n° ';
        }
        echo $billet_max . ' au n° ' . $billet_min;
    ?>
    </h3>

    <?php    
    foreach($postsBy5 as $postBy5) {
?>

    <div class="extract col-lg-6">
        <h3 class="news">
            <?= $postBy5['chapter_title'] . ' : '?>
        </h3> le
        <?= $postBy5['date']; ?>

        <p class="news">
            <?= $postBy5['chapter_extract']; ?>
        </p>
        <a href="index.php?action=post&amp;billet=<?= $postBy5['id']; ?>"> Voir le billet complet ! </a>
    </div>

    <?php
 }
?>
</div>
<?php $all2 = ob_get_clean(); ?>

<?php require('view/frontend/template.php'); ?>
