<?php

require_once 'class/Cfg.php';
if (!Cfg::$user)
    exit;
$opt = ['min_range' => 1];
$id_membre = filter_input(INPUT_GET, 'id_membre', FILTER_VALIDATE_INT, $opt);
if ($id_membre) {
    @unlink("img/memb_{$id_membre}_v.jpg");
    @unlink("img/memb_{$id_membre}_p.jpg");
}
