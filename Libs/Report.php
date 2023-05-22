<?php

/**
 * Manegador de errores y excepciones
 */
class Report
{
    /**
     * Manejador general de errores
     * @param $level
     * @param $message
     * @param $file
     * @param $line
     * @return void
     * @throws ErrorException
     */
    public static function handleError($level, $message, $file, $line)
    {
        if (error_reporting() !== 0) {  // to keep the @ operator working
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }

    /**
     * Manejador de Excepciones general
     * @param $exception
     * @return void
     */
    public static function handleException($exception)
    {
        $code = $exception->getCode();
        if ($code != 404) {
            $code = 500;
        }
        http_response_code($code);

        if (Config::SHOW_ERRORS) {
            
            echo "<h1>Fatal error</h1>";
            echo "<p>Uncaught exception: '" . get_class($exception) . "'</p>";
            echo "<p>Message: '" . $exception->getMessage() . "'</p>";
            echo "<p>Stack trace:<pre>" . $exception->getTraceAsString() . "</pre></p>";
            echo "<p>Thrown in '" . $exception->getFile() . "' on line " . $exception->getLine() . "</p>";
            
        } else {
            $log = ROOT . DS . 'App' . DS . 'Temp' . DS . 'Logs' . DS . date('Y-m-d') . '.txt';
            ini_set('error_log', $log);

            $message = "Uncaught exception: '" . get_class($exception) . "'";
            $message .= " with message '" . $exception->getMessage() . "'";
            $message .= "\nStack trace: " . $exception->getTraceAsString();
            $message .= "\nThrown in '" . $exception->getFile() . "' on line " . $exception->getLine();

            error_log($message);
            
            Load::view("_Shared/Templates/$code");
        }
    }
}
