<?php

use PHPUnit\Framework\TestCase;

class AutoloaderTest extends TestCase
{           

    public function testExistingClass()
    {
        $class = new SqlBuilder("usuarios");
        $this->assertInstanceOf(SqlBuilder::class, $class);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Clase no encontrada UnexistingClass
     */
    public function testUnexistingClass()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Clase no encontrada UnexistingClass');
        
        Autoloader::exec('UnexistingClass');
    }
}
