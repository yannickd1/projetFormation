<?php
require_once 'class/Cfg.php';
$opt = ['min_range' => 1];
$id_match = filter_input(INPUT_GET, 'id_match', FILTER_VALIDATE_INT, $opt);


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
        <title>Myteam</title>
        <link href="css/myteamstyle.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="js/myteam.js" type="text/javascript"></script>
    </head>
    <body class="game">
        <?php require_once 'inc/header.php'; ?>

        <h1 id="titre" class="display-3">Résultats</h1>

        <div id="background">
            <div class="match">
                <div><h1>Résultats saison 2018-2019</h1></div>


                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Phase</th>
                            <th scope="col">Domicile</th>
                            <th scope="col">Score</th>
                            <th scope="col">Exterieur</th>
                            <th scope="col">Résultat</th>
                        </tr>
                    </thead>


                    <?php
                    if (Cfg::$user) {
                        ?>
                        <tbody>

                            <?php foreach ($tabMatch as $mat) { ?>
                                <tr onclick="detailMatch(<?= $mat->id_match ?>)">
                                    <td><?= $mat->date_match ?> </td>
                                    <td><?= $mat->getPhase()->libelle ?> </td>


                                    <?php
                                    if ($mat->id_lieu === '2') {
                                        ?>
                                        <td><?= $mat->getAdversaire()->nom_adv ?></td>
                                        <td><?= $mat->points_equipe_adverse ?> - <?= $mat->points_my_team ?></td>
                                        <td><?= $mat->getTeam()->nom ?></td>
                                        <?php
                                    } elseif ($mat->id_lieu === '1') {
                                        ?>
                                        <td><?= $mat->getTeam()->nom ?></td>
                                        <td><?= $mat->points_my_team ?> - <?= $mat->points_equipe_adverse ?> </td>
                                        <td><?= $mat->getAdversaire()->nom_adv ?></td>
                                        <?php
                                    }
                                    ?>
                                    <td><?= $mat->getResultat()->libelle ?> </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                        <?php
                    }
                    ?>
                </table>




            </div>
        </div>


        <footer></footer>
    </body>
</html>
