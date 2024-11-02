<?php

use PHPUnit\Framework\TestCase;

class FlashTest extends TestCase
{
    protected function setUp(): void
    {
        
    }

    protected function tearDown(): void
    {
        
    }

    public function testMessages()
    {
        $types = ['valid', 'info', 'error'];
        foreach ($types as $type) {
            Flash::$type("test {$type} message");
            $this->assertTrue(Flash::hasMessages());
            Flash::render();
            $this->assertFalse(Flash::hasMessages());
        }
    }

}