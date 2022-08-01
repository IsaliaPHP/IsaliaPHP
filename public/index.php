<?php
error_reporting(E_ALL ^ E_STRICT); // Comentar en producción
ini_set('display_errors', 'On'); //comentar en producción

define('RUTA_RAIZ', dirname(dirname(__FILE__)));
define('DS', DIRECTORY_SEPARATOR);
define('RUTA_APLICACION', RUTA_RAIZ . DS . 'app' . DS);
define('RUTA_LIBS', RUTA_RAIZ . DS . 'lib' . DS);

define('START_TIME', microtime(TRUE));

define('RUTA_PUBLICA', '/backend/' );

$url = isset($_GET['url']) ? $_GET['url'] : '/'; // isset($_GET['_url']) ? $_GET['_url'] : '/';

session_start();

//autoload
function __autoload($clase) {
	if (file_exists(RUTA_RAIZ . DS . 'Libs' . DS . $clase . '.php')) {
		require_once(RUTA_RAIZ . DS . 'Libs' . DS . $clase . '.php');
	} else if (file_exists(RUTA_RAIZ . DS . 'App' . DS . 'Controladores' . DS . $clase . '.php')) {
		require_once(RUTA_RAIZ . DS . 'App' . DS . 'Controladores' . DS . $clase . '.php');
	} else if (file_exists(RUTA_RAIZ . DS . 'App' . DS . 'Modelos' . DS . $clase . '.php')) {
		require_once(RUTA_RAIZ . DS . 'App' . DS . 'Modelos' . DS . $clase . '.php');
	} else {
		/* Error Generation Code Here */
	}
}

//echo $url;

Cargar::controlador($url);