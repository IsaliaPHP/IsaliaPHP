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
        $this->_vista = $accion;
        $this->_plantilla = 'default';
    }
    
    
    public function __set($atributo, $valor)
    {
        $this->_propiedades[$atributo] = $valor;
    }
    
    public function __get($atributo)
    {
        return $this->_propiedades[$atributo] ?? null;
    }
    
    public function __destruct(){
        $this->renderizar();
    }
    
    private function renderizar()
    {
        extract($this->_propiedades);
        
        
        $ruta_vistas = RUTA_RAIZ . DS . 'App' . DS . 'Vistas' . DS;
        
        ob_start();
        
        $vista_actual = $this->_vista ?? $this->_accion;
        
        if (file_exists($ruta_vistas . $this->_controlador . DS . $vista_actual . '.phtml')) {
            include ($ruta_vistas . $this->_controlador . DS . $vista_actual . '.phtml');
            //throw (new Exception('Cargar vista: ' . $ruta_vistas . $this->_controlador . DS . $vista_actual . '.phtml'));
        } else {
            throw (new Exception('No existe vista: ' . $ruta_vistas . $this->_controlador . DS . $vista_actual . '.phtml'));
        }
        
        Cargar::asignarContenido(ob_get_clean());
        
        //throw (new Exception('No existe ' . $ruta_vistas . '_Compartidos' . DS . 'Plantillas' . DS . 'default.phtml'));
        
        
        //$archivo_plantilla = $ruta_vistas . '_Compartidos' . DS . 'Plantillas' . DS . 'default.phtml';
        
        $archivo_plantilla = $ruta_vistas . '_Compartidos' . DS . 'Plantillas' . DS . $this->_plantilla . '.phtml';
        if (file_exists($archivo_plantilla)) {
            include($archivo_plantilla);
        } else {
            throw (new Exception('No existe plantilla: ' . $archivo_plantilla));
        }
        
        
    }
    
}