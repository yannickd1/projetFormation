<?php

class MyTeam implements Databasable {

    public $id_my_team;
    public $nom;

    public function __construct($id_my_team = null, $nom = null) {
        $this->id_my_team = $id_my_team;
        $this->nom = $nom;
    }

    public function charger() {
        if (!$this->id_my_team)
            return false;
        $req = "SELECT * FROM my_team WHERE id_my_team={$this->id_my_team}";
        return Connexion::getInstance()->xeq($req)->ins($this);
    }

    public function sauver() {
        $cnx = Connexion::getInstance();
        if ($this->id_my_team) {
            $req = "UPDATE my_team SET nom = {$cnx->esc($this->nom)} WHERE id_my_team = {$this->id_my_team}";
            $cnx->xeq($req);
        } else {
            $req = "INSERT INTO my_team VALUES(DEFAULT,{$cnx->esc($this->nom)})";
            $this->id_my_team = $cnx->xeq($req)->pk();
        }
        return $this;
    }

    public function supprimer() {
        if (!$this->id_my_team)
            return false;
        $req = "DELETE FROM my_team WHERE id_my_team = {$this->id_my_team}";
        return (bool) Connexion::getInstance()->xeq($req)->nb();
    }

    public static function tab($where = 1, $orderBy = 1, $limit = null) {
        $req = "SELECT * FROM my_team WHERE {$where} ORDER BY {$orderBy}" . ($limit ? " LIMIT {$limit}" : '');
        return Connexion::getInstance()->xeq($req)->tab(__CLASS__);
    }

    public static function tous() {
        return MyTeam::tab(1, 'nom');
    }

}
