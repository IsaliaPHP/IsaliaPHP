<?php
class Autocarga
{
    public static function ejecutar($clase)
    {
        $rutas = [
            RUTA_RAIZ . DS . 'Libs' . DS,
            RUTA_RAIZ . DS . 'App' . DS . 'Controladores' . DS,
            RUTA_RAIZ . DS . 'App' . DS . 'Modelos' . DS,
            RUTA_RAIZ . DS . 'App' . DS . 'Libs' . DS
        ];

        foreach($rutas as $ruta) {
            if (is_file($ruta . $clase . '.php')) {
                require_once($ruta . $clase . '.php');
                return;
            }
        }
        
        // PSR0
        if (strpos($clase, '\\')) {
            $psr0 = dirname(RUTA_LIBS) .'/vendor/'.str_replace(['_', '\\'], DS, $clase).'.php';
            
            if (is_file($psr0)) {
                require_once $psr0;
                return;
            }
        }

        throw new Exception("Clase no encontrada $clase");
        
    }
}