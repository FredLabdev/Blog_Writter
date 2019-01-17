<?php
    session_start(); // On démarre la session AVANT toute chose
    try { 
            $db = new PDO('mysql:host=localhost;dbname=forteroche', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        }
        catch(Exception $e) {
            die('Erreur : '.$e->getMessage());
        }
    if (isset($_POST['nv_commentaire'])) {
            $req = $db->prepare('INSERT INTO commentaires(id_billet, auteur, commentaire, date_commentaire) VALUES(:id_billet, :auteur, :commentaire, NOW())');
            $req->execute(array(
                'id_billet' => $_COOKIE['billet_select'],
                'auteur' => 'Jean Forteroche',
                'commentaire' => $_POST['nv_commentaire']
            ));
                echo 'Merci pour votre commentaire ' . htmlspecialchars($_SESSION['prenom']) . ' ' . htmlspecialchars($_SESSION['nom']) .   '<br>';
                echo 'il sera publié sous votre nom :' . ' Jean Forteroche';
        $req->closeCursor(); // Termine le traitement de la requête INSERT INTO
        }
    if(isset($_POST['delete_comment'])) { 
                $req1 = $db->prepare('DELETE FROM commentaires WHERE id = :idnum');
                $req1->execute(array(
                    'idnum' => $_POST['delete_comment']
                ));  
                echo '<br>'.'Le commentaire '. $_POST['delete_comment'] . ' a bien été Supprimé !';
                $req1->closeCursor(); // Termine le traitement de la requête DELETE avant de passer au commentaire suivant
    }
    $req = $db->prepare('SELECT id, auteur, commentaire, DATE_FORMAT(date_commentaire, \'%d/%m/%Y à %Hh%imin%ss\') AS date_commentaire_fr FROM commentaires WHERE id_billet = ? ORDER BY date_commentaire LIMIT 0, 5');
    $req->execute(array($_GET['billet'])); 
    $commentaires = $req->fetchAll();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Jean Forteroche</title>
    <!-- Feuille de style css et Bibliothèque d'icones FontAwesome -->
    <link rel="stylesheet" href="backend_style.css" />
</head>

<body>

    <!-- Header -->

    <?php include("forteroche_header.php"); ?>

    <br />
    <p>=======================================================================================</p>
    <!-- Menu -->

    <?php include("forteroche_menu_admin.php"); ?>

    <br />
    <p>=======================================================================================</p>
    <!-- Confirm connect -->

    <p>
        Nous sommes le :
        <?php echo date('d/m/Y') . '<br>';
        	if(isset($_SESSION['pseudo'])) {
            	echo ' Bonjour ' . $_SESSION['pseudo'] . $_SESSION['nom'];
        	} else {
            	echo 'Erreur nom ou prénom visiteur';
        	}
        ?>
    </p>

    <p>=======================================================================================</p>
    <!-- Affichage Billet sélectionné sur page d'accueil et les commentaires associés -->

    <?php     // connexion à la base de données
        try { 
            $db = new PDO('mysql:host=localhost;dbname=forteroche', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        }
        catch(Exception $e) {
            die('Erreur : '.$e->getMessage());
        }
        do { 
            $req = $db->prepare('SELECT id, titre_episode, contenu_episode, DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%imin%ss\') AS date_creation_fr FROM billets WHERE id = ?'); // récupération du billet séléctionné sur la page accueil_frontend
            $req->execute(array($_COOKIE['billet_select'])); // grâce à son id envoyée en paramètre via le lien URL 
            $data = $req->fetch();
            if ($_COOKIE['billet_select'] AND !$data) {
            echo '<p class="alert">' . 'Ce billet n\'existe pas !' . '</p>'; // message d'alerte si billet n'existe pas
            }
        } while ($_POST['rafraichir']);  // on recommence à chaque click sur bouton "Rafraîchir")
    ?>

    <div class="news">
        <h3>
            <?php echo htmlspecialchars($data['titre_episode']); ?>
            <em> publié le
                <?php echo $data['date_creation_fr']; ?>
            </em>
        </h3>
        <p>
            <?php echo nl2br(htmlspecialchars($data['contenu_episode'])); ?>
        </p>
    </div>
    <h2>Commentaires</h2>

    <form method="post" action="backend_comment_billet_admin.php?billet=<?php echo $_GET['billet'] ?>">
        <input name="rafraichir" type="hidden" />
        <input type="submit" value="Rafraîchir les commentaires" /> <!-- Bouton "Rafraichir qui reactualise les commentaires si nouveau -->
    </form>

    <?php
        $req->closeCursor(); // Termine le traitement de la requête
        foreach ($commentaires as $data) {
    ?>
    <!-- BOUCLE POUR CHAQUE COMMENTAIRE TROUVE : -->

    <!-- Détail du commentaire -->
    <p>le
        <?php echo $data['date_commentaire_fr'] . ' '; ?>
        <strong>
            <?php echo htmlspecialchars($data['auteur']); ?>
        </strong>
        à écrit
    </p>
    <p style="font-style: italic;">
        <?php echo nl2br(htmlspecialchars($data['commentaire'])); ?>
    </p>

    <!-- Bouton de Suppression du commentaire -->

    <form method="post" action="backend_comment_billet_admin.php?billet=<?php echo $_GET['billet'] ?>">
        <input type="hidden" name="delete_comment" value="<?php echo $data['id'] ?>" />
        <input type="submit" value="Supprimer ce message" />
    </form>

    <p>.......................................................................................</p>

    <?php   // Requête DELETE de Suppression du commentaire en fonction de son numéro d'id
        }
     ?>

    <!-- RAJOUT D'UN COMMENTAIRE A LA SUITE : -->

    <h3>Ajouter un commentaire :</h3>
    <form method="post" action="backend_comment_billet_admin.php?billet=<?php echo $_GET['billet'] ?>">
        <p>
            <label>Votre message :</label><br>
            <textarea name="nv_commentaire" rows="8" cols="45">
            </textarea>
        </p>
        <input name="billet_id" type="hidden" />
        <input type="submit" value="Envoyer votre commentaire" />
    </form>

    <?php   // Contrôle si contact autorisé à commenter
    
        $req1 = $db->prepare('SELECT bloq_comment FROM contacts WHERE pseudo = ?');
        $req1->execute(array($_SESSION['pseudo']));
        $data1 = $req1->fetch();
        if ($data1['bloq_comment'] == 1) {
            echo '<p class="alert">Désolé vous n\'êtes pas autorisé à poster des commentaires</p>';
            
            // Si contact autorisé insertion de son nouveau commentaire
            
        }
    ?>

    <!-- Footer -->
    <br />
    <p>=======================================================================================</p>
    <?php include("forteroche_footer.php"); ?>

</body>

</html>
