# Clase Modelo

La clase Modelo ha sido creada para simplificar el acceso y manipulación de los datos.


## Convenciones


### Nombrado de clases
A modo de regla, cada nombre de clase tendrá una correspondencia con un nombre de tabla en la base de datos.
Los nombres de las clases deberán utilizarse en el formato NombreDeClase (PascalCase) y de ese modo se traducirá el nombre de la tabla en la base de datos como nombre_de_clase. Vamos a ver algunos ejemplos más comunes.

```php
//tabla producto
class Producto extends Modelo
{

}

//tabla categoria_producto
class CategoriaProducto extends Modelo
{

}
```

Ahora bien, si uno quisiera utilizar un nombre de clase y asignar un nombre de tabla diferente es posible hacerlo usando el siguiente código

```php
class Productos extends Modelo
{
    public function inicializar()
    {
        //cambiar el nombre de la tabla según sea necesario
        $this->asignarNombreDeTabla('mis_productos');
    }
}
```

El método inicializar se ejecuta como primer método luego de la creación de una clase (new Clase()). Gracias a eso es posible cambiar la configuración del nombre de la tabla de acuerdo a las necesidades del proyecto en el que uno esté trabajando.

```php
$productos = new Productos();
//desde este momento, cualquier llamada a los métodos de la clase Modelo 
//que se realice en el objeto $productos referenciará a la tabla mis_productos
```


### Identificador predeterminado
Cada tabla esperará que el identificador predeterminado sea llamado *id*, de tipo entero (o similar) y que además sea auto incremental (o una secuencia).


### Configuración de la base de datos
La configuración para acceder a la base de datos se realiza en el archivo App\Libs\Configuracion.php, que es una clase que contiene algunas constantes que permiten definir la cadena de conexión (en formato PDO), el usuario, la contraseña y parámetros opcionales.


### Acceso a los datos
Cada vez que solicitemos datos a la tabla, la clase Modelo nos regresará los datos en formato de arreglo asociativo de PHP, es decir, como ['clave' => 'valor']. Veremos ejemplos en cada uno de los métodos de búsqueda de información.


## Métodos
A continuación se describe cada uno de sus métodos y las recomendaciones para hacer uso de ellos.


### asignarNombreDeTabla

Sintaxis
```php
asignarNombreDeTabla(string $nombre_de_tabla)
```

El método asignarNombreDeTabla permite personalizar el nombre que tiene la tabla para que no utilice el estándar de la convención (NombreDeClase se convierte en nombre_de_clase y se asume como el nombre de la tabla). Aunque puede hacerse antes de ejecutar cualquier operación de la clase, se recomienda utilizar el método inicializar para asignarlo cada vez que la clase se crea como objeto (new Clase()).


### inicializar

Sintaxis
```php
inicializar()
```

El método inicializar ha sido creado como método de apoyo cada vez que se crea una instancia de la clase. Antes de que se ejecute cualquier método que el programad@r haya codificado, se ejecutará el método inicializar. Es útil, por el momento, para modificar a gusto el nombre de la tabla sin usar el estándar de la convención (NombreDeClase se convierte en nombre_de_clase y se asume como el nombre de la tabla).


### obtenerPorId

Sintaxis
```php
obtenerPorId(integer $id)
```

El método obtenerPorId, como indica su nombre, permite realizar la búsqueda de un registro en la tabla a partir de su número identificador (id). Como parámetro espera recibir un número entero. Retorna un array asociativo PHP.

```php
$primer_producto = (new Producto)->obtenerPorId(1);

/*
retorna un arreglo asociativo con los datos del producto con id = 1

$primer_producto contendrá un arreglo asociativo como se ve a continuación
['id' => 1, 'nombre' => 'Primer Producto', 'precio' => 99.99, 'activo' => 1]

luego, en el código uno puede tener acceso a las propiedades como
echo $primer_producto['nombre'];
*/
```


### obtenerPrimero

Sintaxis
```php
obtenerPrimero(string $condicion, array $parametros = null)
```

El método obtenerPrimero requiere como parámetros un string como condición (generalmente una instrucción WHERE de sql) y, opcionalmente, un array de parámetros (para pasarle opciones PDO). Retorna un array asociativo PHP.

```php
$ultima_entrada = (new Entrada)->obtenerPrimero("WHERE activa = 1 ORDER BY id DESC");

/*
retorna un arreglo asociativo con los datos de la última entrada 
activa registrada en la tabla entrada

$ultima_entrada contendrá un arreglo asociativo como se ve a continuación
['id' => 99, 'titulo' => 'Beneficios del té verde', 'cuerpo' => '...', 'activa' => 1]

luego, en el código uno puede tener acceso a las propiedades como
echo $ultima_entrada['titulo'];
*/

$usuario = (new Usuario)->obtenerPrimero("WHERE login = :login", [':login' => $login]);
/*
En este ejemplo buscamos un usuario de acuerdo al atributo login. Usamos el 
parámetro :login en el texto de la condición el que luego debemos pasar en el arreglo
asociativo de parámetros (segundo parámetro del método)
*/
```


### obtenerFilas

Sintaxis
```php
obtenerFilas(string $condicion, array $parametros = null)
```

El método obtenerFilas es similar en parámetros al método obtenerPrimero, con la diferencia que retorna un arreglo de arreglos asociativos.

```php
$ultimas_entradas = (new Entrada)->obtenerFilas("WHERE activa = 1 ORDER BY id DESC LIMIT 10");

/*
retorna un arreglo con arreglos asociativo con los datos de las última 10 entradas 
activas registrada en la tabla entrada

$ultimas_entrada contendrá 
[
    ['id' => 99, 'titulo' => 'Beneficios del té verde', 'cuerpo' => '...', 'activa' => 1],
    ['id' => 98, 'titulo' => 'Beneficios del café', 'cuerpo' => '...', 'activa' => 1],
    ['id' => 97, 'titulo' => 'Beneficios del chocolate', 'cuerpo' => '...', 'activa' => 1],
    ['id' => 96, 'titulo' => 'Beneficios del té rojo', 'cuerpo' => '...', 'activa' => 1],
    ['id' => 95, 'titulo' => 'Beneficios del coco', 'cuerpo' => '...', 'activa' => 1],
    ['id' => 94, 'titulo' => 'Beneficios del limón', 'cuerpo' => '...', 'activa' => 1],
    ['id' => 93, 'titulo' => 'Beneficios del apio', 'cuerpo' => '...', 'activa' => 1],
    ['id' => 92, 'titulo' => 'Beneficios de dormir bien', 'cuerpo' => '...', 'activa' => 1],
    ['id' => 91, 'titulo' => 'Beneficios beber agua', 'cuerpo' => '...', 'activa' => 1],
    ['id' => 90, 'titulo' => 'Beneficios de sonreir', 'cuerpo' => '...', 'activa' => 1],
];
*/
//luego, en el código uno puede tener acceso a las propiedades como
foreach($ultimas_entradas as $entrada) {
    echo $entrada['titulo'];
}


$usuarios = (new Usuario)->obtenerFilas("WHERE perfil = :perfil", [':perfil' => 'admin']);
/*
En este ejemplo buscamos los usuarios cuyo perfil tiene el valor admin. Usamos el 
parámetro :perfil en el texto de la condición el que luego debemos pasar en el arreglo
asociativo de parámetros 
*/
//en el código se puede tener acceso a las propiedades como
foreach($usuarios as $usuario) {
    echo $usuario['nombre'];
}
```


## Agregar elementos

Para agregar elementos en la tabla (insertar) la clase modelo implementa dos estrategias.


### agregar

Sintaxis
```php
agregar(array $datos)
```

El método agregar permite insertar un nuevo elemento en la tabla a partir de los atributos recibidos en el parámetro $datos, el que corresponde con un arreglo asociativo de PHP. Retorna el identificador del elemento insertado en la tabla.

```php
    $datos_de_la_entrada = [
        'titulo' => 'Beneficios de usar PHP', 'cuerpo' => '...', 'activa' => 1
        ];
    $entrada = new Entrada();
    $id_insertado = $entrada->agregar($datos_de_la_entrada);
```


### guardar

Sintaxis
```php
guardar()
```

El método guardar permite insertar un nuevo elemento o actualizar uno existente en la tabla. Retorna un booleano indicando si la operación fue exitosa o no (verdadero o falso).

Para usar este método es necesario utilizar el objeto modelo como contedor de propiedades lo que puede hacerse de dos formas según se detalla en el ejemplo que sigue:

```php
    //usar guardar con arreglo asociativo para hacer un insert
    $datos_de_la_entrada = [
        'titulo' => 'Beneficios de usar PHP', 'cuerpo' => '...', 'activa' => 1
        ];
    $entrada = new Entrada();
    $entrada->cargar($datos_de_la_entrada);
    $entrada->guardar();
    //en términos de sql sería algo así
    //INSERT INTO entrada (titulo, cuerpo, activa) 
    //VALUES ('Beneficios de usar PHP', '...', 1);

    //usar la clase como contenedor de propiedades para hacer un insert
    $entrada = new Entrada();
    $entrada->titulo = 'Beneficios de usar PHP';
    $entrada->cuerpo = '...';
    $entrada->activa = 1;

    $entrada->guardar();
    //en términos de sql sería algo así
    //INSERT INTO entrada (titulo, cuerpo, activa) 
    //VALUES ('Beneficios de usar PHP', '...', 1);

    //ahora veremos la opción de actualizar datos usando arreglo asociativo
    $datos_de_la_entrada = [
        'id' => 99,
        'activa' => 0
        ];
    $entrada = new Entrada();
    $entrada->cargar($datos_de_la_entrada);
    $entrada->guardar(); //actualiza el atributo activa para el identificador 99
    //en términos de sql sería algo así
    //UPDATE entrada set activa = 0 WHERE id = 99;

    //usar la clase como contenedor de propiedades para hacer una actualización
    $entrada = new Entrada();
    $entrada->id = 99;
    $entrada->activa = 0;
    $entrada->guardar(); //actualiza el atributo activa para el identificador 99
    //en términos de sql sería algo así
    //UPDATE entrada set activa = 0 WHERE id = 99;
```

Como consejo práctico, cuando se usa el método guardar es necesario cuidar el uso del atributo id en las propiedades del objeto, ya que si el valor en él es mayor a cero asumirá que se trata de una actualización y en el caso que sea 0 o null asumirá que debe crear una inserción.


### actualizar

Sintaxis
```php
actualizar(array $datos, string $condicion = null)
```

El método actualizar permite modificar uno o varios registros en la tabla de acuerdo a la condición que se use al invocarlo. Retorna el número de filas (registros) afectadas en la actualización.

```php
    //inactivar todos los registros de la tabla entrada que son del usuario 2
    $datos_de_la_entrada = [ 'activa' => 0 ];
    $condicion = "WHERE usuario_id = 2";

    $entrada = new Entrada();
    $entrada->actualizar($datos_de_la_entrada, $condicion);
    //en términos de sql sería algo así
    //UPDATE entrada set activa = 0 WHERE usuario_id = 2;
```


### eliminar

Sintaxis
```php
eliminar()
```

El método eliminar permite quitar un registro en la tabla de acuerdo a la propiedad id que se haya asignado en el objeto. Retorna el número de filas (registros) afectadas en la eliminación o false si no pudo eliminar el registro.

```php
    //eliminar el registro con identificador 99 desde la tabla
    $entrada = new Entrada();
    $entrada->id = 99;
    $entrada->eliminar();
    //en términos de sql sería algo así
    //DELETE FROM entrada WHERE id = 99;
```


### eliminarTodos

Sintaxis
```php
eliminarTodos(string $condicion, array $parametos = null)
```

El método eliminarTodos permite quitar uno o más registros en la tabla de acuerdo a la condición que se haya pasado como parámetro. Retorna el número de filas (registros) afectadas en la eliminación.

```php
    //eliminar todas las entradas inactivas
    $entrada = new Entrada();
    $entrada->eliminarTodos("WHERE activa = 0");
    //en términos de sql sería algo así
    //DELETE FROM entrada WHERE activa = 0;

    //eliminar todas las entradas del usuario 2
    $entrada = new Entrada();
    $entrada->eliminarTodos("WHERE usuario_id = :id", [':id' => 2]);
    //en términos de sql sería algo así
    //DELETE FROM entrada WHERE usuario_id = 2;

```


## Métodos de apoyo para operaciones de manipulación de datos
Tal como en las operaciones de bases de datos, se ha implementado la posibilidad de que el desarrollad@r pueda realizar operaciones antes y después de llamar un método de modificación de datos (agregar, actualizar, eliminar)

Estos métodos son los siguientes: antes_de_agregar, despues_de_agregar, antes_de_actualizar, despues_de_actualizar, antes_de_eliminar, despues_de_eliminar

Dentro de las utilidades que uno puede darle caben las validaciones, o la generación de valores para atributos que tienen tratamiento especial. Por ejemplo generar un atribuco como SLUG (nombre amigable para las entradas de un blog), la encriptación de una contraseña, el cálculo de los impuestos a partir del valor NETO o BRUTO, despachar un correo luego de insertar cierta información, registrar logs de auditoría de sistema, actualizar un atributo de fecha, entre otras tantas ideas.


## Otras opciones para acceder a datos
Si bien la clase Modelo presenta métodos útiles para la mayor parte de los casos, algún usuario avanzado podría requerir utilizar directamente SQL para hacer consultas más detalladas o complejas.

Para ese tipo de tareas es posible utilizar la clase Bd (acrónimo de BaseDatos)

A continuación se detallan los métodos que pueden utilizarse desde ella


### obtenerFilas

Sintaxis
```php
obtenerFilas(string $sql, array $parametos = null)
```

El método obtenerFilas retorna un arreglo de arreglos asociativos a partir de la instrucción sql y los parámetros (opcionales) que se hayan pasado al invocarlo.

```php

//ejemplo 1
$ultimas_entradas = Bd::obtenerFilas(
    "SELECT * 
    FROM entrada 
    WHERE activa = 1 
    ORDER BY id DESC 
    LIMIT 10");

//en el código se puede tener acceso a las propiedades como
foreach($ultimas_entradas as $entrada) {
    echo $entrada['titulo'];
}

//ejemplo 2
$ultimas_entradas_con_nombre_del_autor = Bd::obtenerFilas(
    "SELECT e.*, u.nombre as autor
    FROM entrada e INNER JOIN usuario u
    ON e.usuario_id = u.id 
    WHERE e.activa = 1 
    ORDER BY e.id DESC
    LIMIT 10");    

//ejemplo 3
$ultimas_n_entradas_con_nombre_del_autor = Bd::obtenerFilas(
    "SELECT e.*, u.nombre as autor
    FROM entrada e INNER JOIN usuario u
    ON e.usuario_id = u.id 
    WHERE e.activa = 1 
    ORDER BY e.id DESC
    LIMIT :limite", [':limite' => 20]);    
```


### obtenerFila

Sintaxis
```php
obtenerFila(string $sql, array $parametos = null)
```

El método obtenerFila retorna el primer registro como arreglo asociativo a partir de la instrucción sql y los parámetros (opcionales) que se hayan pasado al invocarlo.

```php
$ultima_entrada_activa = Bd::obtenerFila(
    "SELECT * 
    FROM entrada 
    WHERE activa = 1 
    ORDER BY id DESC");

//en el código se puede tener acceso a las propiedades como
echo $ultima_entrada_activa['titulo'];

```


### obtenerValor

Sintaxis
```php
obtenerValor(string $sql)
```

El método obtenerValor retorna un escalar (valor) desde la base de datos. Puede ser útil para solicitar la fecha desde la base de datos o la cuenta, suma o promedio sobre algún elemento calculado que sea requerido.

```php
$fecha_y_hora_de_la_bd = Bd::obtenerValor("SELECT CURRENT_TIMESTAMP");

//en el código se puede tener acceso al resultado como
echo $fecha_y_hora_de_la_bd;


$cuenta_entradas_del_usuario = Bd::obtenerValor(
    "SELECT count(*) 
    FROM entrada 
    WHERE usuario_id = 5");

//en el código se puede tener acceso al resultado como
echo $cuenta_entradas_del_usuario;

```


### insertar

Sintaxis
```php
insertar(string $tabla, array $datos)
```

El método insertar permite enviar una instrucción de inserción de acuerdo al nombre de la tabla que se ha pasado en el parámetro tabla, que luego se llena con los valores del arreglo asociativo datos. Retorna el identificador del elemento insertado.

```php
$datos_de_la_entrada = [
        'titulo' => 'Beneficios de usar PHP', 'cuerpo' => '...', 'activa' => 1
        ];

$ultimo_id_insertado = Bd::insertar("entrada", $datos_de_la_entrada);

//en el código se puede tener acceso al resultado como
echo $ultimo_id_insertado;

```


### actualizar

Sintaxis
```php
actualizar(string $tabla, array $datos, string $condicion = null)
```

El método actualizar permite enviar una instrucción de actualización de acuerdo al nombre de la tabla que se ha pasado en el parámetro tabla, que luego se llena con los valores del arreglo asociativo datos y la condición de actualización (opcional). Retorna el número de filas afectadas por la actualización.

```php
$datos_de_la_entrada = ['activa' => 0 ];

$filas_afectadas = Bd::actualizar("entrada", $datos_de_la_entrada, "WHERE id = 99");

//en el código se puede tener acceso al resultado como
echo $filas_afectadas;

```


### eliminar

Sintaxis
```php
eliminar(string $tabla, string $condicion, array $parametros = null)
```

El método eliminar permite enviar una instrucción de eliminación de acuerdo al nombre de la tabla que se ha pasado en el parámetro tabla, que luego se llena con los valores del arreglo asociativo parámetros (opcional) y la condición de eliminación. Retorna el número de filas afectadas por la eliminación.

```php

$filas_afectadas = Bd::eliminar("entrada", "WHERE activa = 0");

//en el código se puede tener acceso al resultado como
echo $filas_afectadas;


$entradas_eliminadas_del_usuario = Bd::eliminar("entrada", 
    "WHERE usuario_id = :usuario", [':usuario' => 3]);

//en el código se puede tener acceso al resultado como
echo $entradas_eliminadas_del_usuario;

```


### iniciarTransaccion

Sintaxis
```php
iniciarTransaccion()
```

El método iniciarTransaccion prepara un espacio para la generación de instrucciones que deben ejecutarse como un bloque de acuerdo al estandar ACID. Se usan cuando un paquete de instrucciones deben ser todas exitosas o en su defecto, no pasar ninguna.
Siempre debe acompañarse de los métodos aceptarTransaccion y reversarTransaccion, los que deben incluirse para que el resto de las operaciones puedan seguir su normal ejecución.

```php
Bd::iniciarTransaccion();
//crear el bloque try catch que siempre debe acompañar a las transacciones
try {

    //instrucciones que deben tratarse como un bloque único
    $filas_afectadas = Bd::eliminar("entrada", "WHERE activa = 0");
    $filas_afectadas = Bd::eliminar("entrada", "WHERE usuario_id = 9");
    $filas_afectadas = Bd::eliminar("entrada", "WHERE usuario_id = 12");

    //asumimos que si llega hasta aquí todo ha ido como corresponde y por lo
    //tanto se acepta la transacción (COMMIT)
    Bd::aceptarTransaccion();
} catch(Exception $e) {
    //hubo algún problema o error y por lo tanto debe reversarse la 
    //transacción (ROLLBACK)
    Bd::reversarTransaccion();
}

```

Esta debe ser la forma estándar de utilizarlo para evitar efectos no deseados en la ejecución del código. 

A modo de consejo extra, debe evitarse el uso de instrucciones de selección/consulta dentro de los bloques de transacción por los efectos adversos que se provocan como bloqueo de los datos.


### aceptarTransaccion

Sintaxis
```php
aceptarTransaccion()
```

El método aceptarTransaccion indica que las ejecuciones han sido exitosas en un bloque de transacción (envía la instrucción COMMIT).

```php
Bd::aceptarTransaccion();
```


### reversarTransaccion

Sintaxis
```php
reversarTransaccion()
```

El método reversarTransaccion debe invocarse cuando las ejecuciones en una transacción no han sido correctas (envía la instrucción ROLLBACK).

```php
Bd::reversarTransaccion();
```
