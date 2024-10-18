<?php

/**
 * Config
 * @author nelson rojas
 * Clase de configuraciones
 */
class Config {
    /**
     * utiliza de forma predeterminada MySQL (o MariaDB)
     */
    const CONNECTION_STRING = 'mysql:host=127.0.0.1;dbname=NombreBaseDatos;charset=utf8';
    const USER = 'usuario_base_datos';
    const PASSWORD = 'clave_del_usuario_base_datos';
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
     * Lista blanca para mostrar errores al desarrollador
     */
    const EXCEPTIONS = ["127.0.0.1"];

    /** 
     * Secuencia para asegurar los formularios
     */
    const SAFETY_SEED = 'c91f8a8f55c329afe6dca874514e3aff';
    
    /**
     * Controlador predeterminado
     */
    const DEFAULT_CONTROLLER = 'Home';
    
    /**
     * Acción o método predeterminado de cualquier controlador
     */
    const DEFAULT_ACTION = 'index';
}
