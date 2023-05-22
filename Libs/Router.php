<?php

/**
 * Permite realizar la acción de redirección en los controladores
 */
class Router {

    /**
     * Hace una redirección de acuerdo a la ruta enviada como parámetro
     * @param string $route
     * @return void
     */
    public static function to(string $route) {
        header('Location: ' . PUBLIC_PATH . $route, TRUE, 302);
        return;
    }

}
