<?php

class Selectionne implements Databasable {

    public $id_selectionne;
    public $id_membre;
    public $id_date_game;


    public function __construct($id_selectionne = null, $id_membre = null, $id_date_game = null) {
        $this->id_selectionne = $id_selectionne;
        $this->id_membre = $id_membre;
        $this->id_date_game = $id_date_game;
        
    }

    public function charger() {
        if (!$this->id_selectionne)
            return false;
        $req = "SELECT * FROM selectionne WHERE id_selectionne={$this->id_selectionne}";
        return Connexion::getInstance()->xeq($req)->ins($this);
    }

    public function sauver() {
        $cnx = Connexion::getInstance();
        if ($this->id_selectionne) {
            $req = "UPDATE selectionne SET points = {$this->points} WHERE id_selectionne = {$this->id_selectionne}";
            $cnx->xeq($req);
        } else {
            $req = "INSERT INTO selectionne VALUES(DEFAULT,{$this->id_membre},{$this->id_date_game},{$this->points})";
            $this->id_selectionne = $cnx->xeq($req)->pk();
        }
        return $this;
    }

    public function supprimer() {
        if (!$this->id_selectionne)
            return false;
        $req = "DELETE FROM selectionne WHERE id_selectionne = {$this->id_selectionne}";
        return (bool) Connexion::getInstance()->xeq($req)->nb();
    }


    public static function tab($where = 1, $orderBy = 1, $limit = null) {
        $req = "SELECT * FROM selectionne WHERE {$where} ORDER BY {$orderBy}" . ($limit ? " LIMIT {$limit}" : '');
        return Connexion::getInstance()->xeq($req)->tab(__CLASS__);
    }

    public static function tous() {
        return Selectionne::tab(1, 'id_selectionne');
    }

}
