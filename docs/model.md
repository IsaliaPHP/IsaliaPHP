# Clase Model de IsaliaPHP

La clase Model ha sido creada para simplificar el acceso y manipulación de los datos.


## Convenciones


### Nombrado de clases
A modo de regla, cada nombre de clase tendrá una correspondencia con un nombre de tabla en la base de datos.
Los nombres de las clases deberán utilizarse en el formato NombreDeClase (PascalCase) y de ese modo se traducirá el nombre de la tabla en la base de datos como nombre_de_clase (snake_case). Vamos a ver algunos ejemplos más comunes.

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
        $this->setTableName('articulos');
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
La configuración para acceder a la base de datos se realiza en el archivo app\libs\config.php, que es una clase que contiene algunas constantes que permiten definir la cadena de conexión (en formato PDO), el usuario, la contraseña y parámetros opcionales.


### Acceso a los datos
Cada vez que solicitemos datos a la tabla, la clase *Model* nos regresará los datos en formato de objeto de PHP, es decir que podremos acceder los datos como $obj->atributo. Veremos ejemplos en cada uno de los métodos de búsqueda de información.


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

El método initialize ha sido creado como método de apoyo cada vez que se crea una instancia de la clase. Antes de que se ejecute cualquier método que el/la desarrollador/a haya codificado, se ejecutará el método initialize. Es útil, para modificar a gusto el nombre de la tabla sin usar el estándar de la convención (NombreDeClase que se convierte en nombre_de_clase y se asume como el nombre de la tabla).


### findById

Sintaxis
```php
findById(integer $id)
```

El método **findById**, como indica su nombre, permite realizar la búsqueda de un registro en la tabla a partir de su número identificador (id). Como parámetro espera recibir un número entero. Retorna un objeto con los atributos de la tabla.

```php
$primer_producto = (new Producto)->findById(1);

/*
retorna un objecto con los datos del producto con id = 1
luego, en el código uno puede tener acceso a las propiedades como
*/
echo $primer_producto->id;
echo $primer_producto->nombre;

```


### findFirst

Sintaxis
```php
findFirst(string $condition, array $parameters = null)
```

El método **findFirst** requiere como parámetros un string como condición (generalmente una instrucción condicional de sql, sin incluir la palabra WHERE) y, opcionalmente, un array de parámetros (para pasarle opciones PDO). Retorna un objeto con los atributos de la tabla.

```php
$ultima_entrada = (new Entrada)->findFirst("activa = 1 ORDER BY id DESC");

/*
retorna un objeto con los datos de la última entrada 
activa registrada en la tabla entrada

$ultima_entrada contendrá un objeto
luego, en el código uno puede tener acceso a las propiedades como
*/
echo $ultima_entrada->id;
echo $ultima_entrada->titulo;
echo $ultima_entrada->cuerpo;



$usuario = (new Usuario)->findFirst("login = :login", [':login' => $login]);
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

El método **findAll** es similar en parámetros al método obtenerPrimero, con la diferencia que retorna un arreglo de objetos.

```php
$ultimas_entradas = (new Entrada)->findAll("activa = 1 ORDER BY id DESC LIMIT 10");

/*
retorna un arreglo con objetos con los datos de las última 10 entradas 
activas registrada en la tabla entrada
*/
//luego, en el código uno puede tener acceso a las propiedades como
foreach($ultimas_entradas as $entrada) {
    echo $entrada->titulo;
}


$usuarios = (new Usuario)->findAll("perfil = :perfil", [':perfil' => 'admin']);
/*
En este ejemplo buscamos los usuarios cuyo perfil tiene el valor admin. Usamos el 
parámetro :perfil en el texto de la condición el que luego debemos pasar en el arreglo
asociativo de parámetros 
*/
//en el código se puede tener acceso a las propiedades como
foreach($usuarios as $usuario) {
    echo $usuario->nombre;
}
```

### findBySQL

Sintaxis
```php
findAll(string $condition, array $parameters = null)
```

El método **findBySQL** permite ejecutar una consulta SQL y obtener los resultados como un arreglo de objetos. Es útil cuando se necesita cargar información de diferentes tablas.

```php
$ultimas_entradas = (new Entrada)->findBySQL("SELECT * FROM entrada WHERE activa = 1 ORDER BY id DESC LIMIT 10");

/*
retorna un arreglo con objetos con los datos de las última 10 entradas 
activas registrada en la tabla entrada
*/
//luego, en el código uno puede tener acceso a las propiedades como
foreach($ultimas_entradas as $entrada) {
    echo $entrada->titulo;
}

```


## Agregar elementos

Para agregar elementos en la tabla (insertar) la clase model implementa dos estrategias.


### create

Sintaxis
```php
create()
```

El método **create** permite insertar un nuevo elemento en la tabla a partir de los atributos definidos como propiedades del objecto. Retorna el identificador del elemento insertado en la tabla.

```php
    $datos_de_la_entrada = [
        'titulo' => 'Beneficios de usar PHP', 'cuerpo' => '...', 'activa' => 1
        ];
    $entrada = new Entrada($datos_de_la_entrada);
    $id_insertado = $entrada->create();
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


## Modificar/Actualizar elementos

### update

Sintaxis
```php
update(array $attributes, string $condition = null)
```

El método **update** permite modificar uno o varios registros en la tabla de acuerdo a la condición que se use al invocarlo (sin incluir la palabra WHERE). Retorna el número de filas (registros) afectadas en la actualización.

```php
    //desactivar todos los registros de la tabla entrada que son del usuario 2
    $datos_de_la_entrada = [ 'activa' => 0 ];
    $condicion = "usuario_id = 2";

    $entrada = new Entrada();
    $entrada->update($datos_de_la_entrada, $condicion);
    //en términos de sql sería algo así
    //UPDATE entrada set activa = 0 WHERE usuario_id = 2;
```

Si la condición no es enviada se asume que se trata de *id = algo_valor_para_id*
```php
    //desactivar la entrada cuyo id es 6
    $entrada = (new Entrada)->findById(6);
    $datos_de_la_entrada = [ 'activa' => 0 ];
    
    $entrada->update($datos_de_la_entrada);
    //en términos de sql sería algo así
    //UPDATE entrada set activa = 0 WHERE id = 6;

    //volver a activar la entrada cuyo id es 6
    $entrada->activa = 1;
    $entrada->update();
    //en términos de sql sería algo así
    //UPDATE entrada set activa = 1 WHERE id = 6;
```



## Eliminar elementos

### delete

Sintaxis
```php
delete()
```

El método **delete** permite quitar un registro en la tabla de acuerdo a la propiedad id que se haya asignado en el objeto. Retorna el número de filas (registros) afectadas en la eliminación o false si no pudo eliminar el registro.

```php
    //eliminar el registro con identificador 99 desde la tabla
    $entrada = (new Entrada)->findById(99);
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
    $entrada->deleteAll("activa = 0");
    //en términos de sql sería algo así
    //DELETE FROM entrada WHERE activa = 0;

    //eliminar todas las entradas del usuario 2
    $entrada = new Entrada();
    $entrada->deleteAll("usuario_id = :id", [':id' => 2]);
    //en términos de sql sería algo así
    //DELETE FROM entrada WHERE usuario_id = 2;

```


## Métodos de apoyo para operaciones de manipulación de datos
Tal como en las operaciones de bases de datos, se ha implementado la posibilidad de que el desarrollad@r pueda realizar operaciones antes y después de llamar un método de modificación de datos (agregar, actualizar, eliminar)

Estos métodos son los siguientes: beforeCreate, afterCreate, beforeUpdate, afterUpdate, beforeDelete, afterDelete

Dentro de las utilidades que uno puede darle caben las validaciones, o la generación de valores para atributos que tienen tratamiento especial. Por ejemplo generar un atributo como SLUG (nombre amigable para las entradas de un blog), la encriptación de una contraseña, el cálculo de los impuestos a partir del valor NETO o BRUTO, despachar un correo luego de insertar cierta información, registrar logs de auditoría de sistema, actualizar un atributo de fecha, entre otras tantas ideas.


## Consultas avanzadas usando SqlBuilder incluido en la clase Model
Dada la inclusión de la clase SqlBuilder en la clase Model, es posible realizar consultas avanzadas usando métodos de esta clase.

Por ejemplo, para ejecutar una consulta que cargue datos de la tabla entrada, ordenados por fecha de creación descendente, limitados a 10 registros, se puede hacer lo siguiente:

```php
$entradas = (new Entrada)->orderBy('created_at', 'DESC')->limit(10)->findAll();
```

Puede usarse join para cargar datos de otras tablas relacionadas:

```php
$entradas = (new Entrada)->join('usuario', 'usuario.id = entrada.usuario_id')->orderBy('created_at', 'DESC')->limit(10)->findAll();
```

En términos simples, la implementación del SqlBuilder, acompañada de los métodos findAll, findFirst y execute permiten realizar consultas más complejas con una sintaxis más clara y legible.

Para ver las opciones avanzadas disponibles puede consultarse la documentación de la clase SqlBuilder en el archivo docs\sql_builder.md

