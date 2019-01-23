<?php session_start();
require('model.php');
$title = 'Commentaire';
$template = 'backend';
ob_start(); ?>

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

<div class="news">
    <h3>
        <?php
            $post = getPost($_GET['billet']);
        ?>
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

<h2>commentaires</h2>

<!-- RAJOUT D'UN comment A LA SUITE : -->

<?php
    if(isset($_POST['nv_comment'])) {
        $allowComment = permitComments();
        if($allowComment['block_comment'] == 1) {
            echo '<p class="alert">Désolé vous n\'êtes pas autorisé à poster des comments</p>';
        } else {
            addComment($_GET['billet'], $_SESSION['pseudo'], $_POST['nv_comment']);     
            echo '<p class="success">' . 'Votre commentaire a bien été publié ci-dessous' . '</p>';
        }
    }
    if(isset($_POST['delete_comment'])) { 
        deleteComment($_POST['delete_comment']);  
        echo '<p class="success">' . 'Le comment '. $_POST['delete_comment'] . ' a bien été Supprimé !' . '</p>';
    }
?>

<?php
    $comments = getComments($_GET['billet']);
?>
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

<!-- Bouton de Suppression du comment -->

<form method="post" action="backend_comment_billet_admin.php?billet=<?php echo $_GET['billet'] ?>">
    <input type="hidden" name="delete_comment" value="<?php echo $comment['id'] ?>" />
    <input type="submit" value="Supprimer ce message" />
</form>

<p>.......................................................................................</p>
<?php
    }
?>



<h3>Ajouter un comment :</h3>
<form method="post" action="backend_comment_billet_admin.php?billet=<?php echo $_GET['billet']; ?>">
    <p>
        <label>Votre message :</label><br>
        <textarea name="nv_comment" rows="8" cols="45">
            </textarea>
    </p>
    <input type="submit" value="Envoyer votre comment" />
</form>

<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>
