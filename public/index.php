<?php

/**
 * Configurar la zona horaria de la aplicación
 */
date_default_timezone_set("America/Santiago");

/**
 * Definicion de constantes globales
 */
define('ROOT', dirname(dirname(__FILE__)));
define('DS', DIRECTORY_SEPARATOR);
define('APP_PATH', ROOT . DS . 'app' . DS);
define('CORE_PATH', ROOT . DS . 'core' . DS);
define('LIBS_PATH', CORE_PATH . 'libs' . DS);
define('START_TIME', microtime(TRUE));


/**
 * Es ideal definir esta ruta usando un valor fijo. 
 * Por ejemplo, si tu sitio queda alojado en www.servidor.com/blog/
 * asignaremos PUBLIC_PATH como 'www.servidor.com/blog/'
 * En caso que quede directo como elemento raíz, 
 * asignaremos PUBLIC_PATH como 'www.servidor.com'
 */
define('PUBLIC_PATH', substr($_SERVER['SCRIPT_NAME'], 0, -16));
// quita public/index.php

/**
 * url enviada desde el navegador
 */
$url = isset($_GET['url']) ? $_GET['url'] : '/';

/**
 * Iniciar la Sesión de PHP
 */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Generación de carga automática de clases
 */
require_once LIBS_PATH . "autoloader.php";

spl_autoload_register(
    function ($class_name) {
        Autoloader::exec($class_name);
    }
);

/**
 * Permite mostrar los errores mientras estamos desarrollando la aplicación
 */
error_reporting(E_ALL ^ E_STRICT); // Comentar en producción
ini_set('display_errors', 'On'); //comentar en producción    

/**
 * Asignamos los manejadores de errores y excepciones
 */
set_error_handler('Report::handleError');
set_exception_handler('Report::handleException');

/**
 * Como las peticiones que vienen del navegador deben ser
 * consideradas como sospechosas, sanitizamos las variables 
 * globales de PHP por si las dudas
 */
Sanitize::execute();

/**
 * Agregamos los headers necesarios para evitar peticiones POST o GET desde dominios fuera del servidor local
 */
Security::injectAntiCSRFHeader();

/**
 * Cargamos el controlador de acuerdo a la $url recibida en el navegador web
 */
Load::controllerFromUrl($url);

