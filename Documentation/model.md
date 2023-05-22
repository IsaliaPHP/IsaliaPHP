# Clase Model

La clase Model ha sido creada para simplificar el acceso y manipulación de los datos.


## Convenciones


### Nombrado de clases
A modo de regla, cada nombre de clase tendrá una correspondencia con un nombre de tabla en la base de datos.
Los nombres de las clases deberán utilizarse en el formato NombreDeClase (PascalCase) y de ese modo se traducirá el nombre de la tabla en la base de datos como nombre_de_clase. Vamos a ver algunos ejemplos más comunes.

```php
//tabla producto
class Producto extends Model
{

}

//tabla categoria_producto
class CategoriaProducto extends Model
{

}
```

Ahora bien, si uno quisiera utilizar un nombre de clase y asignar un nombre de tabla diferente es posible hacerlo usando el siguiente código

```php
class Productos extends Model
{
    public function initialize()
    {
        //cambiar el nombre de la tabla según sea necesario
        $this->setTableName('mis_productos');
    }
}
```

El método initialize se ejecuta como primer método luego de la creación de una clase (new Clase()). Gracias a eso es posible cambiar la configuración del nombre de la tabla de acuerdo a las necesidades del proyecto en el que uno esté trabajando.

```php
$productos = new Productos();
//desde este momento, cualquier llamada a los métodos de la clase Modelo 
//que se realice en el objeto $productos referenciará a la tabla mis_productos
```


### Identificador predeterminado
Cada tabla esperará que el identificador predeterminado sea llamado *id*, de tipo entero (o similar) y que además sea auto incremental (o una secuencia).


### Configuración de la base de datos
La configuración para acceder a la base de datos se realiza en el archivo App\Libs\Config.php, que es una clase que contiene algunas constantes que permiten definir la cadena de conexión (en formato PDO), el usuario, la contraseña y parámetros opcionales.


### Acceso a los datos
Cada vez que solicitemos datos a la tabla, la clase Modelo nos regresará los datos en formato de arreglo asociativo de PHP, es decir, como ['clave' => 'valor']. Veremos ejemplos en cada uno de los métodos de búsqueda de información.


## Métodos
A continuación se describe cada uno de sus métodos y las recomendaciones para hacer uso de ellos.


### setTableName

Sintaxis
```php
setTableName(string $table_name)
```

El método setTableName permite personalizar el nombre que tiene la tabla para que no utilice el estándar de la convención (NombreDeClase que se convierte en nombre_de_clase y se asume como el nombre de la tabla). Aunque puede hacerse antes de ejecutar cualquier operación de la clase, se recomienda utilizar el método **initialize** para asignarlo cada vez que la clase se crea como objeto (new Clase()).


### initialize

Sintaxis
```php
initialize()
```

El método initialize ha sido creado como método de apoyo cada vez que se crea una instancia de la clase. Antes de que se ejecute cualquier método que el programad@r haya codificado, se ejecutará el método initialize. Es útil, para modificar a gusto el nombre de la tabla sin usar el estándar de la convención (NombreDeClase que se convierte en nombre_de_clase y se asume como el nombre de la tabla).


### findById

Sintaxis
```php
findById(integer $id)
```

El método **findById**, como indica su nombre, permite realizar la búsqueda de un registro en la tabla a partir de su número identificador (id). Como parámetro espera recibir un número entero. Retorna un array asociativo PHP.

```php
$primer_producto = (new Producto)->findById(1);

/*
retorna un arreglo asociativo con los datos del producto con id = 1

$primer_producto contendrá un arreglo asociativo como se ve a continuación
['id' => 1, 'nombre' => 'Primer Producto', 'precio' => 99.99, 'activo' => 1]
*/
//luego, en el código uno puede tener acceso a las propiedades como
echo $primer_producto['nombre'];

```


### findFirst

Sintaxis
```php
findFirst(string $condition, array $parameters = null)
```

El método **findFirst** requiere como parámetros un string como condición (generalmente una instrucción WHERE de sql) y, opcionalmente, un array de parámetros (para pasarle opciones PDO). Retorna un array asociativo PHP.

```php
$ultima_entrada = (new Entrada)->findFirst("WHERE activa = 1 ORDER BY id DESC");

/*
retorna un arreglo asociativo con los datos de la última entrada 
activa registrada en la tabla entrada

$ultima_entrada contendrá un arreglo asociativo como se ve a continuación
['id' => 99, 'titulo' => 'Beneficios del té verde', 'cuerpo' => '...', 'activa' => 1]
*/
//luego, en el código uno puede tener acceso a las propiedades como
echo $ultima_entrada['titulo'];


$usuario = (new Usuario)->findFirst("WHERE login = :login", [':login' => $login]);
/*
En este ejemplo buscamos un usuario de acuerdo al atributo login. Usamos el 
parámetro :login en el texto de la condición el que luego debemos pasar en el arreglo
asociativo de parámetros (segundo parámetro del método)
*/
```


### findAll

Sintaxis
```php
findAll(string $condition, array $parameters = null)
```

El método **findAll** es similar en parámetros al método obtenerPrimero, con la diferencia que retorna un arreglo de arreglos asociativos.

```php
$ultimas_entradas = (new Entrada)->findAll("WHERE activa = 1 ORDER BY id DESC LIMIT 10");

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


$usuarios = (new Usuario)->findAll("WHERE perfil = :perfil", [':perfil' => 'admin']);
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


### create

Sintaxis
```php
create(array $attributes)
```

El método **create** permite insertar un nuevo elemento en la tabla a partir de los atributos recibidos como parámetro, el que corresponde con un arreglo asociativo de PHP. Retorna el identificador del elemento insertado en la tabla.

```php
    $datos_de_la_entrada = [
        'titulo' => 'Beneficios de usar PHP', 'cuerpo' => '...', 'activa' => 1
        ];
    $entrada = new Entrada();
    $id_insertado = $entrada->create($datos_de_la_entrada);
```


### save

Sintaxis
```php
save()
```

El método **save** permite insertar un nuevo elemento o actualizar uno existente en la tabla. Retorna un booleano indicando si la operación fue exitosa o no (verdadero o falso).

Para usar este método es necesario utilizar el objeto modelo como contedor de propiedades lo que puede hacerse de dos formas según se detalla en el ejemplo que sigue:

```php
    //usar guardar con arreglo asociativo para hacer un insert
    $datos_de_la_entrada = [
        'titulo' => 'Beneficios de usar PHP', 'cuerpo' => '...', 'activa' => 1
        ];
    $entrada = new Entrada();
    $entrada->load($datos_de_la_entrada);
    $entrada->save();
    //en términos de sql sería algo así
    //INSERT INTO entrada (titulo, cuerpo, activa) 
    //VALUES ('Beneficios de usar PHP', '...', 1);

    //usar la clase como contenedor de propiedades para hacer un insert
    $entrada = new Entrada();
    $entrada->titulo = 'Beneficios de usar PHP';
    $entrada->cuerpo = '...';
    $entrada->activa = 1;

    $entrada->save();
    //en términos de sql sería algo así
    //INSERT INTO entrada (titulo, cuerpo, activa) 
    //VALUES ('Beneficios de usar PHP', '...', 1);

    //ahora veremos la opción de actualizar datos usando arreglo asociativo
    $datos_de_la_entrada = [
        'id' => 99,
        'activa' => 0
        ];
    $entrada = new Entrada();
    $entrada->load($datos_de_la_entrada);
    $entrada->save(); 
    //actualiza el atributo activa para el identificador 99
    //en términos de sql sería algo así
    //UPDATE entrada set activa = 0 WHERE id = 99;

    //usar la clase como contenedor de propiedades para hacer una actualización
    $entrada = new Entrada();
    $entrada->id = 99;
    $entrada->activa = 0;
    $entrada->save(); 
    //actualiza el atributo activa para el identificador 99
    //en términos de sql sería algo así
    //UPDATE entrada set activa = 0 WHERE id = 99;
```

Como consejo práctico, cuando se usa el método save es necesario cuidar el uso del atributo id en las propiedades del objeto, ya que si el valor en él es mayor a cero asumirá que se trata de una actualización y en el caso que sea 0 o null asumirá que debe realizar una inserción.


### update

Sintaxis
```php
update(array $attributes, string $condition = null)
```

El método **update** permite modificar uno o varios registros en la tabla de acuerdo a la condición que se use al invocarlo. Retorna el número de filas (registros) afectadas en la actualización.

```php
    //inactivar todos los registros de la tabla entrada que son del usuario 2
    $datos_de_la_entrada = [ 'activa' => 0 ];
    $condicion = "WHERE usuario_id = 2";

    $entrada = new Entrada();
    $entrada->update($datos_de_la_entrada, $condicion);
    //en términos de sql sería algo así
    //UPDATE entrada set activa = 0 WHERE usuario_id = 2;
```


### delete

Sintaxis
```php
delete()
```

El método **delete** permite quitar un registro en la tabla de acuerdo a la propiedad id que se haya asignado en el objeto. Retorna el número de filas (registros) afectadas en la eliminación o false si no pudo eliminar el registro.

```php
    //eliminar el registro con identificador 99 desde la tabla
    $entrada = new Entrada();
    $entrada->id = 99;
    $entrada->delete();
    //en términos de sql sería algo así
    //DELETE FROM entrada WHERE id = 99;
```


### deleteAll

Sintaxis
```php
deleteAll(string $condition, array $parameters = null)
```

El método **deleteAll** permite quitar uno o más registros en la tabla de acuerdo a la condición que se haya pasado como parámetro. Retorna el número de filas (registros) afectadas en la eliminación.

```php
    //eliminar todas las entradas inactivas
    $entrada = new Entrada();
    $entrada->deleteAll("WHERE activa = 0");
    //en términos de sql sería algo así
    //DELETE FROM entrada WHERE activa = 0;

    //eliminar todas las entradas del usuario 2
    $entrada = new Entrada();
    $entrada->deleteAll("WHERE usuario_id = :id", [':id' => 2]);
    //en términos de sql sería algo así
    //DELETE FROM entrada WHERE usuario_id = 2;

```


## Métodos de apoyo para operaciones de manipulación de datos
Tal como en las operaciones de bases de datos, se ha implementado la posibilidad de que el desarrollad@r pueda realizar operaciones antes y después de llamar un método de modificación de datos (agregar, actualizar, eliminar)

Estos métodos son los siguientes: beforeCreate, afterCreate, beforeUpdate, afterUpdate, beforeDelete, afterDelete

Dentro de las utilidades que uno puede darle caben las validaciones, o la generación de valores para atributos que tienen tratamiento especial. Por ejemplo generar un atributo como SLUG (nombre amigable para las entradas de un blog), la encriptación de una contraseña, el cálculo de los impuestos a partir del valor NETO o BRUTO, despachar un correo luego de insertar cierta información, registrar logs de auditoría de sistema, actualizar un atributo de fecha, entre otras tantas ideas.


## Otras opciones para acceder a datos
Si bien la clase Modelo presenta métodos útiles para la mayor parte de los casos, algún usuario avanzado podría requerir utilizar directamente SQL para hacer consultas más detalladas o complejas.

Para ese tipo de tareas es posible utilizar la clase Db (acrónimo de Database)

Puede verse la documentación de Db en el archivo Documentation\db.md

