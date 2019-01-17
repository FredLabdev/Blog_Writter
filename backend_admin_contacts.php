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
        Bienvenue sur l' administration de vos contacts !
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

    <!-- Liste des contacts par catégorie -->

    <h3>
        Liste des contacts classés par catégorie :
    </h3>

    <?php   // connexion à la base de données
        try {  
            $db = new PDO('mysql:host=localhost;dbname=forteroche', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        }
        catch(Exception $e) {
            die('Erreur : '.$e->getMessage());
        }
            // Nombre totale de contacts
    
        $reponse = $db->query('SELECT COUNT(*) AS nbre_contacts FROM contacts');
        $data = $reponse->fetch();
        echo '<p>Nombre d\'abonnés à votre blog à ce jour: ' . $data['nbre_contacts'] . '</p>';
        $reponse->closeCursor(); // Termine le traitement de la requête
    
            // Classement par groupe puis nom
    
        $reponse = $db->query('SELECT c.nom AS nom_contact, c.prenom AS prenom_contact, g.categorie AS categorie_groupe FROM groupes AS g INNER JOIN contacts AS c ON c.id_groupe = g.id ORDER BY id_groupe, nom');
        while ($data = $reponse->fetch()) {
        echo $data['categorie_groupe'] . ' : ' . $data['nom_contact'] . ' ' . $data['prenom_contact'] . '<br>';
        }
        $reponse->closeCursor(); // Termine le traitement de la requête
    ?>

    <br />
    <p>===========================================================</p>

    <!-- Voir les champs d'un contact -->

    <h3>
        Voir les champs d'un contact :
    </h3>

    <form method="post" action="backend_admin_contacts.php">
        <label>Sélectionnez un contact : </label><select name="contact">
            <?php
                $reponse = $db->query('SELECT *, UPPER(nom) AS nom_maj, LOWER(prenom) AS prenom_min FROM contacts');
                while ($data = $reponse->fetch()) { // liste déroulante des contacts
                echo '<option value="' . $data['id'] . '">' . $data['nom_maj'] . ' ' . $data['prenom_min'] . '</option>';
                }
                $reponse->closeCursor(); // Termine le traitement de la requête
            ?>
        </select>
        <input type="submit" value="valider" name="valider" /><br>

        <?php
            if (isset($_POST['contact']) AND isset($_POST['valider'])) {
                $req = $db->prepare('SELECT * FROM contacts WHERE id = ?');
                $req->execute(array($_POST['contact']));
                while ($data = $req->fetch()) {  // Détail du contact sélectionné
                    echo 'Date de création : ' . $data['date_creation'] . '<br>';
                    echo 'Nom : ' . $data['nom'] . '<br>';
                    echo 'Prénom : ' . $data['prenom'] . '<br>';  
                    echo 'Pseudo : ' . $data['pseudo'] . '<br>';  
                    echo 'Mail : ' . $data['email'] . '<br>';  
                    echo 'Mot de passe : ' . $data['password'] . '<br>';  
                }
                $req->closeCursor(); // Termine le traitement de la requête
            }
        ?>
    </form>

    <br />
    <p>===========================================================</p>

    <!-- Modification du champs d'un contact -->

    <h3>
        Modifier le champ d'un contact :
    </h3>

    <form method="post" action="backend_admin_contacts.php">
        <label>Sélectionnez un contact : </label><select name="contact-modif">
            <?php
                $reponse = $db->query('SELECT * FROM contacts');
                while ($data = $reponse->fetch()) { // liste déroulante des contacts
                echo '<option value="' . $data['id'] . '">' . $data['nom'] . '</option>';
                }
                $reponse->closeCursor(); // Termine le traitement de la requête
            ?>
        </select><br> <!-- Sélection du champ à modifier -->
        <label>Supprimer tout le contact ?</label><input type="checkbox" name="delete" /><br>
        <label>Bloquer ses commentaires</label><input type="checkbox" name="bloquage" /><br>
        <label>Sélectionnez le champ à modifier : </label><select name="champ">
            <option value="1">Nom</option>
            <option value="2">Prénom</option>
            <option value="3">Pseudo</option>
            <option value="4">e-mail</option>
            <option value="5">Mot de passe</option>
        </select><br>
        <label>Nouveau contenu du champ : </label><input type="text" name="modif_champ" />
        <input type="submit" value="Appliquer" name="remplacer" />

        <?php 
            if(isset($_POST['champ']) AND isset($_POST['delete'])) {  // si tout le contact à supprimer
                $req = $db->prepare('DELETE FROM contacts WHERE id = :idnum');
                $req->execute(array(
                    'idnum' => $_POST['contact-modif'] // fonction de l'id récupérée de la liste déroulante des contacts
                ));  
                echo '<br>'.'Le contact a bien été Supprimé !';
                $req->closeCursor(); // Termine le traitement de la requête
             // Sinon si modification d'un champ seulement    
            } else if(isset($_POST['bloquage']) AND isset($_POST['contact-modif']) AND isset($_POST['remplacer'])) {
                    $req = $db->prepare('UPDATE contacts SET bloq_comment = 1 WHERE id = :idnum');
                    $req->execute(array(
                        'idnum' => $_POST['contact-modif']
                    ));  
                    echo '<br>'.'Ce contact ne pourra plus poster de commentaires !';
                    $req->closeCursor(); // Termine le traitement de la requête
                } else if(isset($_POST['champ']) AND isset($_POST['contact-modif']) AND isset($_POST['modif_champ']) AND isset($_POST['remplacer'])) {
                if ($_POST['champ'] == 1) { // si modif nom
                    $req = $db->prepare('UPDATE contacts SET nom = :nvnom WHERE id = :idnum');
                    $req->execute(array(
                        'nvnom' => $_POST['modif_champ'],
                        'idnum' => $_POST['contact-modif']
                    ));  
                    echo '<br>'.'La modification du nom du contact a bien été enrégistrée !';
                    $req->closeCursor(); // Termine le traitement de la requête
                } else if ($_POST['champ'] == 2) { // si modif prénom
                    $req = $db->prepare('UPDATE contacts SET prenom = :nvprenom WHERE id = :idnum');
                    $req->execute(array(
                        'nvprenom' => $_POST['modif_champ'],
                        'idnum' => $_POST['contact-modif']
                    ));
                    echo '<br>'.'La modification du prénom du contact a bien été enrégistrée !';
                    $req->closeCursor(); // Termine le traitement de la requête
                } else if ($_POST['champ'] == 3) { // si modif pseudo
                    $req = $db->prepare('UPDATE contacts SET pseudo = :nvpseudo WHERE id = :idnum');
                    $req->execute(array(
                        'nvpseudo' => $_POST['modif_champ'],
                        'idnum' => $_POST['contact-modif']
                    ));
                    echo '<br>'.'La modification du pseudo du contact a bien été enrégistrée !';
                    $req->closeCursor(); // Termine le traitement de la requête
                } else if ($_POST['champ'] == 4) { // si modif email
                    $req = $db->prepare('UPDATE contacts SET email = :nvemail WHERE id = :idnum');
                    $req->execute(array(
                        'nvemail' => $_POST['modif_champ'],
                        'idnum' => $_POST['contact-modif']
                    ));
                    echo '<br>'.'La modification de l\'email du contact a bien été enrégistrée !';
                    $req->closeCursor(); // Termine le traitement de la requête
                } else if ($_POST['champ'] == 5) { // si modif mot de passe
                    $req = $db->prepare('UPDATE contacts SET password = :newpassword WHERE id = :idnum');
                    $req->execute(array(
                        'newpassword' => $_POST['modif_champ'],
                        'idnum' => $_POST['contact-modif']
                    ));
                    echo '<br>'.'La modification du mot de passe du contact a bien été enrégistrée !';
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
