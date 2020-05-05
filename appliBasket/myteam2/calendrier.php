<?php
require_once 'class/Cfg.php';
$opt = ['min_range' => 1];
$id_match = filter_input(INPUT_GET, 'id_match', FILTER_VALIDATE_INT, $opt);


if (!Cfg::$user) {
    header('Location:login.php');
    exit;
}

$tabGame = DateGame::tous();

$game = new Match();
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

        <div><h1 id="titre" class="display-3">Calendrier</h1></div>


        <div id="background">
            <div class="match">
                <div><h1>Calendrier saison 2018-2019</h1></div>


                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Phase</th>
                            <th scope="col">Domicile</th>
                            <th scope="col">Exterieur</th>
                        </tr>
                    </thead>


                    <?php
                    if (Cfg::$user) {
                        ?>
                        <tbody>

                            <?php foreach ($tabGame as $gam) { ?>
                                                                                <!--                                <tr onclick="detailMatch(<?= $gam->id_match ?>)">-->
                            <td><?= $gam->date_game ?> </td>
                            <td><?= $gam->getPhase()->libelle ?> </td>


                            <?php
                            if ($gam->id_lieu === '2') {
                                ?>
                                <td><?= $gam->getAdversaire()->nom_adv ?></td>
                                <td><?= $gam->getTeam()->nom ?></td>
                                <?php
                            } elseif ($gam->id_lieu === '1') {
                                ?>
                                <td><?= $gam->getTeam()->nom ?></td>
                                <td><?= $gam->getAdversaire()->nom_adv ?></td>
                                <?php
                            }
                            ?>
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
