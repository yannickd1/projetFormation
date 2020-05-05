<?php

class EquipeAdverse implements Databasable {

    public $id_equipe_adverse;
    public $nom_adv;

    public function __construct($id_equipe_adverse = null, $nom_adv = null) {
        $this->id_equipe_adverse = $id_equipe_adverse;
        $this->nom_adv = $nom_adv;
    }

    public function charger() {
        if (!$this->id_equipe_adverse)
            return false;
        $req = "SELECT * FROM equipe_adverse WHERE id_equipe_adverse={$this->id_equipe_adverse}";
        return Connexion::getInstance()->xeq($req)->ins($this);
    }

    public function sauver() {
        $cnx = Connexion::getInstance();
        if ($this->id_equipe_adverse) {
            $req = "UPDATE equipe_adverse SET nom_adv = {$cnx->esc($this->nom_adv)} WHERE id_equipe_adverse = {$this->id_equipe_adverse}";
            $cnx->xeq($req);
        } else {
            $req = "INSERT INTO equipe_adverse VALUES(DEFAULT,{$cnx->esc($this->nom_adv)})";
            $this->id_equipe_adverse = $cnx->xeq($req)->pk();
        }
        return $this;
    }

    public function supprimer() {
        if (!$this->id_equipe_adverse)
            return false;
        $req = "DELETE FROM equipe_adverse WHERE id_equipe_adverse = {$this->id_equipe_adverse}";
        return (bool) Connexion::getInstance()->xeq($req)->nb();
    }

    public static function tab($where = 1, $orderBy = 1, $limit = null) {
        $req = "SELECT * FROM equipe_adverse WHERE {$where} ORDER BY {$orderBy}" . ($limit ? " LIMIT {$limit}" : '');
        return Connexion::getInstance()->xeq($req)->tab(__CLASS__);
    }

    public static function tous() {
        return EquipeAdverse::tab(1, 'nom_adv');
    }

}
