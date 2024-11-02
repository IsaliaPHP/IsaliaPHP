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

    public function testRenderPartial()
    {
        View::partial("footer", ["time" => 10]);
        $this->expectOutputRegex("/footer/");
    }

    public function testRenderUnexistingPartial()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("No se encuentra _shared/partials/inexistente.phtml");
        View::partial("inexistente");
    }

}
