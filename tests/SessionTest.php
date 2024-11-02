<?php

use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{
    protected function setUp(): void
    {
        session_start();
    }

    protected function tearDown(): void
    {
        session_destroy();
    }

    public function testGet()
    {
        $this->assertEquals(null, Session::get('test'));
    }   

    public function testSet()
    {
        Session::set('test', 'value');
        $this->assertEquals('value', Session::get('test'));
    }

    public function testDelete()
    {
        Session::set('test', 'value');
        Session::delete('test');
        $this->assertEquals(null, Session::get('test'));
    }

    public function testDestroy()
    {
        Session::set('test', 'value');
        Session::destroy();
        session_start();
        $this->assertEquals(null, Session::get('test'));
    }
}
