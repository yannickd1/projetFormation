<?php
require_once 'class/Cfg.php';
if (!Cfg::$user) {
    header('Location:login.php');
    exit;
}
$tabErreur = [];
$entrainement = new Entrainement();
$opt = ['min_range' => 1];
$entrainement->id_entrainement = filter_input(INPUT_GET, 'id_entrainement', FILTER_VALIDATE_INT, $opt);
// Arrivée en POST après validation du formulaire.

$entrainement->id_entrainement = filter_input(INPUT_POST, 'id_entrainement', FILTER_VALIDATE_INT, $opt);
$entrainement->date_entrainement = filter_input(INPUT_POST, 'date_entrainement', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$entrainement->id_type_entrainement = filter_input(INPUT_POST, 'id_type_entrainement', FILTER_VALIDATE_INT, $opt);
$tabDateEntrainement = date_parse_from_format('Y-m-d', $entrainement->date_entrainement);
if (filter_input(INPUT_POST, 'submit')) {
    if (!$entrainement->id_type_entrainement) {
        $tabErreur[] = "Type entrainement absent ou invalide";
    }
    if ($tabDateEntrainement['errors']) {
        $tabErreur[] = "Date absente ou invalide";
    } else {
        $annee = $tabDateEntrainement['year'];
        $mois = $tabDateEntrainement['month'];
        $jour = $tabDateEntrainement['day'];
        if (!$entrainement->date_entrainement || !checkdate($mois, $jour, $annee))
            $tabErreur[] = "Date absente ou invalide";
    }
    if (!$tabErreur) {
        $entrainement->sauver();
        header("location:entrainement.php");
        exit;
    }
}

$tabType = Type::tous();
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
            <div id="ajouterTraining">
                <h1 class="display-3">Ajouter un entrainement</h1>
                <div class="erreur"><?= implode('<br/>', $tabErreur) ?></div>
                <form class="form" name="form1" action="ajoutertraining.php" method="post">

                    <input class="form-control" type="hidden" name="id_entrainement" value="<?= $entrainement->id_entrainement ?>"/>


                    <div class="col-md-2 mb-3">
                        <label>Date entrainement</label>
                        <input class="form-control" name="date_entrainement" size="10" value="<?= $entrainement->date_entrainement ?: date('d-m-Y') ?>"/>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label>Type entrainement</label>
                        <select class="form-control" name="id_type_entrainement">
                            <?php
                            foreach ($tabType as $typ) {
                                $selected = $entrainement->id_type_entrainement == $typ->id_type_entrainement ? 'selected="selected"' : '';
                                ?>
                                <option value="<?= $typ->id_type_entrainement ?>" <?= $selected ?>>
                                    <?= $typ->libelle ?>
                                </option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label></label>
                        <div>
                            <input class="btn btn-danger"type="button" value="Annuler" onclick="annuler(<?= $entrainement->id_entrainement ?>)"/>
                            <input class="btn btn-light" type="submit" name="submit" value="Valider"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
