<?php

require_once 'class/Cfg.php';
if (!Cfg::$user)
    exit;
$opt = ['min_range' => 1];
$id_membre = filter_input(INPUT_GET, 'id_membre', FILTER_VALIDATE_INT, $opt);
(new Membre($id_membre))->supprimer();
@unlink("img/memb_{$id_membre}_v.jpg"); // '@' operateur de suppression d'erreur.
@unlink("img/memb_{$id_membre}_p.jpg");
