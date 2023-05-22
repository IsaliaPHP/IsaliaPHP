## Carpeta de Modelos

Los modelos son archivos donde podemos alojar la lógica de la aplicación, como los cálculos, las llamadas a base de datos, el envío de correos, entre otras funcionalidades que son reutilizables.

### Ejemplo de Modelos
```php
/* Carro de Compras */
class CarroDeCompras
{
    private static $_elementos = [];

    public static function agregar($item, $cantidad)
    {
        self::$_elementos[] = array('item' => $item, 'cantidad' => $cantidad);
    }

    public static function obtenerItems()
    {
        return self::$_elementos;
    }

    public static function destruir($item)
    {
        self::$_elementos = [];
    }
}
```

```php
/* Conectado a la tabla item */
class Item extends Model
{

}
```

En la Wiki podrás ver una sección completa dedicada a la manipulación y consulta de datos usando la clase Modelo.


```php
/* Enviar Correos de forma Simple */
class Correo
{
    public static function enviar($de, $para, $asunto, $cuerpo)
    {
        $encabezado = "From:" . $de . "\nReply-To:" . $de . "\n";
        $encabezado = $encabezado . "X-Mailer:PHP/" . phpversion() . "\n";
        $encabezado = $encabezado . "Mime-Version: 1.0\n";
        $encabezado = $encabezado . "Content-Type: text/html";

        mail($para, $asunto, $cuerpo, $header) or die("Su mensaje no pudo enviarse.");
    }
}
```




