<?php
session_start(); // On démarre la session AVANT toute chose
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Jean Forteroche</title>
    <!-- Feuille de style css et Bibliothèque d'icones FontAwesome -->
    <link rel="stylesheet" href="frontend_style.css" />
</head>

<body>

    <?php include("forteroche_header.php"); ?>

    <?php include("forteroche_menu.php"); ?>

    <p>
        Page contact :
    </p>
    <p>Aujourd'hui nous sommes le
        <?php echo date('d/m/Y h:i:s'); ?>.
    </p>

    <br />
    <p>===========================================================</p>
    <!-- envoie Fichier en méthode POST -->

    <h3>
        Envoyer un fichier :
    </h3>


    <form action="forteroche_fichier.php" method="post" enctype="multipart/form-data">
        <p>
            Formulaire d'envoi de fichier (taille maxi 1Mo) :<br />
            <input type="file" name="monfichier" /><br />
            <input type="submit" value="Envoyer le fichier" />
        </p>
    </form>

    <!-- Footer -->
    <br />
    <p>===========================================================</p>
    <?php include("forteroche_footer.php"); ?>

</body>

</html>
