<?php
require_once 'class/Cfg.php';
if (!Cfg::$user) {
    header('Location:login.php');
    exit;
}
$cnx = Connexion::getInstance();
$tabErreur = [];
$match = new Match();
$opt = ['min_range' => 1];
$match->id_match = filter_input(INPUT_GET, 'id_match', FILTER_VALIDATE_INT, $opt);
// Arrivée en POST après validation du formulaire.
if (filter_input(INPUT_POST, 'submit')) {
    $match->id_match = filter_input(INPUT_POST, 'id_match', FILTER_VALIDATE_INT, $opt);
    $match->date_match = filter_input(INPUT_POST, 'date_match', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $match->id_phase = filter_input(INPUT_POST, 'id_phase', FILTER_VALIDATE_INT, $opt);
    $match->id_lieu = filter_input(INPUT_POST, 'id_lieu', FILTER_VALIDATE_INT, $opt);
    $match->id_my_team = filter_input(INPUT_POST, 'id_my_team', FILTER_VALIDATE_INT, $opt);
    $match->id_equipe_adverse = filter_input(INPUT_POST, 'id_equipe_adverse', FILTER_VALIDATE_INT, $opt);
    $match->id_resultat_my_team = filter_input(INPUT_POST, 'id_resultat_my_team', FILTER_VALIDATE_INT, $opt);
    $match->points_my_team = filter_input(INPUT_POST, 'points_my_team', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $match->points_equipe_adverse = filter_input(INPUT_POST, 'points_equipe_adverse', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

    $tabDateMatch = date_parse_from_format('Y-m-d', $match->date_match);
    if (!$match->id_phase) {
        $tabErreur[] = "Phase absente ou invalide";
    }

    if (!$match->id_lieu) {
        $tabErreur[] = "Lieu absent ou invalide";
    }

    if (!$match->id_my_team) {
        $tabErreur[] = "Equipe absente";
    }

    if (!$match->id_equipe_adverse) {
        $tabErreur[] = "Equipe absente ou invalide";
    }
    if (!$match->id_resultat_my_team) {
        $tabErreur[] = " Resultat my team absent ou invalide";
    }

    if (!$match->points_my_team) {
        $tabErreur[] = " Nombre de points my team absent ou invalide";
    }

    if (!$match->points_equipe_adverse) {
        $tabErreur[] = " Nombre de points de l'équipe adverse absent ou invalide";
    }

    if ($tabDateMatch['errors']) {
        $tabErreur[] = "Date absente ou invalide";
    } else {
        $annee = $tabDateMatch['year'];
        $mois = $tabDateMatch['month'];
        $jour = $tabDateMatch['day'];
        if (!$match->date_match || !checkdate($mois, $jour, $annee))
            $tabErreur[] = "Date absente ou invalide";
    }
    if (!$tabErreur) {
        $cnx->start();
        $match->sauver();
        //Traitement upload.
        $upload = new Upload('photo', Cfg::TAB_EXT, Cfg::TAB_MIME);
        //Upload facultatif.
        if ($upload->codeErreur === 4) {

            $cnx->commit();
            header("Location:index.php");
            exit;
        }
        //Un upload a bien eu lieu.
        $tabErreur = array_merge($tabErreur, $upload->tabErreur);
        //Traitement image.
        if (!$upload->tabErreur) {
            $image = new Image($upload->cheminServeur);
            $tabErreur = array_merge($tabErreur, $image->tabErreur);
            if (!$image->tabErreur) {
                $image->copier(Cfg::IMG_V_LARGEUR, Cfg::IMG_V_HAUTEUR, "photos/pict_{$match->id_match}_v.jpg");
                $image->copier(Cfg::IMG_P_LARGEUR, Cfg::IMG_P_HAUTEUR, "photos/pict_{$match->id_match}_p.jpg");
                $tabErreur = array_merge($tabErreur, $image->tabErreur);
                if (!$image->tabErreur) {

                    $cnx->commit();
                    header("Location:index.php");
                    exit;
                }
            }
        }


        $cnx->rollback();
    }
}
//Arrivée depuis l'accueil pour modifier.
elseif ($match->id_match && !$match->charger()) {
    header("Location:match.php");
    exit;
}

$tabMatch = Match::tous();
$tabPhase = Phase::tous();
$tabLieu = Lieu::tous();
$tabMyTeam = MyTeam::tous();
$tabAdversaire = EquipeAdverse::tous();
$tabResultat = ResultatMyTeam::tous();
$idImg = file_exists("photos/pict_{$match->id_match}_v.jpg") ? $match->id_match : 0;
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
        <script>
            const TAB_EXT = JSON.parse(`<?= json_encode(Cfg::TAB_EXT) ?>`);
            const TAB_MIME = JSON.parse(`<?= json_encode(Cfg::TAB_MIME) ?>`);
            const MAX_FILE_SIZE = <?= Upload:: maxFileSize() ?>;
        </script>
    </head>
    <body class="game">
        <?php require_once 'inc/header.php'; ?>
        <div id="background">
            <div id="ajouterMatch">
                <h1 class="display-3">Ajouter un match</h1>
                <div class="erreur"><?= implode('<br/>', $tabErreur) ?></div>
                <form name="form1" action="ajoutermatch.php" method="post" enctype="multipart/form-data" >

                    <input class="form-control" type="hidden" name="id_match" value="<?= $match->id_match ?>"/>


                    <div class="col-md-2 mb-3">
                        <label>Date match</label>
                        <input class="form-control" name="date_match" size="10" value="<?= $match->date_match ?: date('Y-m-d') ?>"/>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label>Phase</label>
                        <select class="form-control" name="id_phase">
                            <?php
                            foreach ($tabPhase as $ph) {
                                $selected = $ph->id_phase == $ph->id_phase ? 'selected="selected"' : '';
                                ?>
                                <option value="<?= $ph->id_phase ?>" <?= $selected ?>>
                                    <?= $ph->libelle ?>
                                </option>
                                <?php
                            }
                            ?>
                        </select>
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
                        <label>Résultat</label>
                        <select class="form-control" name="id_resultat_my_team">
                            <?php
                            foreach ($tabResultat as $res) {
                                $selected = $res->id_resultat_my_team == $res->id_resultat_my_team ? 'selected="selected"' : '';
                                ?>
                                <option value="<?= $res->id_resultat_my_team ?>" <?= $selected ?>>
                                    <?= $res->libelle ?>
                                </option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>Points My Team</label>
                        <input class="form-control" type="number" name="points_my_team" value="<?= $match->points_my_team ?>" size="4" step="1" min="0" max="250" required="required" />
                    </div>
                    <div class="col-md-2 mb-3">
                        <label>Points équipe adverse</label>
                        <input class="form-control" type="number" name="points_equipe_adverse" value="<?= $match->points_equipe_adverse ?>" size="4" step="1" min="0" max="250" required="required" />
                    </div>

                    <div class="item">
                        <label>Photo (JPEG)</label>
                        <input type="file" id="photo" value="photo" name="photo" onchange="afficherPhoto(this.files)"/>
<!--                        <input type="button" value="Parcourir..." onclick="this.form.photo.click()"/>-->
                    </div>


                    <div class="item">
                        <label></label>
                        <div>
                            <input class="btn btn-danger"type="button" value="Annuler" onclick="annuler(<?= $match->id_match ?>)"/>
                            <input class="btn btn-light" type="submit" name="submit" value="Valider"/>
                        </div>
                    </div>
                </form>
                <div id="vignette" style="background-image: url(img/memb_<?= $idImg ?>_v.jpg?alea<?= rand() ?>)">

                </div>
            </div>
        </div>
    </body>
</html>
