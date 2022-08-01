<?php
class Modelo 
{
	protected $_tabla;
    private $_bd;
    private $_sql;
    
    function __construct($nombreTabla) {
        $this->_tabla = $nombreTabla;        
        $this->_bd = new Bd();
        $this->_sql = new GeneradorSql();
    }
    
    function __destruct() {
        
    }
    
    function primero($condiciones = null, $parametros = null)
    {
    	
    	$this->_sql->agregarTabla($this->_tabla);
		if ($condiciones) {
			$this->_sql->agregarCondicion($condiciones);
		}
		$condiciones[] = 'limit:1';

    	$result = $this->buscar($condiciones, $parametros);
    	if ($result) {
    		return $result[0];
    	} else {
    		return null;
    	}
    }
    
    function buscar($condiciones = null, $parametros = null)
    {
    	
    	$this->_sql->agregarTabla($this->_tabla);
		if ($condiciones) {
			$this->_sql->agregarCondicion($condiciones);
		}

    	return $this->_bd->buscar($this->_sql, $parametros);
    }
    
    public function insertar($data)
    {
    	
    	$this->_sql->insertar($this->_tabla);
    	$this->_sql->agregarDatos($data);

    	return $this->_bd->ejecutar($this->_sql, $data) > 0;
    }
    
    public function actualizar($data, $condiciones = null, $parametros = null)
    {
    	
        $this->_sql->actualizar($this->_tabla);
        $this->_sql->agregarDatos($data);
        if ($condiciones) {
    		$this->_sql->agregarCondicion($condiciones);
    	}
    	
    	$parameters = array_merge($data, $parametros ?? []);

    	return $this->_bd->ejecutar($this->_sql, $parameters) > 0;
    }
    
    public function eliminar($condiciones = null, $parametros = null)
    {	
    	
    	$this->_sql->eliminar($this->_tabla);
    	
    	if ($condiciones) {
    		$this->_sql->agregarCondicion($condiciones);
    	}

    	return $this->_bd->ejecutar($this->_sql, $parametros) > 0;
    }
}
