<?php

class Modelo
{
    protected $_nombre_modelo;
    protected $_nombre_tabla;
    protected $_datos = [];
    
    public function __construct($nombreClase = '') {
        if (empty($nombreClase)) {
            $this->_nombre_modelo = get_class($this);
        } else {
            $this->_nombre_modelo = $nombreClase;
        }

        $this->_nombre_tabla = strtolower($this->_nombre_modelo);
    }
    
    public function obtenerPorId(int $id)
    {
        $sql = "SELECT * FROM " . $this->_nombre_tabla . 
               " WHERE id = $id";
        return Bd::obtenerFila($sql);
    }
    
    public function obtenerFilas($condicion = '', $parametros = null)
    {
        $sql = "SELECT * FROM " . $this->_nombre_tabla . " ";
        
        if (!empty($condicion)) {
            $sql .= $condicion;
        }
        
        return Bd::obtenerFilas($sql, $parametros);
    }
    
    public function agregar($datos)
    {
        return Bd::insertar($this->_nombre_tabla, $datos);
    }

    public function actualizar($datos, $condicion)
    {
        return Bd::actualizar($this->_nombre_tabla, $datos, $condicion);
    }
    
    public function eliminarTodos($condicion, $parametros = null)
    {
        return Bd::eliminar($this->_nombre_tabla, $condicion, $parametros);
    }
    
    public function eliminar()
    {
        if (intval($this->id) > 0) {
            $condicion = " WHERE id = " . intval($this->id);
            return Bd::eliminar($this->_nombre_tabla, $condicion);
        } else {
            return false;
        }
    }
    
    public function guardar()
    {
        if (intval($this->id) > 0) {
            return $this->actualizar($this->_datos, " WHERE id = " . $this->id) > 0;
        } else {
            $nuevo_id = $this->agregar($this->_datos);
            $this->id = $nuevo_id;
            return $nuevo_id > 0;
        }
    } 
    
    
    
    public function __set($atributo, $valor)
    {
        $this->_datos[$atributo] = $valor;
    }
    
    public function __get($atributo)
    {
        return $this->_datos[$atributo] ?? null;
    }
    
       
}