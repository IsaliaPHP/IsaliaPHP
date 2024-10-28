<?php

/**
 * Sanitize
 * @author nelson rojas
 * @abstract
 * Clase encargada de eliminar caracteres extraños en las variables globales de PHP
 */
class Sanitize {

    /**
     * Elimina las barras invertidas de las variables globales de PHP
     * @param mixed $value
     * @return mixed
     */
    private static function stripSlashesDeep($value) {
        $value = is_array($value) ? array_map('Sanitize::stripSlashesDeep', $value) : stripslashes($value);
        return $value;
    }

    /**
     * Elimina las comillas mágicas de las variables globales de PHP
     * @return void
     */
    private static function removeMagicQuotes() {
        $_GET = static::stripSlashesDeep($_GET);
        $_POST = static::stripSlashesDeep($_POST);
        $_COOKIE = static::stripSlashesDeep($_COOKIE);
    }

    /**
     * Elimina las variables globales de PHP
     * @return void
     */
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
