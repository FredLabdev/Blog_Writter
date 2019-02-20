<?php 
    session_start();
    $title = 'Forteroche/Membres';
    if ($_SESSION['group_id'] == 1) {
        $template = 'backend';
    } else {
        $template = 'frontend';
    }
    ob_start(); 
?>

<div class="d-flex flex-column align-items-center white">

    <div id="members">

        <!-- LISTE DES ADMINISTRATUERS ET MODERATEURS -->

        <h3>
            Liste des administrateurs et modérateurs :
        </h3>
        <p>
            <?php 
        foreach($membersByGroup as $member) {
            echo $member['grade_groupe'] . ' : ' . $member['name_member'] . ' ' . $member['first_name_member'] . '<br>';
        }
        ?>
        </p>

        <!-- LISTE DES MEMBRES -->

        <div class="row">
            <div class="col-lg-4 col-xs-12 justify-content-left">
                <div class="row d-inline-flex flex-nowrap">
                    <h3 class="col-lg-11 col-xs-8"> Nombre d'abonnés :
                    </h3>
                    <p class=" col-xs-3 btn-success btn-lg active">
                        <?= $membersCount['nbre_members'] ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- MESSAGES -->

        <div class="row col-12 text-center">
            <?php if($message_success) { ?>
            <span class="alert alert-success col-4 offset-4">
                <?= $message_success; ?>
            </span>
            <?php } else if($message_error) { ?>
            <span class="alert alert-danger col-4 offset-4">
                <?= $message_error; ?>
            </span>
            <?php } ?>
        </div>

        <!-- EDITION D'UN MEMBRE -->

        <div class="row">
            <div class="col-lg-4 col-xs-12 justify-content-left">
                <h3> Editer un membre :
                </h3>

                <!-- SELECTION -->

                <form id="MemberForm" class="form-row col-xs-3" method="post" action="index.php?action=membersDetail">
                    <select name="member">
                        <option name="choix" value=""></option>
                        <?php
                            foreach($membersByName as $member) {
                                echo '<option value="' . $member['id'] . '">' . $member['name_maj'] . ' ' . $member['first_name_min'] . '</option>';
                            }
                        ?>
                    </select>
                    <button class="offset-1 btn btn-light btn-sm blue" type="submit">Editer</button><br>

                    <!-- DETAIL -->

                    <?php
                        foreach($memberDetails as $dataMember) { // Détail du member sélectionné
                            echo 'Nom : ' . $dataMember['name'] . '<br>';
                            echo 'Prénom : ' . $dataMember['first_name'] . '<br>';  
                            echo 'Pseudo : ' . $dataMember['pseudo'] . '<br>';  
                            echo 'Mail : ' . $dataMember['email'] . '<br>';  
                            echo 'Date de création : ' . $dataMember['creation_date'] . '<br>';
                            if($dataMember['block_comment'] == 0) {
                                echo 'Commentaires autorisés : oui' . '<br>';  
                            } else {
                                echo 'Commentaires autorisés : non' . '<br>';  
                            }; 
                            if($dataMember['group_id'] == 2) {
                                echo 'Ce membre est un modérateur';
                            };  
                        }
                    ?>
                </form>
            </div>
        </div>

        <!-- ACTIONS POSSIBLES -->

        <?php
        if ($memberDetails) {
    ?>
        <h3>
            Actions possibles :
        </h3>
        <form id="modifMember" method="post" action="index.php?action=memberModif">
            <input type="hidden" name="member_modif" value="<?php echo $dataMember['id']; ?>" />
            <label>
                <?php 
            if($dataMember['block_comment'] == 0) {
                echo 'Bloquer ses commentaires';
            } else {
                echo 'Réautoriser ses commentaires';
            };
            ?>
            </label>
            <input type="checkbox" name="block_comment" value="<?php if($dataMember['block_comment'] == 0){echo '1';}else{echo '0';};?>" /><br>
            <label>
                <?php 
            if ($_SESSION['group_id'] == 1) {
                if ($dataMember['group_id'] != 2) {
                    echo 'Donner un pouvoir de modérateur de commentaires';
                } else {
                    echo 'Retirer son pouvoir de modérateur de commentaires';
                }
            ?>
            </label>
            <input type="checkbox" name="moderator" value="<?php if($dataMember['group_id'] == 3){echo '2';}else{echo '3';};?>" />
            <?php 
            }
        ?>
            <p></p>
            <button class="member-button btn btn-light btn-lg blue offset-lg-2" type="submit" name="remplacer">Appliquer</button>
        </form>
    </div>

    <!-- SUPPRESSION DU COMPTE -->

    <div class="delete-member white">

        <?php 
    if ($_SESSION['group_id'] == 1) {
    ?>

        <form class="deleteMember d-flex flex-lg-row justify-content-lg-around" name="delete">
            <label>Pour supprimer ce compte, cliquez ici :</label>
            <input type="hidden" name="member_modif" value="<?= $dataMember['id']; ?>" />
            <a href="#" class="delete" onClick="var memberId = document.forms.delete.member_modif.value;
        function valid_confirm(member) {
            if (confirm('Voulez-vous vraiment désinscrire ce membre ?')) {
                var url = 'index.php?action=memberDelete&memberErase=' + member;
                document.location.href = url;
                return true;
            } else {
                alert('Je me disais aussi...');
                return false;
            }
        }
        valid_confirm(memberId);"> Désincrire ce membre </a>
        </form>
        <?php 
    }
    }
    ?>

        <?php 
    if ($_SESSION['group_id'] == 1) {
        $backend = ob_get_clean();
    } else {
        $frontend = ob_get_clean();
    }
    ?>

    </div>

</div>

<?php require('view/frontend/template.php'); ?>
