<?php 

/**
 * 
 * @author nelson rojas
 * class Controlador
 * @property string _controlador
 * @property string _accion
 * @property string _vista
 * @property string _plantilla
 */
class Controlador 
{
    protected $_propiedades = [];
    
    
    public function __construct($controlador, $accion)
    {
        $this->_controlador = str_replace("Controlador", "", $controlador);
        $this->_accion = $accion;
    }
    
    public final function obtenerPropiedades()
    {
        return $this->_propiedades;    
    }
    
    
    public function __set($atributo, $valor)
    {
        $this->_propiedades[$atributo] = $valor;
    }
    
    public function __get($atributo)
    {
        return $this->_propiedades[$atributo] ?? null;
    }
       
}
