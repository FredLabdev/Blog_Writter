<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>
        <?= $title ?>
    </title>
    <link href="style.css" rel="stylesheet" />
</head>

<body class="<?= $template ?>">
    <header>
        <h1>Jean Forteroche</h1>
        <h2>Billet simple pour l'Alaska</h2>
    </header>
    <?php
        if (!$menu == 'no_menu') {
            echo '<nav id="menu">';
            echo '<div class="element_menu">';
            echo '<h3>Menu</h3>';
            echo '<ul>';
            if($template == 'backend') {
            echo'<li><a href="index.php?action=listPosts">Accueil</a></li>';
            echo '<li><a href="backend_modif_billet.php">Modifier un billet</a></li>';
            echo '<li><a href="index.php?action=contactDetail">Administrer les contacts</a></li>';
            } else {
            echo'<li><a href="index.php?action=listPosts">Accueil</a></li>';
            echo '<li><a href="index.php?action=contactDetail">Gérer son compte</a></li>';
            echo '<li><a href="frontend_contact.php">Contact</a></li>';
            }
            echo '<li><a href="#" id="deconnexion">Deconnexion</a></li>';
            echo '</ul>';
            echo '</div>';
            echo '</nav>';
        }
    ?>
    <p>
        Bonjour
        <?php echo ' ' . $_SESSION['first_name'];?>
        , nous sommes le :
        <?php echo ' ' . date('d/m/Y') . '<br>';?>
    </p>
    <?= $content ?>
    <footer id="pied_de_page">
        <script src="ajax.js"></script>
        <script src="forteroche.js"></script>
        <p>Copyright Fred Lab, tous droits réservés</p>
    </footer>

</body>

</html>
