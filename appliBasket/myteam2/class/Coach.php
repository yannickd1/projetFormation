<?php

class Coach implements Databasable {

    public $id_coach;
    public $id_niveau;
    public $id_entrainement;
    public $log;
    public $mdp;
    public $nom;
    public $prenom;

    public function __construct($id_coach = null, $id_niveau = null, $id_entrainement = null, $log = null, $mdp = null, $nom = null, $prenom = null) {
        $this->id_coach = $id_coach;
        $this->id_niveau = $id_niveau;
        $this->id_entrainement = $id_entrainement;
        $this->log = $log;
        $this->mdp = $mdp;
        $this->nom = $nom;
        $this->prenom = $prenom;
    }

    public function charger() {
        if (!$this->id_coach)
            return false;
        $req = "SELECT * FROM coach WHERE id_coach={$this->id_coach}";
        return Connexion::getInstance()->xeq($req)->ins($this);
    }

    public function sauver() {
        $cnx = Connexion::getInstance();
        if ($this->id_coach) {
            $req = "UPDATE coach SET id_coach={$this->id_coach}, log={$cnx->esc($this->log)}, mdp={$cnx->esc($this->mdp)}, nom={$this->nom}, prenom={$this->prenom} WHERE id_coach={$this->id_coach}";
            $cnx->xeq($req);
        } else {
            $req = "INSERT INTO coach VALUES(DEFAULT,{$this->id_niveau},{$this->id_entrainement}, {$cnx->esc($this->log)},{$cnx->esc($this->mdp)}, {$cnx->esc($this->nom)},{$cnx->esc($this->prenom)})";
            $this->id_coach = $cnx->xeq($req)->pk();
        }
        return $this;
    }

    public function supprimer() {
        if (!$this->id_coach)
            return false;
        $req = "DELETE FROM coach WHERE id_coach = {$this->id_coach}";
        return (bool) Connexion::getInstance()->xeq($req)->nb();
    }

    public function infosCoach() {
        if (!$this->id_coach)
            return null;
        $req = "SELECT * FROM coach";
        return Connexion::getInstance()->xeq($req)->prem('Coach');
    }

    public static function tab($where = 1, $orderBy = 1, $limit = null) {
        $req = "SELECT * FROM coach WHERE {$where} ORDER BY {$orderBy}" . ($limit ? " LIMIT {$limit}" : '');
        return Connexion::getInstance()->xeq($req)->tab(__CLASS__);
    }

    public function getCoach() {
        return (new Coach($this->id_coach))->charger();
    }

    public static function tous() {
        return Reunion::tab(1, 'date_coach DESC');
    }

    public function login() {
        $_SESSION['id_coach'] = null;
        if (!($this->log || $this->mdp))
            return false;
        $mdp = $this->mdp;
        $cnx = Connexion::getInstance();
        $req = "SELECT * FROM coach WHERE log={$cnx->esc($this->log)}";
        if (!$cnx->xeq($req)->ins($this))
            return false;
        if (!password_verify($mdp, $this->mdp))
            return false;
        $_SESSION['id_coach'] = $this->id_coach;
        return true;
    }

    public static function getUserSession() {
        if (empty($_SESSION['id_coach']))
            return null;
        $coach = new Coach($_SESSION['id_coach']);
        return $coach->charger() ? $coach : null;
    }

}
