# CRUD

En esta sección de la documentación se presenta un CRUD (create, read, update, delete) clásico y simple que se trabajará sobre una tabla llamada item y que sirve para alojar la información de las publicaciones que se crean en un sitio web.

Veremos primeramente la estructura de la tabla

```sql
CREATE TABLE item
(
    id int not null primary key auto_increment,
    nombre varchar(1000) not null,
    slug varchar(1000),
    cuerpo text,
    activo int,
    fcreacion timestamp default CURRENT_TIMESTAMP,
    factualizacion timestamp
)
```

Ahora crearemos nuestra clase de Modelo

```php
// App\Models\Item.php
class Item extends Model
{
    public function beforeUpdate()
    {
        $this->factualizacion = date("Y-m-d H:i:s");
    }
    
    public function beforeCreate()
    {
        $this->activo = 1;
    }
}
```
En la clase hemos activado dos métodos de apoyo, antes de actualizar (beforeUpdate) y antes de crear (beforeCreate)

En ellos llevamos a cabo operaciones sobre el registro actual antes de ejecutar las operaciones de actualización y creación respectivamente.

Ahora veremos la estructura en términos del controlador

```php
//App\Controllers\ItemsController.php
class ItemsController extends Controller
{
    public function initialize()
    {
        Load::setTemplate('default');
    }
    
    public function index()
    {
        $this->lista_de_entradas = (new Item)->findAll("WHERE activo = 1 ORDER BY id DESC LIMIT 10");
        return Load::view("Items/index", $this->getProperties());
    }
    
    public function add()
    {
        if (Request::hasPost('item')) {
            $item = new Item();
            $item->load(Request::post('item'));
            
            if ($item->save()) {
                //redirigir al método index del controlador Items
                return Router::to("Items");
            } else {
                $this->item = Request::post('item');
                $this->mensaje = "No pudo crearse el item.";
            }
        }
        //cargar la vista
        return Load::view("Items/add", $this->getProperties());
    }
    
    public function edit(int $id)
    {
        if (Request::hasPost('item')) {
            $item = new Item();
            $item->load(Request::post('item'));
            $item->id = $id;
            
            if ($item->save()) {
                //redirigir al método index del controlador Items
                return Router::to("Items");
            } else {
                $this->mensaje = "No pudo actualizarse el item.";
            }
        }
        //buscar el elemento en la tabla por su identificador
        $this->item = (new Item)->findById($id);
        //cargar la vista
        return Load::view("Items/edit", $this->getProperties());
    }
    
    public function delete(int $id)
    {
        $item = new Item();
        $item->id = $id;
        $item->delete();
        //redirigir al método index del controlador Items
        return Router::to("Items");
    }
}
```

Inicializamos el controlador con el método initialize y en él le decimos a Load que deberá usar la plantilla (template) llamada 'default'.

El método index hace una operación de consulta sobre la tabla item solicitando sólo los elementos activos, extrayendo sólo los últimos 10 elementos de la tabla.

Carga la vista Items/index en la que se podrá trabajar con la variable $lista_de_entradas que está contenida en el arreglo que se retorna al llamar a getProperties.

Veamos primero qué contiene el template default (alojada en App\Views\_Shared\Templates\default.phtml)

```php
<!doctype html>
<html>
    <head>
        <title>IsaliaPHP les da la bienvenida</title>
        <link rel="stylesheet" type="text/css" 
              href="<?= PUBLIC_PATH ?>css/simple.css">
    </head>
    <body>
    
    	<?php echo Load::getContent(); ?>
    	
  		<?php echo Load::partial('piedepagina'); ?>
	</body>
</html>    
```

La plantilla es muy sencilla, y carga en ella lo que venga desde la variable content que está en la clase Load (que se obtiene usando getContent)

Tambián carga una vista parcial (que son trozos de vistas que nos pueden ser de utilidad en diferentes vistas: un pie de página, un menú, entre otros).

Veamos el contenido de la vista parcial (alojada en App\Views\_Shared\Partials\piedepagina.phtml)

```php
<p>
  <?php 
      echo 'Tiempo: '.round((microtime(1)-START_TIME),4).' seg.'; 
      echo ', Memoria Usada: '.number_format(memory_get_usage() / 1048576, 2).' MB';?>
</p>
```

Ahora vamos por las vistas en el siguiente orden: index, add y edit.

```php
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
