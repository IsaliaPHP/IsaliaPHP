# IsaliaPHP  [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/IsaliaPHP/IsaliaPHP/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/IsaliaPHP/IsaliaPHP/?branch=main) [![Build Status](https://scrutinizer-ci.com/g/IsaliaPHP/IsaliaPHP/badges/build.png?b=main)](https://scrutinizer-ci.com/g/IsaliaPHP/IsaliaPHP/build-status/main) [![Code Quality](https://codeclimate.com/github/IsaliaPHP/IsaliaPHP/badges/gpa.svg)
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
Las clases que sean creadas deben ser creadas usando el formato NombreDeClase, por ejemplo: Usuario, CarroDeCompras, Categoria. Del mismo modo, el nombre de archivo de la clase debe mantenerse tal cual en el sistema de archivos, es decir, Usuario.php, CarroDeCompras.php, Categoria.php.

#### Carga automática de clases
El framework cuenta con un cargador automático de clases (Autoloader). Inicialmente buscará aquellas clases que estén creadas en las siguientes ubicaciones:
- \Libs
- \App\Controllers
- \App\Libs
- \App\Models
- \App\Helpers

Luego cargará cualquier clase que haya sido incluida usando composer (desde la carpeta vendor)

#### Nombre de Controladores
Los controladores deberán crearse en la carpeta App\Controllers. Su nombre de clase debe seguir las características indicadas anteriormente, pero además deberá agregarse el sufijo Controller en el nombre de la misma (y también en el nombre del archivo físico). Ejemplos para nombres de clases válidos: UsuariosController, LoginController, HomeController. De igual forma, los nombres de archivos deberán coincidir con la regla del nombrado de clases, es decir, UsuariosController.php, LoginController.php, HomeController.php.

#### Controlador predeterminado
De forma predeterminada, IsaliaPHP utiliza el controlador HomeController como clase inicial. Esta configuración puede ser modificada en el archivo App\Libs\Config.php

#### Acción/método predeterminada
De igual forma, se ha definido que el nombre de la acción predeterminada sea *index*, por lo tanto, cada vez que un usuario utilice una llamada al controlador sin incluir la acción, IsaliaPHP buscará la acción (método/función) index dentro del controlador. Esta configuración puede ser modificada igualmente en el archivo App\Libs\Config.php

#### Ubicación de las vistas
Cada controlador deberá contar con vistas, las que alojará dentro de la ruta \App\Views\NombreDelControlador (sin el sufijo Controlador). Por ejemplo, las vistas del controlador UsuariosController se encontrarán en App\Views\Usuarios.

#### Nombre de las vistas
Las vistas pueden llevar el nombre que el usuario estime conveniente. Sólo es necesario que la extensión del archivo sea .phtml. Por ejemplo, si el controlador UsuariosController tiene un metodo para agregar usuarios, entonces podría tener una archivo de vista llamado agregar.phtml. Se hace hincapié en que el nombre no es relevante porque es el usuario quien decide qué vista es la que puede cargar dentro del método del controlador.

#### Enviar datos las vistas
Las vistas pueden recibir datos desde el controlador de forma sencilla. Los elementos enviados a la vista deben ir en el formato de arreglo asociativo de acuerdo al ejemplo:

```php
class UsuariosController extends Controller
{
    public function index(){
        return Load::view('Usuarios/index', ['saludo' => 'Hola Mundo']);
    }
}
```

La vista recibirá una variable como $saludo, con la cual podrá interactuar libremente.

Otra forma de pasar datos desde el controlador a la vista es usar las propiedades del controlador y luego pasar todo lo que haya sido agregado al mismo tal como se muestra en el siguiente ejemplo.

```php
class EntradasController extends Controller
{
    public function index(){
        $this->lista_de_entradas_activas = (new Entradas)->findAll("WHERE activa = 1");
        return Load::view('Entradas/index', $this->getProperties());
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
            //va al controlador login, método entrar
            return Router::to('login/entrar'); 
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

### Acceso a la base de datos
IsaliaPHP implementa una clase llamada Db (por DataBase /Base de Datos en español) en la cual se alojan métodos estáticos que permiten la consulta, creación, actualización y eliminación de datos. En términos de utilidad, las operaciones de INSERT, UPDATE y DELETE están encapsuladas para evitar errores clásicos de escritura de sentencias SQL. Como la clase utiliza PDO, hace uso de dichas funcionalidades. Los métodos de consulta de datos reciben SQL directo.

Puede verse su documentación en detalle dentro del archivo Documentation\db.md


### Documentación
La documentación podrá encontrarse dentro de la carpeta Documentation en la raíz del proyecto.


### Aviso final
El código aquí expuesto no asegura ni garantiza que esté libre de errores o fallos, por lo tanto, el usuario es responsable de utilizarlo bajo su propio criterio. Su creador no entrega garantías de ningún tipo sobre el código y no es responsable por el uso que las personas hagan de él.
