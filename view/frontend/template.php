<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>
        <?= $title ?>
    </title>
    <link href="public/style.css" rel="stylesheet" />
</head>

<body class="<?= $template ?>">
    <header>
        <h1>Jean Forteroche</h1>
        <h2>Billet simple pour l'Alaska</h2>
    </header>
    <?php ob_start(); ?>
    <nav id="menu">
        <div class="element_menu">
            <h3>Menu</h3>
            <ul>
                <?php
                    if($template == 'backend') {
                        echo'<li><a href="index.php?action=listPosts">Administrer les billets</a></li>';
                        echo '<li><a href="index.php?action=memberDetail">Administrer les members</a></li>';
                    } else {
                        echo'<li><a href="index.php?action=listPosts">Accueil</a></li>';
                        echo '<li><a href="index.php?action=memberDetail">Gérer son compte</a></li>';
                    }
                ?>
                <li><a href="#" id="deconnexion">Deconnexion</a></li>
            </ul>
        </div>
    </nav>
    <?php 
        $menu = ob_get_clean();
        if (!$noMenu == 'no_menu') {
            echo $menu;
        }
        echo $all1;
        if ($template == 'backend') {
            echo $backend;
        }
        if ($template == 'frontend') {
            echo $frontend;
        }
        echo $all2;
    ?>
    <footer id="pied_de_page">
        <script src="public/ajax.js"></script>
        <script src="public/forteroche.js"></script>
        <p>Copyright Fred Lab, tous droits réservés</p>
    </footer>

</body>

</html>
