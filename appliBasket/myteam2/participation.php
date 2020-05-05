<?php
require_once 'class/Cfg.php';
//if (!Cfg::$user) {
//    header("Location: login.php");
//    exit;
//}
$cnx = Connexion::getInstance();
$tabErreur = [];
$participant = new Participant();

$opt = ['min_range' => 1];

$participant->id_participant = filter_input(INPUT_GET, 'id_participant', FILTER_VALIDATE_INT, $opt);
$participant->id_entrainement = filter_input(INPUT_POST, 'id_entrainement', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$participant->id_poste = filter_input(INPUT_POST, 'id_poste', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$participant->nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$participant->prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$participant->annee_naissance = filter_input(INPUT_POST, 'annee_naissance', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$participant->telephone = filter_input(INPUT_POST, 'telephone', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

//arrivée en POST après validation du formulaire

if (filter_input(INPUT_POST, 'submit')) {
    if (!$participant->id_entrainement) {
        $tabErreur[] = "Date entrainement absente";
    }
    if (!$participant->id_poste) {
        $tabErreur[] = "Poste absent";
    }
    if (!$participant->nom) {
        $tabErreur[] = "Nom absent";
    }
    if (!$participant->prenom) {
        $tabErreur[] = "Prenom absent";
    }
    if (!$participant->annee_naissance) {
        $tabErreur[] = "Année de naissance absente ou invalide";
    }
    if (!$participant->telephone) {
        $tabErreur[] = "Numéro de téléphone absent ou invalide";
    }

    $participant->sauver();
    header("location:index.php");
    exit;
}

$tabPoste = Poste::tous();
$tabTeam = MyTeam::tous();
$tabEntrainement = Entrainement::tous();
?>

<!DOCTYPE html>
<html>
    <head>
        <link href="css/myteamstyle.css" rel="stylesheet" type="text/css"/>
        <script src="js/myteam.js" type="text/javascript"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Myteam</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <meta charset="UTF-8">
        <title>My Team</title>
    </head>
    <body>

        <div id="background">
            <div id="sinscrire">
                <h1 class="display-3">S'inscrire à un entrainement</h1>
                <div class="erreur"><?= implode('<br/>', $tabErreur) ?></div>
                <form name="form1" action="participation.php" method="post" enctype="multipart/form-data">

                    <input class="form-control" type="hidden" name="id_participant" value="<?= $participant->id_participant ?>"/>
                    <input class="form-control" type="hidden" name="id_entrainement" value="<?= $participant->id_entrainement ?>"/>

                    <div class="col-md-2 mb-3">
                        <label>Entrainement</label>
                        <select class="form-control" name="id_entrainement">
                            <?php
                            foreach ($tabEntrainement as $entr) {
                                $selected = $participant->id_entrainement == $entr->id_entrainement ? 'selected="selected"' : '';
                                ?>
                                <option value="<?= $entr->id_entrainement ?>" <?= $selected ?>>
                                    <?= $entr->date_entrainement ?>

                                </option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>


                    <div class="col-md-2 mb-3">
                        <label>Nom</label>
                        <input class="form-control" name="nom" maxlength="20" value="<?= $participant->nom ?>" required="required"/>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>Prenom</label>
                        <input class="form-control" name="prenom" maxlength="20" value="<?= $participant->prenom ?>" required="required"/>
                    </div>


                    <div class="col-md-2 mb-3">
                        <label>Année de naissance</label>
                        <input class="form-control" type="number" name="annee_naissance" value="<?= $participant->annee_naissance ?>" size="4" step="1" min="1900" max="3000" required="required" />
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>Poste</label>
                        <select class="form-control" name="id_poste">
                            <?php
                            foreach ($tabPoste as $poste) {
                                $selected = $participant->id_poste == $poste->id_poste ? 'selected="selected"' : '';
                                ?>
                                <option value="<?= $poste->id_poste ?>" <?= $selected ?>>
                                    <?= $poste->libelle ?>
                                </option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>Numéro de téléphone</label>
                        <input class="form-control" type="tel" name="telephone" size="10" value="<?= $participant->telephone ?>"/>
                    </div>


                    <div class="item">
                        <label></label>

                        <div id="button">
                            <input class="btn btn-danger" type="button" value="<?= I18n::get('FORM_LABEL_CANCEL') ?>" onclick="annuler(<?= $participant->id_participant ?>)"/>
                            <input class="btn btn-light" type="submit" name="submit" value="S'inscrire"/>

                        </div>
                    </div>
                </form>


            </div>

        </div>



    </body>
</html>
