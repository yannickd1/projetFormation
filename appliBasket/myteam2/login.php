<?php
require_once 'class/Cfg.php';
$tabErreur = [];
$user = new Membre();

// Arrivée en POST après validation du formulaire.
if (filter_input(INPUT_POST, 'submit')) {
    $user->log = filter_input(INPUT_POST, 'log', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $user->mdp = filter_input(INPUT_POST, 'mdp', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

    if (!$user->log) {
        $tabErreur[] = I18n::get('FORM_ERR_LOG');
    }
    if (!$user->mdp) {
        $tabErreur[] = I18n::get('FORM_ERR_MDP');
    }
    if (!$tabErreur && $user->login()) {
        header('Location:index.php');
        exit;
    }
    $tabErreur[] = I18n::get('FORM_ERR_LOGIN');
}

$user = null;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" lang="fr">
        <title>MyTeam</title>
        <link href="css/myteamstyle.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>

    </head>

    <body>
      <div class="MyTeamTitle">
        <h1 id="loginTitle">
          My Team
        </h1>
        </div>

        <div id="container">

            <div class="erreur"><?= implode('<br/>', $tabErreur) ?></div>
            <form class="form-signin" name="form1" action="login.php" method="post">


                <div class="item">
                    <label><?= I18n::get('FORM_LABEL_LOGIN') ?></label>
                    <input class="form-control" name="log" maxlength="10" required="required"/>
                </div>
                <div class="item">
                    <label><?= I18n::get('FORM_LABEL_MDP') ?></label>
                    <input class="form-control" type="password" name="mdp" size="10" maxlength="10" required="required"/>
                </div>
                <div class="item">
                    <label></label>
                    <button class="btn btn-lg btn-outline-white btn-block" type="submit" name="submit" value="<?= I18n::get('FORM_LABEL_CONNECT') ?>">CONNECTER</button>

                </div>
                <a href="participation.php">S'inscrire à un entrainement</a>
            </form>

        </div>
    </body>
</html>
