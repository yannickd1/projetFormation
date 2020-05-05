<?php

class Phase implements Databasable {

    public $id_phase;
    public $libelle;

    public function __construct($id_phase = null, $libelle = null) {
        $this->id_phase = $id_phase;
        $this->libelle = $libelle;
    }

    public function charger() {
        if (!$this->id_phase)
            return false;
        $req = "SELECT * FROM statut WHERE id_phase={$this->id_phase}";
        return Connexion::getInstance()->xeq($req)->ins($this);
    }

    public function sauver() {
        $cnx = Connexion::getInstance();
        if ($this->id_phase) {
            $req = "UPDATE phase SET libelle = {$cnx->esc($this->libelle)} WHERE id_phase = {$this->id_phase}";
            $cnx->xeq($req);
        } else {
            $req = "INSERT INTO phase VALUES(DEFAULT,{$cnx->esc($this->libelle)})";
            $this->id_phase = $cnx->xeq($req)->pk();
        }
        return $this;
    }

    public function supprimer() {
        if (!$this->id_phase)
            return false;
        $req = "DELETE FROM phase WHERE id_phase = {$this->id_phase}";
        return (bool) Connexion::getInstance()->xeq($req)->nb();
    }

    public static function tab($where = 1, $orderBy = 1, $limit = null) {
        $req = "SELECT * FROM phase WHERE {$where} ORDER BY {$orderBy}" . ($limit ? " LIMIT {$limit}" : '');
        return Connexion::getInstance()->xeq($req)->tab(__CLASS__);
    }

    public static function tous() {
        return Phase::tab(1, 'libelle');
    }

}
