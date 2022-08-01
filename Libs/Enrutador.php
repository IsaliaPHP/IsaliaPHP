<?php

class Enrutador
{
  	static function irA($ruta)
    {
      header('Location: ' . RUTA_PUBLICA . $ruta, TRUE, 302);
    }
}