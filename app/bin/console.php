<?php

$current_dir = dirname(dirname(__FILE__));

define('APP_PATH', dirname(dirname(__FILE__)));
define('DS', DIRECTORY_SEPARATOR);

echo APP_PATH . PHP_EOL;

if (!str_ends_with(dirname(__FILE__), DS . 'app' . DS .'bin')) {
    die("El script desde estar alojado en la ruta /suproyecto/app/bin");
}


function mostrarMenu()
{
    echo "----------------------------------\n";
    echo "Consola Interactiva IsaliaPHP\n";
    echo "----------------------------------\n";
    echo "1. Crear Modelo\n";
    echo "2. Crear Controlador\n";
    echo "3. Crear Scaffolding\n";
    echo "4. Crear Vistas CRUD\n";
    echo "5. Salir\n";
    echo "----------------------------------\n";
    echo "Selecciona una opción: ";
}

function crearModelo($nombreModelo = '')
{
    echo "==== Crear Modelo ====\n";
    echo "Se le solicitará el nombre del modelo que debe ser en notación PascalCase \n";
    echo "Por ejemplo Producto, Compras, Ventas, DetalleCompras. \n";
    echo "Recuerde que IsaliaPHP mapea automáticamente el modelo con la tabla \n";
    echo "usando la siguiente convención: \n";
    echo "- PascalCase, el nombre de clase, (ejemplo: Post)\n";
    echo "- snake_case, el nombre de la tabla (ejemplo: post) \n";
    echo "=======================\n";
    if ($nombreModelo == '') {
        echo "Ingrese el nombre del modelo: ";
        $nombreModelo = trim(fgets(STDIN));        
    }
    $nombreArchivoModelo = pascalToSnakeCase($nombreModelo);

    // Aquí puedes agregar la lógica para crear el modelo
    if (file_exists(APP_PATH . DS . "models" . DS . $nombreArchivoModelo . ".php")) {
        echo "El modelo ya existe.\n";
        return;
    }

    $archivoModelo = APP_PATH . DS . "models" . DS . $nombreArchivoModelo . ".php";
    $templateModelo = file_get_contents(APP_PATH . DS . "bin" . DS . "console" . DS . "model_template.php");
    $templateModelo = str_replace("{{nombreModelo}}", $nombreModelo, $templateModelo);
    file_put_contents($archivoModelo, $templateModelo);

    echo "Modelo '$nombreModelo' creado con éxito.\n";
}

function crearControlador($nombreControlador = '', $controladorPadre = 'Controller', $definicionModelo = '')
{
    echo "==== Crear Controlador ====\n";
    echo "Se le solicitará el nombre del controlador que debe ser en notación PascalCase \n";
    echo "Por ejemplo Producto, Compras, Ventas, DetalleCompras. \n";
    echo "No debe incluir el sufijo Controller al final. Se agregará automáticamente. \n";
    echo "=======================\n";
    if ($nombreControlador == '') {
        echo "Ingrese el nombre del controlador: ";
        $nombreControlador = trim(fgets(STDIN));        
    }
    $nombreArchivoControlador = pascalToSnakeCase($nombreControlador . "Controller");

    // Aquí puedes agregar la lógica para crear el controlador
    if (file_exists(APP_PATH . DS . "controllers" . DS . $nombreArchivoControlador . ".php")) {
        echo "El controlador ya existe.\n";
        return;
    }

    $archivoControlador = APP_PATH . DS . "controllers" . DS . $nombreArchivoControlador . ".php";
    $templateControlador = file_get_contents(APP_PATH . DS . "bin" . DS . "console" . DS . "controller_template.php");
    $templateControlador = str_replace("{{nombreControlador}}", $nombreControlador, $templateControlador);
    $templateControlador = str_replace("{{clasePadre}}", "$controladorPadre", $templateControlador);
    $templateControlador = str_replace("{{definicionModelo}}", "$definicionModelo", $templateControlador);
    file_put_contents($archivoControlador, $templateControlador);

    echo "Controlador '$nombreControlador' creado con éxito.\n";
}

function crearScaffolding()
{
    echo "==== Crear Scaffolding ====\n";
    echo "Paso 1: Controlador\n";
    echo "Se le solicitará el nombre del controlador que debe ser en notación PascalCase \n";
    echo "Por ejemplo Producto, Compras, Ventas, DetalleCompras. \n";
    echo "No debe incluir el sufijo Controller al final. Se agregará automáticamente. \n";
    echo "=======================\n";

    echo "Ingrese el nombre del controlador: ";
    $nombreControlador = trim(fgets(STDIN));
    
    echo "Paso 2: Modelo\n";
    echo "Se le solicitará el nombre del modelo que debe ser en notación PascalCase \n";
    echo "Por ejemplo Producto, Compras, Ventas, DetalleCompras. \n";
    echo "Recuerde que IsaliaPHP mapea automáticamente el modelo con la tabla \n";
    echo "usando la siguiente convención: \n";
    echo "- PascalCase, el nombre de clase, (ejemplo: Post)\n";
    echo "- snake_case, el nombre de la tabla (ejemplo: post) \n";
    echo "=======================\n";
    echo "Ingrese el nombre del modelo: ";
    $nombreModelo = trim(fgets(STDIN));

    $definicionModelo = "protected \$_model = \"$nombreModelo\";";

    crearControlador($nombreControlador, "ScaffoldController", $definicionModelo);
    crearModelo($nombreModelo);

    echo "Scaffolding '$nombreControlador' creado con éxito.\n";
}

function crearVistasCRUD()
{
    echo "==== Crear vistas CRUD ====\n";
    echo "Paso 1: Controlador\n";
    echo "Se le solicitará el nombre del controlador que debe ser en notación PascalCase \n";
    echo "Por ejemplo Producto, Compras, Ventas, DetalleCompras. \n";
    echo "No debe incluir el sufijo Controller al final. Se agregará automáticamente. \n";

    echo "Ingrese el nombre del controlador: ";
    $nombreControlador = trim(fgets(STDIN));
    $url_controlador = pascalToSnakeCase($nombreControlador);

    echo "Paso 2: Modelo\n";
    echo "Se le solicitará el nombre del modelo que debe ser en notación PascalCase \n";
    echo "Por ejemplo Producto, Compras, Ventas, DetalleCompras. \n";
    echo "Recuerde que IsaliaPHP mapea automáticamente el modelo con la tabla \n";
    echo "usando la siguiente convención: \n";
    echo "- PascalCase, el nombre de clase, (ejemplo: Post)\n";
    echo "- snake_case, el nombre de la tabla (ejemplo: post) \n";
    echo "=======================\n";
    echo "Ingrese el nombre del modelo: ";
    $nombreModelo = trim(fgets(STDIN));

    $nombreTabla = pascalToSnakeCase($nombreModelo);

    echo "Paso 3: Atributos\n";
    echo "Se le solicitará la lista de atributos de la tabla \n";
    echo "Indique solamente aquellos que espera que puedan ser llenados por el usuario\n";
    echo "No incluya Id, o atributos calculados manualmente/automáticamente como fechas\n";
    echo "Ejemplo:\n";
    echo "titulo,cuerpo,activo:\n";
    echo "No incluya espacios entre atributos\n";
    echo "=======================\n";
    echo "Ingrese la lista de atributos del modelo separados por coma: ";
    $atributos_originales = trim(fgets(STDIN));
    $atributos = explode(',', $atributos_originales);

    if (!is_dir(APP_PATH . DS . "views" . DS . $url_controlador)) {
        mkdir(APP_PATH . DS . "views" . DS . $url_controlador, 0755, true);
    }

    $columnas_tabla = '';
    foreach ($atributos as $atributo) {
        $columnas_tabla .= "\t<th>".ucfirst($atributo)."</th>" . PHP_EOL;
    }

    $atributos_tabla = '';
    foreach ($atributos as $atributo) {
        $atributos_tabla .= "\t<td><?=$" . "item->$atributo; ?></td>" . PHP_EOL;
    }

    $template_index = file_get_contents(APP_PATH . DS . "bin". DS. "console". DS . "view_index_template.phtml");
    $template_index = str_replace("{{nombreModelo}}", $nombreModelo, $template_index);
    $template_index = str_replace("{{url_controlador}}", $url_controlador, $template_index);
    $template_index = str_replace("{{columnas_tabla}}", $columnas_tabla, $template_index);
    $template_index = str_replace("{{atributos_tabla}}", $atributos_tabla, $template_index);
    file_put_contents(APP_PATH . DS . "views" . DS . $url_controlador . DS . "index.phtml", $template_index);

    $atributos_form = '';
    foreach ($atributos as $atributo) {
        $atributos_form .= "<div>" . PHP_EOL;
        $atributos_form .= "<?= Form::label(\"data.$atributo\", \"".ucfirst($atributo)."\"); ?>" . PHP_EOL;
        $atributos_form .= "<?= Form::text(\"data.$atributo\");?>" . PHP_EOL;
        $atributos_form .= "</div>" . PHP_EOL;
    }

    $template_create = file_get_contents(APP_PATH . DS . "bin". DS. "console". DS . "view_create_template.phtml");
    $template_create = str_replace("{{nombreModelo}}", $nombreModelo, $template_create);
    $template_create = str_replace("{{url_controlador}}", $url_controlador, $template_create);
    $template_create = str_replace("{{atributos_form}}", $atributos_form, $template_create);
    file_put_contents(APP_PATH . DS . "views" . DS . $url_controlador . DS . "create.phtml", $template_create);

    $atributos = explode(',', 'id,'.$atributos_originales);
    $atributos_form = '';
    foreach ($atributos as $atributo) {
        $atributos_form .= "<div>" . PHP_EOL;
        $atributos_form .= "<?= Form::label(\"data.$atributo\", \"".ucfirst($atributo)."\"); ?>" . PHP_EOL;
        
        $soloLectura = "";
        if ($atributo == "id") {
            $soloLectura = "readonly";
        }

        $atributos_form .= "<?= Form::text(\"data.$atributo\", \"$soloLectura\", htmlspecialchars($" . "current_item->$atributo));?>" . PHP_EOL;
        $atributos_form .= "</div>" . PHP_EOL;
    }
    $template_edit = file_get_contents(APP_PATH . DS . "bin". DS. "console". DS . "view_edit_template.phtml");
    $template_edit = str_replace("{{nombreModelo}}", $nombreModelo, $template_edit);
    $template_edit = str_replace("{{url_controlador}}", $url_controlador, $template_edit);
    $template_edit = str_replace("{{atributos_form}}", $atributos_form, $template_edit);
    file_put_contents(APP_PATH . DS . "views" . DS . $url_controlador . DS . "edit.phtml", $template_edit);

    $detalleAtributos = '';
    foreach ($atributos as $atributo) {
        $detalleAtributos .= "<div>" . PHP_EOL;
        $detalleAtributos .= "<strong>".ucfirst($atributo)."</strong>" . PHP_EOL;
        $detalleAtributos .= "<p><?=$" . "current_item->$atributo" . "?></p>" . PHP_EOL;
        $detalleAtributos .= "</div>" . PHP_EOL;
    }
    $template_show = file_get_contents(APP_PATH . DS . "bin". DS. "console". DS . "view_show_template.phtml");
    $template_show = str_replace("{{nombreModelo}}", $nombreModelo, $template_show);
    $template_show = str_replace("{{url_controlador}}", $url_controlador, $template_show);
    $template_show = str_replace("{{detalleAtributos}}", $detalleAtributos, $template_show);
    file_put_contents(APP_PATH . DS . "views" . DS . $url_controlador . DS . "show.phtml", $template_show);

    // Aquí puedes agregar la lógica para crear las vistas CRUD
    echo "Vistas CRUD para '$nombreControlador' creadas con éxito.\n";
}

// Agregar esta nueva función después de crearVistasCRUD()
function pascalToSnakeCase($string)
{
    return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
}

while (true) {
    mostrarMenu();
    $opcion = trim(fgets(STDIN));

    switch ($opcion) {
        case '1':
            crearModelo();
            break;
        case '2':
            crearControlador();
            break;
        case '3':
            crearScaffolding();
            break;
        case '4':
            crearVistasCRUD();
            break;
        case '5':
            echo "Saliendo...\n";
            exit(0);
        default:
            echo "Opción no válida, intenta nuevamente.\n";
    }
    echo "\n";
}
