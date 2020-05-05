<?php

class ResultatMyTeam implements Databasable {

    public $id_resultat_my_team;
    public $libelle;

    public function __construct($id_resultat_my_team = null, $libelle = null) {
        $this->id_resultat_my_team = $id_resultat_my_team;
        $this->libelle = $libelle;
    }

    public function charger() {
        if (!$this->id_resultat_my_team)
            return false;
        $req = "SELECT * FROM resultat_my_team WHERE id_resultat_my_team={$this->id_resultat_my_team}";
        return Connexion::getInstance()->xeq($req)->ins($this);
    }

    public function sauver() {
        $cnx = Connexion::getInstance();
        if ($this->id_resultat_my_team) {
            $req = "UPDATE resultat_my_team SET libelle = {$cnx->esc($this->libelle)} WHERE id_resultat_my_team = {$this->id_resultat_my_team}";
            $cnx->xeq($req);
        } else {
            $req = "INSERT INTO resultat_my_team VALUES(DEFAULT,{$cnx->esc($this->libelle)}";
            $this->id_resultat_my_team = $cnx->xeq($req)->pk();
        }
        return $this;
    }

    public function supprimer() {
        if (!$this->id_resultat_my_team)
            return false;
        $req = "DELETE FROM resultat_my_team WHERE id_resultat_my_team = {$this->id_resultat_my_team}";
        return (bool) Connexion::getInstance()->xeq($req)->nb();
    }

    public static function tab($where = 1, $orderBy = 1, $limit = null) {
        $req = "SELECT * FROM resultat_my_team WHERE {$where} ORDER BY {$orderBy}" . ($limit ? " LIMIT {$limit}" : '');
        return Connexion::getInstance()->xeq($req)->tab(__CLASS__);
    }

    public static function tous() {
        return ResultatMyTeam::tab(1, 'libelle');
    }

}
