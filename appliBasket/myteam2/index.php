<?php
require_once 'class/Cfg.php';
if (!Cfg::$user) {
    header('Location:login.php');
    exit;
}


$dernierInscrit = Membre::derniereInscription();
$idImg = file_exists("img/memb_{$dernierInscrit->id_membre}_v.jpg") ? $dernierInscrit->id_membre : 0;
$opt = ['min_range' => 1];
$id_membre = filter_input(INPUT_GET, 'id_membre', FILTER_VALIDATE_INT, $opt);

$match = new Match();
$dategame = new DateGame();
$pts = new PtsJoueur();
$training = new MembreEntrainement();
$train = new Entrainement();
$visiteur = new Participant();
$id = file_exists("img/memb_{$pts->bestPlayerLastGame()->id_membre}_v.jpg") ? $pts->bestPlayerLastGame()->id_membre : 0;
$memb = new Membre();
$playerImg = file_exists("img/memb_{$memb->gestionMaillots()->id_membre}_v.jpg") ? $memb->gestionMaillots()->id_membre : 0;
$gestion = new Membre();
$tabGouter = Membre::tous();

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
      
      <div class="MyTeamTitle">
        <h1 id="loginTitle">
          My Team
        </h1>
        </div>

        <?php require_once 'inc/header.php' ?>
        <h1 id="titre" class="display-3">Accueil</h1>
        <div id="background">
            <div><br></div>
            <div id="accueil">


                <div>
                    <h1 class="display-3">Entrainement : </h1>
                    <p>Prochain entrainement le : <?= $train->getDateNextTraining()->date_entrainement ?></p>
                    <p>Type entrainement : <?= $train->getDateNextTraining()->libelle ?></p>
                    <p>Nombre de joueur de l'équipe : <?= $training->getParticipant() ?> joueur(s)</p>
                    <p>Nombre de participants séance d'essai : <?= $visiteur->getVisiteur() ?> participant(s)</p>

                </div>

                <div onclick="detailMatch(<?= $match->dernierMatch()->id_match ?>)">
                    <h1 class="display-3">Dernier match: </h1>
                    <p><?= $match->dernierMatch()->date_match ?></p>
                    <p> Résultat: <?= $match->dernierMatch()->libelle ?> </p>
                    <p> <?= $match->dernierMatch()->nom ?> <?= $match->dernierMatch()->points_my_team ?> - <?= $match->dernierMatch()->points_equipe_adverse ?> <?= $match->dernierMatch()->nom_adv ?> </p>
                </div>

                <div>
                    <h1 class="display-3">Meilleur marqueur du dernier match: </h1>
                    <div onclick="detailMembre(<?= $pts->bestPlayerLastGame()->id_membre ?>)">
                        <img class="imgJoueurAccueil rounded-circle" width="150" height="150"  src="img/memb_<?= $id ?>_v.jpg?alea=<?= rand() ?>">
                        <p>Nom : <?= $pts->bestPlayerLastGame()->prenom ?>  </p>
                        <p>Prenom : <?= $pts->bestPlayerLastGame()->nom ?> </p>
                        <p> Points: <?= $pts->bestScoringLastGame()->points ?> pts </p>
                    </div>
                </div>

                <div>
                    <h1 class="display-3">Prochain match: </h1>
                    <p><?= $dategame->getDateGame()->date_game ?></p>
                    <p>Phase : <?= $dategame->getDatePhase()->libelle ?></p>
                    <p>vs</p>
                    <p><?= $dategame->getDateAdversaire()->nom_adv ?></p>
                    <p>@</p>
                    <p><?= $dategame->getDateLieu()->libelle ?></p>
                </div>

                <h1 class="display-3">Dernier membre inscrit: </h1>
                <div class="" onclick="detailMembre(<?= $dernierInscrit->id_membre ?>)" >
                    <img class="imgJoueurAccueil rounded-circle" width="150" height="150" src="img/memb_<?= $idImg ?>_v.jpg?alea=<?= rand() ?>">
                    <p>Nom : <?= $dernierInscrit->nom ?> </p>
                    <p>Prenom : <?= $dernierInscrit->prenom ?> </p>
                </div>

                <div>
                    <h1 class="display-3">Responable des maillots de la semaine: </h1>
                    <img class="imgJoueurAccueil rounded-circle" width="150" height="150" src="img/memb_<?= $playerImg ?>_v.jpg?alea=<?= rand() ?>">
                    <p>Nom : <?= $gestion->gestionMaillots()->nom ?></p>
                </div>

                <div class="table-responsive-sm">
                    <h1 class="display-3">Gestion du goûter : </h1>
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Joueurs</th>
                                <th scope="col">Produits</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($tabGouter as $gouter) {
                                ?>
                                <tr>
                                    <td><?= $gouter->nom ?></td>
                                    <td><?= $gouter->gestionGouter()->libelle ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <footer></footer>
    </body>
</html>
