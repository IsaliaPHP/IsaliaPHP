<?php 

/**
 * 
 * @author nelson rojas
 * class Controller
 * @property string _controller
 * @property string _action
 * @property string _controller_url
 */
class Controller
{
    protected $_properties = [];
    
    /**
     * Inicializa el controlador usando el nombre y la accion que debera ejecutarse
     * Ej: DetalleVentasController, index
     * Detalle de las variables internas
     * _controller almacena el nombre de la clase ej: DetalleVentas
     * _controller_url almacena la ruta de la url en formato snake_case ej: detalle_ventas
     * _action almacena el nombre del metodo a ejecutar (en minusculas y en snake_case)
     */
    public function __construct($controller, $action)
    {
        $this->_controller = str_replace("Controller", "", $controller);
        $this->_controller_url = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $this->_controller));
        $this->_action = $action;

        // revisa si existe un metodo llamado "initialize"
        // y lo llama. es ideal para configuraciones
        if ((int) method_exists($this, "initialize")) {
            call_user_func(array($this, "initialize"));
        }
        
        // revisa si existe un metodo llamado "beforeFilter"
        // y lo llama. es ideal para autenticacion o autorizacion
        if ((int) method_exists($this, "beforeFilter")) {
            call_user_func(array($this, "beforeFilter"));
        }

    }
    
    /**
     * recupera el contenido desde el arreglo _properties
     */
    public final function getProperties()
    {
        return $this->_properties;    
    }
    
    
    public function __set($attribute, $value)
    {
        $this->_properties[$attribute] = $value;
    }
    
    public function __get($attribute)
    {
        return $this->_properties[$attribute] ?? null;
    }
       
}
