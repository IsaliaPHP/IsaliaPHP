<?php

/**
 * Clase que permite escribir logs
 */
class Consola {

    const LOGS_DIR = RUTA_RAIZ . DS . 'App' . DS .
            'Temporales' . DS . 'Logs';

    /**
     * Escribe dentro del archivo de log de acuerdo a la fecha del sistema
     * @param string $mensaje
     * @return void
     * @throws Exception
     */
    public static function escribirLog(string $mensaje) {
        try {
            $arch = fopen(realpath(Consola::LOGS_DIR) .
                    DS . "log_" . date("Y-m-d") . ".txt", "a+");

            fwrite($arch, "[" . date("Y-m-d H:i:s.u") . " " .
                    $mensaje . "\n");
            fclose($arch);
        } catch (\Exception $ex) {
            throw (new \Exception('Imposible escribir en el directorio ' . Consola::LOGS_DIR));
        }
    }

}
