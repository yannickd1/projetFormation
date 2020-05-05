<?php
require_once 'class/Cfg.php';
$tabMembre = Membre::tous();
$opt = ['min_range' => 1];
$id_membre = filter_input(INPUT_GET, 'id_membre', FILTER_VALIDATE_INT, $opt);
$membre = new Membre($id_membre);

if (!Cfg::$user) {
    header('Location:login.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Myteam</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="js/myteam.js" type="text/javascript"></script>
        <link rel="stylesheet" href="css/myteamstyle.css">
    </head>


    <body>

        <?php require_once 'inc/header.php'; ?>
        <h1 id="titre" class="display-3">Effectif</h1>

        <div id="background">
            <div><h1 id="titreEffectif">Effectif 2018-2019</h1></div>
            <div class="container">

                <?php
                foreach ($tabMembre as $memb) {

                    $idImg = file_exists("img/memb_{$memb->id_membre}_p.jpg") ? $memb->id_membre : 0;
                    ?>
                    <div class="photos" onclick="detailMembre(<?= $memb->id_membre ?>)" >
                        <img  class="img img-responsive" src="img/memb_<?= $idImg ?>_p.jpg?alea=<?= rand() ?>">


                        <div class="button">
                            <p>Nom : <?= $memb->nom ?> </p>
                            <p>Prenom : <?= $memb->prenom ?> </p>
                            <?php
                            if (Cfg::$user->id_niveau > 1) {
                                ?>
                                <img class="ico editer img-responsive" src="img/edit.svg"
                                     alt="Editer"
                                     width="27px"
                                     height="27px"
                                     onclick="editerMembre(event,<?= $memb->id_membre ?>)"/>
                                <img class="ico supprimer img-responsive" src="img/delete_user.svg"
                                     alt="Supprimer"
                                     width="27px"
                                     height="27px"
                                     onclick="supprimerMembre(event,<?= $memb->id_membre ?>)"/>
                                <img class="supprimerImage img-responsive" src="img/supImg.svg"
                                     alt="Supprimer image"
                                     width="35px"
                                     height="35px"
                                     onclick="supprimerImage(event,<?= $memb->id_membre ?>)"/>
                                     <?php
                                 }
                                 ?>

                        </div>
                    </div>

                    <?php
                }
                ?>
            </div>
        </div>

        <footer></footer>
    </div>
</body>
</html>
