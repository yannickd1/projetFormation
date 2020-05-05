<?php

Cfg::init();

class Cfg {

    public static $user = null;
    private static $initDone = false;

    //upload
    const TAB_EXT = [];
    const TAB_MIME = ['image/jpeg'];
//image
    const IMG_V_LARGEUR = 300;
    const IMG_V_HAUTEUR = 300;
    const IMG_P_LARGEUR = 450;
    const IMG_P_HAUTEUR = 450;
    // Session.
    const SESSION_TIMEOUT = 300; // 5 minutes.

    private function __construct() {
        //Classe 100% statique.
    }

    public static function init() {
        if (self::$initDone)
            return false;
        //Auto chargement des classes.
        spl_autoload_register(function ($classe) {
            @include "../framework/{$classe}.php";
        });
        spl_autoload_register(function ($classe) {
            @include "class/{$classe}.php";
        });
// DSN.
        Connexion::setDSN('myteam2', 'root', 'root');

        // Session
        session_set_save_handler(new Session(self::SESSION_TIMEOUT));
        session_start();
        self::$user = Membre::getUserSession();



        // Init done.

        return self::$initDone = true;
    }

}
