<?php

class Home extends Controlador {

    public function index() {
        $this->saludo = 'Hola Mundo'; //se asigna dentro de misVariables
        $this->usuarios = (new Modelo('usuarios'))->todos();
        
        Cargar::vista('Home\index', $this->misVariables());
    }

}
