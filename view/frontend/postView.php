<?php 
    session_start();
    $title = 'Forteroche/Billet';
    if ($_SESSION['group_id'] == 1) {
        $template = 'backend';
    } else {
        $template = 'frontend';
    }
    ob_start();
?>

<div class="container-fluid post-view">

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

    <!-- AFFICHER UN POST -->

    <h3 class="news-title white">
        <?php 
        foreach($postDetails as $dataPost) {
            echo $dataPost['chapter_title'];  
        ?>
    </h3>
    <span class="green">le
        <?= $dataPost['creation_date_fr']; ?>
    </span>

    <div class="post-content">
        <div class="post white">
            <p id="postInForm">
                <?= $dataPost['chapter_content']; ?>
            </p>
        </div>
        <?php } ?>

    </div>

    <?php $all1 = ob_get_clean(); ?>
    <?php ob_start();?>

    <!-- LISTE DES COMMENTAIRES -->

    <h3 class="posts-title green">commentaires</h3>

    <div class="comments-content">

        <?php
        while ($comment = $comments->fetch()) {
        ?>
        <span class="row col-12">
            <strong class="col-6 white">
                <?= $comment['author']; ?>
            </strong>
            <span class="col-6 green">
                le
                <?= $comment['comment_date_fr']; ?>
            </span>
        </span>
        <p class="alert alert-info row col-12">
            <?= nl2br(htmlspecialchars($comment['comment'])); ?>
        </p>

        <div class="boutons row offset-7">

            <!-- BOUTON MODIFIER UN COMMENTAIRE (uniquement si sois-meme)-->

            <?php
            if ($comment['author'] == $_SESSION['pseudo']) {
            ?>

            <a class="btn btn-outline-light btn-sm" data-toggle="collapse" href="#modifComment<?= $comment['id'] ?>" role="button" aria-expanded="false" aria-controls="modifComment"><i class="fas fa-eraser"></i> Modifier
            </a>
            <?php
            } 
            ?>

            <!-- BOUTON SUPPRIMER UN COMMENTAIRE (uniquement si admin, modérateur, ou posté par sois-meme)-->

            <?php
            if ($_SESSION['group_id'] == 1 || $_SESSION['group_id'] == 2 || $comment['author'] == $_SESSION['pseudo']) {
            ?>
            <form action="index.php?action=deleteComment" method="post">
                <input type="hidden" name="postId" value="<?= $dataPost['id']; ?>" />
                <input type="hidden" name="delete_comment" value="<?= $comment['id'] ?>" />
                <button class="btn btn-danger btn-sm" type="submit"><i class="fas fa-trash-alt"></i> Retirer</button>
            </form>
            <?php
            } 
            ?>

            <?php if($comment['comment_signal'] == 0) { ?>

            <!-- BOUTON SIGNALER UN COMMENTAIRE -->

            <form action="index.php?action=signalComment" method="post">
                <input type="hidden" name="postId" value="<?= $dataPost['id']; ?>" />
                <input type="hidden" name="signal_commentId" value="<?= 1 ?>" />
                <input type="hidden" name="signal_comment" value="<?= $comment['id'] ?>" />
                <button class="btn btn-warning btn-sm" type="submit" name="messageSignal"><i class="fas fa-exclamation-circle"></i> Signaler</button>
            </form>
            <?php } ?>

        </div>

        <!-- TEXTAREA POUR MODIFIER COMMENTAIRE -->

        <div class="collapse col-12" id="modifComment<?= $comment['id'] ?>">
            <form name="getCommentModif<?= $comment['id'] ?>" action="index.php?action=modifComment" method="post">
                <input type="hidden" name="postId" value="<?php echo $dataPost['id']; ?>" />
                <input type="hidden" name="modifCommentId" value="<?= $comment['id'] ?>" />
                <p>
                    <textarea id="modifComment<?= $comment['id'] ?>" class="alert alert-info col-12" name="modifComment" rows="8">
                </textarea>
                </p>
                <button class="btn btn-success btn-sm col-2 offset-10" type="submit" name="messageSignal"><i class="fas fa-share-square"></i> Publier</button>
                <p></p>
            </form>
        </div>

        <?php     
        }
        ?>

    </div>


    <!-- BOUTON ET TEXTAREA POUR AJOUTER UN COMMENTAIRE -->

    <p>
        <a class="btn btn-primary col-5 offset-6" data-toggle="collapse" href="#newComment" role="button" aria-expanded="false" aria-controls="newComment"><i class="far fa-comment-alt"></i> Ajouter un commentaire
        </a>
    </p>
    <div class="collapse col-12" id="newComment">
        <form id="newComment" action="index.php?action=addComment" method="post">
            <input type="hidden" name="postId" value="<?php echo $dataPost['id']; ?>" />
            <p>
                <textarea id="newComment" class="alert alert-info col-12" name="nv_comment" rows="8">
            </textarea>
            </p>
            <button class="btn btn-success btn-sm col-2 offset-10" type="submit"><i class="fas fa-share-square"></i> Publier</button>
        </form>
    </div>


    <?php $all2 = ob_get_clean(); ?>
    <?php ob_start();?>

    <!-- MODIFIER UN POST -->

    <div class="container-fluid post-view white">

        <h3 class="posts-title green">
            Modifiez et mettez en forme ce billet ici :
        </h3>

        <form method="post" action="index.php?action=postModif">
            <input type="hidden" name="postId" value="<?php echo $dataPost['id']; ?>" />
            <label>Titre du nouveau billet : </label>
            <input type="text" name="titre" class="col-7 news-title" value="<?= $dataPost['chapter_title'] ?>" /><br>
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

            <div id="modifPostInForm" class="black news" contentEditable>
                <?= $dataPost['chapter_content'] ?>
            </div>
            <textarea id="modifPostPlainText" name="modifPostPlainText"></textarea>
            <textarea id="modifPostHTML" name="modifPostHTML"></textarea>
            <p></p>
            <button type="button submit" class="btn btn-outline-light btn-sm offset-10" onclick="getModifPostInForm();"><i class="fas fa-share-square"></i> Modifier votre billet</button>
        </form>

        <!-- SUPPRIMER UN POST -->

        <h3 class="posts-title green">
            Pour supprimer ce billet, cliquez ici :
        </h3>
        <form name="delete" class="text-center">
            <input type="hidden" name="deletePost" value="<?php echo $dataPost['id']; ?>" />
            <a href="#" class="delete" onClick="var postId = document.forms.delete.deletePost.value;
        function valid_confirm(postId) {
            if (confirm('Voulez-vous vraiment supprimer ce billet et ses commentaires ?')) {
                var url = 'index.php?action=postDelete&postId=' + postId;
                document.location.href = url;
                return true;
            } else {
                alert('Je me disais aussi...');
                return false;
            }
        }
        valid_confirm(postId);"><i class="fas fa-trash-alt"></i> Effacer ce billet </a>
        </form>
    </div>

</div>

<?php $backend = ob_get_clean(); ?>

<?php require('view/frontend/template.php'); ?>
