<?php

class Poste implements Databasable {

    public $id_poste;
    public $libelle;

    public function __construct($id_poste = null, $libelle = null) {
        $this->id_poste = $id_poste;
        $this->libelle = $libelle;
    }

    public function charger() {
        if (!$this->id_poste)
            return false;
        $req = "SELECT * FROM poste WHERE id_poste={$this->id_poste}";
        return Connexion::getInstance()->xeq($req)->ins($this);
    }

    public function sauver() {
        $cnx = Connexion::getInstance();
        if ($this->id_poste) {
            $req = "UPDATE poste SET libelle = {$cnx->esc($this->libelle)} WHERE id_poste = {$this->id_poste})";
            $cnx->xeq($req);
        } else {
            $req = "INSERT INTO poste VALUES(DEFAULT,{$cnx->esc($this->libelle)}";
            $this->id_poste = $cnx->xeq($req)->pk();
        }
        return $this;
    }

    public function supprimer() {
        if (!$this->id_poste)
            return false;
        $req = "DELETE FROM poste WHERE id_poste = {$this->id_poste}";
        return (bool) Connexion::getInstance()->xeq($req)->nb();
    }

    public static function tab($where = 1, $orderBy = 1, $limit = null) {
        $req = "SELECT * FROM poste WHERE {$where} ORDER BY {$orderBy}" . ($limit ? " LIMIT {$limit}" : '');
        return Connexion::getInstance()->xeq($req)->tab(__CLASS__);
    }

    public static function tous() {
        return Poste::tab(1, 'libelle');
    }

}
