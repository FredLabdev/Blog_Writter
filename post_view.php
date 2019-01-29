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

<p>
    Nous sommes le :
    <?php echo date('d/m/Y') . '<br>';
        	if(isset($_SESSION['pseudo'])) {
            	echo ' Bonjour ' . $_SESSION['first_name'];
        	} else {
            	echo 'Erreur nom ou prénom visiteur';
        	}
        ?>
</p>

<p>=======================================================================================</p>

<!-- Détail du billet -->

<div class="news">
    <h3>
        <?php 
            if(!$post) {
                echo '<p class="alert">' . 'Ce billet n\'existe pas !' . '</p>';
            } else {
                echo htmlspecialchars($post['chapter_title']); 
        ?>
        <em> publié le
            <?php 
                echo $post['creation_date_fr']; 
            ?>
        </em>
    </h3>
    <p>
        <?php 
                echo nl2br(htmlspecialchars($post['chapter_content'])); 
            }
            ?>
    </p>
</div>

<!-- Messages d'action sur les commentaires -->

<?php
    if ($commentError) {
        echo $commentError;
    } else if ($commentSuccess) {
        echo $commentSuccess;
    } else if ($commentErase) {
        echo $commentErase;
    }
?>

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

<?php require('template.php'); ?>
