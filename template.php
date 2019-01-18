<!DOCTYPE html>
<html <?php if($onload='refresh_comments' ) { echo 'onload="document.getElementById(\' recup_billet_id\').submit();"'; } ?>>

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
            echo'<li><a href="' . $template .'_accueil.php">Accueil</a></li>';
            if($template == 'backend') {
            echo '<li><a href="backend_modif_billet.php">Modifier un billet</a></li>';
            echo '<li><a href="backend_insert_billet.php">Crééer un nouveau billet</a></li>';
            echo '<li><a href="backend_admin_contacts.php">Administrer les contacts</a></li>';
            } else {
            echo '<li><a href="frontend_admin_compte.php">Gérer son compte</a></li>';
            echo '<li><a href="frontend_contact.php">Contact</a></li>';
            }
            echo '<li><a href="#" id="deconnexion">Deconnexion</a></li>';
            echo '</ul>';
            echo '</div>';
            echo '</nav>';
        }
    ?>
    <?= $content ?>
    <footer id="pied_de_page">
        <script src="forteroche.js"></script>
        <p>Copyright Fred Lab, tous droits réservés</p>
    </footer>

</body>

</html>
