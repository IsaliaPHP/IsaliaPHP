<?php

/**
 * Cargador de elementos como vistas o parciales
 */
class Cargar {

    /**
     * Carga una vista de acuerdo a su nombre
     * @param $nombre
     * @param $parametros
     * @return bool
     */
    public static function vista($nombre, $parametros = null):bool {
        if (isset($parametros) && is_array($parametros)) {
            extract($parametros);
        }

        $ruta = RUTA_RAIZ . DS . 'App' . DS . 'Vistas' . DS . $nombre . '.phtml';

        if (file_exists($ruta)) {
            require_once $ruta;
            return true;
        } else {
            throw new Exception("No se encuentra " . $ruta, 1);
            return false;
        }
    }

    /**
     * Carga vistas parciales según su nombre
     * @param $nombre
     * @param $parametros
     * @return void
     */
    public static function parcial($nombre, $parametros = null) {
        if (isset($parametros) && is_array($parametros)) {
            extract($parametros);
        }

        $ruta = RUTA_RAIZ . DS . 'App' . DS . 'Vistas' . DS .
                '_Compartidos' . DS . 'Parciales' . DS . $nombre . '.phtml';

        if (file_exists($ruta)) {
            require_once $ruta;
        } else {
            throw new Exception("No se encuentra " . $ruta, 1);
        }
    }

    /**
     * Realiza la acción de despachador (ejecutar Controlador->Acción)
     * pasando los parámetros respectivos a la acción
     * @param $url
     * @return void
     * @throws Exception
     */
    public static function controladorPorUrl(string $url) {

        $urlArray = explode("/", $url);

        $controlador = ( isset($urlArray[0]) && !empty($urlArray[0]) ) ? $urlArray[0] : Configuracion::CONTROLADOR_PREDETERMINADO;

        $controlador .= 'Controlador';

        array_shift($urlArray);
        $accion = ( isset($urlArray[0]) && !empty($urlArray[0]) ) ? $urlArray[0] : Configuracion::ACCION_PREDETERMINADA;

        array_shift($urlArray);
        $queryString = $urlArray;

        $controlador = ucwords($controlador);

        $despachador = new $controlador();

        // revisa si existe un metodo llamado "antes_de_filtrar"
        // y lo llama. es ideal para autenticacion o autorizacion
        if ((int) method_exists($controlador, "antes_de_filtrar")) {
            call_user_func_array(array($despachador, "antes_de_filtrar"), $queryString);
        }

        if ((int) method_exists($controlador, $accion)) {
            call_user_func_array(array($despachador, $accion), $queryString);
        } else {
            /* Error Generation Code Here */
            throw new Exception("$controlador/$accion no existe");
        }
    }

}
