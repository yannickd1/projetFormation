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

$tabMatch = Match::tous();
$match = new Match();
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


    <body class="gamePicture">

        <?php require_once 'inc/header.php'; ?>
        <h1 id="titre" class="display-3">Photos</h1>
        <div id="background">

            <div class="picture">

                <?php
                foreach ($tabMatch as $mat) {
                    $idImg = file_exists("photos/pict_{$mat->id_match}_p.jpg") ? $mat->id_match : 0;
                    ?>

                    <h3><?= $mat->date_match ?> </h3>
                    <h4>Match contre <?= $mat->getAdversaire()->nom_adv ?></h4>
                    <img class="img-responsive" src="photos/pict_<?= $idImg ?>_p.jpg?alea=<?= rand() ?>">

                    <?php
                }
                ?>

            </div>


            <footer></footer>
        </div>
    </div>
</body>
</html>
