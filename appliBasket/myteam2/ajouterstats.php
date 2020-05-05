<?php
require_once 'class/Cfg.php';
if (!Cfg::$user) {
    header('Location:login.php');
    exit;
}
$tabErreur = [];
$pts_joueur = new PtsJoueur();
$opt = ['min_range' => 1];
$pts_joueur->id_pts_joueur = filter_input(INPUT_GET, 'id_pts_joueur', FILTER_VALIDATE_INT, $opt);
// Arrivée en POST après validation du formulaire.
if (filter_input(INPUT_POST, 'submit')) {
    $pts_joueur->id_pts_joueur = filter_input(INPUT_POST, 'id_pts_joueur', FILTER_VALIDATE_INT, $opt);
    $pts_joueur->id_match = filter_input(INPUT_POST, 'id_match', FILTER_VALIDATE_INT, $opt);
    $pts_joueur->id_membre = filter_input(INPUT_POST, 'id_membre', FILTER_VALIDATE_INT, $opt);
    $pts_joueur->points = filter_input(INPUT_POST, 'points', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

    if (!$pts_joueur->id_match) {
        $tabErreur[] = "Id match absent ou invalide";
    }
    if (!$pts_joueur->id_membre) {
        $tabErreur[] = "Membre absent ou invalide";
    }
    if (!$pts_joueur->points) {
        $tabErreur[] = "Points absent ou invalide";
    }

    if (!$tabErreur) {
        $pts_joueur->sauver();
        header("location:feuilledematch.php");
        exit;
    }
}

$tabMembre = Membre::tous();
$tabMatch = Match::tous();
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
        <div id="background">
            <div id="ajouterStats">
                <h1 class="display-3">Ajouter stats</h1>
                <div class="erreur"><?= implode('<br/>', $tabErreur) ?></div>
                <form name="form1" action="ajouterstats.php" method="post">

                    <input class="form-control" type="hidden" name="id_pts_joueur" value="<?= $pts_joueur->id_pts_joueur ?>"/>
                    <input class="form-control" type="hidden" name="id_match" value="<?= $pts_joueur->id_match ?>"/>


                    <div class="col-md-2 mb-3">
                        <label>Membre</label>
                        <select class="form-control" name="id_membre">
                            <?php
                            foreach ($tabMembre as $memb) {
                                $selected = $pts_joueur->id_membre == $memb->id_membre ? 'selected="selected"' : '';
                                ?>
                                <option value="<?= $memb->id_membre ?>" <?= $selected ?>>
                                    <?= $memb->nom ?>
                                </option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label>Match</label>
                        <select class="form-control" name="id_match">
                            <?php
                            foreach ($tabMatch as $game) {
                                $selected = $pts_joueur->id_match == $game->id_match ? 'selected="selected"' : '';
                                ?>
                                <option value="<?= $game->id_match ?>" <?= $selected ?>>
                                    <?= $game->date_match ?>
                                </option>
                                <?php
                            }
                            
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label>Nombre de points</label>
                        <input class="form-control" type="number" name="points" value="<?= $pts_joueur->points ?>" size="4" step="1" min="0" max="250" required="required" />
                    </div>

                    <div class="item">
                        <label></label>
                        <div>
                            <input class="btn btn-danger"type="button" value="Annuler" onclick="annuler(<?= $pts_joueur->id_pts_joueur ?>)"/>
                            <input class="btn btn-light" type="submit" name="submit" value="Valider"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
