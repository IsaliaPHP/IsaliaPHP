<?php
class Autoloader
{
    public static function exec($class_name)
    {
        $rutas = [
            ROOT . DS . 'Libs' . DS,
            ROOT . DS . 'App' . DS . 'Controllers' . DS,
            ROOT . DS . 'App' . DS . 'Models' . DS,
            ROOT . DS . 'App' . DS . 'Libs' . DS,
            ROOT . DS . 'App' . DS . 'Helpers' . DS
        ];

        foreach($rutas as $ruta) {
            if (is_file($ruta . $class_name . '.php')) {
                require_once($ruta . $class_name . '.php');
                return;
            }
        }
        
        // PSR0
        if (strpos($class_name, '\\')) {
            $psr0 = dirname(LIBS_PATH) . DS . 'vendor' . DS .str_replace(['_', '\\'], DS, $class_name).'.php';
            
            if (is_file($psr0)) {
                require_once $psr0;
                return;
            }
        }

        throw new Exception("Clase no encontrada $class_name");
        
    }
}