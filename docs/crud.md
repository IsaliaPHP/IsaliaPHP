# CRUD con IsaliaPHP

En esta sección de la documentación se presenta un CRUD (create, read, update, delete) clásico que trabajará sobre una tabla llamada post y que sirve para alojar la información de las publicaciones que se crean en un sitio web.

Veremos primeramente la estructura de la tabla

```sql
CREATE TABLE post
(
    id int not null primary key auto_increment,
    title varchar(255) not null,
    body text,
    created_at timestamp default CURRENT_TIMESTAMP,
    updated_at timestamp
)
```

Ahora crearemos nuestra clase de Modelo

```php
// app\models\post.php
class Post extends Model
{
    public function beforeUpdate()
    {
        $this->updated_at = date("Y-m-d H:i:s");
    }
    
    public function beforeCreate()
    {
        $this->created_at = date("Y-m-d H:i:s");
    }
}
```

## Convenciones de modelo
Los nombres de clase de los modelos mapean las tablas de la base de datos usando el mismo nombre de la clase, pero en minúscula y en modo snake_case
Es decir, si la tabla se llama post, la clase se llamará Post.
Si una clase se llama OrdenCompra, la tabla se llamará orden_compra.

Las tablas deben contar con un atributo llamado id, que es el identificador del registro. Este atributo es de tipo int o bigint y debe ser autoincremental. Debe también ser la llave primaria de la tabla.

Por otro lado, en caso de existir relaciones entre tablas los atributos de relación deben ser nombrados usando el mismo nombre de la relación en modo snake_case seguido del underscore y el sufijo _id. Por ejemplo, si la tabla post tiene una relación con la tabla comment, el atributo de relación se llamará post_id en la tabla comment.

En la documentación de Model encontrarás más detalles como por ejemplo cambiar el nombre de la tabla que mapea el modelo.

En el ejemplo, se ha activado dos métodos de apoyo, antes de actualizar (beforeUpdate) y antes de crear (beforeCreate).

En ellos llevamos a cabo operaciones sobre el registro actual antes de ejecutar las operaciones de actualización y creación respectivamente.

Ahora veremos la estructura en términos del controlador

```php
//app\controllers\posts_controller.php
class PostsController extends Controller
{
    public function index()
    {
        $this->posts = (new Post)->findAll();
    }

    public function show(int $id)
    {
        $this->post = (new Post)->findById($id);
    }

    public function create()
    {
        if (Request::hasPost("post")) {
            $post = new Post(Request::post("post"));
            if ($post->save()) {
                Flash::valid("Post created successfully");
                $this->redirect("posts");
            }
        }
    }

    public function edit(int $id)
    {
        $post = (new Post)->findById($id);
        if (Request::hasPost("post")) {
            if ($post->update(Request::post("post"))) {
                Flash::valid("Post updated successfully");
                $this->redirect("posts");
            }
        }
        $this->post = $post;
    }

    public function delete(int $id)
    {
        //no view required
        $this->setView(null);
        $post = (new Post)->findById($id);
        if ($post && $post->delete()) {
            Flash::valid("Post deleted successfully");
        }
        $this->redirect("posts");
    }

}
```

## Convenciones de controlador

Los controladores pueden tener como nombre lo que el desarrollador desee, pero es recomendable usar el nombre del modelo en singular y el sufijo Controller. Por ejemplo, si el modelo se llama Post, el controlador se llamará PostsController. De todos modos, no hay restricciones al respecto.

Respecto de las vistas predeterminadas, se ha decidido que cada acción tenga su vista asociada.

Por ejemplo, si el controlador se llama PostsController, las vistas asociadas serán:

- index.phtml
- show.phtml
- create.phtml
- edit.phtml
- delete.phtml

Como puedes ver, la convención es bastante simple y no es necesario hacer configuraciones adicionales dentro del controlador.

Si quisieras usar una vista diferente, puedes usar el método setView para indicar cuál es la vista que deseas usar.

```php
//usar una vista diferente
$this->setView('miVista');

//no usar ninguna vista
$this->setView(null);

```

### Explicación del código del controlador

El método index hace una operación de consulta sobre la tabla post solicitando todos los elementos de la tabla.
Una vez asignado el resultado de la consulta a la variable $posts, se lo pasa a la vista posts/index.

El método show hace una operación de consulta sobre la tabla post solicitando un único elemento de la tabla.
Una vez asignado el resultado de la consulta a la variable $post, se lo pasa a la vista posts/show.

El método create recibe los datos del formulario y crea un nuevo elemento de la tabla post. Una vez creado, se redirige al usuario a la acción index del controlador.

El método edit hace una operación de consulta sobre la tabla post solicitando un único elemento de la tabla y se lo pasa a la vista posts/edit que pinta un formulario para editar los datos del elemento. Una vez enviado el formulario, se actualiza el elemento y se redirige al usuario a la acción index del controlador.

El método delete hace una operación de eliminación sobre la tabla post solicitando un único elemento de la tabla.
Una vez eliminado, se redirige al usuario a la acción index del controlador.

## Vistas
Las vistas son archivos que contienen el diseño de la página web. Para facilitar la tarea de desarrollo, se ha decidido usar una vista por acción. Cada vez que pinta una vista lo hace dentro de una plantilla, para permitir tener un contenido común y luego extenderlo con el contenido específico de la vista.

### Template default
Veamos qué contiene el template default (alojada en app\views\_shared\templates\default.phtml)

```php
<!doctype html>
<html>
    <head>
        <title>IsaliaPHP les da la bienvenida</title>
        <link rel="stylesheet" type="text/css" 
             href="<?= PUBLIC_PATH ?>css/simple.css">
    </head>
    <body>
    
    	<?php echo View::getContent(); ?>
    	
  		<?php echo View::partial('footer'); ?>
	</body>
</html>    
```

La plantilla es muy sencilla, y carga en ella lo que venga desde la variable content que está en la clase View (que se obtiene usando getContent)

Tambián carga una vista parcial (que son trozos de vistas que nos pueden ser de utilidad en diferentes vistas: un pie de página, un menú, entre otros).

Veamos el contenido de la vista parcial (alojada en app\views\_shared\partials\footer.phtml)

```php
<p>
  <?php 
      echo 'Tiempo: '.round((microtime(1)-START_TIME),4).' seg.'; 
      echo ', Memoria Usada: '.number_format(memory_get_usage() / 1048576, 2).' MB';?>
</p>
```

Ahora vamos por las vistas en el siguiente orden: index, add y edit.

```php
//app\views\posts\index.phtml
<header>
    <h1>IsaliaPHP - Listado de Items</h1>
</header>
<main>    
    <?php foreach($lista_de_entradas as $item): ?>
    <p><?= $item['nombre'];?> 
    &nbsp;<a href="<?=PUBLIC_PATH?>items/edit/<?= $item['id']?>">Editar</a>
    &nbsp;<a href="<?=PUBLIC_PATH?>items/delete/<?= $item['id']?>">Eliminar</a>
        <br><small><?= $item['factualizacion']?></small>
    </p>
    <hr>
    <?php endforeach; ?>    
</main>
```

Iteramos sobre el arreglo $lista_de_entradas que dejamos en $item. Luego mostramos el nombre de la entrada, un par de enlaces para editar y eliminar y finalmente la fecha de actualiación.

```php
<header>
    <h1>IsaliaPHP - Agregar entrada</h1>
</header>
<main>    
    <form action="<?= PUBLIC_PATH?>items/add" method="post">
        <p>
            <label>Nombre</label>
            <input type="text" name="item[nombre]" required value="<?=$item['nombre'] ?? '';?>" />
        </p>
        <p>
            <label>Cuerpo</label>
            <textarea name="item[cuerpo]" required><?=$item['cuerpo'] ?? '';?></textarea>
        </p>
        <button type="submit">Enviar</button>
    </form>
</main>
```

Aquí tenemos un formulario con los elementos básicos de la publicación. Para términos de usabilidad se ha utilizado la forma de nombre del input como agrupador[atributo]. Con ello hacemos que viaje un paquete de datos con el nombre item, que contiene internamente todos los atributos (nombre y cuerpo). El formulario se dirige al mismo método que lo pintó, pero usando el método POST del formulario. Con esto podemos acceder al contenido desde el controlador como se refuerza en las siguientes líneas:

```php
//revisar si encuentra un contenedor llamado item dentro de $_POST
if (Request::hasPost('item')) {
    $datos = Request::post('item');
    //obtiene un arreglo asociativo como 
    //['nombre' => 'Beneficios del té verde', 'cuerpo' => '...']
    //este arreglo asociativo es el que nos sirve para cargarlo
    //en un modelo usando el método load para luego usar el método save
}
```

Finalmente, el formulario edit no es muy diferente de add, como verá en el ejemplo siguiente:

```php
<header>
    <h1>IsaliaPHP - Editar entrada</h1>
</header>
<main>    
    <form action="<?= PUBLIC_PATH?>items/edit/<?=$item['id']?>" method="post">
        <p>
            <label>Nombre</label>
            <input type="text" name="item[nombre]" required value="<?=$item['nombre'];?>" />
        </p>
        <p>
            <label>Cuerpo</label>
            <textarea name="item[cuerpo]" required><?=$item['cuerpo'];?></textarea>
        </p>
        <button type="submit">Enviar</button>
        
        <?php if(!empty($mensaje)): ?>
        <p><?= $mensaje ?></p>
        <?php endif; ?>        
    </form>
</main>
```

Principalmente, la diferencia radica en la url de la acción, ya que debe llevar el identificador de la entrada.