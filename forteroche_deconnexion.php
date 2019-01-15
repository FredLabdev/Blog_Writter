<?php 
    session_start();

    echo 'Vous êtes bien déconnecté.' . '<br>';
    echo 'A bientôt ' . $_SESSION['prenom'] . '<br>';
    echo '<a href="index.php">Retour à l\'accueil</a>'   ;

    // Suppression des variables de session et de la session
    $_SESSION = array();
    session_destroy();

    // Suppression des cookies de connexion automatique
    setcookie('pseudo', '');
    setcookie('mot_passe', '');

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

    <!-- Header -->

    <?php include("forteroche_header.php"); ?>


    <!-- Footer -->
    <br />
    <p>===========================================================</p>
    <?php include("forteroche_footer.php"); ?>

</body>

</html>
