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
        Bienvenue
        <?php 
            echo $_SESSION['prenom'] . ' ! Vous êtes sur l\'administration du blog !';
        ?>
    </h3>

    <p>===========================================================</p>
    <!-- Liste des billets -->

    <ul>
        <?php   // connexion à la base de données 
            try { 
                $bdd = new PDO('mysql:host=localhost;dbname=forteroche', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            }
            catch(Exception $e) {
                die('Erreur : '.$e->getMessage());
            }
                // Liste des billets par ordre décroissant 
        
            $reponse = $bdd->query('SELECT COUNT(id) AS nbre_billets FROM billets');
            $donnees = $reponse->fetch();
            echo '<h3>' . 'Liste des ' . $donnees['nbre_billets'] . ' billets publiés à ce jour :' . '</h3>';
            $reponse->closeCursor(); // Termine le traitement de la requête
            $compteur = $donnees['nbre_billets'];
            $reponse = $bdd->query('SELECT titre_episode, date_creation FROM billets ORDER BY date_creation DESC');
            while ($donnees = $reponse->fetch()) {
            echo '<li style="color: red;">' . ' N° ' . $compteur . ' : ' . $donnees['titre_episode'] . '</li>';
            $compteur--;
            }
            $reponse->closeCursor(); // Termine le traitement de la requête
        ?>
    </ul>

    <p>===========================================================</p>
    <!-- Les détails par groupe de 5 billets -->

    <p>Page
        <?php // Boucle affichant le bon nombre de liens vers d'autres pages, par groupe de 5 billets 
        
            $reponse = $bdd->query('SELECT COUNT(id) AS nb_billets FROM billets');
            $donnees = $reponse->fetch();
            for ($page=1, $pages_max=($donnees['nb_billets']/5)+($donnees['nb_billets']%5); $page<=$pages_max; $page++) {
                echo '<a href="backend_accueil.php?page=' . $page .'">' . $page . '</a>' . ' '; // page sélectionnée envoyé en url GET
                $reponse->closeCursor(); // Termine le traitement de la requête
            }
        ?>
    </p>

    <?php    // Récupération des indices de billets max et min de chaque page 
    
        if ($_GET['page']) {
            $id_max_page = ($_GET['page']-1)*5;  
            $billet_max = $donnees['nb_billets']-(($_GET['page']-1)*5);
        } else {
            $id_max_page = 0;
            $billet_max = $donnees['nb_billets'];
        }
        if ($billet_max <= 5) {
            $billet_min = 1;
        } else {
            $billet_min = $billet_max-4;
        }
            // On affiche 5 billets max par page et on les définis en paramètrant le OFFSET à opérer 
    
        $req = $bdd->prepare('SELECT id, titre_episode, contenu_episode, DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%imin%ss\') AS date FROM billets ORDER BY date_creation DESC LIMIT 5 OFFSET :idmax');
        $req->bindValue(':idmax', $id_max_page, PDO::PARAM_INT);
        $req->execute(); //
        if ($billet_max == $donnees['nb_billets']) {
            echo '<h3>' . 'Les 5 derniers billets du n° ' . $billet_max . ' au n° ' . $billet_min . '</h3>';
        } else {
        echo '<h3>' . 'Billets du n° ' . $billet_max . ' au n° ' . $billet_min . '</h3>';
        };            
            // On affiche le détail de chaque billet de la plage sélectionnée
    
        while ($donnees = $req->fetch()) {
        echo '<h3 class="news">' . $donnees['titre_episode'] . ' : ' . ' le '. $donnees['date'] . '</h3>';
        echo '<p>' . htmlspecialchars($donnees['contenu_episode']) . '</p>';
            
            // id du billet dont on veut voir les commentaires envoyé en url GET
            
        echo '<a href="backend_comment_billet_admin.php?billet=' . $donnees['id'] .'">' . 'Cliquer pour accéder aux commentaires de ce billet' . '</a>' . '<br>';
            
        if ($_GET['billet'] AND $_GET['billet'] == $donnees['id']) {
            
             // on compte le nbre de commentaires et l'affiche le cas échéant
            
        $req1 = $bdd->prepare('SELECT COUNT(id_billet) AS nbre_comment FROM commentaires WHERE id_billet = ?');
        $req1->execute(array($donnees['id'])); // on compte le nbre de commentaires et l'affiche le cas échéant
        $donnees1 = $req1->fetch(); // 
            if ($donnees1['nbre_comment'] >= 1) {
                echo $donnees1['nbre_comment'] . ' Commentaires' . '<br>'; 
            } 
        $req1->closeCursor(); // Termine le traitement de la requête 1
            
            // Lien vers page des commentaires
            
        echo '<a href="backend_comment_billet_admin.php">' . 'Détails ou ajout d\'un nouveau commentaire' . '</a>' . '<br>';
        }
        echo '<p>.......................................................................................</p>';  
        }
        $req->closeCursor(); // Termine le traitement de la requête
    ?>

    <!-- Footer -->
    <br />
    <p>===========================================================</p>
    <?php include("forteroche_footer.php"); ?>

</body>

</html>
