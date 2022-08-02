<?php

class Usuarios extends Controlador
{
    public function index()
    {
        $this->usuarios = (new Modelo('usuarios'))->todos();
        Cargar::vista('Usuarios/index', $this->misVariables() );
    }
    
    public function agregar()
    {
        if (Peticion::tienePost('usuario')) {
            $data = Peticion::post('usuario');
            $usuario = new Modelo('usuarios');
            
            if ($usuario->insertar($data)) {
                return Enrutador::irA('usuarios/index');
            } else {
                Return Cargar::vista('Usuarios/agregar');
            }
        }
        Return Cargar::vista('Usuarios/agregar');
        
    }
    
    public function editar(int $id)
    {
        $usuario = (new Modelo('usuarios'))->porId($id);
        if (Peticion::tienePost('usuario')) {
            $data = Peticion::post('usuario');
            $usuario = new Modelo('usuarios');
            
            if ($usuario->actualizar($data, "WHERE id = $id")) {
                return Enrutador::irA('usuarios/index');
            }
            $data['id'] = $id;
        }
        return Cargar::vista('Usuarios/editar', ['usuario' => $data ?? $usuario]);
    }
    
    public function eliminar(int $id)
    {
        $usuario = new Modelo('usuarios');
        $usuario->eliminar("WHERE id = $id");
        return Enrutador::irA('usuarios/index');
    }
    
    
}