<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Velocity, site de réservation de vélos dans votre ville" />
    <meta name="author" content="Frédéric Labourel">

    <!-- Favicone du site dans la barre du navigateur -->
    <link rel="icon" href="public/picture/ico/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="public/picture/ico/favicon.ico" type="image/x-icon" />

    <title>
        <?= $title ?>
    </title>

    <!-- Facebook Open Graph data -->
    <meta property="og:title" content="Jean Forteroche" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://www.facebook.com/forteroche" />
    <meta property="og:image" content="public/picture/ico/logo.png" />
    <meta property="og:description" content="Jean Forteroche, Billet simple pour l'Alaska" />

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="Jean Forteroche">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="Jean Forteroche">
    <meta name="twitter:description" content="Jean Forteroche, Billet simple pour l'Alaska">
    <meta name="twitter:creator" content="@author_handle">

    <!-- Twitter Summary card images must be at least 200x200px -->
    <meta name="twitter:image" content="public/picture/ico/logo.png">

    <!-- Icones du site en raccourci écran Apple -->
    <link rel="apple-touch-icon-precomposed" href="public/picture/ico/apple-touch-icon-57-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="public/picture/ico/apple-icon-57x57.png" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="public/picture/ico/apple-icon-72x72.png" />
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="public/picture/ico/apple-icon-114x114.png" />
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="public/picture/ico/apple-icon-144x144.png" />

    <!-- Liens Polices Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Cinzel|Dancing+Script|Inconsolata|Miss+Fajardose|Open+Sans+Condensed:300" rel="stylesheet">

    <!-- Feuille de style css et Bibliothèque d'icones FontAwesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="public/style.css" rel="stylesheet" />
</head>

<body class="<?= $template ?> black">

    <!-- LISTES DU MENU (COMMUN TOUS RESPONSIVES) -->

    <?php ob_start(); 
        
        if($template == 'backend' && $_SESSION['group_id'] != 2) {
    ?>
    <li class="nav-item">
        <a class="nav-link" href="index.php?action=listPosts">Accueil</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="index.php?action=publishing">Roman</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="index.php?action=membersDetail">Membres</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="index.php?action=memberDetail">Compte</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="index.php?action=contact">Contact</a>
    </li>
    <?php
        } else {
    ?>
    <li class="nav-item">
        <a class="nav-link" href="index.php?action=listPosts">Accueil</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="index.php?action=publishing">Roman</a>
    </li>
    <?php
        if($_SESSION['group_id'] == 2) {
    ?>
    <li class="nav-item">
        <a class="nav-link" href="index.php?action=membersDetail">Membres</a>
    </li>
    <?php
        }
    ?>
    <li class="nav-item">
        <a class="nav-link" href="index.php?action=memberDetail">Compte</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="index.php?action=contact">Contact</a>
    </li>
    <?php
        }
    ?>
    <li class="nav-item">
        <a class="nav-link" href="#" id="deconnexion">Deconnexion</a>
    </li>

    <?php $ul = ob_get_clean(); ?>
    <?php ob_start(); ?>

    <!-- MENU SMARTPHONES -->

    <div class="pos-f-t fixed-top">
        <div class="collapse" id="navbarToggleExternalContent">
            <div class="bg-light p-4">
                <div class="container-fluid">
                    <div class="navbar-header col-3">
                        <a class="navbar-brand" href="#">Jean Forteroche</a>
                    </div>
                    <ul class="nav flex-column">
                        <?= $ul ?>
                    </ul>
                </div>
            </div>
        </div>
        <nav class="navbar navbar-light bg-light">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </nav>
    </div>

    <!-- MENU ECRANS -->

    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container-fluid">
            <div class="navbar-header col-3">
                <a class="navbar-brand" href="#">Jean Forteroche</a>
            </div>
            <ul class="nav nav-pills nav-stacked">
                <?= $ul ?>
            </ul>
        </div>
    </nav>

    <?php $menu = ob_get_clean(); ?>
    <?php ob_start(); ?>

    <!-- FOOTER -->

    <footer id="pied_de_page" class="row">
        <p class="col-lg-3 offset-1">Copyright Fred Lab, tous droits réservés</p>
        <p class="col-lg-4 offset-4">
            <button class="btn btn-info social-link"><a href="https://www.facebook.com/pg/thierrygrandnord/posts/" target=_blank><span class="glyphicon glyphicon-facebook"><i class="fab fa-facebook-f fa-lg white"></i></span></a></button>
            <button class="btn btn-info social-link"><a href="https://naalilodge.com" target=_blank><span class="glyphicon glyphicon-comment"><i class="fab fa-instagram fa-lg white"></i></span></a></button>
            <button class="btn btn-info social-link"><a href="mailto: fred.labourel@wanadoo.fr"><span class="glyphicon glyphicon-calendar"><i class="fas fa-at fa-lg white"></i></span></a></button>
            <button class="btn btn-info social-link"><a href="index.php?action=contact"><span class="glyphicon glyphicon-shopping-cart"><i class="fas fa-envelope fa-lg white"></i></span></a></button>
            <button class="btn btn-info social-link"><a href="https://github.com/freddieLab" target=_blank><span class="glyphicon glyphicon-bullhorn fa-lg"><i class="fab fa-github white"></i></span></a></button>
        </p>
    </footer>

    <?php $footer = ob_get_clean(); ?>

    <!-- CONTAINER BOOTSRTAP -->

    <main role="main" class="container-fluid" style="margin-top: 100px">

        <?php 
            if (!$noMenu == 'no_menu') {
                echo $menu;
            }
            echo $all1;
            if ($template == 'adminModerator') {
                echo $adminModerator;
            }
            if ($template == 'frontend') {
                echo $frontend;
            }
            echo $all2;
            if ($template == 'backend') {
                echo $backend;
            }    
            if (!$noFooter == 'no_footer') {
                echo $footer;
            }
        ?>

    </main>

    <!-- jQuery Bibliothèque Production -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <!-- JavaScript Bootstrap-->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="public/ajax.js"></script>
    <script src="public/forteroche.js"></script>

</body>

</html>
