# Scaffoding de IsaliaPHP

También conocido como "andamios", el Scaffolding es una técnica de encapsular comportamientos en una clase y luego reutilizarlos en otra(s). A fin de cuentas, es una forma de herencia o extensión de clases.


## Utilidad en IsaliaPHP
En el caso particular, la clase ScaffoldingController provee los comportamientos clásicos que un programador necesita para realizar CRUD sencillos, sobre una tabla única.


## ¿Cómo hacer uso de ella?
Todo lo que necesitamos para ahorrar código con el apoyo de esta clase es definir un controlador que extienda de ella, definir una variable del controlador y luego sólo crear las vistas.

```php
class ProductosController extends ScaffoldController
{
    protected $_model = "Producto";
}
```

Y eso sería todo nuestro controlador.


## Métodos y Propiedades 
Una vez que se hereda (o extiende) de ScaffoldController es necesario tener en cuenta que el nuevo controlador implementará los siguientes métodos: index, create, edit y delete.


### Método index
En este método lo que se hace es crear una variable de controlador llamada **list_of_items**. En el método index esa variable es cargada llamando al método **findAll** de la clase del Modelo indicada en la propiedad **_model**.

El método index luego enviará la variable **$list_of_items** a la vista, la que espera se encuentre en la carpeta Views/NombreDelControlador/index.phtml.
En el ejemplo, esperará que se encuentre en Views/Productos/index.phtml


### Método show
Es útil si se espera revisar el contenido de un registro particular del modelo.
El método **show** recibe como parámetro un valor entero que corresponde con el identificador del registro que se va a consultar. La llamada a la base de datos se hace con el método **findById** del modelo.
El método show crea una variable de controlador llamada **current_item** que es enviada a la vista Views/NombreDelControlador/show.phtml. 


### Método create
El método **create** se utiliza para mostrar un formulario de creación para el modelo que uno haya indicado. También es el responsable de recibir el formulario enviado (POST) y crear el registro en la tabla a la que referencia el Modelo.
Al igual que index, el método create espera que exista una vista como Views/NombreDelControlador/create.phtml.
En caso que exista un error al crear el registro en la tabla, la vista recibirá una variable llamada **$message**, que indica que hubo un error al intentar crear el elemento.


### Método edit
El método **edit** se utiliza para mostrar un formulario de edición para el modelo que uno haya indicado. También es el responsable de recibir el formulario enviado (POST) y actualizar el registro en la tabla a la que referencia el Modelo.
El método **edit** recibe como parámetro un valor entero que corresponde con el identificador del registro que se va a actualizar.
Al igual que en create, el método edit espera que exista una vista como Views/NombreDelControlador/edit.phtml.
En caso que exista un error al actualizar el registro en la tabla, la vista recibirá una variable llamada **$message**, que indica que hubo un error al intentar actualizar el elemento.


### Método delete
El método **delete** se utiliza para eliminar un registro en la tabla a la que referencia el Modelo.
El método **delete** recibe como parámetro un valor entero que corresponde con el identificador del registro que se va a eliminar.
En la implementación actual de ScaffoldController el método ejecuta la eliminación y luego hace una redirección al método index del controlador en uso.





