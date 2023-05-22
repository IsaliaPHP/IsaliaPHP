<?php

/**
 * Clase para realizar validaciones generales
 */
class Validate
{
    /**
     * Revisa que $input corresponda con un id válido
     * @param $input
     * @return bool
     * @throws Exception
     */
	public static function isId($input):bool
	{
		if (!is_numeric($input) || $input < 1) {
			throw new Exception("Incorrect Id: " . $input);
		}
		return true;
	}
}