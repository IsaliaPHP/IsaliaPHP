<?php

class Html
{
    public static function link(string $url, string $text, array $attributes = [])
    {
        $attributesString = implode(' ', array_map(function($key, $value) {
            return "$key=\"$value\"";
        }, array_keys($attributes), $attributes));
        return "<a href=\"" . PUBLIC_PATH . "$url\" $attributesString>$text</a>";
    }
}