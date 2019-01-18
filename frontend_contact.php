<?php session_start(); ?>
<?php $title = 'Contact'; ?>
<?php $template = 'frontend'; ?>
<?php ob_start(); ?>

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

<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>
