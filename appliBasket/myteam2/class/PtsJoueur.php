<?php

class PtsJoueur implements Databasable {

    public $id_pts_joueur;
    public $id_membre;
    public $id_match;
    public $points;

    public function __construct($id_pts_joueur = null, $id_membre = null, $id_match = null, $points = null) {
        $this->id_pts_joueur = $id_pts_joueur;
        $this->id_membre = $id_membre;
        $this->id_match = $id_match;
        $this->points = $points;
    }

    public function charger() {
        if (!$this->id_pts_joueur)
            return false;
        $req = "SELECT * FROM pts_joueur WHERE id_pts_joueur={$this->id_pts_joueur}";
        return Connexion::getInstance()->xeq($req)->ins($this);
    }

    public function sauver() {
        $cnx = Connexion::getInstance();
        if ($this->id_pts_joueur) {
            $req = "UPDATE pts_joueur SET points = {$this->points} WHERE id_pts_joueur = {$this->id_pts_joueur}";
            $cnx->xeq($req);
        } else {
            $req = "INSERT INTO pts_joueur VALUES(DEFAULT,{$this->id_membre},{$this->id_match},{$this->points})";
            $this->id_pts_joueur = $cnx->xeq($req)->pk();
        }
        return $this;
    }

    public function supprimer() {
        if (!$this->id_pts_joueur)
            return false;
        $req = "DELETE FROM pts_joueur WHERE id_pts_joueur = {$this->id_pts_joueur}";
        return (bool) Connexion::getInstance()->xeq($req)->nb();
    }

    public function getNom() {
        $req = "SELECT * FROM pts_joueur, membre WHERE {$this->id_membre}=membre.id_membre";
        return Connexion::getInstance()->xeq($req)->prem();
    }

    public function getDate() {
        $req = "SELECT * FROM pts_joueur,`match` WHERE {$this->id_match}=match.id_match";
        return Connexion::getInstance()->xeq($req)->prem();
    }

    public function bestScoringLastGame() {
        $req = "SELECT * FROM pts_joueur,`match` ORDER BY date_match DESC, points DESC LIMIT 1";
        return Connexion::getInstance()->xeq($req)->prem();
    }

    public function bestPlayerLastGame() {
        $req = "SELECT * FROM pts_joueur, membre WHERE pts_joueur.id_membre=membre.id_membre ORDER BY id_match DESC, points DESC LIMIT 1";
        return Connexion::getInstance()->xeq($req)->prem();
    }

    public static function tab($where = 1, $orderBy = 1, $limit = null) {
        $req = "SELECT * FROM pts_joueur WHERE {$where} ORDER BY {$orderBy}" . ($limit ? " LIMIT {$limit}" : '');
        return Connexion::getInstance()->xeq($req)->tab(__CLASS__);
    }

    public static function tous() {
        return PtsJoueur::tab(1, 'id_pts_joueur');
    }

}
