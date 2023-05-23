<?php
class Autoloader
{
    public static function exec($class_name)
    {
        $folders = [
            ROOT . DS . 'Libs' . DS,
            ROOT . DS . 'App' . DS . 'Controllers' . DS,
            ROOT . DS . 'App' . DS . 'Models' . DS,
            ROOT . DS . 'App' . DS . 'Libs' . DS,
            ROOT . DS . 'App' . DS . 'Helpers' . DS
        ];

        foreach ($folders as $folder) {
            if (is_file($folder . $class_name . '.php')) {
                require_once($folder . $class_name . '.php');
                return;
            }
        }

        // PSR0
        if (strpos($class_name, '\\')) {
            $psr0 = dirname(LIBS_PATH) . DS . 'vendor' . DS . str_replace(['_', '\\'], DS, $class_name) . '.php';

            if (is_file($psr0)) {
                require_once $psr0;
                return;
            }
        }

        throw new Exception("Clase no encontrada $class_name");
    }
}
