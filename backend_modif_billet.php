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
        Voir les billets :
    </h3>

    <form method="post" action="backend_modif_billet.php">
        <label>Sélectionnez un billet : </label><select name="billet">
            <?php 
                try { // connexion à la base de données
                    $bdd = new PDO('mysql:host=localhost;dbname=forteroche', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
                }
                catch(Exception $e) {
                    die('Erreur : '.$e->getMessage());
                }
                $reponse = $bdd->query('SELECT * FROM billets');
                while ($donnees = $reponse->fetch()) { // Liste déroulante des titres des billets
                echo '<option value="' . $donnees['id'] . '">' . $donnees['titre_episode'] . '</option>';
                }
                $reponse->closeCursor(); // Termine le traitement de la requête
            ?>
        </select>
        <input type="submit" value="valider" name="valider" /><br>

        <?php // Détail du billet sélectionné //
            if (isset($_POST['billet']) AND isset($_POST['valider'])) {
                $req = $bdd->prepare('SELECT * FROM billets WHERE id = ?');
                $req->execute(array($_POST['billet']));
                while ($donnees = $req->fetch()) {
                    echo 'Date de publication : ' . $donnees['date'] . '<br>';
                    echo 'Titre de l\'épisode : ' . $donnees['titre_episode'] . '<br>';
                    echo 'Contenu de l\'épisode : ' . $donnees['contenu_episode'] . '<br>';  
                }
                $req->closeCursor(); // Termine le traitement de la requête
            }
        ?>
    </form>

    <br />
    <p>===========================================================</p>

    <!-- Modification du champs d'un billet -->

    <h3>
        Modifier le champ d'un billet :
    </h3>

    <form method="post" action="backend_modif_billet.php">
        <label>Sélectionnez un billet : </label><select name="billet-modif">
            <?php
                $reponse = $bdd->query('SELECT * FROM billets');
                while ($donnees = $reponse->fetch()) { // Liste déroulante des titres des billets
                echo '<option value="' . $donnees['id'] . '">' . $donnees['titre_episode'] . '</option>';
                }
                $reponse->closeCursor(); // Termine le traitement de la requête
            ?>
        </select><br>
        <label>Supprimer tout le billet ?</label><input type="checkbox" name="delete" /><br>
        <label>Sinon sélectionnez le champ à modifier : </label><select name="champ">
            <option value="1">Titre de l'épisode</option>
            <option value="2">Contenu de l'épisode</option>
        </select><br>
        <label>Nouveau contenu du champ : </label><input type="text" name="modif_champ" />
        <input type="submit" value="Appliquer" name="remplacer" />

        <?php  // Modification du billet
            if(isset($_POST['champ']) AND isset($_POST['delete'])) { // si tout le billet à supprimer
                $req = $bdd->prepare('DELETE FROM billets WHERE id = :idnum');
                    $req->execute(array(
                        'idnum' => $_POST['billet-modif']
                    ));  
                    echo '<br>'.'L\'épisode a bien été Supprimé !';
                    $req->closeCursor(); // Termine le traitement de la requête
            } else if(isset($_POST['champ']) AND isset($_POST['billet-modif']) AND isset($_POST['modif_champ']) AND isset($_POST['remplacer'])) {
                if ($_POST['champ'] == 1) { // si titre à modifier
                    $req = $bdd->prepare('UPDATE billets SET titre_episode = :nvtitre WHERE id = :idnum');
                    $req->execute(array(
                        'nvtitre' => $_POST['modif_champ'],
                        'idnum' => $_POST['billet-modif']
                    ));  
                    echo '<br>'.'Le titre de l\'épisode a bien été modifié !';
                    $req->closeCursor(); // Termine le traitement de la requête
                } else if ($_POST['champ'] == 2) { // si contenu à modifier
                    $req = $bdd->prepare('UPDATE billets SET contenu_episode = :nvcontenu WHERE id = :idnum');
                    $req->execute(array(
                        'nvcontenu' => $_POST['modif_champ'],
                        'idnum' => $_POST['billet-modif']
                    ));  
                    echo '<br>'.'Le contenu de l\'épisode a bien été modifié !';
                    $req->closeCursor(); // Termine le traitement de la requête
                }
            };
        ?>
    </form>


    <!-- Footer -->
    <br />
    <p>===========================================================</p>
    <?php include("forteroche_footer.php"); ?>

</body>

</html>
