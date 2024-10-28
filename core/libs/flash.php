<?php

/**
 * Flash
 * @author nelson rojas
 * @abstract
 * sirve como helper de envío de notificaciones
 */
class Flash
{
    /**
     * set
     * @abstract
     * crea una notificacion segun el tipo y con el contenido de mensaje
     * @param string $type
     * @param string $message
     */
    public static function set($type, $message)
    {
        $_SESSION['flash'] = NULL;
        $_SESSION['flash'] = array(
            'type' => $type,
            'message' => $message
        );
    }
    
    /**
     * valid
     * @abstract
     * sinonimo para set usando el valor valid como tipo
     * @param string $message
     */
    public static function valid($message)
    {
        static::set('valid', $message);
    }
    
    /**
     * info
     * @abstract
     * sinonimo para set usando el valor info como tipo
     * @param string $message
     */
    public static function info($message)
    {
        static::set('info', $message);
    }
    
    /**
     * error
     * @abstract
     * sinonimo para set usando el valor error como tipo
     * @param string $message
     */
    public static function error($message)
    {
        static::set('error', $message);
    }
    

    /**
     * hasMessages
     * @abstract
     * permite saber si hay notificaciones por presentar
     * @return boolean
     */
    public static function hasMessages()
    {
        return (isset($_SESSION['flash']) ? $_SESSION['flash'] !== NULL : false);
    }

    /**
     * render
     * @abstract
     * genera el contenido que corresponde con la notificacion enviada
     */
    public static function render()
    {
        if (isset($_SESSION['flash'])) {
            $result = '<span class="alert alert-' . $_SESSION['flash']['type'] . '">' . $_SESSION['flash']['message'] . '</span>';
            echo $result;
            $_SESSION['flash'] = '';
            unset($_SESSION['flash']);
        }
    }
}
