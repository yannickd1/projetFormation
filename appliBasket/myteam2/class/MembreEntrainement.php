<?php

class MembreEntrainement implements Databasable {

    public $id_membre_entrainement;
    public $id_membre;
    public $id_entrainement;

    public function __construct($id_membre_entrainement = null, $id_membre = null, $id_entrainement = null) {

        $this->id_membre_entrainement = $id_membre_entrainement;
        $this->id_membre = $id_membre;
        $this->id_entrainement = $id_entrainement;
    }

    public function charger() {
        if (!$this->id_membre_entrainement)
            return false;
        $req = "SELECT * FROM membre_entrainement WHERE id_membre_entrainement={$this->id_membre_entrainement}";
        return Connexion::getInstance()->xeq($req)->ins($this);
    }

    public function sauver() {
        $cnx = Connexion::getInstance();
        if ($this->id_membre_entrainement) {
            $req = "UPDATE membre_entrainement SET id_membre ={$this->id_membre}, id_entrainement = {$cnx->esc($this->id_entrainement)} WHERE id_membre_entrainement = {$this->id_membre_entrainement}";
            $cnx->xeq($req);
        } else {
            $req = "INSERT INTO membre_entrainement VALUES(DEFAULT, {$cnx->esc($this->id_membre)},{$cnx->esc($this->id_entrainement)})";
            $this->id_membre_entrainement = $cnx->xeq($req)->pk();
        }
        return $this;
    }

    public function supprimer() {
        if (!$this->id_membre_entrainement)
            return false;
        $req = "DELETE FROM membre_entrainement WHERE id_membre_entrainement = {$this->id_membre_entrainement}";
        return (bool) Connexion::getInstance()->xeq($req)->nb();
    }

    public static function tab($where = 1, $orderBy = 1, $limit = null) {
        $req = "SELECT * FROM membre_entrainement WHERE {$where} ORDER BY {$orderBy}" . ($limit ? " LIMIT {$limit}" : '');
        return Connexion::getInstance()->xeq($req)->tab(__CLASS__);
    }

    public function getNom() {
        $req = "SELECT * FROM membre_entrainement,membre WHERE {$this->id_membre}=membre.id_membre";
        return Connexion::getInstance()->xeq($req)->prem();
    }

    public function getDate() {
        $req = "SELECT * FROM membre_entrainement,entrainement WHERE {$this->id_entrainement}=entrainement.id_entrainement";
        return Connexion::getInstance()->xeq($req)->prem();
    }

    public static function getMembre() {
        return Membre::tous();
    }

    public static function tous() {

        return MembreEntrainement::tab(1, 'id_membre_entrainement');
    }

    public function getParticipant() {

        $req = "SELECT COUNT(DISTINCT(id_membre)) AS nb FROM membre_entrainement JOIN entrainement USING(id_entrainement) WHERE DAYOFWEEK(date_entrainement)=4 OR DAYOFWEEK(date_entrainement)=6 AND WEEK(date_entrainement) = WEEK(CURDATE());";
        return Connexion::getInstance()->xeq($req)->prem()->nb;
    }

}
