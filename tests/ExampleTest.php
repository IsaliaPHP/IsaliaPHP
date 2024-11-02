<?php

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function testBasicFunctionality()
    {
        $a = 1;
        $b = 1;

        $this->assertEquals($a, $b);
    }

}
