<?php
/**
 * Autoloader
 * @author nelson rojas
 * @abstract
 * Clase encargada de la carga de clases del proyecto
 */
class Autoloader
{
    /**
     * variable que almacena las rutas de las clases
     * @var array $_folders
     * @access private
     * @static
     * @var array
     */
    private static $_folders = [LIBS_PATH,
        CORE_PATH . 'traits' . DS,
        CORE_PATH . 'interfaces' . DS,
        APP_PATH . 'controllers' . DS,
        APP_PATH . 'models' . DS,
        APP_PATH . 'libs' . DS,
        APP_PATH . 'helpers' . DS
    ];

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

        foreach (self::$_folders as $folder) {
            if (is_file($folder . $snake_case . '.php')) {
                require_once($folder . $snake_case . '.php');
                return;
            }
        }           

        throw new Exception("Clase no encontrada $class_name");
    }
}
