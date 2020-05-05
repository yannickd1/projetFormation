<?php

class DateGame implements Databasable {

    public $id_date_game;
    public $id_my_team;
    public $id_equipe_adverse;
    public $id_lieu;
    public $id_phase;
    public $id_membre;
    public $date_game;

    public function __construct($id_date_game = null, $id_my_team = null, $id_equipe_adverse = null, $id_lieu = null, $id_phase = null,$id_membre = null, $date_game = null) {
        $this->id_date_game = $id_date_game;
        $this->id_my_team = $id_my_team;
        $this->id_equipe_adverse = $id_equipe_adverse;
        $this->id_lieu = $id_lieu;
        $this->id_phase = $id_phase;
        $this->id_membre = $id_membre;
        $this->date_game = $date_game;
    }

    public function charger() {
        if (!$this->id_date_game)
            return false;
        $req = "SELECT * FROM date_games WHERE id_date_game={$this->id_date_game}";
        return Connexion::getInstance()->xeq($req)->ins($this);
    }

    public function sauver() {
        $cnx = Connexion::getInstance();
        if ($this->id_date_game) {
            $req = "UPDATE date_game SET date_game = {$cnx->esc($this->date_game)} WHERE id_date_game = {$this->id_date_game}";
            $cnx->xeq($req);
        } else {
            $req = "INSERT INTO date_game VALUES(DEFAULT,{$this->id_my_team},{$this->id_equipe_adverse},{$this->id_lieu},{$this->id_phase},{$this->id_membre}, {$cnx->esc($this->date_game)})";
            $this->id_date_game = $cnx->xeq($req)->pk();
        }
        return $this;
    }

    public function supprimer() {
        if (!$this->id_date_game)
            return false;
        $req = "DELETE FROM date_game WHERE id_date_game = {$this->id_date_game}";
        return (bool) Connexion::getInstance()->xeq($req)->nb();
    }

    public static function tab($where = 1, $orderBy = 1, $limit = null) {
        $req = "SELECT * FROM date_game WHERE {$where} ORDER BY {$orderBy}" . ($limit ? " LIMIT {$limit}" : '');
        return Connexion::getInstance()->xeq($req)->tab(__CLASS__);
    }

    public static function tous() {
        return DateGame::tab(1, 'date_game ASC');
    }

    public function getDateGame() {
        $req = "SELECT * FROM date_game WHERE DAYOFWEEK(date_game)=7 AND WEEK(date_game) = WEEK(CURDATE());";
        return Connexion::getInstance()->xeq($req)->prem();
    }

    public function getDateTeam() {
        $req = "SELECT * FROM date_game,my_team WHERE my_team.id_my_team=date_game.id_my_team AND DAYOFWEEK(date_game)=7 AND WEEK(date_game) = WEEK(CURDATE());";
        return Connexion::getInstance()->xeq($req)->prem();
    }

    public function getDateAdversaire() {
        $req = "SELECT * FROM date_game,equipe_adverse WHERE equipe_adverse.id_equipe_adverse=date_game.id_equipe_adverse AND DAYOFWEEK(date_game)=7 AND WEEK(date_game) = WEEK(CURDATE());";
        return Connexion::getInstance()->xeq($req)->prem();
    }

    public function getDateLieu() {
        $req = "SELECT * FROM date_game,lieu WHERE lieu.id_lieu=date_game.id_lieu AND DAYOFWEEK(date_game)=7 AND WEEK(date_game) = WEEK(CURDATE());";
        return Connexion::getInstance()->xeq($req)->prem();
    }

    public function getDatePhase() {
        $req = "SELECT * FROM date_game,phase WHERE phase.id_phase=date_game.id_phase AND DAYOFWEEK(date_game)=7 AND WEEK(date_game) = WEEK(CURDATE());";
        return Connexion::getInstance()->xeq($req)->prem();
    }

    public function getDate() {
        $req = "SELECT * FROM date_game,my_team WHERE my_team.id_my_team=date_game.id_my_team ";
        return Connexion::getInstance()->xeq($req)->prem();
    }

    public function getTeam() {
        $req = "SELECT * FROM date_game,my_team WHERE my_team.id_my_team={$this->id_my_team} ";
        return Connexion::getInstance()->xeq($req)->prem();
    }

    public function getAdversaire() {
        $req = "SELECT * FROM date_game,equipe_adverse WHERE equipe_adverse.id_equipe_adverse={$this->id_equipe_adverse} ";
        return Connexion::getInstance()->xeq($req)->prem();
    }

    public function getLieu() {
        $req = "SELECT * FROM date_game,lieu WHERE lieu.id_lieu={$this->id_lieu} ";
        return Connexion::getInstance()->xeq($req)->prem();
    }

    public function getPhase() {
        $req = "SELECT * FROM date_game,phase WHERE phase.id_phase={$this->id_phase} ";
        return Connexion::getInstance()->xeq($req)->prem();
    }

    public function gestionMaillot() {
        $req = "SELECT * FROM `date_game` ORDER BY date_game ASC LIMIT 1";
        return Connexion::getInstance()->xeq($req)->prem();
    }
     
}
