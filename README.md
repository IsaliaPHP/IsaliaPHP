# IsaliaPHP  [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/IsaliaPHP/IsaliaPHP/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/IsaliaPHP/IsaliaPHP/?branch=main) [![Build Status](https://scrutinizer-ci.com/g/IsaliaPHP/IsaliaPHP/badges/build.png?b=main)](https://scrutinizer-ci.com/g/IsaliaPHP/IsaliaPHP/build-status/main) [![Maintainability](https://api.codeclimate.com/v1/badges/2d2532a4912884b87b8b/maintainability)](https://codeclimate.com/github/IsaliaPHP/IsaliaPHP/maintainability)
Un framework escrito en PHP para proyectos simples con documentación en español.

## Introducción
IsaliaPHP ha sido creado basándose en las ideas de reutilización de código, uso de convenciones y programación orientada a objetos.
Está pensado como herramienta de desarrollo para proyectos simples.
Usa el lenguaje de programación PHP y permite utilizarlo tanto en servidores dedicados, como en servidores compartidos (Shared Hosting).
El framework está diseñado para acceder a bases de datos, aunque se ha elegido que sólo cuente con conexión a una única base de datos. 
El usuario puede seleccionar el motor que necesite, siempre que pueda contar con una configuración compatible con PDO (PHP Data Objects).

## Convenciones
En esta sección se presentan las convenciones, o los acuerdos sobre los cuales se ha creado este framework. 

### Convenciones generales

#### Nombre de Clases
Las clases que sean creadas deben ser creadas usando el formato NombreDeClase, por ejemplo: Usuario, CarroDeCompras, Categoria. A este formato se le conoce como *PascalCase*. Por otro lado, el nombre de archivo de la clase debe usarse en formato *snake_case*, es decir, usuario.php, carro_de_compras.php, categoria.php.

#### Carga automática de clases
El framework cuenta con un cargador automático de clases (Autoloader). Inicialmente buscará aquellas clases que estén creadas en las siguientes ubicaciones:
- \libs
- \app\controllers
- \app\libs
- \app\models
- \app\helpers

Luego cargará cualquier clase que haya sido incluida usando PSR-0 o composer (desde la carpeta vendor)

#### Nombre de Controladores
Los controladores deberán crearse en la carpeta app\controllers. Su nombre de clase debe seguir las características indicadas anteriormente, pero además deberá agregarse el sufijo Controller en el nombre de la misma (y también en el nombre del archivo físico). Ejemplos para nombres de clases válidos: UsuariosController, LoginController, HomeController. De igual forma, los nombres de archivos deberán coincidir con la regla del nombrado de clases en *snake_case*, es decir, usuarios_controller.php, login_controller.php, home_controller.php.

#### Controlador predeterminado
De forma predeterminada, IsaliaPHP utiliza el controlador HomeController como clase inicial. Esta configuración puede ser modificada en el archivo app\libs\config.php

#### Acción/método predeterminada
De igual forma, se ha definido que el nombre de la acción predeterminada sea *index*, por lo tanto, cada vez que un usuario utilice una llamada al controlador sin incluir la acción, IsaliaPHP buscará la acción (método/función) index dentro del controlador. Esta configuración puede ser modificada igualmente en el archivo app\libs\config.php

#### Ubicación de las vistas
Cada controlador deberá contar con vistas, las que alojará dentro de la ruta \app\views\nombre_del_controlador (sin el sufijo Controller). Por ejemplo, las vistas del controlador UsuariosController se encontrarán en app\views\usuarios.

#### Nombre de las vistas
Las vistas pueden llevar el nombre que el usuario estime conveniente. Sólo es necesario que la extensión del archivo sea .phtml. Por ejemplo, si el controlador UsuariosController tiene un metodo para agregar usuarios, entonces podría tener una archivo de vista llamado agregar.phtml. IsaliaPHP procura buscar un archivo de vista con el nombre del método que se esté ejecutando en ese preciso momento, aunque se puede modificar manualmente usando el metodo setView() del controlador. 

#### Enviar datos las vistas
Las vistas pueden recibir datos desde el controlador de forma sencilla. Los elementos enviados se crean como variables del controlador, que luego son recuperados automaticamente en la vista de acuerdo con el siguiente ejemplo:

```php
class UsuariosController extends Controller
{
    //pintar la vista index.phtml
    //y enviar la variable saludo
    public function index(){
        $this->saludo = 'Hola Mundo';
    }
}
```

La vista recibirá una variable como $saludo, con la cual podrá interactuar libremente.

Otra forma de pasar datos desde el controlador a la vista es usar las propiedades del controlador y luego pasar todo lo que haya sido agregado al mismo tal como se muestra en el siguiente ejemplo.

```php
class EntradasController extends Controller
{
    public function index(){
        $this->lista_de_entradas_activas = (new Entradas)->findAll("activa = 1");
    }
}
```

La vista recibirá la variable $lista_de_entradas_activas.


#### Método predeterminado
De forma predeterminada los controladores preguntarán por la existencia de un método llamado *beforeFilter()* que sirve como base para ejecutar código antes de que se se llamen las acciones del controlador. Por ejemplo para autentificación o autorización de acceso en diferentes controladores. Ver el ejemplo a continuación:

```php
class AdminController extends Controller
{

    public function beforeFilter(){
        if (Session::get('esta_logueado') == false) {
            //hace una redireccion al controlador login, método entrar
            $this->redirect('login/entrar'); 
        }
    }
}


class EntradasController extends AdminController
{
    /*
    antes de que se ejecute cualquier método del controlador se llamará a beforeFilter
    dado que lo hereda desde AdminController
    */
    public function index()
    {
        
    }
}
```

### ORM
IsaliaPHP implementa un ORM sencillo (Object-Relational Mapping) para facilitar la interacción con la base de datos. El ORM permite mapear las tablas de la base de datos a clases de PHP, lo que permite interactuar con la base de datos de una manera más natural y sencilla. La clase destinada para esto es la clase Model.
En ella se implementan diferentes métodos para interactuar con la base de datos, como find, findAll, create, update, delete, etc.

Puede verse su documentación en detalle dentro del archivo docs\model.md

### Acceso a la base de datos
IsaliaPHP implementa una clase llamada Db (por DataBase /Base de Datos en español) en la cual se alojan métodos estáticos que permiten la consulta, creación, actualización y eliminación de datos. En términos de utilidad, las operaciones de INSERT, UPDATE y DELETE están encapsuladas para evitar errores clásicos de escritura de sentencias SQL. Como la clase utiliza PDO, hace uso de dichas funcionalidades. Los métodos de consulta de datos reciben SQL directo.

Puede verse su documentación en detalle dentro del archivo docs\db.md


### Documentación
La documentación podrá encontrarse dentro de la carpeta docs en la raíz del proyecto o desde la web [https://isaliaphp.github.io/IsaliaPHP](https://isaliaphp.github.io/IsaliaPHP)

### Aviso final
El código aquí expuesto no asegura ni garantiza que esté libre de errores o fallos, por lo tanto, el usuario es responsable de utilizarlo bajo su propio criterio. Su creador no entrega garantías de ningún tipo sobre el código y no es responsable por el uso que las personas hagan de él.
