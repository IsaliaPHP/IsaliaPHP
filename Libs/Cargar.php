<?php

class Cargar {

    public static function vista($nombre, $parametros = null) {
        if (isset($parametros) && is_array($parametros)) {
            extract($parametros);
        }

        $ruta = RUTA_RAIZ . DS . 'App' . DS . 'Vistas' . DS . $nombre . '.phtml';

        if (file_exists($ruta)) {
            require_once $ruta;
        } else {
            echo "No se encuentra " . $ruta;
            return;
        }
    }

    public static function parcial($nombre, $parametros = null) {
        if (isset($parametros) && is_array($parametros)) {
            extract($parametros);
        }

        $ruta = RUTA_RAIZ . DS . 'App' . DS . 'Vistas' . DS .
                '_Compartidos' . DS . 'Parciales' . DS . $nombre . '.phtml';

        if (file_exists($ruta)) {
            require_once $ruta;
        } else {
            echo "No se encuentra " . $ruta;
        }
    }

    public static function libreria($libreria) {
        if (file_exists(RUTA_LIBS . DS . $libreria)) {
            require_once RUTA_LIBS . DS . $libreria;
        }
    }

    public static function controladorPorUrl($url) {

        $urlArray = array();
        $urlArray = explode("/", $url);

        $controlador = ( isset($urlArray[0]) && !empty($urlArray[0]) ) ? $urlArray[0] : Configuracion::CONTROLADOR_PREDETERMINADO;

        array_shift($urlArray);
        $accion = ( isset($urlArray[0]) && !empty($urlArray[0]) ) ? $urlArray[0] : Configuracion::ACCION_PREDETERMINADA;

        array_shift($urlArray);
        $queryString = $urlArray;

        $controladorNombre = $controlador;
        $controlador = ucwords($controlador);

        $despachador = new $controlador();

        if ((int) method_exists($controlador, $accion)) {
            call_user_func_array(array($despachador, $accion), $queryString);
        } else {
            /* Error Generation Code Here */
            echo "$controlador/$accion no existe";
            return;
        }
    }

}
