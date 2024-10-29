<?php

/**
 * Config
 * @author nelson rojas
 * Clase de configuraciones
 */
class Config {
    /**
     * utiliza de forma predeterminada MySQL (o MariaDB)
     * para testing se utiliza SQLite
     */
    const DB_CONFIG = [
        'default' => [
            'dsn' => 'mysql:host=127.0.0.1;dbname=BaseDatos;charset=utf8',
            'user' => 'usuario',
            'password' => 'clave_usuario',
            'parameters' => [
                PDO::ATTR_PERSISTENT => true, //conexión persistente
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]
            ],
        'testing' => [
            'dsn' => 'sqlite:' . ROOT . '/tests/test.db',
            'user' => null,
            'password' => null,
            'parameters' => [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
            ],
        'invalid' => [
            'dsn' => 'sqlite:' . ROOT . '/tests/invalid.db',
            'user' => null,
            'password' => null,
            'parameters' => [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        ]
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
     * Directorio para almacenar los archivos adjuntos
     */
    const UPLOAD_DIR = ROOT . DS . 'public' . DS . 'files';
    
    /**
     * Tamaño máximo del archivo en bytes (2MB)
     */
    const MAX_UPLOAD_FILE_SIZE = 2097152;

    /**
     * Tipos de archivos permitidos
     */
    const ALLOWED_FILE_TYPES = ['image/jpeg', 'image/png', 'application/pdf'];
    
    /**
     * Controlador predeterminado
     */
    const DEFAULT_CONTROLLER = 'Home';
    
    /**
     * Acción o método predeterminado de cualquier controlador
     */
    const DEFAULT_ACTION = 'index';
}
