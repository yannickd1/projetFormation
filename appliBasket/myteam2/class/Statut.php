<?php

class Statut implements Databasable {

    public $id_statut;
    public $libelle;

    public function __construct($id_statut = null, $libelle = null) {
        $this->id_statut = $id_statut;
        $this->libelle = $libelle;
    }

    public function charger() {
        if (!$this->id_statut)
            return false;
        $req = "SELECT * FROM statut WHERE id_statut={$this->id_statut}";
        return Connexion::getInstance()->xeq($req)->ins($this);
    }

    public function sauver() {
        $cnx = Connexion::getInstance();
        if ($this->id_statut) {
            $req = "UPDATE statut SET libelle = {$cnx->esc($this->libelle)} WHERE id_statut = {$this->id_statut}";
            $cnx->xeq($req);
        } else {
            $req = "INSERT INTO statut VALUES(DEFAULT,{$cnx->esc($this->libelle)}";
            $this->id_statut = $cnx->xeq($req)->pk();
        }
        return $this;
    }

    public function supprimer() {
        if (!$this->id_statut)
            return false;
        $req = "DELETE FROM statut WHERE id_statut = {$this->id_statut}";
        return (bool) Connexion::getInstance()->xeq($req)->nb();
    }

    public static function tab($where = 1, $orderBy = 1, $limit = null) {
        $req = "SELECT * FROM statut WHERE {$where} ORDER BY {$orderBy}" . ($limit ? " LIMIT {$limit}" : '');
        return Connexion::getInstance()->xeq($req)->tab(__CLASS__);
    }

    public static function tous() {
        return Statut::tab(1, 'libelle');
    }

}
