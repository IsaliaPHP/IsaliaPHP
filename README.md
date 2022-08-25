# SimpleMVC
Framework MVC escrito en PHP con clases y funciones en español

## Introducción
SimpleMVC ha sido creado basándose en las ideas de reutilización de código, uso de convenciones y programación orientada a objetos.
Está pensado como herramienta de desarrollo para proyectos simples, que no requieren de librerías externas.
Usa el lenguaje PHP que permite utilizarlo tanto en grandes servidores, como en servidores compartidos (Shared Hosting).
El framework está diseñado para acceder a bases de datos, aunque se ha elegido que sólo cuente con conexión a una única base de datos. 
El usuario puede seleccionar el motor que necesite, siempre que pueda contar con una configuración compatible con PDO.

## Convenciones
En esta sección se presentan las convenciones, o los acuerdos sobre los cuales se ha creado este framework. 

### Convenciones generales

#### Nombre de Clases
Las clases que sean creadas deben ser creadas usando el formato NombreDeClase, por ejemplo: Usuario, CarroDeCompras, Categoria. Del mismo modo, el nombre de archivo de la clase debe mantenerse tal cual en el sistema de archivos, es decir, Usuario.php, CarroDeCompras.php, Categoria.php.

#### Carga automática de clases
El framework cuenta con un cargador automático de clases (Autoloader), pero sólo buscará aquellas clases que estén creadas en las siguientes ubicaciones:
a) \Libs
b) \App\Controladores
c) \App\Modelos

#### Nombre de Controladores
Los controladores deberán crearse en la carpeta App\Controladores. Su nombre de clase debe seguir las características indicadas anteriormente, pero además deberá agregarse el sufijo Controlador en el nombre de la misma (y también en el nombre del archivo físico). Ejemplos para nombres de clases válidos: UsuariosControlador, LoginControlador, HomeControlador. De igual forma, los nombres de archivos deberán coincidir con la regla del nombrado de clases, es decir, UsuariosControlador.php, LoginControlador.php, HomeControlador.php.

#### Controlador predeterminado
De forma predeterminada, SimpleMVC utiliza el controlador HomeControlador como clase inicial. Esta configuración puede ser modificada en el archivo \Libs\Configuracion.php

#### Método predeterminado
De forma predeterminada los controladores preguntan por la existencia de un método llamado antes_de_filtrar() que sirve como base para ejecutar código antes de que se ejecuten las acciones del controlador. Por ejemplo para autentificación o autorización de acceso en diferentes controladores.

#### Vista predeterminada
De igual forma, se ha definido que el nombre de la vista predeterminada sea *index*, por lo tanto, cada vez que un usuario utilice una llamada al controlador sin incluir la acción, de forma predeterminada, SimpleMVC buscará la acción (método/función) index dentro del controlador.

#### Ubicación de las vistas
Cada controlador deberá contar con vistas, las que alojará dentro de la ruta \App\Vistas\NombreDelControlador (sin el sufijo Controlador). Por ejemplo, las vistas del controlador UsuariosControlador se encontrarán en Vistas\Usuarios

#### Nombre de las vistas
Las vistas pueden llevar el nombre que el usuario estime conveniente. Sólo es necesario que la extensión del archivo sea .phtml. Por ejemplo, si el controlador UsuariosControlador tiene un metodo para agregar usuarios, entonces podría tener una archivo de vista llamado agregar.phtml. Se hace hincapié en que el nombre no es relevante porque es el usuario quien decide qué vista es la que puede cargar dentro del método del controlador.

#### Enviar datos las vistas
Las vistas pueden recibir datos desde el controlador de forma sencilla. Los elementos enviados a la vista deben ir en el formato de arreglo asociativo de acuerdo al ejemplo:

```php
class UsuariosControlador
{
    public function index(){
        return Cargar::vista('Usuarios/index', ['saludo' => 'Hola Mundo']);
    }
}
```

La vista recibirá una variable como $saludo, con la cual podrá interactuar libremente.
