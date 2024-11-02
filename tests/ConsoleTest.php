<?php

use PHPUnit\Framework\TestCase;

class ConsoleTest extends TestCase
{
    public function testOutput()
    {
        
        // Asegúrate de que el archivo de log no exista antes de la prueba
        $logFilePath = APP_PATH . 'temp' . DS . 'logs' . DS . 'log_' . date('Y-m-d') . '.txt';
        if (file_exists($logFilePath)) {
            unlink($logFilePath);
        }

        Console::writeLog('Hello, World!');

        // Verifica que el archivo de log se haya creado
        $this->assertFileExists($logFilePath);

        // Verifica que el contenido del archivo de log sea el esperado
        $this->assertStringContainsString('Hello, World!', file_get_contents($logFilePath));
        
        unlink($logFilePath);
    }

    public function testWriteLogThrowsException()
    {

        // Cambia LOGS_DIR a un directorio no accesible o inexistente
        $path = '/path/to/nonexistent/';
        Console::setLogsDir($path . 'temp' . DS . 'logs');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Imposible escribir en el directorio ' . Console::LOGS_DIR);

        // Llama al método writeLog
        Console::writeLog("Este es un mensaje de prueba");

    }
}   