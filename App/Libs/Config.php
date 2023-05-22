<?php

/**
 * Clase de configuraciones
 */
class Config {
    /**
     * utiliza de forma predeterminada MySQL (o MariaDB)
     */
    const CONNECTION_STRING = 'mysql:host=127.0.0.1;dbname=NombreBaseDatos;charset=utf8';
    const USER = 'nombreUsuarioBaseDeDatos';
    const PASSWORD = 'claveDeAccesoDelUsuario';
    const PARAMETERS = [
        PDO::ATTR_PERSISTENT => true, //conexión persistente
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ];

    /**
     * Permite ver los errores mientras estamos trabajando 
     * en el desarrollo de la aplicación. 
     * Una vez que pases a producción lo ideal es pasarle el valor
     * false
     */
    const SHOW_ERRORS = true;
    
    /**
     * Controlador predeterminado
     */
    const DEFAULT_CONTROLLER = 'Home';
    
    /**
     * Acción o método predeterminado de cualquier controlador
     */
    const DEFAULT_ACTION = 'index';
}
