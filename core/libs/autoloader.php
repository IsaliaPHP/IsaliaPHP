<?php
/**
 * Autoloader
 * @author nelson rojas
 * @abstract
 * Clase encargada de la carga de clases del proyecto
 */
class Autoloader
{
    private static $_folders = [LIBS_PATH,
        CORE_PATH . 'traits' . DS,
        CORE_PATH . 'interfaces' . DS,
        APP_PATH . 'controllers' . DS,
        APP_PATH . 'models' . DS,
        APP_PATH . 'libs' . DS,
        APP_PATH . 'helpers' . DS
    ];

    private static function loadPSR0($class_name) {
        // PSR0
        if (strpos($class_name, '\\')) {
            $psr0 = dirname(LIBS_PATH) . DS . 'vendor' . DS . str_replace(['_', '\\'], DS, $class_name) . '.php';

            if (is_file($psr0)) {
                require_once $psr0;
                return true;
            }
        }
        return false;
    }

    private static function loadPSR4($class_name) {
        // revisar si existe archivo de carga de composer y cargarlo
        if (file_exists(dirname(LIBS_PATH) . DS . 'vendor' . DS . 'autoload.php')) {
            require_once dirname(LIBS_PATH) . DS . 'vendor' . DS . 'autoload.php';
            return true;
        }   
        return false;
    }

    /**
     * Realiza la carga de la clase solicitada
     * @param string $class_name
     * @return void
     * @throws Exception
     */
    public static function exec($class_name)
    {
        $result = false;
        $snake_case = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $class_name));

        foreach (static::$_folders as $folder) {
            if (is_file($folder . $snake_case . '.php')) {
                require_once($folder . $snake_case . '.php');
                $result = true;
                break;
            }
        }

        if (!$result) {
            $result = static::loadPSR0($class_name);
        }

        if (!$result) {
            $result = static::loadPSR4($class_name);
        }        

        if ($result) {
            return;
        }

        throw new Exception("Clase no encontrada $class_name");
    }
}
