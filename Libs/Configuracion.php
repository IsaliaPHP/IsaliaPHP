<?php

class Configuracion {

    const CADENA_CONEXION = 'mysql:host=127.0.0.1;dbname=backend;charset=utf8';
    const USUARIO_BD = 'root';
    const CLAVE_BD = '';
    const PARAMETROS_EXTRAS = [
        PDO::ATTR_PERSISTENT => true, //conexión persistente
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ];
    const VER_ERRORES = true;
    
    const CONTROLADOR_PREDETERMINADO = 'Home';
    const ACCION_PREDETERMINADA = 'index';
    

}
