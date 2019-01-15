<?php
session_start(); // On démarre la session AVANT toute chose
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
    <p>===========================================================</p>
    <!-- Menu -->

    <?php include("forteroche_menu_admin.php"); ?>

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
            	echo ' Bonjour ' . $_SESSION['prenom'];
        	} else {
            	echo 'Erreur nom ou prénom visiteur';
        	}
        ?>
    </p>

    <br />
    <p>===========================================================</p>
    <!-- Liste des billets -->

    <h3>
        Liste des billets :
    </h3>

    <form method="post" action="backend_insert_billet.php">
        <input name="rafraichir" type="hidden" />
        <input type="submit" value="rafraîchir" />
    </form>

    <ul>
        <?php
            try { // connexion à la base de données 
                $bdd = new PDO('mysql:host=localhost;dbname=forteroche', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            }
            catch(Exception $e) {
                die('Erreur : '.$e->getMessage());
            }
            do { // on édite la liste des billets au moins une fois sans cliquer sur bouton "Rafraîchir"
                $reponse = $bdd->query('SELECT COUNT(*) AS nbre_billets FROM billets');
                $donnees = $reponse->fetch(); // 
                echo 'Nombre de billets publiés à ce jour: ' . $donnees['nbre_billets'];
                $reponse = $bdd->query('SELECT * FROM billets');
                while ($donnees = $reponse->fetch()) {
                echo '<li style="color: red;">' . $donnees['id'] . ' : ' . $donnees['titre_episode'] . '</li>';
                }
                $reponse->closeCursor(); // Termine le traitement de la requête
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
                $bdd = new PDO('mysql:host=localhost;dbname=forteroche', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            }
            catch(Exception $e) {
                die('Erreur : '.$e->getMessage());
            }
            if(isset($_POST['titre']) AND isset($_POST['contenu'])) { // insertion nouveau billet
                $req = $bdd->prepare('INSERT INTO billets(date_creation, titre_episode, contenu_episode) VALUES(NOW(), :titre, :contenu)');
                $req->execute(array('titre' => $_POST['titre'], 'contenu' => $_POST['contenu']));
                echo 'Le nouvel épisode ' . $_POST['titre'] . ' a bien été créé !';
                $req->closeCursor(); // Termine le traitement de la requête
            }  
        ?>
    </form>



    <!-- Footer -->
    <br />
    <p>===========================================================</p>
    <?php include("forteroche_footer.php"); ?>

</body>

</html>
