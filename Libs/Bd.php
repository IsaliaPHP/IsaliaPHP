<?php

/**
 * Bd
 * Clase para la gestión de acceso a la base de datos
 * y operaciones CRUD: Crear, Leer, Actualizar y Borrar
 *
 */
class Bd
{
    /**
     * Base de datos a la que se conecta
     *
     * @var PDO
     */
    private static $_conexion = null;


    /**
     * Sirve para conectarse a la base de datos
     * usando los parámetros de la clase Configuracion
     * @throws Exception
     */
    private static function conectar()
    {

        if (isset(self::$_conexion)) {
            return TRUE;
        }

        try {
            self::$_conexion = new PDO(
                Configuracion::CADENA_CONEXION,
                Configuracion::USUARIO_BD,
                Configuracion::CLAVE_BD,
                Configuracion::PARAMETROS_EXTRAS
            );
            return TRUE;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Permite reutilizar sentencias para la ejecución de
     * consultas parametrizadas
     */
    private static function _ejecutar(string $sql, array $parametros = null)
    {
        self::conectar();

        $sentencia = self::$_conexion->prepare($sql);
        if (is_array($parametros)) {
            $sentencia->execute($parametros);
        } else {
            $sentencia->execute();
        }
        return $sentencia;
    }

    /**
     * Obtiene filas (registros) desde la base de datos como array
     * @param string $sql
     * @param array $parametros
     *
     * @return array
     */
    public static function obtenerFilas(string $sql, array $parametros = null)
    {

        $sentencia = self::_ejecutar($sql, $parametros);

        $filas = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        $sentencia->closeCursor();

        return $filas;
    }

    /**
     * Obtiene una fila (registro) desde la base de datos como array
     * @param string $sql
     * @param array $parametros
     *
     * @return array
     */
    public static function obtenerFila(string $sql, array $parametros = null)
    {
        $filas = self::obtenerFilas($sql, $parametros);
        if (isset($filas) && count($filas) > 0) {
            return $filas[0];
        } else {
            return null;
        }
    }

    /**
     * Obtiene un escalar (valor) desde la base de datos
     * @param string $sql
     *
     * @return mixed
     */
    public static function obtenerValor(string $sql)
    {
        $sentencia = self::_ejecutar($sql);

        $result = $sentencia->fetch(PDO::FETCH_NUM);
        $sentencia->closeCursor();

        return !empty($result) ? $result[0] : null;
    }

    /**
     * Ejecuta sentencias SQL para Crear, Actualizar o Eliminar
     * Retorna la cantidad de filas afectadas con la consulta
     * @param string $sql
     * @param array $parametros
     *
     * @return int
     */
    private static function ejecutar(string $sql, array $parametros = null)
    {

        $sentencia = self::_ejecutar($sql, $parametros);

        $filasAfectadas = $sentencia->rowCount();

        return $filasAfectadas;
    }

    /**
     * Genera y ejecuta SQL para agregar un registro en la tabla indicada
     * Retorna último ID insertado
     * @param string $tabla
     * @param array $input
     *
     * @return string
     */
    public static function insertar(string $tabla, array $input)
    {
        $atributos = '';
        $valores = '';
        $parametros = [];

        foreach ($input as $key => $value) {
            $atributos .= $key . ',';
            $valores .= ':' . $key . ',';
            $parametros[':' . $key] = $value;
        }

        $atributos = str_replace(',,', '', $atributos . ',');
        $valores = str_replace(',,', '', $valores . ',');

        $sql = "INSERT INTO " . $tabla . " (" . $atributos . ") ";
        $sql .= " VALUES (" . $valores . ")";

        self::ejecutar($sql, $parametros);

        return self::$_conexion->lastInsertId();
    }

    /**
     * Genera y ejecuta SQL para actualizar uno o más registros en la tabla indicada
     * Retorna cantidad de filas afectadas
     * @param string $tabla
     * @param array $input
     * @param string $condicion
     *
     * @return int
     */
    public static function actualizar(string $tabla, array $input, string $condicion = null)
    {
        $valores = '';

        $parametros = [];

        foreach ($input as $key => $value) {
            $valores .= "$key = :$key,";
            $parametros[':' . $key] = $value;
        }

        $valores = str_replace(',,', '', $valores . ',');

        $sql = "UPDATE " . $tabla;
        $sql .= " SET " . $valores . " ";

        if (!empty($condicion)) {
            $sql .= $condicion;
        }

        return self::ejecutar($sql, $parametros);
    }

    /**
     * Genera y ejecuta SQL para eliminar uno o más registro en la tabla indicada
     * Retorna cantidad de filas afectadas
     * @param string $tabla
     * @param string $condicion
     * @param array $parametros
     *
     * @return int
     */
    public static function eliminar(string $tabla, string $condicion, array $parametros = null)
    {
        $sql = "DELETE FROM " . $tabla . " " . $condicion;

        return self::ejecutar($sql, $parametros);
    }


    /**
     * Permite cerrar la conexión a la base de datos
     */
    private static function _cerrarConexion()
    {
        self::$_conexion = null;
    }


    /**
     * Inicia una transacción en caso de requerirla
     */
    public static function iniciarTransaccion()
    {
        self::$_conexion->beginTransaction();
    }


    /**
     * Acepta los cambios realizados dentro de la transacción
     */
    public static function aceptarTransaccion()
    {
        self::$_conexion->commit();
    }


    /**
     * Rechaza los cambios realizados dentro de la transacción
     */
    public static function reversarTransaccion()
    {
        self::$_conexion->rollback();
    }


    /**
     * Cierra la conexión una vez que el objeto sale de memoria
     */
    public function __destruct()
    {
        self::_cerrarConexion();
    }

}
