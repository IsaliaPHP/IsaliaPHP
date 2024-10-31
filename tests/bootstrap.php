<?php

date_default_timezone_set("America/Santiago");

define('ROOT', dirname(dirname(__FILE__)));
define('DS', DIRECTORY_SEPARATOR);
define('APP_PATH', ROOT . DS . 'app' . DS);
define('CORE_PATH', ROOT . DS . 'core' . DS);
define('LIBS_PATH', CORE_PATH . 'libs' . DS);
define('PUBLIC_PATH', '/');

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
 * Asignamos los manejadores de errores y excepciones
 */
set_error_handler('Report::handleError');
set_exception_handler('Report::handleException');