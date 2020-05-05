<?php

class Niveau implements Databasable {

    public $id_niveau;
    public $libelle;

    public function __construct($id_niveau = null, $libelle = null) {
        $this->id_niveau = $id_niveau;
        $this->libelle = $libelle;
    }

    public function charger() {
        if (!$this->id_niveau)
            return false;
        $req = "SELECT * FROM niveau WHERE id_niveau={$this->id_niveau}";
        return Connexion::getInstance()->xeq($req)->ins($this);
    }

    public function sauver() {
        $cnx = Connexion::getInstance();
        if ($this->id_niveau) {
            $req = "UPDATE niveau SET libelle = {$cnx->esc($this->libelle)} WHERE id_niveau = {$this->id_niveau}";
            $cnx->xeq($req);
        } else {
            $req = "INSERT INTO niveau VALUES(DEFAULT,{$cnx->esc($this->libelle)})";
            $this->id_niveau = $cnx->xeq($req)->pk();
        }
        return $this;
    }

    public function supprimer() {
        if (!$this->id_niveau)
            return false;
        $req = "DELETE FROM niveau WHERE id_niveau = {$this->id_niveau}";
        return (bool) Connexion::getInstance()->xeq($req)->nb();
    }

    public static function tab($where = 1, $orderBy = 1, $limit = null) {
        $req = "SELECT * FROM niveau WHERE {$where} ORDER BY {$orderBy}" . ($limit ? " LIMIT {$limit}" : '');
        return Connexion::getInstance()->xeq($req)->tab(__CLASS__);
    }

    public static function tous() {
        return Niveau::tab(1, 'libelle');
    }

}
