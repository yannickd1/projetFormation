<?php

class Membre implements Databasable {

    public $id_membre;
    public $id_entrainement;
    public $id_poste;
    public $id_niveau;
    public $id_my_team;
    public $id_statut;
    public $log;
    public $mdp;
    public $nom;
    public $prenom;
    public $taille_en_cm;
    public $date_inscription;

    public function __construct($id_membre = null, $id_entrainement = null, $id_poste = null, $id_niveau = null, $id_my_team = null, $id_statut = null, $log = null, $mdp = null, $nom = null, $prenom = null, $taille_en_cm = null, $date_inscription = null) {

        $this->id_membre = $id_membre;
        $this->id_entrainement = $id_entrainement;
        $this->id_poste = $id_poste;
        $this->id_niveau = $id_niveau;
        $this->id_my_team = $id_my_team;
        $this->id_statut = $id_statut;
        $this->log = $log;
        $this->mdp = $mdp;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->taille_en_cm = $taille_en_cm;
        $this->date_inscription = $date_inscription;
    }

    public function charger() {
        if (!$this->id_membre)
            return false;
        $req = "SELECT * FROM membre WHERE id_membre={$this->id_membre}";
        return Connexion::getInstance()->xeq($req)->ins($this);
    }

    public function sauver() {
        $cnx = Connexion::getInstance();
        if ($this->id_membre) {
            $req = "UPDATE membre SET log = {$cnx->esc($this->log)}, mdp = {$cnx->esc($this->mdp)}, nom = {$cnx->esc($this->nom)}, prenom = {$cnx->esc($this->prenom)},taille_en_cm={$this->taille_en_cm}, date_inscription = {$cnx->esc($this->date_inscription)}  WHERE id_membre = {$this->id_membre}";
            $cnx->xeq($req);
        } else {
            $req = "INSERT INTO membre VALUES(DEFAULT,{$this->id_entrainement},{$this->id_poste},{$this->id_niveau},{$this->id_my_team},{$this->id_statut},  {$cnx->esc($this->log)}, {$cnx->esc($this->mdp)}, {$cnx->esc($this->nom)}, {$cnx->esc($this->prenom)}, {$this->taille_en_cm}, {$cnx->esc($this->date_inscription)})";
            $this->id_membre = $cnx->xeq($req)->pk();
        }
        return $this;
    }

    public function supprimer() {
        if (!$this->id_membre)
            return false;
        $req = "DELETE FROM membre WHERE id_membre = {$this->id_membre}";
        return (bool) Connexion::getInstance()->xeq($req)->nb();
    }

    public function getStatut() {
        $req = "SELECT * FROM membre ,statut WHERE {$this->id_statut}=statut.id_statut";
        return Connexion::getInstance()->xeq($req)->prem();
    }

    public static function derniereInscription() {
        $req = "SELECT * FROM `membre` ORDER BY date_inscription DESC LIMIT 2";
        return Connexion::getInstance()->xeq($req)->prem();
    }

    public function getPoste() {
        $req = "SELECT * FROM membre,poste WHERE {$this->id_poste}=poste.id_poste";
        return Connexion::getInstance()->xeq($req)->prem();
    }

    public function gestionMaillots() {
        $req = "SELECT * FROM membre ORDER BY rand(dayofyear(CURRENT_DATE)) limit 1";
        return Connexion::getInstance()->xeq($req)->prem();
    }

    public function gestionGouter() {
        $req = "SELECT * FROM gouter ORDER BY RAND() LIMIT 5";
        return Connexion::getInstance()->xeq($req)->prem();
    }


    public function averagePointPerGame() {
        $req = "SELECT ROUND(AVG(points),1)AS moy FROM `pts_joueur`, membre WHERE pts_joueur.id_membre={$this->id_membre}";
        return Connexion::getInstance()->xeq($req)->prem()->moy;
    }

    public static function tab($where = 1, $orderBy = 1, $limit = null) {
        $req = "SELECT * FROM membre WHERE {$where} ORDER BY {$orderBy}" . ($limit ? " LIMIT {$limit}" : '');
        return Connexion::getInstance()->xeq($req)->tab(__CLASS__);
    }

    public static function tous() {
        return Membre::tab(1, 'id_statut DESC');
    }

    public function login() {
        $_SESSION['id_membre'] = null;
        if (!($this->log || $this->mdp))
            return false;
        $mdp = $this->mdp;
        $cnx = Connexion::getInstance();
        $req = "SELECT * FROM membre WHERE log={$cnx->esc($this->log)}";
        if (!$cnx->xeq($req)->ins($this))
            return false;
        if (!password_verify($mdp, $this->mdp))
            return false;
        $_SESSION['id_membre'] = $this->id_membre;
        return true;
    }

    public static function getUserSession() {
        if (empty($_SESSION['id_membre']))
            return null;
        $membre = new Membre($_SESSION['id_membre']);
        return $membre->charger() ? $membre : null;
    }

}
