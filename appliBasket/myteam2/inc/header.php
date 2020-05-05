<header class="header">


            <nav class="navbar navbar-expand-lg navbar-trans ">

                <ul class="navbar-nav mt-2 mt-md-0">
                    <div><h4>Bonjour <?= Cfg::$user->prenom ?> !</h4></div>
                    <div><a class="nav-link" href="index.php">Accueil</a></div>
                    <div><a class="nav-link" href="equipe.php">Equipe</a></div>
                    <?php
                    if (Cfg::$user->id_niveau > 1) {
                        ?>
                        <div><a class="nav-link" href="ajoutermembre.php">Ajouter Membres</a></div>
                        <div><a class="nav-link" href="ajoutertraining.php">Ajouter Entrainements</a></div>
                        <div><a class="nav-link" href="ajouterdategame.php">Ajouter dates matchs</a></div>
                        <div><a class="nav-link" href="ajoutermatch.php">Ajouter Matchs</a></div>
                        <div><a class="nav-link" href="ajouterstats.php">Ajouter Stats</a></div>

                        <?php
                    }
                    ?>
                    <div><a class="nav-link" href="entrainement.php">Entrainements</a></div>
                    <div><a class="nav-link" href="calendrier.php">Calendrier</a></div>
                    <div><a class="nav-link" href="match.php">Résultats</a></div>
                    <div><a class="nav-link" href="photos.php">Photos</a></div>
                    <div class="logout">

                        <a class="btn btn-outline-white btn-outline" href="logout.php">Se déconnecter</a>
                    </div>


                </ul>
            </nav>


</header>
