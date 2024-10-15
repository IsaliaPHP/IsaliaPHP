<?php

/**
 * View
 * @author nelson rojas
 * Clase encargada de la gestión de vistas, plantillas y parciales
 */
class View
{
    static $_content;
    static $_template;
    static $_flash_message;
    static $_hasErrors = false;

    /**
     * Carga una vista de acuerdo a su nombre
     * @param $view_name
     * @param $parameters
     */
    public static function render($view_name, $parameters = null)
    {
        if (isset($parameters) && is_array($parameters)) {
            extract($parameters);
        }

        $ruta_vistas = APP_PATH . 'views' . DS;

        $archivo_vista = $ruta_vistas . $view_name . '.phtml';

        if (!file_exists($archivo_vista)) {
            throw new Exception("No se encuentra " . $archivo_vista, 1);
        }

        if (self::getHasErrors()) {
            return true;
        }

        if (!empty(self::$_template)) {
            ob_start();
            require_once $archivo_vista;
            self::setContent(ob_get_clean());

            $archivo_plantilla = $ruta_vistas . '_shared' . DS . 'templates' . DS . self::$_template . '.phtml';
            if (file_exists($archivo_plantilla)) {
                include($archivo_plantilla);
            } else {
                throw (new Exception('No existe plantilla: ' . $archivo_plantilla));
            }
        } else {
            require_once $archivo_vista;
            return true;
        }
    }

    /**
     * Carga vistas parciales según su nombre
     * @param $view_name
     * @param $parameters
     * @return void
     */
    public static function partial($partial_name, $parameters = null)
    {
        if (isset($parameters) && is_array($parameters)) {
            extract($parameters);
        }

        $ruta = APP_PATH . 'views' . DS .
            '_shared' . DS . 'partials' . DS . $partial_name . '.phtml';

        if (file_exists($ruta)) {
            require $ruta;
        } else {
            throw new Exception("No se encuentra " . $ruta, 1);
        }
    }

    /**
     * funcion útil para cargar vistas dentro de Templates
     */
    public static function setContent($contenido)
    {
        self::$_content =  $contenido;
    }

    /**
     * funcion útil para cargar vistas dentro de Templates
     */
    public static function getContent()
    {
        return self::$_content;
    }

    /**
     * funcion para asignar la plantilla que se mostrará con la vista
     */
    public static function setTemplate($template_name)
    {
        self::$_template = $template_name;
    }

    /**
     * funcion para asignar la plantilla que se mostrará con la vista
     */
    public static function getTemplate()
    {
        return self::$_template;
    }

    public static function setHasErrors(bool $hasErrors)
    {
        self::$_hasErrors = $hasErrors;
    }

    public static function getHasErrors()
    {
        return self::$_hasErrors;
    }

}