<?php 
class GeneradorSql {
    private $_tablas = [];
    private $_columnas = [];
    private $_join = [];
    private $_condiciones = [];
    private $_orden = [];
    private $_limite = [];
    private $_funcion = '';
    private $_datos = [];
    private $_atributos = '';
    private $_valores = '';

    private function _getParams(array $parametros)
    {
        $datos = array();
        foreach ($parametros as $p) {
            if (is_string($p)) {
                $match = explode(': ', $p, 2);
                if (isset($match[1])) {
                    $datos[$match[0]] = $match[1];
                } else {
                    $datos[] = $p;
                }
            } else {
                $datos[] = $p;
            }
        }

        return $datos;
    }
    
    public function agregarTabla(string $nombreTabla)
    {
        $this->_tablas[] = $nombreTabla;
    }

    public function agregarCondicion(array $condicion)
    {
        $condicion = $this->_getParams($condicion);
        
        if (count($condicion) > 0) {
            foreach ($condicion as $key => $value) {
                array_push($this->{'_' . $key}, $value);
            }
        }
    }

    public final function insertar(string $nombreTabla)
    {
        $this->_funcion = "INSERT";
        $this->_tablas[] = $nombreTabla;
    }

    public final function actualizar(string $nombreTabla)
    {
        $this->_funcion = "UPDATE";
        $this->_tablas[] = $nombreTabla;
    }

    public final function eliminar(string $nombreTabla)
    {
        $this->_funcion = "DELETE";
        $this->_tablas[] = $nombreTabla;
    }

    public function agregarDatos(array $datos)
    {
        $this->_datos = $datos;    
    }
    
    private function _generarConsulta(string $condicion, string $desde, string $donde)
    {
        if (strtoupper($condicion) == "INSERT") {
            $this->_atributos = implode(', ', array_keys($this->_datos));
            $this->_valores = implode(', ', array_map(function($item) {
                return ':' . $item;
            }, array_keys($this->_datos)));   
            return 'INSERT INTO ' . $desde . ' (' .
                    $this->_atributos . ') VALUES (' . $this->_valores . ')'; 
        } 
        if (strtoupper($condicion) == "UPDATE") {
            $this->_valores = implode(', ', array_map(function($item) {
                return $item . ' = :' . $item;
            }, array_keys($this->_datos)));
            return 'UPDATE ' . $desde . ' SET ' .
                    $this->_valores . ' ' . $donde;
        }
        if (strtoupper($condicion) == "DELETE") {
            return 'DELETE FROM ' . $desde . ' ' . $donde;
        }
    }
    
    
    private function _generar()
    {
        $desde = implode(',', array_unique($this->_tablas));
        $donde = implode(' AND ', array_unique($this->_condiciones));
        $donde = empty($donde) ? '' : ' WHERE ' . $donde;

        $join = implode(' ', array_unique($this->_join));
        $orden = implode(' ', array_unique($this->_orden));
        $orden = empty($orden) ? '' : ' ORDER BY ' . $orden;
        
        $limite = implode(' ', array_unique($this->_limite));
        $limite = empty($limite) ? '' : ' LIMIT ' . $limite;

        $columnas = implode(',', array_unique($this->_columnas));
        $columnas = empty($columnas) ? '*' : $columnas;


        if (!empty($this->_funcion)) {
            $result = $this->_generarConsulta(
                    $this->_funcion, 
                    $desde, $donde);
        } else {
            $result = 'SELECT ' . $columnas .
                      ' FROM ' . $desde . ' ' . $join . ' ' .
                      $donde . ' ' . $orden . ' ' . $limite;
        }
        
        return $result;
    }
    
    private function _limpiar()
    {
        $this->_condiciones = [];
        $this->_columnas = ['*'];
        $this->_tablas = [];
        $this->_join = [];
        $this->_limite = [];
        $this->_orden = [];
        $this->_funcion = '';
        $this->_datos = [];
        $this->_atributos = '';
        $this->_valores = '';
    }
    
    public function __toString()
    {
        return $this->_generar();        
    }

    public function __destroy()
    {
        $this->_limpiar();
    }
    
}