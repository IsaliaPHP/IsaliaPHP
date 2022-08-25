<?php

/**
 * Clase para gestionar variables de sesión. Ideal para autenticación 
 * o carros de compra
 */
class Sesion
{
	/**
     * Permite obtener una entrada desde la variable $_SESSION
     * de acuerdo a su clave $var 
     * @param string $var
     * @return mixed
     */
	public static function obtener(string $var)
	{
		if (empty($_SESSION[$var])) {
			return null;
		}

		return $_SESSION[$var];
	}

	/**
     * Permite crear una entrada en la variable $_SESSION
     * de acuerdo a su clave $var 
     * @param string $var
     * @param mixed $valor
     * @return void
     */
	public static function asignar(string $var, $valor)
	{
		$_SESSION[$var] = $valor;
	}

	/**
     * Permite eliminar una entrada en la variable $_SESSION
     * de acuerdo a su clave $var 
     * @param string $var
     * @return void
     */
	public static function remover(string $var)
	{
		unset($_SESSION[$var]);
	}

	/**
	 * Permite cerrar la sesión
	 */
	public static function finalizar()
	{
		session_destroy();
	}
}