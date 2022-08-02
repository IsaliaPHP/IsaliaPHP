<?php

class Peticion {

    public static function post($var) {
        return filter_has_var(INPUT_POST, $var) ? $_POST[$var] : NULL;
    }

    public static function tienePost($var) {
        return filter_has_var(INPUT_POST, $var);
    }

    public static function get($var) {
        return filter_has_var(INPUT_GET, $var) ? $_GET[$var] : NULL;
    }

}
