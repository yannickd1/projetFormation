<?php
require_once'class/Cfg.php';
$opt = ['min_range' => 1];
$id_membre = filter_input(INPUT_GET, 'id_membre', FILTER_VALIDATE_INT, $opt);
$membre = new Membre($id_membre);

if (!$membre->charger()) {
    header("Location:indispo.php");
    exit;
}

$idImg = file_exists("img/memb_{$membre->id_membre}_v.jpg") ? $membre->id_membre : 0;
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="css/myteamstyle.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="js/myteam.js" type="text/javascript"></script>

        <title>Fiche joueur</title>
    </head>
    <body>
        <?php require_once 'inc/header.php' ?><br><br><br><br><br><br><br>
        <div id="background">
            <div class="container">
                <div class="photosDetail" >
                    <img class="imgDetail" src="img/memb_<?= $idImg ?>_v.jpg?alea=<?= rand() ?>">

                    <div class="nom">Nom : <?= $membre->nom ?></div>
                    <div class="prenom">Prenom : <?= $membre->prenom ?></div>
                    <?php
                    if ($membre->id_statut < 2) {
                        ?>
                        <div class="taille_en_cm">Taille : <?= $membre->taille_en_cm ?> cm</div>
                        <div class="poste">Poste : <?= $membre->getPoste()->libelle ?></div>
                        <?php
                    }
                    ?>
                    <div class="statut">Statut : <?= $membre->getStatut()->libelle ?></div>
                    <?php
                    if ($membre->id_statut < 2) {
                        ?>
                        <div class="statut">Points par match : <?= $membre->averagePointPerGame() ?> pts</div>
                        <?php
                    }
                    
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>
