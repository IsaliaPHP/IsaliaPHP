<?php

/**
 * Clase de configuraciones
 */
class Configuracion {
    /**
     * utiliza de forma predeterminada MySQL (o MariaDB)
     */
    const CADENA_CONEXION = 'mysql:host=127.0.0.1;dbname=NombreBaseDatos;charset=utf8';
    const USUARIO_BD = 'nombreUsuarioBaseDeDatos';
    const CLAVE_BD = 'claveDeAccesoDelUsuario';
    const PARAMETROS_EXTRAS = [
        PDO::ATTR_PERSISTENT => true, //conexión persistente
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ];

    /**
     * Permite ver los errores mientras estamos trabajando 
     * en el desarrollo de la aplicación. 
     * Una vez que pases a producción lo ideal es pasarle el valor
     * false
     */
    const VER_ERRORES = true;
    
    /**
     * Controlador predeterminado
     */
    const CONTROLADOR_PREDETERMINADO = 'Home';
    
    /**
     * Acción o método predeterminado de cualquier controlador
     */
    const ACCION_PREDETERMINADA = 'index';
}
