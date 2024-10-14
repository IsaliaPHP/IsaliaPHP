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
Los nombres de clase de los modelos mapean las tablas de la base de datos usando el mismo nombre de la clase, pero en minúscula y en modo snake_case, es decir, si la tabla se llama post, la clase se llamará Post. Si una clase se llama OrdenCompra, la tabla se llamará orden_compra.

Las tablas deben contar con un atributo llamado id, que es el identificador del registro. Este atributo es de tipo int o bigint y debe ser autoincremental. Debe también ser la llave primaria de la tabla.

Por otro lado, en caso de existir relaciones entre tablas los atributos de relación deben ser nombrados usando el mismo nombre de la relación en modo snake_case seguido del sufijo _id. Por ejemplo, si la tabla post tiene una relación con la tabla comment (que almacena los comentarios de los post), el atributo de relación se llamará post_id en la tabla comment.

En la documentación de Model encontrarás más detalles como por ejemplo cambiar el nombre predeterminado de la tabla a la que mapea el modelo.

En el ejemplo, se ha activado dos métodos de apoyo, antes de actualizar (beforeUpdate) y antes de crear (beforeCreate).

En ellos llevamos a cabo operaciones sobre el registro actual antes de ejecutar las operaciones de actualización y creación respectivamente.

Ahora veremos la estructura en términos del controlador para el CRUD

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

Los controladores pueden tener como nombre lo que el desarrollador desee, pero es recomendable usar el nombre del modelo en plural y el sufijo Controller. Por ejemplo, si el modelo se llama Post, el controlador se llamará PostsController. De todos modos, no hay restricciones al respecto. La recomendación es usar los nombres de los controladores de acuerdo a la forma en la que se quiere mostrar las urls a los usuarios.

Respecto de las vistas predeterminadas, se ha decidido que cada acción tenga su vista asociada.

En el ejemplo, el controlador PostsController esperará que existan las vistas asociadas:

- index.phtml
- show.phtml
- create.phtml
- edit.phtml
- delete.phtml, aunque este comportamiento es omitido al usar setView(null).

Como puedes ver, la convención es bastante simple y no es necesario hacer configuraciones adicionales dentro del controlador.

Si quisieras usar una vista diferente, puedes usar el método setView para indicar cuál es la vista que deseas cargar.

```php
//usar una vista diferente
$this->setView('miVista');

//no usar vista
$this->setView(null);

```

### Explicación del código del controlador

El método index hace una operación de consulta sobre la tabla post solicitando todos los elementos de la tabla.
Una vez asignado el resultado de la consulta a la variable $posts, se lo pasa a la vista posts/index.

El método show hace una operación de consulta sobre la tabla post solicitando un único elemento de la tabla que corresponde con el identificador definido en $id.
Una vez asignado el resultado de la consulta a la variable $post, se lo pasa a la vista posts/show.

El método create primero pinta la vista posts/create que corresponde con un formulario para crear posts. Luego, al hacer la petición desde el formulario recibe los datos y crea un nuevo elemento de la tabla post. Una vez creado, se crea una notificación y se redirige al usuario a la acción index del controlador.

El método edit hace una operación de consulta sobre la tabla post solicitando un único elemento de la tabla y se lo pasa a la vista posts/edit que pinta un formulario para editar los datos del elemento. Una vez enviado el formulario, se actualiza el elemento, se crea una notificación y se redirige al usuario a la acción index del controlador.

El método delete hace una operación de eliminación sobre la tabla post solicitando un único elemento de la tabla.
Una vez eliminado, se crea una notificación y se redirige al usuario a la acción index del controlador.

## Vistas
Las vistas son archivos que contienen el diseño de la página web. Para facilitar la tarea de desarrollo, se ha decidido usar una vista por acción. Cada vez que pinta una vista lo hace dentro de una plantilla, para permitir tener un contenido común y luego extenderlo con el contenido específico de la vista.

### Template default
Veamos qué contiene el template default (alojado en app\views\_shared\templates\default.phtml)

```php
<!doctype html>
<html>
    <head>
        <title>IsaliaPHP les da la bienvenida</title>
        <link rel="stylesheet" type="text/css" 
             href="<?= PUBLIC_PATH ?>css/isaliaphp_style.css">        
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

Ahora vamos por las vistas en el siguiente orden: index, show, create y edit.

```php
//vista: app/views/posts/index.phtml
<h1>List of posts</h1>

<?php if (Flash::hasMessages()) { ?>    
    <?= Flash::render(); ?>
<?php } ?>

<?= Html::link("posts/create", "Create new post") ?>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Body</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Options</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($posts as $post): ?>
            <tr>
                <td><?= $post->id; ?></td>
                <td><?= $post->title; ?></td>
                <td><?= substr($post->body, 0, 100) . '...'; ?></td>
                <td><?= $post->created_at; ?></td>
                <td><?= $post->updated_at; ?></td>
                <td>
                    <?= Html::link("posts/show/$post->id", "View") ?>
                    <?= Html::link("posts/edit/$post->id", "Edit") ?>
                    <?= Html::link("posts/delete/$post->id", "Delete", ["onclick" => "return confirm('Are you sure?')"]) ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

```

Iteramos sobre el arreglo $posts que dejamos en $post. Luego mostramos el nombre de la entrada, el cuerpo, la fecha de creación y actualización y finalmente un par de enlaces para ver, editar y eliminar.

Pasamos a la vista show.
```php
//vista: app/views/posts/show.phtml
<header>
    <h1><?= $post->title ?></h1>
</header>

<main>
    <article>
        <p class="post-meta">Published on: <?= date('F j, Y', strtotime($post->created_at)) ?></p>
        <div class="post-content">
            <?= $post->body ?>
        </div>
    </article>
    <nav>
		 <?= Html::link("posts", "Back to Posts") ?>
    </nav>
</main>

```
En ella se muestran los datos de la publicación seleccionada.

Ahora iremos a la vista create.
```php
//vista: app/views/posts/create.phtml
<h1>Create New Post</h1>

<?php if (Flash::hasMessages()) { ?>    
    <?= Flash::render(); ?>
<?php } ?>

<form action="<?= PUBLIC_PATH ?>posts/create" method="post">
    <div>
        <label for="post_title">Title:</label>
        <input type="text" id="post_title" name="post[title]" required>
    </div>
    <div>
        <label for="post_body">Body:</label>
        <textarea id="post_body" name="post[body]" required></textarea>
    </div>
    <div>
        <button type="submit">Create Post</button>
    </div>
</form>

<?= Html::link('posts', 'Back to Posts') ?>

```

Aquí tenemos un formulario con los elementos básicos de la publicación. Para términos de usabilidad se ha utilizado la forma de nombre del input como agrupador[atributo]. Con ello hacemos que viaje un paquete de datos con el nombre *post*, que contiene internamente todos los atributos (title y body). El formulario se dirige al mismo método que lo pintó, pero usando el método POST del formulario. Con esto podemos acceder al contenido desde el controlador como se refuerza en las siguientes líneas:

```php
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
```

Finalmente, el formulario edit no es muy diferente de *create*, como verá en el ejemplo siguiente:

```php
//vista: app/views/posts/edit.phtml
<h1>Edit Post</h1>

<?php if (Flash::hasMessages()) { ?>    
    <?= Flash::render(); ?>
<?php } ?>

<form action="<?= PUBLIC_PATH ?>posts/edit/<?= $post->id ?>" method="post">
    <div>
        <label for="post_title">Title:</label>
        <input type="text" id="post_title" name="post[title]" value="<?= htmlspecialchars($post->title) ?>" required>
    </div>
    
    <div>
        <label for="post_body">Body:</label>
        <textarea id="post_body" name="post[body]" required><?= htmlspecialchars($post->body) ?></textarea>
    </div>
    
    <div>
        <button type="submit">Update Post</button>
    </div>
</form>
<p>Last updated at: <?= $post->updated_at ?></p>
<?= Html::link('posts', 'Back to Posts') ?>
<?= Html::link("posts/view/{$post->id}", 'View Post') ?>
```

Principalmente, la diferencia radica en la url de la acción, ya que debe llevar el identificador de la entrada.