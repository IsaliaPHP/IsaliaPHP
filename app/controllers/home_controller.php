<?php

/**
 * Controlador inicial si no se cambia en 
 * app\libs\config.php
 */
class HomeController extends Controller
{
    /** 
     * funcion que se ejecuta antes de cualquier acción del controlador
     */
    public function beforeFilter()
    {
    }

    /**
     * funcion que se ejecuta despues de beforeFilter y antes de 
     * la acción requerida
     */
    public function initialize()
    {
     
    }
    /**
     * Método predeterminado si no se modifica en 
     * app\libs\config.php
     */
    public function index() {
        //carga la vista ubicada en app\views\home\index.phtml
    }

}
