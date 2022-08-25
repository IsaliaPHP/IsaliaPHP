<?php

/**
 * Controlador inicial si no se cambia en la clase 
 * \Libs\Configuracion
 */
class HomeControlador 
{

    /**
     * Método predeterminado si no se modifica en la clase 
     * \Libs\Configuracion
     */
    public function index() {
        return Cargar::vista("home/index");
    }

}
