<?php

class Type implements Databasable {

    public $id_id_type_entrainement;
    public $libelle;

    public function __construct($id_type_entrainement = null, $libelle = null) {
        $this->id_type_entrainement = $id_type_entrainement;
        $this->libelle = $libelle;
    }

    public function charger() {
        if (!$this->id_type_entrainement)
            return false;
        $req = "SELECT * FROM type_entrainement WHERE id_type_entrainement={$this->id_type_entrainement}";
        return Connexion::getInstance()->xeq($req)->ins($this);
    }

    public function sauver() {
        $cnx = Connexion::getInstance();
        if ($this->id_type_entrainement) {
            $req = "UPDATE type_entrainement SET libelle = {$cnx->esc($this->libelle)} WHERE id_type_entrainement = {$this->id_type_entrainement}";
            $cnx->xeq($req);
        } else {
            $req = "INSERT INTO type_entrainement VALUES(DEFAULT,{$this->id_type_entrainement},{$cnx->esc($this->libelle)}";
            $this->id_type_entrainement = $cnx->xeq($req)->pk();
        }
        return $this;
    }

    public function supprimer() {
        if (!$this->id_type_entrainement)
            return false;
        $req = "DELETE FROM type_entrainement WHERE id_type_entrainement = {$this->id_type_entrainement}";
        return (bool) Connexion::getInstance()->xeq($req)->nb();
    }

    public static function tab($where = 1, $orderBy = 1, $limit = null) {
        $req = "SELECT * FROM type_entrainement WHERE {$where} ORDER BY {$orderBy}" . ($limit ? " LIMIT {$limit}" : '');
        return Connexion::getInstance()->xeq($req)->tab(__CLASS__);
    }

    public static function tous() {
        return Type::tab(1, 'libelle');
    }

}
