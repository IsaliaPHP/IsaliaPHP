<?php


define('RUTA_RAIZ', dirname(dirname(__FILE__)));
define('DS', DIRECTORY_SEPARATOR);
define('RUTA_APLICACION', RUTA_RAIZ . DS . 'app' . DS);
define('RUTA_LIBS', RUTA_RAIZ . DS . 'lib' . DS);

define('START_TIME', microtime(TRUE));


/**
 * Es ideal definir esta ruta usando un valor fijo. 
 * Por ejemplo, si tu sitio queda alojado en www.servidor.com/blog/
 * asignaremos RUTA_PUBLICA como 'www.servidor.com/blog/'
 * En caso que quede directo como elemento raíz, 
 * asignaremos RUTA_PUBLICA como 'www.servidor.com'
 */
define('RUTA_PUBLICA', substr($_SERVER['SCRIPT_NAME'], 0, -16)); 
// quita public/index.php

/**
 * url enviada desde el navegador
 */
$url = isset($_GET['url']) ? $_GET['url'] : '/'; 

/**
 * Iniciar la Sesión de PHP
 */
session_start();

/**
 * Generación de carga automática de clases
 */
spl_autoload_register(
    function ($clase) {
        if (file_exists(RUTA_RAIZ . DS . 'Libs' . DS . $clase . '.php')) {
            require_once(RUTA_RAIZ . DS . 'Libs' . DS . $clase . '.php');
        } else if (file_exists(RUTA_RAIZ . DS . 'App' . DS . 'Controladores' . DS . $clase . '.php')) {
            require_once(RUTA_RAIZ . DS . 'App' . DS . 'Controladores' . DS . $clase . '.php');
        } else if (file_exists(RUTA_RAIZ . DS . 'App' . DS . 'Modelos' . DS . $clase . '.php')) {
            require_once(RUTA_RAIZ . DS . 'App' . DS . 'Modelos' . DS . $clase . '.php');
        } else {
            throw new Exception("Clase no encontrada $clase");
        }
    }
);

if (Configuracion::VER_ERRORES == true) {
    error_reporting(E_ALL ^ E_STRICT); // Comentar en producción
    ini_set('display_errors', 'On'); //comentar en producción    
}

/**
 * Asignamos los manejadores de errores y excepciones
 */
set_error_handler('Errores::manejarError');
set_exception_handler('Errores::manejarExcepcion');

/**
 * Como las peticiones que vienen del navegador deben ser
 * consideradas como sospechosas, Sanitizamos las variables 
 * globales de PHP por si las dudas
 */
Sanitizar::ejecutar();

/**
 * Cargamos el controlador de acuerdo a la $url recibida en el navegador web
 */
Cargar::controladorPorUrl($url);
