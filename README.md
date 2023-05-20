# SimpleMVC  [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nelsonrojasn/SimpleMVC/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/nelsonrojasn/SimpleMVC/?branch=main) [![Build Status](https://scrutinizer-ci.com/g/nelsonrojasn/SimpleMVC/badges/build.png?b=main)](https://scrutinizer-ci.com/g/nelsonrojasn/SimpleMVC/build-status/main)
Framework MVC escrito en PHP con clases y funciones en español

## Introducción
SimpleMVC ha sido creado basándose en las ideas de reutilización de código, uso de convenciones y programación orientada a objetos.
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
- \App\Controladores
- \App\Libs
- \App\Modelos

Luego cargará cualquier clase que haya sido incluida usando composer (desde la carpeta vendor)

#### Nombre de Controladores
Los controladores deberán crearse en la carpeta App\Controladores. Su nombre de clase debe seguir las características indicadas anteriormente, pero además deberá agregarse el sufijo Controlador en el nombre de la misma (y también en el nombre del archivo físico). Ejemplos para nombres de clases válidos: UsuariosControlador, LoginControlador, HomeControlador. De igual forma, los nombres de archivos deberán coincidir con la regla del nombrado de clases, es decir, UsuariosControlador.php, LoginControlador.php, HomeControlador.php.

#### Controlador predeterminado
De forma predeterminada, SimpleMVC utiliza el controlador HomeControlador como clase inicial. Esta configuración puede ser modificada en el archivo \Libs\Configuracion.php

#### Acción/método predeterminada
De igual forma, se ha definido que el nombre de la acción predeterminada sea *index*, por lo tanto, cada vez que un usuario utilice una llamada al controlador sin incluir la acción, SimpleMVC buscará la acción (método/función) index dentro del controlador.

#### Ubicación de las vistas
Cada controlador deberá contar con vistas, las que alojará dentro de la ruta \App\Vistas\NombreDelControlador (sin el sufijo Controlador). Por ejemplo, las vistas del controlador UsuariosControlador se encontrarán en Vistas\Usuarios.

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

#### Método predeterminado
De forma predeterminada los controladores preguntarán por la existencia de un método llamado *antes_de_filtrar()* que sirve como base para ejecutar código antes de que se ejecuten las acciones del controlador. Por ejemplo para autentificación o autorización de acceso en diferentes controladores. Ver el ejemplo a continuación:

```php
class UsuariosControlador extends Controlador
{

    public function antes_de_filtrar(){
        if (Sesion::obtener('esta_logueado') == false) {
            return Enrutador::irA('login/entrar'); //va al controlador login, método entrar
        }
    }
    
    public function index(){
        return Cargar::vista('Usuarios/index', [
            "usuarios" => Bd::obtenerFilas("SELECT * FROM usuarios")
            ] ); //la vista recibe la variable $usuarios
    }
}
```

### Acceso a la base de datos
SimpleMVC implementa una clase llamada Bd (por Base de Datos) en la cual se alojan métodos estáticos que permiten la consulta, creación, actualización y eliminación de datos. En términos de utilidad, las operaciones de INSERT, UPDATE y DELETE están encapsuladas para evitar errores clásicos de escritura de sentencias SQL. Como la clase utiliza PDO, hace uso de dichas funcionalidades. Los métodos de consulta de datos reciben SQL directo.

#### Obtener N filas de una consulta, método obtenerFilas (buscar registros)
El método obtenerFilas retorna un arreglo de arreglos asociativos de acuerdo a la consulta SQL que se le pase como parámetro (si parece más sencillo, es lo mismo que decir que regresa una lista de registros/filas/tuplas)

```php
$usuarios = Bd::obtenerFilas("SELECT * FROM usuarios");
```

#### Obtener una fila a partir de una consulta, método obtenerFila (buscar un registro)
A diferencia del método anterior, sólo regresa un arreglo asociativo a partir de la consulta pasada como parámetro (retorna un registro/fila/tupla)

```php
$usuario_actual = Bd::obtenerFila("SELECT * FROM usuarios WHERE id = :id", [":id" => $id]); //aquí usamos parámetros de PDO
```

#### Agregar un registro en la tabla, método insertar
El método insertar permite agregar un registro en la tabla de acuerdo a los datos pasados como arreglo asociativo. Retorna el id del último registro agregado.

```php
$datos = ["nombre" => "Erick", "email" => "erick@servidor.com", "activo" => 1];
$usuario_id = Bd::insertar("usuarios", $datos); //le indicamos a qué tabla debe insertar el registro
```

#### Actualizar un registro en la tabla, método actualizar
El método actualizar permite modificar un registro en la tabla de acuerdo a los datos pasados como arreglo asociativo. Retorna la cantidad de filas afectadas en la actualización.

```php
$datos = ["nombre" => "Erick Root"]; //solo actualizaremos el nombre del usuario
$filas_actualizadas = Bd::actualizar("usuarios", $datos, "WHERE id = $id"); //asumimos que $id viene de algún paso previo
```

#### Eliminar un registro en la tabla, método eliminar
El método eliminar permite eliminar un registro en la tabla de acuerdo a la condición indicada. Retorna la cantidad de filas afectadas en la eliminación.

```php
Bd::eliminar("usuarios", "WHERE id = $id"); //asumimos que $id viene de un paso previo
```

### Documentación
El resto de la documentación podrá encontrarse dentro de la sección Wiki en este mismo repositorio.

### Aviso final
El código aquí expuesto no asegura ni garantiza que esté libre de errores o fallos, por lo tanto, el usuario es responsable de utilizarlo bajo su propio criterio. Su creador no entrega garantías de ningún tipo sobre el código y no es responsable por el uso que las personas hagan de él.
