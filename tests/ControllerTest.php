<?php

use PHPUnit\Framework\TestCase;

class IndexController extends Controller
{
    public function index()
    {
        $this->setView(null);
        $this->saludo = "Hola";

        print_r($this->getProperties());
        $this->redirect("/");
    }
}

class ControllerTest extends TestCase
{
    public function testIndex()
    {
        Load::controllerFromUrl("index/index");
        $this->assertTrue(true);
    }
}
