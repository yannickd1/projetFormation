<?php

class Lieu implements Databasable {

    public $id_lieu;
    public $libelle;

    public function __construct($id_lieu = null, $libelle = null) {
        $this->id_lieu = $id_lieu;
        $this->libelle = $libelle;
    }

    public function charger() {
        if (!$this->id_lieu)
            return false;
        $req = "SELECT * FROM lieu WHERE id_lieu={$this->id_lieu}";
        return Connexion::getInstance()->xeq($req)->ins($this);
    }

    public function sauver() {
        $cnx = Connexion::getInstance();
        if ($this->id_lieu) {
            $req = "UPDATE lieu SET libelle = {$cnx->esc($this->libelle)} WHERE id_lieu = {$this->id_lieu}";
            $cnx->xeq($req);
        } else {
            $req = "INSERT INTO lieu VALUES(DEFAULT,{$cnx->esc($this->libelle)})";
            $this->id_lieu = $cnx->xeq($req)->pk();
        }
        return $this;
    }

    public function supprimer() {
        if (!$this->id_lieu)
            return false;
        $req = "DELETE FROM lieu WHERE id_lieu = {$this->id_lieu}";
        return (bool) Connexion::getInstance()->xeq($req)->nb();
    }

    public static function tab($where = 1, $orderBy = 1, $limit = null) {
        $req = "SELECT * FROM lieu WHERE {$where} ORDER BY {$orderBy}" . ($limit ? " LIMIT {$limit}" : '');
        return Connexion::getInstance()->xeq($req)->tab(__CLASS__);
    }

    public static function tous() {
        return Lieu::tab(1, 'libelle');
    }

}
