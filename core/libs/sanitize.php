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
     * Ejecuta funciones que se encargan de sanitizar variables globales de PHP
     * @return void
     */
    public static function execute() {
        static::removeMagicQuotes();
    }
}
