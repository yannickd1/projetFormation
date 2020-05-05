<?php
require_once 'class/Cfg.php';
if (!Cfg::$user) {
    header("Location: login.php");
    exit;
}
$cnx = Connexion::getInstance();
$tabErreur = [];
$membre = new Membre();

$opt = ['min_range' => 1];

$membre->id_membre = filter_input(INPUT_GET, 'id_membre', FILTER_VALIDATE_INT, $opt);
$membre->id_entrainement = filter_input(INPUT_POST, 'id_entrainement', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$membre->id_poste = filter_input(INPUT_POST, 'id_poste', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$membre->id_my_team = filter_input(INPUT_POST, 'id_my_team', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$membre->id_niveau = filter_input(INPUT_POST, 'id_niveau', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$membre->id_statut = filter_input(INPUT_POST, 'id_statut', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$membre->log = filter_input(INPUT_POST, 'log', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$membre->mdp = filter_input(INPUT_POST, 'mdp', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$membre->nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$membre->prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$membre->taille_en_cm = filter_input(INPUT_POST, 'taille_en_cm', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$membre->date_inscription = filter_input(INPUT_POST, 'date_inscription', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$tabDateInscription = date_parse_from_format('Y-m-d', $membre->date_inscription);
//arrivée en POST après validation du formulaire

if (filter_input(INPUT_POST, 'submit')) {
    if (!$membre->id_entrainement) {
        $tabErreur[] = "Date entrainement absente";
    }
    if (!$membre->id_poste) {
        $tabErreur[] = "Poste absent";
    }
    if (!$membre->id_my_team) {
        $tabErreur[] = "Equipe absente";
    }
    if (!$membre->id_niveau) {
        $tabErreur[] = "Niveau absent";
    }
    if (!$membre->id_statut) {
        $tabErreur[] = "Statut absent";
    }
    if (!$membre->log) {
        $tabErreur[] = "Login absent ou invalide";
    }
    if (!$membre->mdp) {
        $tabErreur[] = "Mot de passe absent ou invalide";
    }
    if (!$membre->nom) {
        $tabErreur[] = "Nom absent";
    }
    if (!$membre->prenom) {
        $tabErreur[] = "Prenom absent";
    }
    if (!$membre->taille_en_cm) {
        $tabErreur[] = "Taille absente ou invalide";
    }
    if ($tabDateInscription['errors']) {
        $tabErreur[] = "Date absente ou invalide";
    } else {
        $annee = $tabDateInscription['year'];
        $mois = $tabDateInscription['month'];
        $jour = $tabDateInscription['day'];
        if (!$membre->date_inscription || !checkdate($mois, $jour, $annee))
            $tabErreur[] = "Date absente ou invalide";
    }

    if (!$tabErreur) {
        $membre->mdp = password_hash($membre->mdp, PASSWORD_DEFAULT);
        $cnx->start();
        $membre->sauver();

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
                $image->copier(Cfg::IMG_V_LARGEUR, Cfg::IMG_V_HAUTEUR, "img/memb_{$membre->id_membre}_v.jpg");
                $image->copier(Cfg::IMG_P_LARGEUR, Cfg::IMG_P_HAUTEUR, "img/memb_{$membre->id_membre}_p.jpg");
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
elseif ($membre->id_membre && !$membre->charger()) {
    header("Location:equipe.php");
    exit;
}

$tabPoste = Poste::tous();
$tabStatut = Statut::tous();
$tabNiveau = Niveau::tous();
$tabTeam = MyTeam::tous();
$tabEntrainement = Entrainement::tous();
$idImg = file_exists("img/memb_{$membre->id_membre}_v.jpg") ? $membre->id_membre : 0;
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
        <script>
            const TAB_EXT = JSON.parse(`<?= json_encode(Cfg::TAB_EXT) ?>`);
            const TAB_MIME = JSON.parse(`<?= json_encode(Cfg::TAB_MIME) ?>`);
            const MAX_FILE_SIZE = <?= Upload:: maxFileSize() ?>;
        </script>
        <meta charset="UTF-8">
        <title>My Team</title>
    </head>
    <body>
        <?php require_once 'inc/header.php' ?>
        <div id="background">
            <div id="ajouterMembre">
                <h1 class="display-3">Ajouter un membre</h1>
                <div class="erreur"><?= implode('<br/>', $tabErreur) ?></div>
                <form name="form1" action="ajoutermembre.php" method="post" enctype="multipart/form-data">

                    <input class="form-control" type="hidden" name="id_membre" value="<?= $membre->id_membre ?>"/>
                    <input class="form-control" type="hidden" name="id_entrainement" value="<?= $membre->id_entrainement ?>"/>

                    <div class="col-md-2 mb-3">
                        <label>Entrainement</label>
                        <select class="form-control" name="id_entrainement">
                            <?php
                            foreach ($tabEntrainement as $entr) {
                                $selected = $membre->id_entrainement == $entr->id_entrainement ? 'selected="selected"' : '';
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
                        <label>Equipe</label>
                        <select class="form-control" name="id_my_team" >
                            <?php
                            foreach ($tabTeam as $team) {
                                $selected = $membre->id_my_team == $team->id_my_team ? 'selected="selected"' : '';
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
                        <label>Niveau d'accés</label>
                        <select class="form-control" name="id_niveau">
                            <?php
                            foreach ($tabNiveau as $niv) {
                                $selected = $membre->id_niveau == $niv->id_niveau ? 'selected="selected"' : '';
                                ?>
                                <option value="<?= $niv->id_niveau ?>" <?= $selected ?>>
                                    <?= $niv->libelle ?>
                                </option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>Statut</label>
                        <select class="form-control" name="id_statut">
                            <?php
                            foreach ($tabStatut as $stat) {
                                $selected = $membre->id_statut == $stat->id_statut ? 'selected="selected"' : '';
                                ?>
                                <option value="<?= $stat->id_statut ?>" <?= $selected ?>>
                                    <?= $stat->libelle ?>
                                </option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>


                    <div class="col-md-2 mb-3">
                        <label>Login</label>
                        <input class="form-control" name="log" value="<?= $membre->log ?>"/>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>Mot de passe</label>
                        <input class="form-control" name="mdp" size="10" value="<?= $membre->mdp ?>"/>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>Nom</label>
                        <input class="form-control" name="nom" maxlength="20" value="<?= $membre->nom ?>" required="required"/>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>Prenom</label>
                        <input class="form-control" name="prenom" maxlength="20" value="<?= $membre->prenom ?>" required="required"/>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>Poste</label>
                        <select class="form-control" name="id_poste">
                            <?php
                            foreach ($tabPoste as $poste) {
                                $selected = $membre->id_poste == $poste->id_poste ? 'selected="selected"' : '';
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
                        <label>Taille en cm</label>
                        <input class="form-control" type="number" name="taille_en_cm" value="<?= $membre->taille_en_cm ?>" size="4" step="1" min="100" max="250" required="required" />
                    </div>

                    <div class="col-md-2 mb-3">
                        <label>Date inscription</label>
                        <input class="form-control" name="date_inscription" size="10" value="<?= $membre->date_inscription ?: date('d-m-Y') ?>"/>
                    </div>
                    <div class="item">
                        <label>Photo (JPEG)</label>
                        <input type="file" id="photo" value="photo" name="photo" onchange="afficherPhoto(this.files)"/>
                        <!--<input type="button" value="Parcourir..." onclick="this.form.photo.click()"/>-->
                    </div>
                    <div class="item">
                        <label></label>

                        <div>
                            <input class="btn btn-danger" type="button" value="<?= I18n::get('FORM_LABEL_CANCEL') ?>" onclick="annuler(<?= $membre->id_membre ?>)"/>
                            <input class="btn btn-light" type="submit" name="submit" value="<?= I18n::get('FORM_LABEL_SUBMIT') ?>"/>

                        </div>


                    </div>

                </form>
                <div id="vignette" style="background-image: url(img/memb_<?= $idImg ?>_v.jpg?alea<?= rand() ?>)">

                </div>

            </div>
        </div>
    </body>
</html>
