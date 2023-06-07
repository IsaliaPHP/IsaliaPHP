<?php

/**
 * @property list_of_items array
 * @property message string
 * @property current_item array
 */
class ScaffoldController extends AdminController
{
    //debe indicarse aquí el nombre de la clase del modelo
    //esta clase debe extender desde Model
    //se asigna como propiedad protected dentro del controlador
    //que extienda de ScaffoldController
    //ej: protected $_model = "Producto";
    //para usar el modelo Producto que apunta a la tabla producto.
    protected $_model;

    public function index()
    {
        $this->list_of_items = (new $this->_model)->findAll();
        //espera que exista una vista en la carpeta con el nombre del controlador
        //y que su nombre sea index.phtml
        Load::view($this->_controller . "/index", $this->getProperties());
    }

    public function create()
    {
        $model_instance = new $this->_model();

        //espera que el paquete de datos enviado desde el formulario venga
        //encapsulado dentro del nombre data
        //ej: data[nombre], data[precio]
        if (Request::hasPost('data')) {
            $requested_data = Request::post('data');
            $model_instance->load($requested_data);
            if ($model_instance->save()) {
                //redirigir a la acción index del controlador actual
                Router::to($this->_controller);
            } else {
                $this->message = "Imposible crear el elemento";
            }
        }
        //espera que exista una vista en la carpeta con el nombre del controlador
        //y que su nombre sea create.phtml
        Load::view($this->_controller . "/create", $this->getProperties());
    }

    public function edit(int $id)
    {
        $model_instance = new $this->_model();
         //espera que el paquete de datos enviado desde el formulario venga
        //encapsulado dentro del nombre data
        //ej: data[nombre], data[precio]
        if (Request::hasPost('data')) {
            $requested_data = Request::post('data');
            $model_instance->load($requested_data);
            $model_instance->id = $id;
            if ($model_instance->save()) {
                //redirigir a la acción index del controlador actual
                Router::to($this->_controller);
            } else {
                $this->message = "Imposible actualizar el elemento";
            }
        }
        $this->current_item = $model_instance->findById($id);
        //espera que exista una vista en la carpeta con el nombre del controlador
        //y que su nombre sea edit.phtml
        Load::view($this->_controller . "/edit", $this->getProperties());
    }

    public function delete(int $id)
    {
        //no requiere vista, porque elimina y luego hace una redirección
        //a la vista index del controlador
        $model_instance = new $this->_model();
        $model_instance->id = $id;
        $model_instance->delete();
        //redirigir a la acción index del controlador actual
        Router::to($this->_controller);
    }
}