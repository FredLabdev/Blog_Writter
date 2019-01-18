<?php session_start(); ?>
<?php $title = 'Commentaires'; ?>
<?php $template = 'backend'; ?>
<?php ob_start(); ?>

<?php
    session_start(); // On démarre la session AVANT toute chose
    try { 
            $db = new PDO('mysql:host=localhost;dbname=forteroche', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        }
        catch(Exception $e) {
            die('Erreur : '.$e->getMessage());
        }
    if (isset($_POST['nv_comment'])) {
            $req = $db->prepare('INSERT INTO comments(post_id, author, comment, date_comment) VALUES(:post_id, :author, :comment, NOW())');
            $req->execute(array(
                'post_id' => $_COOKIE['billet_select'],
                'author' => 'Jean Forteroche',
                'comment' => $_POST['nv_comment']
            ));
                echo 'Merci pour votre comment ' . htmlspecialchars($_SESSION['first_name']) . ' ' . htmlspecialchars($_SESSION['name']) .   '<br>';
                echo 'il sera publié sous votre nom :' . ' Jean Forteroche';
        $req->closeCursor(); // Termine le traitement de la requête INSERT INTO
        }
    if(isset($_POST['delete_comment'])) { 
                $req1 = $db->prepare('DELETE FROM comments WHERE id = :idnum');
                $req1->execute(array(
                    'idnum' => $_POST['delete_comment']
                ));  
                echo '<br>'.'Le comment '. $_POST['delete_comment'] . ' a bien été Supprimé !';
                $req1->closeCursor(); // Termine le traitement de la requête DELETE avant de passer au comment suivant
    }
    $req = $db->prepare('SELECT id, author, comment, DATE_FORMAT(date_comment, \'%d/%m/%Y à %Hh%imin%ss\') AS date_comment_fr FROM comments WHERE post_id = ? ORDER BY date_comment LIMIT 0, 5');
    $req->execute(array($_GET['billet'])); 
    $comments = $req->fetchAll();
?>

<br />
<p>=======================================================================================</p>
<!-- Confirm connect -->

<p>
    Nous sommes le :
    <?php echo date('d/m/Y') . '<br>';
        	if(isset($_SESSION['pseudo'])) {
            	echo ' Bonjour ' . $_SESSION['pseudo'] . $_SESSION['name'];
        	} else {
            	echo 'Erreur nom ou prénom visiteur';
        	}
        ?>
</p>

<p>=======================================================================================</p>
<!-- Affichage Billet sélectionné sur page d'accueil et les comments associés -->

<?php     // connexion à la base de données
        try { 
            $db = new PDO('mysql:host=localhost;dbname=forteroche', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        }
        catch(Exception $e) {
            die('Erreur : '.$e->getMessage());
        }
        do { 
            $req = $db->prepare('SELECT id, chapter_title, chapter_content, DATE_FORMAT(creation_date, \'%d/%m/%Y à %Hh%imin%ss\') AS creation_date_fr FROM posts WHERE id = ?'); // récupération du billet séléctionné sur la page accueil_frontend
            $req->execute(array($_COOKIE['billet_select'])); // grâce à son id envoyée en paramètre via le lien URL 
            $data = $req->fetch();
            if ($_COOKIE['billet_select'] AND !$data) {
            echo '<p class="alert">' . 'Ce billet n\'existe pas !' . '</p>'; // message d'alerte si billet n'existe pas
            }
        } while ($_POST['rafraichir']);  // on recommence à chaque click sur bouton "Rafraîchir")
    ?>

<div class="news">
    <h3>
        <?php echo htmlspecialchars($data['chapter_title']); ?>
        <em> publié le
            <?php echo $data['creation_date_fr']; ?>
        </em>
    </h3>
    <p>
        <?php echo nl2br(htmlspecialchars($data['chapter_content'])); ?>
    </p>
</div>
<h2>comments</h2>

<form method="post" action="backend_comment_billet_admin.php?billet=<?php echo $_GET['billet'] ?>">
    <input name="rafraichir" type="hidden" />
    <input type="submit" value="Rafraîchir les comments" /> <!-- Bouton "Rafraichir qui reactualise les comments si nouveau -->
</form>

<?php
        $req->closeCursor(); // Termine le traitement de la requête
        foreach ($comments as $data) {
    ?>
<!-- BOUCLE POUR CHAQUE comment TROUVE : -->

<!-- Détail du comment -->
<p>le
    <?php echo $data['date_comment_fr'] . ' '; ?>
    <strong>
        <?php echo htmlspecialchars($data['author']); ?>
    </strong>
    à écrit
</p>
<p style="font-style: italic;">
    <?php echo nl2br(htmlspecialchars($data['comment'])); ?>
</p>

<!-- Bouton de Suppression du comment -->

<form method="post" action="backend_comment_billet_admin.php?billet=<?php echo $_GET['billet'] ?>">
    <input type="hidden" name="delete_comment" value="<?php echo $data['id'] ?>" />
    <input type="submit" value="Supprimer ce message" />
</form>

<p>.......................................................................................</p>

<?php   // Requête DELETE de Suppression du comment en fonction de son numéro d'id
        }
     ?>

<!-- RAJOUT D'UN comment A LA SUITE : -->

<h3>Ajouter un comment :</h3>
<form method="post" action="backend_comment_billet_admin.php?billet=<?php echo $_GET['billet'] ?>">
    <p>
        <label>Votre message :</label><br>
        <textarea name="nv_comment" rows="8" cols="45">
            </textarea>
    </p>
    <input name="billet_id" type="hidden" />
    <input type="submit" value="Envoyer votre comment" />
</form>

<?php   // Contrôle si contact autorisé à commenter
    
        $req1 = $db->prepare('SELECT block_comment FROM contacts WHERE pseudo = ?');
        $req1->execute(array($_SESSION['pseudo']));
        $data1 = $req1->fetch();
        if ($data1['block_comment'] == 1) {
            echo '<p class="alert">Désolé vous n\'êtes pas autorisé à poster des comments</p>';
            
            // Si contact autorisé insertion de son nouveau comment
            
        }
    ?>

<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>
