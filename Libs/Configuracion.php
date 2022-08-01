<?php

class Configuracion 
{
	public const CADENA_CONEXION = 'mysql:host=127.0.0.1;dbname=backend;charset=utf8';
	public const USUARIO_BD = 'root';
	public const CLAVE_BD = '';
	public const PARAMETROS_EXTRAS = [
				PDO::ATTR_PERSISTENT => true, //conexión persistente
				PDO::ATTR_ERRMODE    => PDO::ERRMODE_EXCEPTION,
			];

	public const CONTROLADOR_PREDETERMINADO = 'Home';
	public const ACCION_PREDETERMINADA = 'index';

}