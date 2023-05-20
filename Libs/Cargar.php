<?php

/**
 * Cargador de elementos como vistas o parciales
 */
class Cargar {

    static $_contenido;
    static $_plantilla;
    
    /**
     * Carga una vista de acuerdo a su nombre
     * @param $nombre
     * @param $parametros
     */
    public static function vista($nombre, $parametros = null) {
        if (isset($parametros) && is_array($parametros)) {
            extract($parametros);
        }

        $ruta_vistas = RUTA_RAIZ . DS . 'App' . DS . 'Vistas' . DS ;
        
        $archivo_vista = $ruta_vistas . $nombre . '.phtml';
        
        if (!file_exists($archivo_vista)) {
            throw new Exception("No se encuentra " . $archivo_vista, 1);
        }

        
        if (!empty(self::$_plantilla)) {
            ob_start();
            require_once $archivo_vista;
            self::asignarContenido(ob_get_clean());
            
            $archivo_plantilla = $ruta_vistas . '_Compartidos' . DS . 'Plantillas' . DS . self::$_plantilla . '.phtml';
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

        $despachador = new $controlador($controlador, $accion);

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

    
    public static function asignarContenido($contenido)
    {
        self::$_contenido =  $contenido;
    }
    
    public static function obtenerContenido()
    {
        return self::$_contenido;
    }
    
    public static function asignarPlantilla($nombrePlantilla)
    {
        self::$_plantilla = $nombrePlantilla;
    }
}
