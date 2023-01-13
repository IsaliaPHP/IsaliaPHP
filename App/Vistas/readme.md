## Bienvenido a la carpeta de las vistas

Las vistas pueden crearse de acuerdo a la organización que te parezca conveniente, sólo debes respetar 2 condiciones:

- Deben ser archivos con extensión phtml, por ejemplo home.phtml
- Cuando cargues la vista con Cargar::vista($nombre_de_la_vista), debes tener en claro que la estructura de directorios y el nombre de la vista deben coincidir con el uso de mayúsculas y minúsculas si usas sistema de archivo de Linux o Mac. En Windows da lo mismo, porque todo lo considera como si fuera en mayúsculas. Por ejemplo, Cargar::vista("Home/contacto") buscará la existencia de App/Vistas/Home/contacto.phtml

