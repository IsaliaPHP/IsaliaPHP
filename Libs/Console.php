<?php

/**
 * Clase que permite escribir logs
 */
class Console {

    const LOGS_DIR = APP_PATH . 'Temp' . DS . 'Logs';

    /**
     * Escribe dentro del archivo de log de acuerdo a la fecha del sistema
     * @param string $mensaje
     * @return void
     * @throws Exception
     */
    public static function writeLog(string $mensaje) {
        try {
            $arch = fopen(realpath(self::LOGS_DIR) .
                    DS . "log_" . date("Y-m-d") . ".txt", "a+");

            fwrite($arch, "[" . date("Y-m-d H:i:s.u") . " " .
                    $mensaje . "\n");
            fclose($arch);
        } catch (\Exception $ex) {
            throw (new \Exception('Imposible escribir en el directorio ' . self::LOGS_DIR));
        }
    }

}
