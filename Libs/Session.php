<?php

/**
 * Clase para gestionar variables de sesión. Ideal para autenticación 
 * o carros de compra
 */
class Session
{
	/**
	 * Permite obtener una entrada desde la variable $_SESSION
	 * de acuerdo a su clave $key 
	 * @param string $key
	 * @return mixed
	 */
	public static function get(string $key)
	{
		if (empty($_SESSION[$key])) {
			return null;
		}

		return $_SESSION[$key];
	}

	/**
	 * Permite crear una entrada en la variable $_SESSION
	 * de acuerdo a su clave $key 
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	public static function set(string $key, $value)
	{
		$_SESSION[$key] = $value;
	}

	/**
	 * Permite eliminar una entrada en la variable $_SESSION
	 * de acuerdo a su clave $key 
	 * @param string $key
	 * @return void
	 */
	public static function delete(string $key)
	{
		unset($_SESSION[$key]);
	}

	/**
	 * Permite cerrar la sesión
	 */
	public static function destroy()
	{
		session_destroy();
	}
}
