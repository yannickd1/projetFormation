<?php
require_once 'class/Cfg.php';
if (!Cfg::$user) {
    header('Location:login.php');
    exit;
}
$tabEntrainement = Entrainement::tous();
$opt = ['min_range' => 1];
$id_entrainement = filter_input(INPUT_GET, 'id_entrainement', FILTER_VALIDATE_INT, $opt);
$entrainement = new Entrainement();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Myteam</title>
        <link href="css/myteamstyle.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="js/myteam.js" type="text/javascript"></script>
    </head>
    <body class="training">


        <?php require_once 'inc/header.php' ?>

        <h1 id="titre" class="display-3">Entrainements</h1>
        <div id="background">

            <div class="entrainement">
                <div><h1>Entrainement saison 2018-2019</h1></div>
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Type</th>
                            <th scope="col">Participation</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($tabEntrainement as $entr) {
                            ?>
                            <tr>
                                <td><?= $entr->date_entrainement ?></td>
                                <td><?= $entr->getType()->libelle ?></td>
                                <td><input class="btn btn-light" name="<?= $entr->id_entrainement ?>" type="button" value="participer" onclick="sinscrire(<?= Cfg::$user->id_membre ?>, <?= $entr->id_entrainement ?>)">

                                </td>

                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>


            </div>
        </div>
        <footer></footer>
    </body>
</html>
