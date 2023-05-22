# Clase Db

La clase Db (por Database /Base de Datos en español) aloja métodos estáticos que permiten la consulta, creación, actualización y eliminación de datos. En términos de utilidad, las operaciones de INSERT, UPDATE y DELETE están encapsuladas para evitar errores clásicos de escritura en sentencias SQL. Como la clase utiliza PDO, hace uso de dichas funcionalidades. Los métodos de consulta de datos reciben SQL directo.

A continuación se describen sus métodos.

### findAll

Sintaxis
```php
findAll(string $sql, array $parameters = null)
```

El método **findAll** retorna un arreglo de arreglos asociativos a partir de la instrucción sql y los parámetros (opcionales) que se hayan pasado al invocarlo.

```php

//ejemplo 1
$ultimas_entradas = Db::findAll(
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
$ultimas_entradas_con_nombre_del_autor = Db::findAll(
    "SELECT e.*, u.nombre as autor
    FROM entrada e INNER JOIN usuario u
    ON e.usuario_id = u.id 
    WHERE e.activa = 1 
    ORDER BY e.id DESC
    LIMIT 10");    

//ejemplo 3
$ultimas_n_entradas_con_nombre_del_autor = Db::findAll(
    "SELECT e.*, u.nombre as autor
    FROM entrada e INNER JOIN usuario u
    ON e.usuario_id = u.id 
    WHERE e.activa = 1 
    ORDER BY e.id DESC
    LIMIT :limite", [':limite' => 20]);    
```


### findFirst

Sintaxis
```php
findFirst(string $sql, array $parameters = null)
```

El método **findFirst** retorna el primer registro como arreglo asociativo a partir de la instrucción sql y los parámetros (opcionales) que se hayan pasado al invocarlo.

```php
$ultima_entrada_activa = Db::findFirst(
    "SELECT * 
    FROM entrada 
    WHERE activa = 1 
    ORDER BY id DESC");

//en el código se puede tener acceso a las propiedades como
echo $ultima_entrada_activa['titulo'];

```


### getScalar

Sintaxis
```php
getScalar(string $sql)
```

El método **getScalar** retorna un escalar (valor) desde la base de datos. Puede ser útil para solicitar la fecha desde la base de datos o la cuenta, suma o promedio sobre algún elemento calculado que sea requerido.

```php
$fecha_y_hora_de_la_bd = Db::getScalar("SELECT CURRENT_TIMESTAMP");

//en el código se puede tener acceso al resultado como
echo $fecha_y_hora_de_la_bd;


$cuenta_entradas_del_usuario = Db::getScalar(
    "SELECT count(*) 
    FROM entrada 
    WHERE usuario_id = 5");

//en el código se puede tener acceso al resultado como
echo $cuenta_entradas_del_usuario;

```


### insert

Sintaxis
```php
insert(string $table_name, array $data)
```

El método **insert** permite enviar una instrucción de inserción de acuerdo al nombre de la tabla que se ha pasado en el parámetro table_name, que luego se llena con los valores del arreglo asociativo data. Retorna el identificador del elemento insertado.

```php
$datos_de_la_entrada = [
        'titulo' => 'Beneficios de usar PHP', 'cuerpo' => '...', 'activa' => 1
        ];

$ultimo_id_insertado = Db::insert("entrada", $datos_de_la_entrada);

//en el código se puede tener acceso al resultado como
echo $ultimo_id_insertado;

```


### update

Sintaxis
```php
update(string $table_name, array $data, string $condition = null)
```

El método **update** permite enviar una instrucción de actualización de acuerdo al nombre de la tabla que se ha pasado en el parámetro table_name, que luego se llena con los valores del arreglo asociativo data y la condición de actualización (opcional). Retorna el número de filas afectadas por la actualización.

```php
$datos_de_la_entrada = ['activa' => 0 ];

$filas_afectadas = Db::update("entrada", $datos_de_la_entrada, "WHERE id = 99");

//en el código se puede tener acceso al resultado como
echo $filas_afectadas;

```


### delete

Sintaxis
```php
delete(string $table_name, string $condition, array $parameters = null)
```

El método **delete** permite enviar una instrucción de eliminación de acuerdo al nombre de la tabla que se ha pasado en el parámetro table_name, que luego se llena con los valores del arreglo asociativo parameters (opcional) y la condición de eliminación. Retorna el número de filas afectadas por la eliminación.

```php

$filas_afectadas = Db::delete("entrada", "WHERE activa = 0");

//en el código se puede tener acceso al resultado como
echo $filas_afectadas;


$entradas_eliminadas_del_usuario = Db::delete("entrada", 
    "WHERE usuario_id = :usuario", [':usuario' => 3]);

//en el código se puede tener acceso al resultado como
echo $entradas_eliminadas_del_usuario;

```


### beginTransaction

Sintaxis
```php
beginTransaction()
```

El método **beginTransaction** prepara un espacio para la generación de instrucciones que deben ejecutarse como un bloque de acuerdo al estandar ACID. Se usa cuando un paquete de instrucciones deben ejecutarse todas exitosamente o en su defecto, no pasar ninguna.
Siempre debe acompañarse de los métodos **commit** y **rollback**, los que deben incluirse para que el resto de las operaciones puedan seguir su normal ejecución.

```php
Db::beginTransaction();
//crear el bloque try catch que siempre debe acompañar a las transacciones
try {

    //instrucciones que deben tratarse como un bloque único
    $filas_afectadas = Db::delete("entrada", "WHERE activa = 0");
    $filas_afectadas = Db::delete("entrada", "WHERE usuario_id = 9");
    $filas_afectadas = Db::delete("entrada", "WHERE usuario_id = 12");

    //asumimos que si llega hasta aquí todo ha ido como corresponde y por lo
    //tanto se acepta la transacción (COMMIT)
    Db::commit();
} catch(Exception $e) {
    //hubo algún problema o error y por lo tanto debe reversarse la 
    //transacción (ROLLBACK)
    Db::rollback();
}

```

Esta debe ser la forma estándar de utilizarlo para evitar efectos no deseados en la ejecución del código. 

A modo de consejo extra, debe evitarse el uso de instrucciones de selección/consulta dentro de los bloques de transacción por los efectos adversos que se provocan como bloqueo de los datos.


### commit

Sintaxis
```php
commit()
```

El método **commit** indica que las ejecuciones han sido exitosas en un bloque de transacción (envía la instrucción COMMIT).

```php
Db::commit();
```


### rollback

Sintaxis
```php
rollback()
```

El método **rollback** debe invocarse cuando las ejecuciones en una transacción no han sido correctas (envía la instrucción ROLLBACK).

```php
Db::rollback();
```