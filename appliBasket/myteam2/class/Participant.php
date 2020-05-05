<?php

class Participant implements Databasable {

    public $id_participant;
    public $id_entrainement;
    public $id_poste;
    public $nom;
    public $prenom;
    public $annee_naissance;
    public $telephone;

    public function __construct($id_participant = null, $id_entrainement = null, $id_poste = null, $nom = null, $prenom = null, $annee_naissance = null, $telephone = null) {

        $this->id_participant = $id_participant;
        $this->id_entrainement = $id_entrainement;
        $this->id_poste = $id_poste;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->annee_naissance = $annee_naissance;
        $this->telephone = $telephone;
    }

    public function charger() {
        if (!$this->id_participant)
            return false;
        $req = "SELECT * FROM participant WHERE id_participant={$this->id_participant}";
        return Connexion::getInstance()->xeq($req)->ins($this);
    }

    public function sauver() {
        $cnx = Connexion::getInstance();
        if ($this->id_participant) {
            $req = "UPDATE participant SET nom = {$cnx->esc($this->nom)}, prenom = {$cnx->esc($this->prenom)}, annee_naissance = {$cnx->esc($this->annee_naissance)}, telephone = {$cnx->esc($this->telephone)} WHERE id_participant = {$this->id_participant}";
            $cnx->xeq($req);
        } else {
            $req = "INSERT INTO participant VALUES(DEFAULT,{$this->id_entrainement},{$this->id_poste},{$cnx->esc($this->nom)},{$cnx->esc($this->prenom)},{$cnx->esc($this->annee_naissance)},{$cnx->esc($this->telephone)})";
            $this->id_participant = $cnx->xeq($req)->pk();
        }
        return $this;
    }

    public function supprimer() {
        if (!$this->id_participant)
            return false;
        $req = "DELETE FROM participant WHERE id_participant = {$this->id_participant}";
        return (bool) Connexion::getInstance()->xeq($req)->nb();
    }

    public function getPoste() {
        $req = "SELECT * FROM participant,poste WHERE {$this->id_poste}=poste.id_poste";
        return Connexion::getInstance()->xeq($req)->prem();
    }

    public function getVisiteur() {
        $req = "SELECT COUNT(DISTINCT(id_participant)) AS nb FROM `participant` JOIN entrainement USING(id_entrainement) WHERE DAYOFWEEK(date_entrainement)=4 OR DAYOFWEEK(date_entrainement)=6 AND WEEK(date_entrainement) = WEEK(CURDATE());";
        return Connexion::getInstance()->xeq($req)->prem()->nb;
    }

    public static function tab($where = 1, $orderBy = 1, $limit = null) {
        $req = "SELECT * FROM participant WHERE {$where} ORDER BY {$orderBy}" . ($limit ? " LIMIT {$limit}" : '');
        return Connexion::getInstance()->xeq($req)->tab(__CLASS__);
    }

    public static function tous() {
        return Participant::tab(1, 'id_participant DESC');
    }

    public function login() {
        $_SESSION['id_participant'] = null;
        if (!($this->log || $this->mdp))
            return false;
        $mdp = $this->mdp;
        $cnx = Connexion::getInstance();
        $req = "SELECT * FROM participant WHERE log={$cnx->esc($this->log)}";
        if (!$cnx->xeq($req)->ins($this))
            return false;
        if (!password_verify($mdp, $this->mdp))
            return false;
        $_SESSION['id_participant'] = $this->id_participant;
        return true;
    }

    public static function getUserSession() {
        if (empty($_SESSION['id_participant']))
            return null;
        $participant = new Participant($_SESSION['id_participant']);
        return $participant->charger() ? $participant : null;
    }

}
