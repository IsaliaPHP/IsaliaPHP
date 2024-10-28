<?php 

/**
 * Controller
 * @author nelson rojas
 * @abstract
 * Clase base para la gestion de controladores
 * @property string _controller
 * @property string _action
 * @property string _view
 * @property string _controller_url
 * @property string _redirect
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
        $this->_view = $action;

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
     * Reemplaza el nombre de la vista predeterminada a cargar
     * @param string $view
     */
    public function setView($view)
    {
        $this->_view = $view;
    }
    
    /**
     * recupera el contenido desde el arreglo _properties
     */
    public final function getProperties()
    {
        return $this->_properties;    
    }
    
    
    /**
     * Setter para los atributos de la clase
     * @param string $attribute
     * @param mixed $value
     */
    public function __set($attribute, $value)
    {
        $this->_properties[$attribute] = $value;
    }
    
    /**
     * Getter para los atributos de la clase
     * @param string $attribute
     * @return mixed
     */
    public function __get($attribute)
    {
        return $this->_properties[$attribute] ?? null;
    }

    /**
     * Redirecciona a la ruta especificada
     * @param string $route
     */
    public function redirect($route)
    {
        $this->_redirect = $route;
    }

    /**
     * Destructor de la clase
     * se encarga de renderizar la vista y redireccionar si es necesario
     */
    public function __destruct()
    {
        if ($this->_redirect !== null) {
            Router::to($this->_redirect);
            return true;
        } else { $current_view = $this->_controller_url . "/" . $this->_view;
            if (empty(View::getTemplate())) {
                View::setTemplate("default");
            }

            if ($this->_view != null) {
                View::render($current_view, $this->_properties);
            }
        }
    }

}
