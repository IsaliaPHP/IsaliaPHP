<?php

/**
 * Class Security
 * @abstract
 * Permite usar CSRF token en los formularios, basado en el contenido del siguiente link
 * @see https://stackoverflow.com/questions/6287903/how-to-properly-add-csrf-token-using-php/31683058#31683058
 */
class Security
{
    /**
     * injectAntiCSRFHeader
     * @abstract
     * Agrega los headers necesarios para evitar peticiones POST o GET desde dominios fuera del servidor local
     * @return void
     */
    static function injectAntiCSRFHeader()
    {
        // Cross-Origin Resource Sharing Header
        header('Access-Control-Allow-Origin: http://127.0.0.1');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept');
    }

}
