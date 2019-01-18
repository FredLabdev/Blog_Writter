<?php session_start(); ?>
<?php $title='nv_billet' ; ?>
<?php $template = 'backend'; ?>
<?php ob_start(); ?>

<br />
<p>===========================================================</p>
<!-- Confirm connect -->

<h3>
    Bienvenue sur l' administration de votre blog !
</h3>

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

<br />
<p>===========================================================</p>
<!-- Liste des posts -->

<h3>
    Liste des posts :
</h3>

<form method="post" action="backend_insert_billet.php">
    <input name="rafraichir" type="hidden" />
    <input type="submit" value="rafraîchir" />
</form>

<ul>
    <?php
            try { // connexion à la base de données 
                $db = new PDO('mysql:host=localhost;dbname=forteroche', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            }
            catch(Exception $e) {
                die('Erreur : '.$e->getMessage());
            }
            do { // on édite la liste des posts au moins une fois sans cliquer sur bouton "Rafraîchir"
                $req = $db->query('SELECT COUNT(*) AS nbre_posts FROM posts');
                $data = $req->fetch(); // 
                echo 'Nombre de posts publiés à ce jour: ' . $data['nbre_posts'];
                $req = $db->query('SELECT * FROM posts');
                while ($data = $req->fetch()) {
                echo '<li style="color: red;">' . $data['id'] . ' : ' . $data['chapter_title'] . '</li>';
                }
                $req->closeCursor(); // Termine le traitement de la requête
            } while ($_POST['rafraichir']);  // on recommence à chaque click sur bouton "Rafraîchir"
        ?>
</ul>

<br />
<p>===========================================================</p>

<!-- Ajouter un billet -->

<h3>
    Ajouter un nouveau billet :
</h3>

<form method="post" action="backend_insert_billet.php">
    <p>
        <label>Titre du billet : <input type="text" name="titre" /></label><br>
        <label>Contenu du billet : <input type="text" name="contenu" /></label><br>
    </p>
    <input type="submit" value="envoyer" /><br>

    <?php
            try {  // connexion à la base de données
                $db = new PDO('mysql:host=localhost;dbname=forteroche', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            }
            catch(Exception $e) {
                die('Erreur : '.$e->getMessage());
            }
            if(isset($_POST['titre']) AND isset($_POST['contenu'])) { // insertion nouveau billet
                $req = $db->prepare('INSERT INTO posts(creation_date, chapter_title, chapter_content) VALUES(NOW(), :titre, :contenu)');
                $req->execute(array('titre' => $_POST['titre'], 'contenu' => $_POST['contenu']));
                echo 'Le nouvel épisode ' . $_POST['titre'] . ' a bien été créé !';
                $req->closeCursor(); // Termine le traitement de la requête
            }  
        ?>
</form>

<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>
