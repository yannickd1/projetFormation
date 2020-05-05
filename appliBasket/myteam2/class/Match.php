<?php

class Match implements Databasable {

    public $id_match;
    public $id_phase;
    public $id_lieu;
    public $id_equipe_adverse;
    public $id_my_team;
    public $id_resultat_my_team;
    public $date_match;
    public $points_my_team;
    public $points_equipe_adverse;

    public function __construct($id_match = null, $id_phase = null, $id_lieu = null, $id_equipe_adverse = null, $id_my_team = null, $id_resultat_my_team = null, $date_match = null, $point_my_team = null, $points_equipe_adverse = null) {

        $this->id_match = $id_match;
        $this->id_phase = $id_phase;
        $this->id_lieu = $id_lieu;
        $this->id_equipe_adverse = $id_equipe_adverse;
        $this->id_my_team = $id_my_team;
        $this->id_resultat_my_team = $id_resultat_my_team;
        $this->date_match = $date_match;
        $this->points_my_team = $point_my_team;
        $this->points_equipe_adverse = $points_equipe_adverse;
    }

    public function charger() {
        if (!$this->id_match)
            return false;
        $req = "SELECT * FROM match WHERE id_match={$this->id_match}";
        return Connexion::getInstance()->xeq($req)->ins($this);
    }

    public function sauver() {
        $cnx = Connexion::getInstance();
        if ($this->id_match) {
            $req = "UPDATE `match` SET date_match = {$cnx->esc($this->date_match)}, points_my_team = {$this->points_my_team}, points_equipe_adverse = {$this->points_equipe_adverse} WHERE id_match = {$this->id_match}";
            $cnx->xeq($req);
        } else {
            $req = "INSERT INTO `match` VALUES(DEFAULT,{$this->id_phase},{$this->id_lieu},{$this->id_equipe_adverse},{$this->id_my_team},{$this->id_resultat_my_team},{$cnx->esc($this->date_match)},{$this->points_my_team},{$this->points_equipe_adverse})";
            $this->id_match = $cnx->xeq($req)->pk();
        }
        return $this;
    }

    public function supprimer() {
        if (!$this->id_match)
            return false;
        $req = "DELETE FROM `match` WHERE id_match ={$this->id_match}";
        return (bool) Connexion::getInstance()->xeq($req)->nb();
    }

    public static function tab($where = 1, $orderBy = 1, $limit = null) {
        $req = "SELECT * FROM `match` WHERE {$where} ORDER BY {$orderBy}" . ($limit ? " LIMIT {$limit}" : '');
        return Connexion::getInstance()->xeq($req)->tab(__CLASS__);
    }

    public static function tous() {
        return Match::tab();
    }

    public function getResultat() {
        $req = "SELECT * FROM `match`,resultat_my_team WHERE resultat_my_team.id_resultat_my_team={$this->id_resultat_my_team}";
        return Connexion::getInstance()->xeq($req)->prem();
    }

    public function getTeam() {
        $req = "SELECT * FROM `match`,my_team WHERE my_team.id_my_team={$this->id_my_team}";
        return Connexion::getInstance()->xeq($req)->prem();
    }

    public function getAdversaire() {
        $req = "SELECT * FROM `match`,equipe_adverse WHERE equipe_adverse.id_equipe_adverse={$this->id_equipe_adverse}";
        return Connexion::getInstance()->xeq($req)->prem();
    }

    public function getPhase() {
        $req = "SELECT * FROM `match`,phase WHERE phase.id_phase={$this->id_phase}";
        return Connexion::getInstance()->xeq($req)->prem();
    }

    public function getLieu() {
        $req = "SELECT * FROM `match`,lieu WHERE lieu.id_lieu={$this->id_lieu}";
        return Connexion::getInstance()->xeq($req)->prem();
    }

    public function dernierMatch() {
//        $req = "SELECT * FROM `match`, resultat_my_team WHERE match.id_resultat_my_team=resultat_my_team.id_resultat_my_team ORDER BY date_match DESC LIMIT 1";
        $req = "SELECT * FROM `match` JOIN resultat_my_team ON id_match JOIN my_team ON id_match JOIN equipe_adverse ON id_match ORDER BY date_match DESC LIMIT 1;";
        return Connexion::getInstance()->xeq($req)->prem();
    }

}
