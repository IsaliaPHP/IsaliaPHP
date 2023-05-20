<?php

/**
 * Permite realizar la acción de redirección en los controladores
 */
class Enrutador {

    /**
     * Hace una redirección de acuerdo a la ruta enviada como parámetro
     * @param string $ruta
     * @return void
     */
    public static function irA(string $ruta) {
        header('Location: ' . RUTA_PUBLICA . $ruta, TRUE, 302);
        return;
    }

}
