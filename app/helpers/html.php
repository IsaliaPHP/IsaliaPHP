<?php

/**
 * Clase Html
 * @abstract clase encargada de generar código HTML de manera dinámica.
 * @author nelson rojas
 */
class Html
{

    /**
     * Método para generar un enlace HTML.
     * @param string $url La URL a la que se enlazará.
     * @param string $text El texto del enlace.
     * @param array $attributes Atributos adicionales para el enlace.
     * @return string El código HTML del enlace.
     */
    public static function link(string $url, string $text, array $attributes = [])
    {
        $attributesString = self::buildAttributesString($attributes);
        return "<a href=\"" . PUBLIC_PATH . "$url\" $attributesString>$text</a>";
    }

    /**
     * Método para generar una imagen HTML.
     * @param string $url La URL de la imagen.
     * @param array $attributes Atributos adicionales para la imagen.
     * @return string El código HTML de la imagen.
     */
    public static function image(string $url, array $attributes = [])
    {
        $attributesString = self::buildAttributesString($attributes);
        return "<img src=\"" . PUBLIC_PATH . "$url\" $attributesString>";
    }

    /**
     * Método para generar un script HTML.
     * @param string $url La URL del script.
     * @param array $attributes Atributos adicionales para el script.
     * @return string El código HTML del script.
     */
    public static function script(string $url, array $attributes = [])
    {
        $attributesString = self::buildAttributesString($attributes);
        return "<script src=\"" . PUBLIC_PATH . "$url\" $attributesString></script>";
    }

    /**
     * Método para generar un enlace a un archivo CSS.
     * @param string $url La URL del archivo CSS.
     * @param array $attributes Atributos adicionales para el enlace.
     * @return string El código HTML del enlace.
     */
    public static function style(string $url, array $attributes = [])
    {
        $attributesString = self::buildAttributesString($attributes);
        return "<link rel=\"stylesheet\" href=\"" . PUBLIC_PATH . "$url\" $attributesString>";
    }

    /**
     * Método para construir una cadena de atributos HTML.
     * @param array $attributes Los atributos a construir.
     * @return string La cadena de atributos construida.
     */
    private static function buildAttributesString(array $attributes): string
    {
        return implode(' ', array_map(function($key, $value) {
            return "$key=\"$value\"";
        }, array_keys($attributes), $attributes));
    }
}
