<?php

/**
 * 
 * @author nelson rojas
 * class Modelo
 * @property int id
 * @method inicializar
 */
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

        $this->_nombre_tabla = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $this->_nombre_modelo));


        if ((int) method_exists($this, "inicializar")) {
            call_user_func(array($this, "inicializar"));
        }
    }

    public function asignarNombreDeTabla(string $nombre_de_tabla)
    {
        $this->_nombre_tabla = $nombre_de_tabla;
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
    
    public function obtenerPrimero($condicion, $parametros = null)
    {
        $sql = "SELECT * FROM " . $this->_nombre_tabla . " ";
        
        if (!empty($condicion)) {
            $sql .= $condicion;
        }
        
        return Bd::obtenerFila($sql, $parametros);
    }
    
    
    public function agregar($datos)
    {
        $this->antes_de_agregar();
        return Bd::insertar($this->_nombre_tabla, $datos);
        $this->despues_de_agregar();
    }

    public function actualizar($datos, $condicion = null)
    {
        $this->antes_de_actualizar();
        return Bd::actualizar($this->_nombre_tabla, $datos, $condicion);
        $this->despues_de_actualizar();
    }
    
    public function eliminarTodos($condicion, $parametros = null)
    {
        $this->antes_de_eliminar();
        return Bd::eliminar($this->_nombre_tabla, $condicion, $parametros);
        $this->despues_de_eliminar();
    }
    
    public function eliminar()
    {
        if (intval($this->id) > 0) {
            $condicion = " WHERE id = " . intval($this->id);
            $this->antes_de_eliminar();
            return Bd::eliminar($this->_nombre_tabla, $condicion);
            $this->despues_de_eliminar();
        } else {
            return false;
        }
    }
    
    public function cargar($datos)
    {
        foreach($datos as $clave => $valor) {
            $this->$clave = $valor;
        }
    }
    
    public function guardar()
    {
        if (intval($this->id) > 0) {
            $this->antes_de_actualizar();
            return $this->actualizar($this->_datos, " WHERE id = " . $this->id) > 0;
            $this->despues_de_actualizar();
        } else {
            $this->antes_de_agregar();
            $nuevo_id = intval($this->agregar($this->_datos));
            $this->despues_de_agregar();
            $this->id = $nuevo_id;
            return $nuevo_id > 0;
        }
        return false;
    } 
    
    
    
    public function __set($atributo, $valor)
    {
        $this->_datos[$atributo] = $valor;
    }
    
    public function __get($atributo)
    {
        return $this->_datos[$atributo] ?? null;
    }
    
    //metodos auxiliares
    public function antes_de_agregar() {}
    public function antes_de_actualizar() {}
    public function antes_de_eliminar() {}
    public function despues_de_agregar() {}
    public function despues_de_actualizar() {}
    public function despues_de_eliminar() {}

}
