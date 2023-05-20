<?php 

/**
 * 
 * @author nelson rojas
 * class Controlador
 * @property string _controlador
 * @property string _accion
 */
class Controlador 
{
    protected $_propiedades = [];
    
    
    public function __construct($controlador, $accion)
    {
        $this->_controlador = str_replace("Controlador", "", $controlador);
        $this->_accion = $accion;
    }
    
    /**
     * recupera el contenido desde el arreglo _propiedades
     */
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
