<?php

/**
 * ScaffoldController
 * @author nelson rojas
 * @property list_of_items array
 * @property message string
 * @property current_item
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
    }

    public function show(int $id)
    {
        $this->current_item = (new $this->_model)->findById($id);        
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
                Flash::valid("Elemento creado correctamente");
                $this->redirect($this->_controller_url);
            } else {
                Flash::error("Imposible crear el elemento");
            }
        }
        //espera que exista una vista en la carpeta con el nombre del controlador
        //y que su nombre sea create.phtml        
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
                Flash::valid("Elemento actualizado correctamente");
                $this->redirect($this->_controller_url);
            } else {
                Flash::error("Imposible actualizar el elemento");
            }
        }
        $this->current_item = $model_instance->findById($id);
        //espera que exista una vista en la carpeta con el nombre del controlador
        //y que su nombre sea edit.phtml        
    }

    public function delete(int $id)
    {
        //no requiere vista, porque elimina y luego hace una redirección
        //a la vista index del controlador
        $model_instance = (new $this->_model)->findById($id);
        $this->setView(null);
        if ($model_instance && $model_instance->delete()) {
            //redirigir a la acción index del controlador actual
            Flash::valid("Elemento eliminado correctamente");
        }
        $this->redirect($this->_controller_url);
    }
}