<?php

/**
 * Cargador de elementos como vistas o parciales
 */
class Load
{

    static $_content;
    static $_template;

    /**
     * Carga una vista de acuerdo a su nombre
     * @param $partial_name
     * @param $parameters
     */
    public static function view($partial_name, $parameters = null)
    {
        if (isset($parameters) && is_array($parameters)) {
            extract($parameters);
        }

        $ruta_vistas = ROOT . DS . 'App' . DS . 'Views' . DS;

        $archivo_vista = $ruta_vistas . $partial_name . '.phtml';

        if (!file_exists($archivo_vista)) {
            throw new Exception("No se encuentra " . $archivo_vista, 1);
        }


        if (!empty(self::$_template)) {
            ob_start();
            require_once $archivo_vista;
            self::setContent(ob_get_clean());

            $archivo_plantilla = $ruta_vistas . '_Shared' . DS . 'Templates' . DS . self::$_template . '.phtml';
            if (file_exists($archivo_plantilla)) {
                include($archivo_plantilla);
            } else {
                throw (new Exception('No existe plantilla: ' . $archivo_plantilla));
            }
        } else {
            require_once $archivo_vista;
            return true;
        }
    }

    /**
     * Carga vistas parciales según su nombre
     * @param $partial_name
     * @param $parameters
     * @return void
     */
    public static function partial($partial_name, $parameters = null)
    {
        if (isset($parameters) && is_array($parameters)) {
            extract($parameters);
        }

        $ruta = ROOT . DS . 'App' . DS . 'Views' . DS .
            '_Shared' . DS . 'Partials' . DS . $partial_name . '.phtml';

        if (file_exists($ruta)) {
            require $ruta;
        } else {
            throw new Exception("No se encuentra " . $ruta, 1);
        }
    }

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
        }
    }

    /**
     * funcion útil para cargar vistas dentro de Templates
     */
    public static function setContent($contenido)
    {
        self::$_content =  $contenido;
    }

    /**
     * funcion útil para cargar vistas dentro de Templates
     */
    public static function getContent()
    {
        return self::$_content;
    }

    /**
     * funcion para asignar la plantilla que se mostrará con la vista
     */
    public static function setTemplate($template_name)
    {
        self::$_template = $template_name;
    }
}
