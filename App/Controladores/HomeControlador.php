<?php

/**
 * Controlador inicial si no se cambia en 
 * App\Libs\Configuracion
 */
class HomeControlador extends Controlador
{
    /** 
     * funcion que se ejecuta antes de cualquier acción del controlador
     */
    public function antes_de_filtrar()
    {
        Cargar::asignarPlantilla('default');
    }
    /**
     * Método predeterminado si no se modifica en 
     * App\Libs\Configuracion
     */
    public function index() {
        Cargar::vista("Home/index");
    }

}
