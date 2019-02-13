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

<br />
<p>===========================================================</p>
<!-- Confirm connect -->
<h3>
    Détail d'un billet
</h3>
<p>=======================================================================================</p>
<p class="success">
    <?= $message_success; ?>
</p>
<p class="alert">
    <?= $message_error; ?>
</p>
<!-- Détail du billet -->

<div class="news">
    <h3>
        <?php 
            foreach($postDetails as $dataPost) { // Détail du member sélectionné
            echo $dataPost['chapter_title'];  
        ?>
        <em> publié le
            <?php 
            echo $dataPost['creation_date_fr']; 
        ?>
        </em>
    </h3>

    <input id="postContentHTML" value="<?= $dataPost['chapter_content']; ?>" type="hidden" />
    <p id="postInForm"></p>

    <?php 
        }
        ?>

</div>

<?php $all1 = ob_get_clean(); ?>
<?php ob_start();?>
<!-- Modifier ce billet -->

<h3>
    Modifiez et mettez en forme ce billet ici :
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
<div id="modifPostInForm" contentEditable></div>

<form method="post" action="index.php?action=postModif">
    <input type="hidden" name="postId" value="<?php echo $dataPost['id']; ?>" />
    <label>Sinon sélectionnez le champ à modifier : </label><select name="champ">
        <option value=""></option>
        <option value="1">Titre de l'épisode</option>
        <option value="2">Contenu de l'épisode</option>
    </select>
    <textarea id="modifPostPlainText" name="modifPostPlainText"></textarea>
    <textarea id="modifPostHTML" name="modifPostHTML"></textarea>

    <input type="submit" value="Valider" name="remplacer" onclick="getModifPostInForm();" />
</form>

<br />
<p>===========================================================</p>
<h3>
    Pour supprimer ce billet, cliquez ici :
</h3>
<form name="delete">
    <input type="hidden" name="deletePost" value="<?php echo $dataPost['id']; ?>" />
    <a href="#" onClick="var postId = document.forms.delete.deletePost.value;
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
        valid_confirm(postId);"> Effacer ce billet </a>
</form>

<?php $backend = ob_get_clean(); ?>
<?php ob_start();?>
<!-- Liste des commentaires -->

<h2>commentaires</h2>

<?php
    while ($comment = $comments->fetch()) {
?>
<p>le
    <?= $comment['comment_date_fr'] . ' '; ?>
    <strong>
        <?= htmlspecialchars($comment['author']); ?>
    </strong>
    à écrit
</p>
<p style="font-style: italic;">
    <?= nl2br(htmlspecialchars($comment['comment'])); ?>
</p>

<!-- Bouton de Suppression pour un commentaire (uniquement si admin, modérateur, ou poste par sois-meme)-->
<?php
        if ($_SESSION['group_id'] == 1 || $_SESSION['group_id'] == 2 || $comment['author'] == $_SESSION['pseudo']) {
    ?>
<form action="index.php?action=deleteComment&amp;billet=<?= $_GET['billet']; ?>" method="post">
    <input type="hidden" name="delete_comment" value="<?= $comment['id'] ?>" />
    <input type="submit" value="Supprimer" />
</form>

<!-- Bouton de Signalement d'un commentaire comme indésirable (uniquement si modérateur ou admin)-->
<?php
        } if ($_SESSION['group_id'] == 1 || $_SESSION['group_id'] == 2) {
    ?>
<form action="index.php?action=signalComment&amp;billet=<?= $_GET['billet']; ?>" method="post">
    <input type="hidden" name="signal_commentId" value="<?php if($comment['comment_signal'] == 0){echo '1';}else{echo '0';};?>" />
    <input type="hidden" name="signal_comment" value="<?= $comment['id'] ?>" />
    <input type="submit" name="messageSignal" value="Signaler" />
</form>

<!-- Bouton de Modification d'un commentaire (uniquement si sois-meme)-->
<?php
        } if ($comment['author'] == $_SESSION['pseudo']) {
    ?>
<form name="getCommentModif<?= $comment['id'] ?>" action="index.php?action=modifComment&amp;billet=<?= $_GET['billet']; ?>" method="post">
    <input type="hidden" name="modifCommentId" value="<?= $comment['id'] ?>" />
    <a href="#<?= $comment['id'] ?>" class="button" onclick="
    function getModifComment() {
        var getModifComment = document.getElementById('modifComment<?= $comment['id'] ?>');
        var modifCommentSubmit = document.getElementById('modifCommentSubmit<?= $comment['id'] ?>');
        if (getModifComment.className == 'hidden') {
            getModifComment.className = 'appear';   
            modifCommentSubmit.className = 'appear';                                    
        } else {
            getModifComment.className = 'hidden';  
            modifCommentSubmit.className = 'hidden';        
        }
    }
    getModifComment();"> Modifier</a>
    <p id=modifComment<?=$comment['id'] ?> class="hidden">
        <label>Votre nouveau commentaire :</label><br>
        <textarea name="modifComment" rows="8" cols="45">
            </textarea>
    </p>
    <input id=modifCommentSubmit<?=$comment['id'] ?> class="hidden" type="submit" value="Valider ce commenatire" />
</form>
<?php
    }

}
?>
<p>===========================================================</p>
<!-- Ajout d'un commentaire -->

<a href="#" class="button" onclick="
    getNewComment();"> Ajouter un commentaire </a>
<form id="newComment" class="hidden" action="index.php?action=addComment&amp;billet=<?= $_GET['billet']; ?>" method="post">
    <p>
        <label>Votre commentaire :</label><br>
        <textarea name="nv_comment" rows="8" cols="45">
            </textarea>
    </p>
    <input type="submit" value="Envoyer votre comment" />
</form>

<?php $all2 = ob_get_clean(); ?>

<?php require('view/frontend/template.php'); ?>
