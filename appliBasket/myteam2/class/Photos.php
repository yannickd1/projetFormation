<?php

class Photos implements Databasable {

    public $id_photos;

    public function __construct($id_photos = null) {
        $this->id_photos = $id_photos;
    }

    public function charger() {
        if (!$this->id_photos)
            return false;
        $req = "SELECT * FROM photos WHERE id_photos={$this->id_photos}";
        return Connexion::getInstance()->xeq($req)->ins($this);
    }

    public function sauver() {
        $cnx = Connexion::getInstance();
        if ($this->id_photos) {
            $req = "UPDATE photos SET libelle = {$cnx->esc($this->libelle)} WHERE id_photos = {$this->id_photos}";
            $cnx->xeq($req);
        } else {
            $req = "INSERT INTO photos VALUES(DEFAULT,{$cnx->esc($this->libelle)})";
            $this->id_photos = $cnx->xeq($req)->pk();
        }
        return $this;
    }

    public function supprimer() {
        if (!$this->id_photos)
            return false;
        $req = "DELETE FROM photos WHERE id_photos = {$this->id_photos}";
        return (bool) Connexion::getInstance()->xeq($req)->nb();
    }

    public static function tab($where = 1, $orderBy = 1, $limit = null) {
        $req = "SELECT * FROM photos WHERE {$where} ORDER BY {$orderBy}" . ($limit ? " LIMIT {$limit}" : '');
        return Connexion::getInstance()->xeq($req)->tab(__CLASS__);
    }

    public static function tous() {
        return Photos::tab(1, 'id_photos');
    }

}
