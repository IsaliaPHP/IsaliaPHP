# SqlBuilder

La clase `SqlBuilder` de IsaliaPHP es una herramienta poderosa para construir consultas SQL de manera dinámica y flexible. Ha sido creada pensando en la simplicidad y la flexibilidad de uso. Esta guía te mostrará cómo utilizar sus principales características.

## Inicialización

Para comenzar a usar `SqlBuilder`, primero debes crear una instancia de la clase, especificando la tabla principal sobre la que se realizará la consulta:

```php
$query = new SqlBuilder('users');
```

## Selección de columnas

Puedes especificar las columnas que deseas seleccionar utilizando el método `select()`:

```php
$query->select('id', 'name', 'email');
```

También puedes pasar un array de columnas:

```php
$query->select(['id', 'name', 'email']);
```

Si no especificas columnas, se seleccionarán todas (`*`) por defecto.

## Condiciones WHERE

Utiliza el método `where()` para agregar condiciones a tu consulta:

```php
$query->where('active = 1');
```

## Ordenamiento

Para ordenar los resultados, usa el método `orderBy()`:

```php
$query->orderBy('name');
```

## Paginación

La clase ofrece un método conveniente para la paginación:

```php
$query->paginate(2, 10); // Página 2, 10 resultados por página
```

## Joins

Puedes agregar joins a tu consulta con el método `join()`:

```php
$query->join('LEFT JOIN orders ON users.id = orders.user_id');
```

## Límite y offset

Para limitar el número de resultados o establecer un offset:

```php
$query->limit(10)->offset(20);
```

## Generación de la consulta SQL

Para obtener la consulta SQL generada, puedes usar el método `toSql()` o simplemente convertir el objeto a string:

```php
$sql = $query->toSql();
// o
$sql = (string) $query;
```

## Ejemplo completo

Aquí tienes un ejemplo que combina varias características:

```php
$query = (new SqlBuilder('users'))
    ->select('users.id', 'users.name', 'users.email', 'orders.order_date')
    ->join('LEFT JOIN orders ON users.id = orders.user_id')
    ->where('users.active = 1')
    ->orderBy('users.name')
    ->limit(10);

echo $query;
```

Este ejemplo generará una consulta SQL similar a:

```sql
SELECT users.id, users.name, users.email, orders.order_date 
FROM users 
LEFT JOIN orders ON users.id = orders.user_id 
WHERE users.active = 1 
ORDER BY users.name 
LIMIT 10
```

# Guía avanzada de SqlBuilder

## Condiciones WHERE más complejas

### Múltiples condiciones

Puedes encadenar múltiples llamadas a `where()` para agregar varias condiciones:

````php
$query = new SqlBuilder('users');
$query->where('age >= 18')
      ->where('status = "active"')
      ->where('country IN ("USA", "Canada", "Mexico")');
````

Esto generará:

````sql
SELECT * FROM users 
WHERE age >= 18 
  AND status = "active" 
  AND country IN ("USA", "Canada", "Mexico")
````

### Condiciones OR

Para condiciones OR, puedes usar el método `orWhere()`:

````php
$query = new SqlBuilder('products');
$query->where('category = "electronics"')
      ->where('price < 1000')
      ->orWhere('featured = 1');
````

Resultado:

````sql
SELECT * FROM products 
WHERE category = "electronics" 
  AND price < 1000 
  OR featured = 1
````

### Condiciones con paréntesis

Para agrupar condiciones, puedes usar métodos que acepten una función de callback:

````php
$query = new SqlBuilder('orders');
$query->where('status = "pending"')
      ->whereGroup(function($q) {
          $q->where('total > 1000')
            ->orWhere('priority = "high"');
      });
````

Esto generará:

````sql
SELECT * FROM orders 
WHERE status = "pending" 
  AND (total > 1000 OR priority = "high")
````

## Joins más complejos

### Múltiples joins

Puedes encadenar varios joins:

````php
$query = new SqlBuilder('users');
$query->select('users.id', 'users.name', 'orders.order_date', 'products.name as product_name')
      ->join('LEFT JOIN orders ON users.id = orders.user_id')
      ->join('LEFT JOIN order_items ON orders.id = order_items.order_id')
      ->join('LEFT JOIN products ON order_items.product_id = products.id')
      ->where('users.status = "active"')
      ->orderBy('orders.order_date DESC');
````

### Join con condiciones adicionales

Puedes agregar condiciones adicionales a tus joins:

````php
$query = new SqlBuilder('posts');
$query->select('posts.*', 'users.name as author')
      ->join('LEFT JOIN users ON posts.author_id = users.id AND users.active = 1')
      ->where('posts.published_at <= NOW()');
````

## Subconsultas

Puedes usar subconsultas en tus condiciones:

````php
$subquery = (new SqlBuilder('orders'))
    ->select('user_id')
    ->where('total > 1000');

$query = new SqlBuilder('users');
$query->where('id IN (' . $subquery->toSql() . ')');
````

Esto generará:

````sql
SELECT * FROM users 
WHERE id IN (SELECT user_id FROM orders WHERE total > 1000)
````

## Agrupación y funciones de agregación

Puedes usar funciones de agregación y agrupar resultados:

````php
$query = new SqlBuilder('sales');
$query->select('product_id', 'SUM(quantity) as total_sold')
      ->groupBy('product_id')
      ->having('total_sold > 100')
      ->orderBy('total_sold DESC');
````

Resultado:

````sql
SELECT product_id, SUM(quantity) as total_sold 
FROM sales 
GROUP BY product_id 
HAVING total_sold > 100 
ORDER BY total_sold DESC
````

## Uniones (UNION)

Si tu clase SqlBuilder soporta uniones, podrías usarlas así:

````php
$query1 = (new SqlBuilder('users'))
    ->select('name', 'email')
    ->where('status = "active"');

$query2 = (new SqlBuilder('archived_users'))
    ->select('name', 'email')
    ->where('archived_date > "2023-01-01"');

$unionQuery = $query1->union($query2);
````

Esto generaría:

````sql
(SELECT name, email FROM users WHERE status = "active")
UNION
(SELECT name, email FROM archived_users WHERE archived_date > "2023-01-01")
````

Estos ejemplos muestran cómo puedes construir consultas SQL más complejas y flexibles utilizando la clase SqlBuilder.


## Conclusión

La clase `SqlBuilder` de IsaliaPHP proporciona una interfaz fluida y fácil de usar para construir consultas SQL complejas de manera programática. Esto permite una mayor flexibilidad y legibilidad en la construcción de consultas dinámicas en tu aplicación, y te permite complementar el uso de la clase Model con consultas complejas.
