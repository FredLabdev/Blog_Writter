<?php 
    session_start();
    $title = 'Forteroche/Accueil';
    if ($_SESSION['group_id'] == 1) {
        $template = 'backend';
    } else {
        $template = 'frontend';
    }
    ob_start(); 
?>

<!-- SLIDER -->


<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img class="d-block w-100" src="public/picture/picture11.jpg" alt="First slide">
            <h3 class="title-slider d-none d-md-block offset-lg-1">
                Bonjour <strong>
                    <?= $_SESSION['first_name']; ?>
                </strong>
            </h3>
            <h3 class="title-slider col-xs-12 col-lg-6 offset-lg-6">
                Un billet simple pour l'Alaska
            </h3>
            <h2 class="text-slider yellow ">Index des
                <?= $postsCount['nbre_posts'] ?> extraits publiés à ce jour
            </h2>
            <ul class="index col-xs-10 offset-1 list-unstyled yellow">
                <?php
                        $compteur = $postsCount['nbre_posts']-($postsCount['nbre_posts']-1);
                        foreach($postsList as $post) {        
                    ?>
                <li>
                    <a href="index.php?action=post&amp;billet=<?= $post['id']; ?>" class="yellow">
                        N°
                        <?= $compteur ?> :
                        <?= $post['chapter_title'] ?>
                    </a>
                </li>
                <?php
                        $compteur++;
                        }
                    ?>
            </ul>
            <div class="carousel-caption d-none d-md-block">
                <h5>Un billet simple pour l'Alaska</h5>
                <p>Jean Forteroche</p>
            </div>
        </div>
        <div class="carousel-item">
            <img class="d-block w-100" src="public/picture/picture7.jpg" alt="Second slide">
            <h3 class="title-slider col-xs-12 col-lg-6 offset-lg-6">
                Un billet simple pour l'Alaska
            </h3>
            <h2 class="text-slider white">Entrez dans l'aventure
            </h2>
            <div class="carousel-caption d-none d-md-block">
                <h5>Un billet simple pour l'Alaska</h5>
                <p>Jean Forteroche</p>
            </div>
        </div>
        <div class="carousel-item">
            <img class="d-block w-100" src="public/picture/picture15.jpg" alt="Third slide">
            <h3 class="title-slider col-xs-12 col-lg-6 offset-lg-6">
                Un billet simple pour l'Alaska
            </h3>
            <h2 class="text-slider">Le roman couve...
            </h2>
            <div class="carousel-caption d-none d-md-block black">
                <h5>Un billet simple pour l'Alaska</h5>
                <p>Jean Forteroche</p>
            </div>
        </div>
    </div>
    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>


<?php $all1 = ob_get_clean(); ?>
<?php ob_start();?>



<!-- EXTRAITS DES POSTS -->
<div class="posts-view">

    <h3 class="posts-title green">
        <?php
                if ($billet_max == $postsCount['nbre_posts']) {
                    echo 'Les 5 derniers posts du n° ';
                } else {
                    echo 'Billets du n° ';
                }
                echo $billet_max . ' au n° ' . $billet_min;
            ?>
    </h3>

    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <li class="page-item">
                <a class="page-link bg-darkgreen white" href="index.php?action=pagePosts&amp;page=<?php if($_GET['page'] < $pages_max){echo $_GET['page']+1;} else {echo $pages_max;} ?>">Former</a>
                <?php 
                    for ($index=1, $pages_max; $index <= $pages_max; $index++) {
                ?>
            <li class="page-item">
                <a class="page-link bg-darkgreen white" href="index.php?action=pagePosts&amp;page=<?= $pages_max+1-$index ?>">
                    <?= $index ?></a>
                <?php
                    }
                ?>
            <li class="page-item">
                <a class="page-link bg-darkgreen white" href="index.php?action=pagePosts&amp;page=<?php if($_GET['page'] > 1){echo $_GET['page']-1;} else {echo 1;} ?>">Recent</a>
        </ul>
    </nav>

    <div class="posts-extracts col-xs-12 white">
        <?php    
                for ($i=0; $i<5; $i++) {
                    if ($postsBy5[$i] != "") {
            ?>
        <div class="extract">
            <h3 class="news-title">
                <?= $postsBy5[$i]['chapter_title'] ?>
            </h3>
            <p class="green">le
                <?= $postsBy5[$i]['date']; ?>
            </p>
            <p class="news">
                <?= $postsBy5[$i]['chapter_extract']; ?>
            </p>
            <a href="index.php?action=post&amp;billet=<?= $postsBy5[$i]['id']; ?>" class="btn btn-primary offset-9"><span class="badge badge-light">
                    <?= $commentsCountBy5[$i]['nbre_comment']; ?></span> commentaires - Billet complet <i class="fab fa-readme"></i></a>

        </div>
        <?php
                }
                }
            ?>
    </div>
</div>

<!-- LISTE DES COMMENTAIRES SIGNALES -->

<?php
        if ($_SESSION['group_id'] == 1 || $_SESSION['group_id'] == 2) {
    ?>

<div id="comments-signaled" class="container-fluid posts-view white">

    <h3 class="posts-title orange"><i class="fas fa-exclamation-circle"></i> Commentaires signalés</h3>

    <div class="comments-content col-xs-10 offset-1">

        <?php
            if ($signalComments) {
                foreach($signalComments as $signalComment) {
            ?>
        <span class="row col-xs-12">
            <strong class="col-xs-3 white">
                <?= $signalComment['author']; ?>
            </strong>
            <span class="col-xs-3 green">
                le
                <?= $signalComment['comment_date_fr']; ?>
            </span>
            <span class="col-xs-6 orange text-right"><i class="fas fa-exclamation-circle"></i> signalé le
                <?= $signalComment['signal_date_fr']; ?> par
                <strong>
                    <?= $signalComment['signal_author']; ?></strong>
            </span>
        </span>
        <p class="alert alert-info row col-xs-12">
            <?= nl2br(htmlspecialchars($signalComment['comment'])); ?>
        </p>

        <!-- BOUTON SUPPRIMER UN COMMENTAIRE (uniquement si admin, modérateur, ou posté par sois-meme)-->

        <div class="boutons row col-xs-12">
            <form class="offset-7" action="index.php?action=deleteComment" method="post">
                <input type="hidden" name="delete_comment" value="<?= $signalComment['id'] ?>" />
                <button class="btn btn-danger btn-sm" type="submit"><i class="fas fa-trash-alt"></i> Supprimer</button>
            </form>
            <a href="index.php?action=post&amp;billet=<?= $signalComment['post_id']; ?>" class="btn btn-primary btn-sm">Accéder au billet complet <i class="fab fa-readme"></i></a>

        </div>
        <?php
                }
                
            } else {
            ?>
        <div class="row col-xs-12 text-center">
            <span class="alert alert-success col-xs-4 offset-4">
                Aucun commentaires n'a été signalé
            </span>
        </div>
    </div>

    <?php
            }
        ?>
</div>
<?php
        }
    ?>

<?php $all2 = ob_get_clean(); ?>
<?php ob_start();?>

<!-- NOUVEAU POST -->

<div class="post-view white">
    <div class=title-container>
        <h3 class="posts-title green">
            Saisissez et mettez en forme un nouveau billet ici :
        </h3>
        <div class="row col-xs-12 text-center">
            <?php if($message_success) { ?>
            <span class="alert alert-success col-xs-4 offset-4">
                <?= $message_success; ?>
            </span>
            <?php } else if($message_error) { ?>
            <span class="alert alert-danger col-xs-4 offset-4">
                <?= $message_error; ?>
            </span>
            <?php } ?>
        </div>
    </div>
    <form method="post" action="index.php?action=addPost">
        <label>Titre du nouveau billet : </label>
        <input type="text" name="titre" class="col-xs-7 news-title" /><br>
        <label>L'insérer après un billet particulier : </label>
        <select name="postBefore" class="col-xs-7 news-title">
            <option value=""></option>
            <?php
                foreach($postsList as $postBefore) {
                    echo '<option value="' . $postBefore['id'] . '">' . $postBefore['chapter_title'] . '</option>';
                }
            ?>
        </select>
        <p></p>
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

        <div id="newPostInForm" class="black news" contentEditable></div>
        <textarea id="newPostPlainText" name="newPostPlainText"></textarea>
        <textarea id="newPostHTML" name="newPostHTML"></textarea>
        <p></p>
        <button type="button submit" class="btn btn-outline-light btn-sm col-xs-1 offset-10" onclick="getNewPostInForm();"><i class="fas fa-share-square"></i> Publier votre billet</button>
    </form>
</div>



<?php $backend = ob_get_clean(); ?>

<?php require('view/frontend/template.php'); ?>
