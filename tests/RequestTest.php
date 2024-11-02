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
        Request::setInputData(post: ['nombre' => 'Juan']);

        $this->assertEquals('Juan', Request::post('nombre'));
        $this->assertNull(Request::post('edad'));

        $this->assertTrue(Request::hasPost('nombre'));
    }

    public function testGet()
    {
        Request::setInputData(get: ['nombre' => 'Juan']);
        $this->assertEquals('Juan', Request::get('nombre'));
        $this->assertNull(Request::get('edad'));

        $this->assertTrue(Request::hasGet('nombre'));
    }

    public function testConfig()
    {
        Request::setConfig(new Config());
        $this->assertInstanceOf(Config::class, Request::$config);
    }

    public function testSafetyKey()
    {
        Request::setInputData(post: ['safety_key' => 'd34648415b8548c37368d41babd07ff2V39378d7f92f8fe0829b3e64728566cae0']);
        $this->assertTrue(Request::isSafe());
    }
}
