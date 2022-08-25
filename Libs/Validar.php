<?php

/**
 * Clase para realizar validaciones generales
 */
class Validar
{
    /**
     * Revisa que $input corresponda con un id válido
     * @param $input
     * @return bool
     * @throws Exception
     */
	public static function esId($input):bool
	{
		if (!is_numeric($input) || $input < 1) {
			throw new Exception("Id incorrecto: " . $input);
		}
		return true;
	}
}