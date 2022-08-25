<?php

/**
 * Clase repositorio de objetos
 */
class Repositorio
{
	private static $repositorio = [];

    /**
     * Permite obtener o crear una entrada desde el repositorio de acuerdo al
     * nombre de la clase y su id
     * @param $nombre_clase
     * @param $id
     * @return mixed
     */
	public static function obtener($nombre_clase, $id)
	{
		if (!isset(self::$repositorio[$nombre_clase][$id])) {
			self::$repositorio[$nombre_clase][$id] = new $nombre_clase($id);
		}

		return self::$repositorio[$nombre_clase][$id];
	}
}