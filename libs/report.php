<?php

/**
 * Report
 * @author nelson rojas
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
        View::setHasErrors(true);
        
        $code = $exception->getCode();
        if ($code != 404) {
            $code = 500;
        }
        http_response_code($code);

        if (Config::SHOW_ERRORS || in_array($_SERVER['REMOTE_ADDR'], Config::EXCEPTIONS)) {
            View::partial("error/header");
            echo "<h1 class='text-danger'>Fatal error</h1>";
            echo "<p>Uncaught exception: '" . get_class($exception) . "'</p>";
            echo "<p>Message: '" . $exception->getMessage() . "'</p>";
            echo "<p>Stack trace:<pre>" . $exception->getTraceAsString() . "</pre></p>";
            echo "<p>Thrown in '" . $exception->getFile() . "' on line " . $exception->getLine() . "</p>";
            View::partial("error/footer");            
        } else {
            $log = APP_PATH . 'temp' . DS . 'logs' . DS . date('Y-m-d') . '.txt';
            ini_set('error_log', $log);

            $message = "Uncaught exception: '" . get_class($exception) . "'";
            $message .= " with message '" . $exception->getMessage() . "'";
            $message .= "\nStack trace: " . $exception->getTraceAsString();
            $message .= "\nThrown in '" . $exception->getFile() . "' on line " . $exception->getLine();

            error_log($message);
            
            View::render("_shared/templates/$code");
        }
    }
}
