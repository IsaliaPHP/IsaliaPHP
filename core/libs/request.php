<?php

/**
 * Request
 * @author nelson rojas
 * @abstract
 * Clase para gestionar los Requests POST y GET
 */
class Request {
    /**
     * Datos de entrada
     */
    private static $inputData = [
        'post' => null,
        'get' => null,
    ];

    /**
     * Configuración
     */
    public static $config;

    /**
     * Establece los datos de entrada
     */
    public static function setInputData(array $post = null, array $get = null) {
        self::$inputData['post'] = $post;
        self::$inputData['get'] = $get;
    }

    /**
     * Establece la configuración
     */
    public static function setConfig($config) {
        self::$config = $config;
    }

    /**
     * Obtiene el contenido de $_POST[$var]
     * @param string $var
     * @return mixed|null
     */
    public static function post(string $var) {
        $post = self::$inputData['post'] ?? $_POST;
        return isset($post[$var]) ? $post[$var] : null;
    }

    /**
     * Permite saber si $var está contenido dentro de $_POST
     * @param string $var
     * @return bool
     */
    public static function hasPost(string $var): bool {
        $post = self::$inputData['post'] ?? $_POST;
        return isset($post[$var]);
    }

    /**
     * Obtiene el contenido de $var dentro de $_GET[$var]
     * @param string $var
     * @return mixed|null
     */
    public static function get(string $var) {
        $get = self::$inputData['get'] ?? $_GET;
        return isset($get[$var]) ? $get[$var] : null;
    }

    /**
     * Permite saber si existe $var dentro de $_GET
     * @param string $var
     * @return bool
     */
    public static function hasGet(string $var): bool {
        $get = self::$inputData['get'] ?? $_GET;
        return isset($get[$var]);
    }

    /**
     * Permite comprobar si una peticion usando formularios
     * es segura, usando Form:open o Form::openMultipart
     * @return bool
     */
    public static function isSafe()
    {
        $post = self::$inputData['post'] ?? $_POST;
        $result = isset($post['safety_key']) ? $post['safety_key'] : '';

        if (strlen($result) != 66) {
            return false;
        }
        $resultMD5 = substr($result, -33, -1);

        $safetySeed = self::$config ? self::$config::SAFETY_SEED : Config::SAFETY_SEED;
        return $resultMD5 === md5($safetySeed);
    }
}
