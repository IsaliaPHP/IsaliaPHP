<?php

class Informacion
{
	const LOGS_DIR = RUTA_RAIZ . DS . 'App'. DS . 
		'Temporales' . DS . 'Logs';

    public static function escribirLog($mensaje)
	{
		try {
            $arch = fopen(realpath(Informacion::LOGS_DIR) . 
            	DS . "log_" . date("Y-m-d") . ".txt", "a+");

            fwrite($arch, "[" . date("Y-m-d H:i:s.u") . " " . 
            	$mensaje . "\n");
            fclose($arch);
        } catch (\Exception $ex) {
            throw (new \Exception('Imposible escribir en el directorio ' . Informacion::LOGS_DIR));
        }
	}
}