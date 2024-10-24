<?php

/**
 * Clase Auth
 * @author nelson rojas
 */
class Auth
{
    /**
     * @var string
     */ 
    private static $model;
    /**
     * @var string
     */
    private static $checkMethod;
    
    /**
     * Constructor de la clase Auth
     * @param string $model
     * @param string $checkMethod
     */
    public function __construct($model, $checkMethod)
    {
        self::$model = $model;
        self::$checkMethod = $checkMethod;    
    }

    /**
     * Verifica si hay un usuario autenticado actualmente
     * @return boolean
     */
    public static function isLogged()
    {
        return Session::get('isLogged');
    }

    /**
     * Autentica un usuario
     * @param string $username
     * @param string $password
     * @return boolean
     */
    public static function login($username, $password)
    {
        $model = new self::$model();
        $checkMethod = self::$checkMethod;
        $user = $model->$checkMethod($username, $password);
        if($user){
            Session::set('isLogged', true);
            Session::set('user', $user);
            return true;
        }
        return false;
    }

    /**
     * Obtiene el usuario autenticado actualmente
     * @return User|null
     */
    public static function getCurrentUser()
    {
        return Session::get('user') ?? null;
    }

    /**
     * Cierra la sesión del usuario
     */
    public static function logout()
    {
        Session::delete('isLogged');
        Session::delete('user');
    }
}
