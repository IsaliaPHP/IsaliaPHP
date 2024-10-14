<?php
/**
 * Autoloader
 * @author nelson rojas
 * @abstract
 * Clase encargada de la carga de clases del proyecto
 */
class Autoloader
{
    public static function exec($class_name)
    {
        $folders = [
            LIBS_PATH,
            APP_PATH . 'controllers' . DS,
            APP_PATH . 'models' . DS,
            APP_PATH . 'libs' . DS,
            APP_PATH . 'helpers' . DS
        ];

        $snake_case = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $class_name));

        foreach ($folders as $folder) {
            if (is_file($folder . $snake_case . '.php')) {
                require_once($folder . $snake_case . '.php');
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
