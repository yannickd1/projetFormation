<?php
require_once 'class/Cfg.php';
if (!Cfg::$user) {
    header('Location:login.php');
    exit;
}
$cnx = Connexion::getInstance();
$tabErreur = [];
$gameDate = new DateGame();
$opt = ['min_range' => 1];
$gameDate->id_game_date = filter_input(INPUT_POST, 'id_game_date', FILTER_VALIDATE_INT, $opt);
$gameDate->id_my_team = filter_input(INPUT_POST, 'id_my_team', FILTER_VALIDATE_INT, $opt);
$gameDate->id_equipe_adverse = filter_input(INPUT_POST, 'id_equipe_adverse', FILTER_VALIDATE_INT, $opt);
$gameDate->id_lieu = filter_input(INPUT_POST, 'id_lieu', FILTER_VALIDATE_INT, $opt);
$gameDate->id_phase = filter_input(INPUT_POST, 'id_phase', FILTER_VALIDATE_INT, $opt);
$gameDate->id_membre = filter_input(INPUT_POST, 'id_membre', FILTER_VALIDATE_INT, $opt);
$gameDate->date_game = filter_input(INPUT_POST, 'date_game', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

$tabDateGame = date_parse_from_format('Y-m-d', $gameDate->date_game);

if (filter_input(INPUT_POST, 'submit')) {
    if (!$gameDate->id_my_team) {
        $tabErreur[] = "Equipe absente";
    }

    if (!$gameDate->id_equipe_adverse) {
        $tabErreur[] = "Equipe absente ou invalide";
    }

    if (!$gameDate->id_lieu) {
        $tabErreur[] = "Lieu absent ou invalide";
    }

    if (!$gameDate->id_phase) {
        $tabErreur[] = "Phase absente ou invalide";
    }

    if (!$gameDate->id_membre) {
        $tabErreur[] = "Membre absent ou invalide";
    }

    if ($tabDateGame['errors']) {
        $tabErreur[] = "Date absente ou invalide";
    } else {
        $annee = $tabDateGame['year'];
        $mois = $tabDateGame['month'];
        $jour = $tabDateGame['day'];
        if (!$gameDate->date_game || !checkdate($mois, $jour, $annee))
            $tabErreur[] = "Date absente ou invalide";
    }

    if (!$tabErreur) {
        $gameDate->sauver();
        header("location:calendrier.php");
        exit;
    }
}
//Arrivée depuis l'accueil pour modifier.
elseif ($gameDate->id_game_date && !$gameDate->charger()) {
    header("Location:match.php");
    exit;
}


$tabLieu = Lieu::tous();
$tabPhase = Phase::tous();
$tabMyTeam = MyTeam::tous();
$tabAdversaire = EquipeAdverse::tous();
$tabMembre = Membre::tous();

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
    <body class="game">
        <?php require_once 'inc/header.php'; ?>
        <div id="background">
            <div id="ajouterMatch">
                <h1 class="display-3">Ajouter un match au calendrier</h1>
                <div class="erreur"><?= implode('<br/>', $tabErreur) ?></div>
                <form name="form1" action="ajouterdategame.php" method="post" enctype="multipart/form-data" >

                    <input class="form-control" type="hidden" name="id_game_date" value="<?= $gameDate->id_game_date ?>"/>


                    <div class="col-md-2 mb-3">
                        <label>Date match</label>
                        <input class="form-control" name="date_game" size="10" value="<?= $gameDate->date_game ?: date('Y-m-d') ?>"/>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label>Lieu</label>
                        <select class="form-control" name="id_lieu">
                            <?php
                            foreach ($tabLieu as $li) {
                                $selected = $li->id_phase == $li->id_lieu ? 'selected="selected"' : '';
                                ?>
                                <option value="<?= $li->id_lieu ?>" <?= $selected ?>>
                                    <?= $li->libelle ?>
                                </option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>Phase</label>
                        <select class="form-control" name="id_phase">
                            <?php
                            foreach ($tabPhase as $pha) {
                                $selected = $pha->id_phase == $pha->id_phase ? 'selected="selected"' : '';
                                ?>
                                <option value="<?= $pha->id_phase ?>" <?= $selected ?>>
                                    <?= $pha->libelle ?>
                                </option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>My Team</label>
                        <select class="form-control" name="id_my_team">
                            <?php
                            foreach ($tabMyTeam as $team) {
                                $selected = $team->id_my_team == $team->id_my_team ? 'selected="selected"' : '';
                                ?>
                                <option value="<?= $team->id_my_team ?>" <?= $selected ?>>
                                    <?= $team->nom ?>
                                </option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>Equipe adverse</label>
                        <select class="form-control" name="id_equipe_adverse">
                            <?php

                            foreach ($tabAdversaire as $adv) {
                                $selected = $adv->id_equipe_adverse == $adv->id_equipe_adverse ? 'selected="selected"' : '';
                                ?>
                                <option value="<?= $adv->id_equipe_adverse ?>" <?= $selected ?>>
                                    <?= $adv->nom_adv ?>
                                </option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>


                    <div class="col-md-2 mb-3">
                        <label>Joueurs sélectionnés</label>

                            <?php
                            foreach ($tabMembre as $sel) {
                            ?>
                                <label><?= $sel->prenom ?> <?= $sel->nom ?></label>
                                <?php var_dump($sel->id_membre); ?>
                                <input name="id_membre" type='checkbox' value="<?= $sel->id_membre ?>"/>

                                <br><br>
                        <?php

                            }
                            ?>
                    </div>

                    <div class="item">
                        <label></label>
                        <div>
                            <input class="btn btn-danger"type="button" value="Annuler" onclick="annuler(<?= $gameDate->id_game_date ?>)"/>
                            <input class="btn btn-light" type="submit" name="submit" value="Valider"/>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </body>
</html>
