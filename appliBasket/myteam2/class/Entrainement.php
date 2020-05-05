<?php

class Entrainement implements Databasable {

    public $id_entrainement;
    public $id_type_entrainement;
    public $date_entrainement;

    public function __construct($id_entrainement = null, $id_type_entrainement = null, $date_entrainement = null) {

        $this->id_entrainement = $id_entrainement;
        $this->id_type_entrainement = $id_type_entrainement;
        $this->date_entrainement = $date_entrainement;
    }

    public function charger() {
        if (!$this->id_entrainement)
            return false;
        $req = "SELECT * FROM entrainement WHERE id_entrainement={$this->id_entrainement}";
        return Connexion::getInstance()->xeq($req)->ins($this);
    }

    public function sauver() {
        $cnx = Connexion::getInstance();
        if ($this->id_entrainement) {
            $req = "UPDATE entrainement SET id_type_entrainement ={$this->id_type_entrainement}, date_entrainement = {$cnx->esc($this->date_entrainement)} WHERE id_entrainement = {$this->id_entrainement}";
            $cnx->xeq($req);
        } else {
            $req = "INSERT INTO entrainement VALUES(DEFAULT,{$this->id_type_entrainement},{$cnx->esc($this->date_entrainement)})";
            $this->id_entrainement = $cnx->xeq($req)->pk();
        }
        return $this;
    }

    public function getType() {
        $req = "SELECT * FROM entrainement, type_entrainement WHERE {$this->id_type_entrainement}=type_entrainement.id_type_entrainement";
        return Connexion::getInstance()->xeq($req)->prem();
    }

    public function getDateNextTraining() {
        $req = "SELECT * FROM entrainement JOIN type_entrainement USING(id_type_entrainement) WHERE DAYOFWEEK(date_entrainement)=4 AND WEEK(date_entrainement) = WEEK(CURDATE()) OR DAYOFWEEK(date_entrainement)=6 AND WEEK(date_entrainement) = WEEK(CURDATE())  ;";
        return Connexion::getInstance()->xeq($req)->prem();
    }

    public function getDateParticipation() {
        $req = "SELECT DISTINCT date_entrainement FROM entrainement WHERE WEEK(date_entrainement)=WEEK(CURDATE());";
        return Connexion::getInstance()->xeq($req)->prem();
    }

    public function getDate() {
        $req = "SELECT * FROM entrainement WHERE MONTH(date_entrainement)= MONTH(CURDATE())";
        return Connexion::getInstance()->xeq($req)->prem();
    }

    public function getTrainingDay() {
        $req = "SELECT * FROM entrainement WHERE WEEK(date_entrainement) AND WEEK(date_entrainement) = WEEK(CURDATE()) LIMIT 2;";
        return Connexion::getInstance()->xeq($req)->prem();
    }

    public function supprimer() {
        if (!$this->id_entrainement)
            return false;
        $req = "DELETE FROM entrainement WHERE id_entrainement = {$this->id_entrainement}";
        return (bool) Connexion::getInstance()->xeq($req)->nb();
    }

    public static function tab($where = 1, $orderBy = 1, $limit = null) {
        $req = "SELECT * FROM entrainement WHERE {$where} ORDER BY {$orderBy}" . ($limit ? " LIMIT {$limit}" : '');
        return Connexion::getInstance()->xeq($req)->tab(__CLASS__);
    }

    public static function tous() {
        //aprÃ©s amÃ©lioration.
        return Entrainement::tab(1, 'date_entrainement');
    }

    public static function getMembreEntrainement() {
        return MembreEntrainement::tous();
    }

}
