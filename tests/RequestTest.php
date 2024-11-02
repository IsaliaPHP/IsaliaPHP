<?php

use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    protected function setUp(): void
    {
        // Limpiar las superglobales antes de cada prueba
        $_POST = [];
        $_GET = [];
    }

    public function testPost()
    {
        $_POST['nombre'] = 'Juan';

        $this->assertEquals('Juan', Request::post('nombre'));
        $this->assertNull(Request::post('edad'));
    }

    public function testGet()
    {
        $_GET['nombre'] = 'Juan';

        $this->assertEquals('Juan', Request::get('nombre'));
        $this->assertNull(Request::get('edad'));
    }
}
