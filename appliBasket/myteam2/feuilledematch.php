<?php
require_once 'class/Cfg.php';
if (!Cfg::$user) {
    header('Location:login.php');
    exit;
}
$pts = new PtsJoueur();
$opt = ['min_range' => 1];
$id_match = filter_input(INPUT_GET, 'id_match', FILTER_VALIDATE_INT, $opt);
$pts->id_match = $id_match;
$tabStat = PtsJoueur::tab("id_match={$id_match}");
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
    <?php require_once 'inc/header.php'; ?>

    <body class="game">
        <h2 class="display-3">Feuille de match</h2>
        <div id="background">
            <div id="feuilleDeMatch">

                <h3 class="display-4">Match du : <?= $pts->getDate()->date_match ?></h3>

                <table class="table">
                    <thead class="thead-light">
                        <tr>

                            <th scope="col">Joueur</th>
                            <th scope="col">Pts</th>

                        </tr>
                    </thead>

                    <?php
                    if (Cfg::$user) {
                        ?>
                        <tbody>
                            <?php foreach ($tabStat as $st) { ?>
                                <tr>
                                    <td><?= $st->getNom()->nom ?> </td>
                                    <td><?= $st->points ?> </td>
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
