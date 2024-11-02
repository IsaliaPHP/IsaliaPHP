<?php

use PHPUnit\Framework\TestCase;

class HelloController extends Controller
{
    public function index()
    {
        View::setTemplate("index");
        $this->setView(null);
        $this->saludo = "Hola";        
        echo $this->saludo;
    }
}

class ViewTest extends TestCase
{
    public function testRenderViewNotFound()
    {        
        $this->expectException(Exception::class);
        //$this->expectExceptionMessage("No se encuentra home/show.phtml");        
        View::render("home/show");
    }    

    public function testRenderThrowsExceptionForNonExistentTemplate()
    {
        View::$_template = null;
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("No se encuentra _shared/templates/index.phtml");
        View::render("hello/index");
    }

}
