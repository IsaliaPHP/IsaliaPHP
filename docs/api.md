# API de IsaliaPHP

Bienvenidas y bienvenidos al API de IsaliaPHP. Aquí expondremos cada una de las clases que componen este framework.

# Clase View

La clase View (vista en español) aloja métodos estáticos que permiten incluir elementos de visualización como vistas o vistas parciales, así como también asignar la plantilla HTML padre sobre la cual se cargarán las vistas.

A continuación se describen sus métodos.

### render

Sintaxis
```php
render($view_name, $parameters = null)
```

El método **render** nos permite cargar una vista en una acción del controlador. El primer parámetro recibe el nombre del archivo de la vista que será recuperado desde app/views. El segundo parámetro (opcional), permite enviar un arreglo asociativo PHP con diferentes variables que uno pudiera necesitar en la vista. En sí, el segundo parámetro es la vía de comunicación entre la acción del controlador y la vista. Los controladores ejecutan el método **render** para enviar la vista al navegador, pero lo hacen al momento de que la clase va saliendo de memoria, es decir, en el método **__destruct** de la clase.

```php
class HomeController extends Controller
{
    public function index() 
    {
        //cargar la página inicial
        $this->pagina = (new Pagina)->findById(1);
    }
}
```

En el ejemplo, **View::render** intentará cargar la vista ubicada en app/views/home/index.phtml. 


### partial

Sintaxis
```php
partial($partial_name, $parameters = null)
```

El método **partial** nos permite cargar una **vista parcial** desde las vistas o desde las plantillas. El primer parámetro recibe el nombre del archivo de la vista parcial que será recuperado desde app/views/_shared/partials. El segundo parámetro (opcional), permite enviar un arreglo asociativo PHP con diferentes variables que uno pudiera necesitar en la vista. En sí, el segundo parámetro es la vía de comunicación entre la vista y la vista parcial.

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
Este ejemplo es una plantilla. En las plantillas se recibe el contenido de las vistas usando **View::getContent**, y se pueden invocar vistas parciales con **View::partial**. Para este caso, se ha cargado la vista parcial "footer" que será buscada desde App/Views/_Shared/Partials/footer.phtml.

Los usos de las vistas parciales son principalmente para poder contar con un trozo de código que puede cargarse desde diferentes vistas o plantillas. Por ejemplo un menú, una sección de enlaces, una lista de archivos descargables, la cabecera con el logo y nombre del sitio, una sección de suscribirse, entre otros tantos usos que uno vea que se repiten entre las diferentes vistas y que uno quisiera modificar una vez para que los cambios se apliquen en todos los lugares donde se haya utilizado la vista parcial.


### getContent

Sintaxis
```php
getContent()
```

El método **getContent** es útil cuando creamos plantillas. Internamente IsaliaPHP aloja el contenido de las vistas en memoria y luego las guarda en una variable interna de la clase (_content). Para obtener todo el contenido que se ha guardado en _content usamos el método **getContent**.

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
    </body>
</html>
```
En el ejemplo, la plantilla dibujará el contenido de cada vista utilizada desde las acciones del controlador.


### setTemplate

Sintaxis
```php
setTemplate($template_name)
```

El método **setTemplate** nos permite definir con qué plantilla se cargarán las vistas al momento de enviarse como resultado al navegador del usuario. De forma predeterminada, IsaliaPHP no usa una plantilla, sino que expone las vistas con su contenido directo. Por eso es necesario que cada controlador, o en cada acción del controlador, se pueda definir qué plantilla será la encargada de "adornar" visualmente nuestras vistas.

```php
<?php

class HomeController extends Controller
{
    public function initialize()
    {
        View::setTemplate('default');
    }
    
    public function index() 
    {
        $this->pagina = (new Pagina)->findById(1);
    }

}
```
En este ejemplo, usamos el método **initialize** del controlador para definir que todas las acciones del controlador utilizarán la plantilla **default**. Las plantillas se alojan en app/views/_shared/templates. Su extensión es también **.phtml**.

Como se indicó antes, puede usarse el método **setTemplate** como elemento general, o puede usarse por cada acción del controlador.
Digamos que queremos que una cierta acción se ejecute como petición AJAX (una llamada asíncrona desde el navegador del cliente que no recarga la página completa). Entonces en dicha acción enviaremos por ejemplo una respuesta como JSON (una notación para objetos en Javascript). Si tenemos una plantilla destinada para tal efecto, usaremos **setTemplate** como en el siguiente ejemplo:

```php

    //método modificar_perfil del controlador Recursos
    public function modificar_perfil(int $perfil_id, int $recurso_id, int $estado)
    {
        View::setTemplate('plain');
        $this->result = (new PerfilRecurso)->actualizarConfiguracion(
            $perfil_id, $recurso_id, $estado);
    }
```

Ahora veamos qué hace la plantilla **plain**

```php
//contenido de la plantilla plain
<?= View::getContent(); ?>
```
Sólo pasa el contenido de lo que se genere en la vista, sin adornos (sin cabecera html, sin css, sin js)

Y para finalizar, veremos qué hace la vista con las propiedades enviadas desde el controlador.

```php
//entrega el contenido de $result que se genera como un arreglo asociativo 
//$result puede contener los siguientes valores
// $result = ['result' => 'ok']
// $result = ['result' => 'error']

echo json_encode($result);
```
Con eso, la petición AJAX recibirá un objeto JSON con la propiedad result, la que tendrá que evaluar para saber si la actualización del perfil fue exitosa (ok) o si no pudo realizarse (error)

El uso de plantillas diferentes nos permite hacer variadas salidas de contenido, no solo HTML. Podemos enviar descargas de archivos, archivos PDF, planillas XLSX (Excel), archivos de texto plano (txt), XML, JSON, entre otras tantas ideas.


### setContent

Sintaxis
```php
setContent($contenido)
```

El método **setContent** es un método que sólo es útil dentro del método **view** de esta misma clase. Su utilidad en dicho método es permitir guardar al contenido resultante de la carga de las variables y el archivo de la vista almacenándolos en la variable de clase _content. Esto permitirá posteriormente recuperar el contenido con **getContent**.


# Clase Loader

La clase Loader (Cargador en español) se encarga de despachar las peticiones desde el controlador frontal (index.php ubicado en public).

### controllerFromUrl

Sintaxis
```php
controllerFromUrl(string $url)
```

El método **controllerFromUrl** es un método que sólo se carga desde el controlador frontal (el archivo index.php ubicado en la carpeta public). Este método es el encargado de traducir las peticiones que realizan los usuarios desde el navegador para luego invocar al controlador y acción requeridos.


# Clase Router

La clase Router (Enrutador en español) permite hacer redirecciones dentro de las acciones del controlador.

### to

Sintaxis
```php
to(string $route)
```

El método **to** es un método que nos permite indicar hacia qué acción y controlador deberemos redirigir al usuario desde la acción actual.
Por ejemplo, si un usuario inicia sesión, debemos redireccionarlo hacia la página inicial (el controlador y acción inicial)

```php

//redirigir al controlador y acción predeterminados (Home/index)
Router::to("");

//redirigir al controlador Productos, a su acción predeterminada (index)
Router::to("Productos");

//redirigir al controlador Login, a su acción entrar
Router::to("Login/entrar");

```
A modo de convenición, en un controlador encargado de hacer un CRUD (crear, leer, actualizar y borrar) se estila que al terminar cada acción que afecta datos se redireccione hacia la acción index, es decir, cuando se envía el formulario para crear un registro, y la creación es exitosa, se hace redirección. Cuando estamos editando y enviamos el formulario, y la actualización es realizada, redirigimos. Lo mismo cuando pedimos borrar un registro. Pero esto es un comportamiento en el que uno puede no estar de acuerdo. Por ejemplo en vez de redirigir en la edición, permanecemos en el formulario editando en vez de redirigir. Cuando usuario decide volver al inicio, entonces él puede hacerlo a su gusto. Es otra forma de ver y resolver la situación.

Para mayor referencia de cómo utilizarlo en controladores, vea la sección CRUD en el archivo crud.md


# Clase Request

La clase Request (Petición en español) se encarga de abstraer los elementos globales de PHP $_POST y $_GET, permitiendo un acceso más seguro a los valores en ellos almacenados.

### hasPost

Sintaxis
```php
hasPost(string $var)
```

El método **hasPost** (tiene envío) se utiliza para saber si fue enviada una cierta variable de formulario. Las variables de formulario se reciben por su propiedad **name** (nombre) y es a ellas a las que consultamos en el método. Por ejemplo, si tenemos un formulario de login, tendremos dos variables: usuario y clave.

```php
    if (Request::hasPost("usuario") && Request::hasPost("clave")) {
        //procesar la verificación de usuario con usuario y clave
    }
```
**hasPost** nos devuelve verdaro si encuentra la variable y falso en caso que no esté disponible.


### post

Sintaxis
```php
post(string $var)
```

El método **post** permite extraer una variable que esté cargada en el global $_POST para que podamos utilizarla de acuerdo a la necesidad de la acción en el controlador.

Si volvemos al mismo ejemplo del formulario de login, complementaremos el código para aclarar su funcionalidad.

```php
    if (Request::hasPost("usuario") && Request::hasPost("clave")) {
        $valor_de_usuario = Request::post("usuario");
        $valor_de_clave = Request::post("clave");

        //procesar la verificación de usuario con usuario y clave
    }
```

Hemos alojado los contenidos enviados en el formulario de login en $valor_de_usuario y $valor_de_clave respectivamente. Con dichos valores ahora podríamos utilizar alguna consulta a la base de datos para verificar si algún usuario coincide con el par de datos recuperados.

Ahora bien, cuando tenemos formularios con muchos datos, como por ejemplo para actualizar o crear un registro, podemos valernos de una estrategia simplificada.

Usualmente creamos los formularios de la siguiente forma:

```html
<form action="Productos/agregar" method="post">
    <label>Nombre</label>
    <input type="text" name="nombre" />
    <label>Descripción</label>
    <textarea name="descripcion"></textarea>
    <label>Precio</label>
    <input type="text" name="precio" />
    <button type="submit">Crear producto</button>
</form>
```

Si luego quisiéramos validar que cada elemento fue enviado (bien nos valdría también incluir el atributo required en cada input) tendríamos que hacer una verificación con muchos atributos:

```php
    if (Request::hasPost("nombre") && Request::hasPost("descripcion") && Request::hasPost("precio")) {
        //crear el producto con los datos recibidos
        $producto = new Producto();
        $producto->nombre = Request::post('nombre');
        $producto->descripcion = Request::post('descripcion');
        $producto->precio = Request::post('precio');
        $producto->save();
        $this->redirect("productos");
    }
```

Pero como la idea es buscar estrategias para escribir menos, vamos a reescribir el formulario de forma que lo enviemos como una unidad, como un paquete.

```html
<form action="Productos/agregar" method="post">
    <label>Nombre</label>
    <input type="text" name="producto[nombre]" required />
    <label>Descripción</label>
    <textarea name="producto[descripcion]" required></textarea>
    <label>Precio</label>
    <input type="text" name="producto[precio]" required />
    <button type="submit">Crear producto</button>
</form>
```

Y modificamos el comportamiento del controlador

```php
    if (Request::hasPost("producto")) {
        //crear el producto con los datos recibidos
        $producto = new Producto(Request::post('producto'));
        $producto->create();
        $this->redirect("productos");
    }
```

Por ahora bajamos de 9 líneas de código a 7, pero el caso es que estamos creando un registro con 3 atributos. Qué sería si tuviéramos 10 o 15 atributos?

En ese caso, la estrategia 1 crecería de acuerdo a la cantidad de atributos, pero la estrategia 2, la de usar un paquete de datos, sería constante. No necesitamos más que esas 7 líneas (bueno, aún podemos incluir algún if para validar que se crea el producto, pero con eso ya bastaría)


### hasGet

Sintaxis
```php
hasGet(string $var)
```

El método **hasGet** (tiene variable de url) se utiliza para saber si fue enviada una cierta variable de url. Las variables de url generalmente las vemos en los buscadores, o en las páginas de los bancos. Usualmente se envían como **www.ejemplo.com/ruta1?variable1=valor1&variable2=valor2**
Aquí, en este ejemplo son variables de url **variable1** y **variable2**

Podríamos enviar en ellas la página actual dentro de un conjunto paginado de datos o información, o el orden que tienen los datos.

```php
    if (Request::hasGet("pagina")) {
        $pagina_actual = Request::get("pagina");
    }
    if (Request::hasGet("orden")) {
        $orden_de_visualizacion = Request::get("orden");
    }
```
**hasGet** nos devuelve verdaro si encuentra la variable y falso en caso que no esté disponible.


### get

Sintaxis
```php
get(string $var)
```

El método **get** permite extraer una variable que esté cargada en el global $_GET para que podamos utilizarla de acuerdo a la necesidad de la acción en el controlador.

Si volvemos al mismo ejemplo la url anterior **www.ejemplo.com/productos?pagina=5&orden=asc**

```php
    if (Request::hasGet("pagina")) {
        $pagina_actual = Request::get("pagina");
    }
    if (Request::hasGet("orden")) {
        $orden_de_visualizacion = Request::get("orden");
    }
```

Al pasar por la acción del controlador, los valores vendrán: para $pagina_actual en "5", y para $orden_de_visualizacion en "asc".


# Clase Session

La clase Session (Sesión en español) aloja métodos estáticos que permiten cargar datos en el global $_SESSION de PHP. Son variables que permanecen en la conexión de cada usuario y que son útiles por ejemplo para establecer si el usuario está conectado, o alguna configuración especial del mismo.

A continuación se describen sus métodos.

### set

Sintaxis
```php
set(string $key, $value)
```

El método **set** permite asignar un valor en la lista de propiedades que almacenará la clase.

```php
    Session::set('ha_iniciado_sesion', true);
    Session::set('nombre_usuario', 'admin');
    Session::set('id_usuario', 1);
```


### get

Sintaxis
```php
get(string $key)
```

El método **get** permite obtener un valor desde la lista de propiedades almacenada en la clase.

```php
    if (Session::get('ha_iniciado_sesion') === true) {
        echo Session::get('nombre_usuario');
        echo Session::get('id_usuario');
    }
```


### delete

Sintaxis
```php
delete(string $key)
```

El método **delete** permite eliminar un valor desde la lista de propiedades almacenada en la clase.

```php
    //eliminamos la variable ha_iniciado_sesion
    Session::delete('ha_iniciado_sesion');
```



### destroy

Sintaxis
```php
destroy()
```

El método **destroy** eliminar todo el contenido de una sesión de usuario. Es útil cuando queremos cerrar sesión de un usuario en el sitio.

```php
    //destruimos la sesión del usuario
    Session::destroy();
```


# Clase Console

La clase Console (Consola en español) se ha creado para dejar evidencia de acciones que sean de utilidad como auditoría o para efectos de desarrollo. Su finalidad es escribir mensajes en un archivo de texto que se genera diariamente.

A continuación se describen sus métodos.

### writeLog

Sintaxis
```php
writeLog(string $mensaje)
```

El método **writeLog** escribe el contenido de $mensaje dentro del archivo de texto del día. Los archivos se crean en app/temp/logs

```php
Console::writeLog("El usuario $usuario ha iniciado sesión");
```

Se puede invocar el método **writeLog** en las acciones del controlador, los modelos, vistas, librerías (app/libs) o ayudante (app/helpers)


