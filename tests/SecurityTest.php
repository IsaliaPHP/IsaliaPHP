<?php

use PHPUnit\Framework\TestCase;

class SecurityTest extends TestCase
{
    public function testInjectAntiCSRFHeader()
    {
        // Mockear la función header si es utilizada por Security::injectAntiCSRFHeader
        $this->expectOutputString(''); 

        Security::injectAntiCSRFHeader();

        $this->assertTrue(true);
    }
}