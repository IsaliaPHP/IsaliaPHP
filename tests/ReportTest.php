<?php

use PHPUnit\Framework\TestCase;

class ReportTest extends TestCase
{

    protected function setUp(): void
    {
        Config::$SHOW_ERRORS = true;
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';        
    }

    public function testGenerateReport()
    {
        // Simular un error para probar handleError
        $level = E_WARNING;
        $message = "Test error message";
        $file = "test.php";
        $line = 123;

        // Verificar que handleError lanza una ErrorException
        $this->expectException(\ErrorException::class);
        Report::handleError($level, $message, $file, $line);

        // Probar handleException
        try {
            // Crear una excepción de prueba
            throw new \Exception("Test exception");
        } catch (\Exception $e) {
            // Capturar la salida
            ob_start();
            Report::handleException($e);
            $output = ob_get_clean();

            // Verificar que la salida contiene los elementos esperados
            $this->assertStringContainsString('Fatal error', $output);
            $this->assertStringContainsString('Test exception', $output);
            $this->assertStringContainsString('Uncaught exception', $output);
        }
    }

    public function testHandleExceptionWithShowErrors()
    {
        // Configurar para mostrar errores
        Config::$SHOW_ERRORS = true;

        // Crear una excepción de prueba
        $exception = new \Exception("Test exception", 500);

        // Capturar la salida
        ob_start();
        Report::handleException($exception);
        $output = ob_get_clean();

        // Verificar que la salida contiene los elementos esperados
        $this->assertStringContainsString('Fatal error', $output);
        $this->assertStringContainsString('Uncaught exception', $output);
        $this->assertStringContainsString('Test exception', $output);
    }

    public function testHandleExceptionWithoutShowErrors()
    {
        // Configurar para no mostrar errores
        Config::$SHOW_ERRORS = false;

        // Crear una excepción de prueba
        $exception = new \Exception("Test exception", 500);

        // Capturar el log de errores
        $logFile = APP_PATH . 'temp' . DS . 'logs' . DS . date('Y-m-d') . '.txt';
        if (file_exists($logFile)) {
            unlink($logFile); // Eliminar el log anterior si existe
        }

        Report::handleException($exception);

        // Verificar que el log de errores contiene los elementos esperados
        $this->assertFileExists($logFile);
        $logContent = file_get_contents($logFile);
        $this->assertStringContainsString('Uncaught exception', $logContent);
        $this->assertStringContainsString('Test exception', $logContent);
        
        unlink($logFile);
    }
}
