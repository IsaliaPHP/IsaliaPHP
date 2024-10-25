<?php

/**
 * Request
 * @author nelson rojas
 * Clase para gestionar los Requests POST y GET
 */
class Request {
    /**
     * Obtiene el contenido de $_POST[$var]
     * @param string $var
     * @return mixed|null
     */
    public static function post(string $var) {
        return filter_has_var(INPUT_POST, $var) ? $_POST[$var] : NULL;
    }

    /**
     * Permite saber si $var está contenido dentro de $_POST
     * @param string $var
     * @return bool
     */
    public static function hasPost(string $var):bool {
        return filter_has_var(INPUT_POST, $var);
    }

    /**
     * Obtiene el contenido de $var dentro de $_GET[$var]
     * @param string $var
     * @return mixed|null
     */
    public static function get(string $var) {
        return filter_has_var(INPUT_GET, $var) ? $_GET[$var] : NULL;
    }

    /**
     * Permite saber si existe $var dentro de $_GET
     * @param string $var
     * @return bool
     */
    public static function hasGet(string $var):bool {
        return filter_has_var(INPUT_GET, $var);
    }

    /**
     * Permite comprobar si una peticion usando formularios
     * es segura, usando Form:open o Form::openMultipart
     * @return bool
     */
    public static function isSafe()
    {
        $result = filter_has_var(INPUT_POST, 'safety_key') ? $_POST['safety_key'] : '';
        
        if (strlen($result) != 66) {
            return false;
        }
        $resultMD5 = substr($result, - 33, - 1);

        if ($resultMD5 === md5(Config::SAFETY_SEED)) {
            return true;
        } else {
            return false;
        }
    }
}
