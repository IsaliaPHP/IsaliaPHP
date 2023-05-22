<?php

/**
 * Clase encargada de eliminar caracteres extraños en las variables globales de PHP
 */
class Sanitize {

    private static function stripSlashesDeep($value) {
        $value = is_array($value) ? array_map('Sanitize::stripSlashesDeep', $value) : stripslashes($value);
        return $value;
    }

    private static function removeMagicQuotes() {
        $_GET = static::stripSlashesDeep($_GET);
        $_POST = static::stripSlashesDeep($_POST);
        $_COOKIE = static::stripSlashesDeep($_COOKIE);
    }

    /** Check register globals and remove them * */
    private static function unregisterGlobals() {
        if (ini_get('register_globals')) {
            $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
            foreach ($array as $value) {
                foreach ($GLOBALS[$value] as $key => $var) {
                    if ($var === $GLOBALS[$key]) {
                        unset($GLOBALS[$key]);
                    }
                }
            }
        }
    }

    /**
     * Ejecuta funciones que se encargan de sanitizar variables globales de PHP
     * @return void
     */
    public static function execute() {
        static::removeMagicQuotes();
        static::unregisterGlobals();
    }
}
