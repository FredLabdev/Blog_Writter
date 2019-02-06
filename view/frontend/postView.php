<?php 
    session_start();
    $title = 'Commentaire';
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
    <?php echo $message_success; ?>
</p>
<p class="alert">
    <?php echo $message_error; ?>
</p>
<!-- Détail du billet -->

<div class="news">
    <h3>
        <?php 
            if($postDatas) {
                echo htmlspecialchars($postDatas['chapter_title']); 

        ?>
        <em> publié le
            <?php 
                echo $postDatas['creation_date_fr']; 
            ?>
        </em>
    </h3>
    <p>
        <?php 
                echo nl2br(htmlspecialchars($postDatas['chapter_content'])); 
            }
            ?>
    </p>
</div>

<!-- Modifier ce billet -->

<h3>
    Modifier ce billet :
</h3>
<form method="post" action="index.php?action=postModif">
    <input type="hidden" name="postId" value="<?php echo $postDatas['id']; ?>" />
    <label>Sinon sélectionnez le champ à modifier : </label><select name="champ">
        <option value=""></option>
        <option value="1">Titre de l'épisode</option>
        <option value="2">Contenu de l'épisode</option>
    </select><br>
    <label>Nouveau contenu du champ : </label>
    <textarea name="modif_champ" rows="8" cols="45"></textarea>
    <input type="submit" value="Valider" name="remplacer" />
</form>

<br />
<p>===========================================================</p>
<h3>
    Pour supprimer ce billet, cliquez ici :
</h3>
<form name="delete">
    <input type="hidden" name="deletePost" value="<?php echo $postDatas['id']; ?>" />
    <a href="#" onClick="var postId = document.forms.delete.deletePost.value;
        function valid_confirm(postId) {
            if (confirm('Voulez-vous vraiment supprimer ce billet et ces commentaires ?')) {
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

<!-- Liste des commentaires -->

<h2>commentaires</h2>

<?php
    while ($comment = $comments->fetch()) {
?>
<p>le
    <?php echo $comment['comment_date_fr'] . ' '; ?>
    <strong>
        <?php echo htmlspecialchars($comment['author']); ?>
    </strong>
    à écrit
</p>
<p style="font-style: italic;">
    <?php echo nl2br(htmlspecialchars($comment['comment'])); ?>
</p>

<!-- Bouton de Suppression pour un commentaire (uniquement si admin, moderateur ou poste par sois-meme)-->

<?php
        if ($_SESSION['group_id'] == 1 || $_SESSION['group_id'] == 2 || $comment['author'] == $_SESSION['pseudo']) {
    ?>
<form action="index.php?action=deleteComment&amp;billet=<?php echo $_GET['billet']; ?>" method="post">
    <input type="hidden" name="delete_comment" value="<?php echo $comment['id'] ?>" />
    <input type="submit" value="Supprimer ce message" />
</form>
<?php
        }
    ?>

<p>.......................................................................................</p>

<?php
    }
?>

<!-- Ajout d'un commentaire -->

<h3>Ajouter un comment :</h3>
<form action="index.php?action=addComment&amp;billet=<?php echo $_GET['billet']; ?>" method="post">
    <p>
        <label>Votre message :</label><br>
        <textarea name="nv_comment" rows="8" cols="45">
            </textarea>
    </p>
    <input type="submit" value="Envoyer votre comment" />
</form>

<?php $content = ob_get_clean(); ?>

<?php require('view/frontend/template.php'); ?>
