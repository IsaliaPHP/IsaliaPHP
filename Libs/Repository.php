<?php

/**
 * Clase repositorio de objetos
 */
class Repository
{
	private static $attributes = [];

	/**
	 * Permite obtener o crear una entrada desde el repositorio de acuerdo al
	 * nombre de la clase y su id
	 * @param $class_name
	 * @param $id
	 * @return mixed
	 */
	public static function get($class_name, $id)
	{
		if (!isset(self::$attributes[$class_name][$id])) {
			self::$attributes[$class_name][$id] = new $class_name($id);
		}

		return self::$attributes[$class_name][$id];
	}
}
