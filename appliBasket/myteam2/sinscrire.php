<?php

require_once 'class/Cfg.php';
//if (!Cfg::$user) {
//    header('location:login.php');
//    exit;
//}

$membre = new MembreEntrainement();
$opt = ['min_range' => 1];
$id_membre = filter_input(INPUT_GET, 'id_membre', FILTER_VALIDATE_INT, $opt);
$id_entrainement = filter_input(INPUT_GET, 'id_entrainement', FILTER_VALIDATE_INT, $opt);
$membre->id_membre = $id_membre;
$membre->id_entrainement = $id_entrainement;
if ($membre->sauver()) {
    header('location:index.php');
    exit;
}

