<?php 

/**
 * AdminController
 * @author nelson rojas
 */
class AdminController extends Controller
{
    /**
     * Método que se ejecuta antes de cualquier acción del controlador
     * Si el usuario no está autenticado, redirige a la página de login
     */
    final public function beforeFilter()
    {
        if (!Auth::isLogged()) {
            Flash::error('Por favor, inicie sesión para continuar.');
            $this->redirect('login');
        }

        $this->additionalBeforeFilter();
    }


    /**
     * Método que puede ser sobrescrito por las clases hijas
     * para añadir lógica adicional antes de las acciones
     */
    protected function additionalBeforeFilter()
    {
        // Las clases hijas pueden sobrescribir este método
        // para añadir lógica adicional
    }
}