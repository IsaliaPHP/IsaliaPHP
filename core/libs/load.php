<?php

/**
 * Load
 * @author nelson rojas
 * @abstract
 * Se encarga de despachar las peticiones de url a los controladores
 */
class Load
{

    /**
     * Realiza la acción de despachador (ejecutar Controller->Action)
     * pasando los parámetros respectivos a la acción
     * @param $url
     * @return void
     * @throws Exception
     */
    public static function controllerFromUrl(string $url)
    {

        $urlArray = explode("/", $url);

        $controlador = (isset($urlArray[0]) && !empty($urlArray[0])) ? $urlArray[0] : Config::DEFAULT_CONTROLLER;

        $controlador .= 'Controller';

        array_shift($urlArray);
        $accion = (isset($urlArray[0]) && !empty($urlArray[0])) ? $urlArray[0] : Config::DEFAULT_ACTION;

        array_shift($urlArray);
        $queryString = $urlArray;

        $controlador = ucwords($controlador);

        $despachador = new $controlador($controlador, $accion);

        if ((int) method_exists($controlador, $accion)) {
            call_user_func_array(array($despachador, $accion), $queryString);
        } else {
            /* Error Generation Code Here */
            throw new Exception("$controlador/$accion no existe");
            return false;
        }
    }

}
