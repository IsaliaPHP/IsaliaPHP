<?php

/**
* Clase Form
* @abstract clase encargada de pintar elementos de formulario
* @author nelson rojas
*/
class Form 
{
static $_inputId;
    static $_inputName;
    static $_options;
    static $_value;
    static $_checked;

    /**
     * Genera un formulario
     * @param string $url, url del formulario
     * @param array $attributes, atributos del formulario
     * @return string
     */
    public static function open(string $url, array $attributes = [])
    {
        $attributesString = implode(' ', array_map(function($key, $value) {
            return "$key=\"$value\"";
        }, array_keys($attributes), $attributes));
        return "<form action=\"". PUBLIC_PATH . "$url\" method=\"post\" $attributesString >" .
                PHP_EOL . self::hidden('safety_key', md5(rand()) .
                chr(rand(65, 90)) . md5(Config::SAFETY_SEED) .
                chr(rand(48, 57))) . PHP_EOL;
    }

    /**
     * @return string
     */
    public static function close()
    {
        return "</form>" . PHP_EOL;
    }

    /**
     * Genera un formulario con el atributo enctype="multipart/form-data" ideal para subir archivos
     * @param string $url
     * @param array $attributes
     * @return string
     */
    public static function openMultipart(string $url, array $attributes = [])
    {
        $attributes["enctype"] = "multipart/form-data";
 
        return static::open($url, $attributes);        
    }

    /**
     * @param string $field
     * @param string $options
     * @param string $value
     * @param boolean $checked
     */
    private static function getInput($field, $options = '', $value = null, $checked = FALSE) {
        self::$_inputId = $field;
        self::$_inputName = $field;
        self::$_options = $options;
        self::$_value = '';
        self::$_checked = '';

        if (strpos($field, '.') !== FALSE) {
            $elements = explode('.', $field);
            self::$_inputId = $elements[0] . '_' . $elements[1];
            self::$_inputName = $elements[0] . '[' . $elements[1] . ']';
        }
        if ($value != null) {
            self::$_value = " value=\"$value\"";
        }

        if ($checked === TRUE) {
            $checked = " checked=\"checked\"";
        } else {
            $checked = "";
        }
        self::$_options = $options . $checked;
    }

    /**
     * Genera un input personalizado
     * @param string $type, tipo de input, text, password, date, etc.
     * @param string $field, nombre del input
     * @param string $options, opciones del input
     * @param string $value, valor del input
     * @param boolean $checked, estado del input
     * @return string
     */
    public static function input($type, $field, $options = '', $value = null, $checked = FALSE) {
        self::getInput($field, $options, $value, $checked);

        return "<input type=\"$type\" id=\"" . self::$_inputId .
                "\" name=\"" . self::$_inputName .
                "\" " . self::$_options .
                " " . self::$_value . " />" . PHP_EOL;
    }

    /**
     * Genera un input de tipo text
     * @param string $field, nombre del input, ejemplo post.title, post.description, o solo title, description
     * @param string $options, opciones del input
     * @param string $value, valor del input
     * @return string
     */
    public static function text($field, $options = '', $value = null) {
        return self::input('text', $field, $options, $value);
    }

    /**
     * Genera un input de tipo password
     * @param string $field, nombre del input, ejemplo user.password, o solo password
     * @param string $options, opciones del input
     * @param string $value, valor del input
     * @return string
     */
    public static function password($field, $options = '', $value = null) {
        return self::input('password', $field, $options, $value);
    }

    /**
     * Genera un textarea
     * @param string $field, nombre del input, ejemplo post.description, o description
     * @param string $options, opciones del input
     * @param string $value, valor del input
     * @return string
     */
    public static function textarea($field, $options = '', $value = null) {
        self::getInput($field, $value);

        return "<textarea id=\"" . self::$_inputId .
                "\" name=\"" . self::$_inputName . "\" $options>" .
                $value . "</textarea>" . PHP_EOL;
    }

    /**
     * Genera un input de tipo checkbox
     * @param string $field, nombre del input, ejemplo post.published, o published
     * @param string $options, opciones del input
     * @param string $value, valor del input
     * @param boolean $checked, estado del input, true o false
     * @return string
     */
    public static function check($field, $options = '', $value = '', $checked = FALSE) {
        return self::input('checkbox', $field, $options, $value, $checked);
    }

    /**
     * Genera un select
     * @param string $field, nombre del input, ejemplo post.category, o category
     * @param array $data, array de datos, ejemplo $data = ['1' => 'Categoria 1', '2' => 'Categoria 2', '3' => 'Categoria 3']
     * @param string $options, opciones del input
     * @param string $select, valor seleccionado, ejemplo '1'
     * @param string $value, valor del input
     * @return string
     */
    public static function select($field, $data, $options = '', $select = null, $value = null) {
        self::getInput($field, $options, $value);

        $result = "<select id=\"" .
                self::$_inputId . "\" name=\"" .
                self::$_inputName . "\" " .
                self::$_options . ">" . PHP_EOL;

        if (!empty($select)) {
            $result .= "<option value=\"\">" . $select . "</option>" . PHP_EOL;
        }

        foreach ($data as $key => $show) {
            $selected = '';
            if ($key == $value && isset($value)) {
                $selected = "selected=\"selected\"";
            }
            $result .= "<option value=\"$key\" $selected>" . $show . "</option>" . PHP_EOL;
        }

        $result .= "</select>" . PHP_EOL;

        return $result;
    }

    /**
     * Genera un label para un input
     * @param string $field, nombre del input, ejemplo post.title, post.description, o solo title, description
     * @param string $text, texto del label
     * @param string $options, opciones del label
     * @return string
     */
    public static function label($field, $text, $options = '') {
        self::getInput($field, $options);

        return "<label for=\"" . self::$_inputName . "\" " .
                self::$_options . ">$text</label>" .PHP_EOL;
    }

    /**
     * Genera un input de tipo hidden
     * @param string $field, nombre del input, ejemplo post.id, o id
     * @param string $value, valor del input
     * @return string
     */
    public static function hidden($field, $value = null) {
        return self::input('hidden', $field, "", $value);
    }

    /**
     * Genera un boton personalizable
     * @param string $value, valor del boton
     * @param string $type, tipo de boton, button, submit, reset, etc.
     * @param string $options, opciones del boton
     * @return string
     */
    public static function button($value, $type = 'button', $options = '') {
        return "<button type=\"$type\" $options>$value</button>";
    }

    /**
     * Genera un boton de tipo submit
     * @param string $value, valor del boton
     * @param string $options, opciones del boton
     * @return string
     */
    public static function submit($value, $options = '') {
        return static::button($value, "submit", $options);
    }

    /**
     * Genera las opciones para un select
     * @param array $data, array de datos, ejemplo $data = [['id' => '1', 'name' => 'Categoria 1'], ['id' => '2', 'name' => 'Categoria 2'], ['id' => '3', 'name' => 'Categoria 3']]
     * @param string $value_field, valor del input, ejemplo 'id'
     * @param string $show_field, texto del input, ejemplo 'name'
     * @param string $selected_value, valor seleccionado, ejemplo '1'
     * @return string
     */
    public static function optionsForSelect(array $data, string $value_field, string $show_field, $selected_value = null)
    {
        $result = "<option value=\"\">Seleccione</option>" . PHP_EOL;
        foreach($data as $item) {
            $selected = "";
            if ($selected_value == $item[$value_field] && !empty($selected_value)) {
                $selected = "selected=\"selected\"";
            }

            $result .= "<option value=\"$item[$value_field]\" $selected>" . $item[$show_field] . "</option>" . PHP_EOL;
        }
        return $result;
    }
}
