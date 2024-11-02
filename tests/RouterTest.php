<?php

use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    protected function tearDown(): void
    {
    }

    public function testTo()
    {
        // Mockear la función header si es utilizada por Router::to
        $this->expectOutputString(''); // Si Router::to produce alguna salida

        // Simular el comportamiento de Router::to
        Router::to('/test');

        // Aquí podrías verificar el estado después de la ejecución
        // Por ejemplo, si Router::to cambia alguna propiedad estática
        // o si se espera que se llame a una función específica.
        $this->assertTrue(true);
    }
}