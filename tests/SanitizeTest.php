<?php

use PHPUnit\Framework\TestCase;

class SanitizeTest extends TestCase
{
    public function testExecute()
    {
        // Set up test environment
        $_GET = ["test\\string\\"];
        $_POST = ["another\\test\\"];
        $_COOKIE = ["cookie\\value\\"];
        
        
        
        Sanitize::execute();

        $this->assertEquals(["teststring"], $_GET);
        $this->assertEquals(["anothertest"], $_POST);
        $this->assertEquals(["cookievalue"], $_COOKIE);

    }
}
