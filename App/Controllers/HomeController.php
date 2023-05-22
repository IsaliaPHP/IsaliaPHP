<?php

/**
 * Controlador inicial si no se cambia en 
 * App\Libs\Config
 */
class HomeController extends Controller
{
    /** 
     * funcion que se ejecuta antes de cualquier acción del controlador
     */
    public function beforeFilter()
    {
        Load::setTemplate('default');
    }
    /**
     * Método predeterminado si no se modifica en 
     * App\Libs\Config
     */
    public function index() {
        Load::view("Home/index");
    }

}
