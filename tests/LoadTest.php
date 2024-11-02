<?php

use PHPUnit\Framework\TestCase;

class LoadTest extends TestCase
{
    public function testLoadUrl()
    {
        Load::controllerFromUrl("/");
        $this->assertTrue(true);
    }

    public function testExceptionForNonexistentController()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Clase no encontrada UserController");
        
        Load::controllerFromUrl("user/show");
    }

    public function testExceptionForNonexistentControllerAction()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("HomeController/show no existe");
        
        
        Load::controllerFromUrl("home/show");
    }
}