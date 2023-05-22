<?php

/**
 * Db
 * Clase para la gestión de acceso a la base de datos
 * y operaciones CRUD: Crear, Leer, update y Borrar
 *
 */
class Db
{
    /**
     * Base de datos a la que se conecta
     *
     * @var PDO
     */
    private static $_connection = null;


    /**
     * Sirve para conectarse a la base de datos
     * usando los parámetros de la clase Configuracion
     * @throws Exception
     */
    private static function connect()
    {

        if (isset(self::$_connection)) {
            return TRUE;
        }

        try {
            self::$_connection = new PDO(
                Config::CONNECTION_STRING,
                Config::USER,
                Config::PASSWORD,
                Config::PARAMETERS
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
    private static function _exec(string $sql, array $parameters = null)
    {
        self::connect();

        $sentence = self::$_connection->prepare($sql);
        if (is_array($parameters)) {
            $sentence->execute($parameters);
        } else {
            $sentence->execute();
        }
        return $sentence;
    }

    /**
     * Obtiene filas (registros) desde la base de datos como array
     * @param string $sql
     * @param array $parameters
     *
     * @return array
     */
    public static function findAll(string $sql, array $parameters = null)
    {

        $sentence = self::_exec($sql, $parameters);

        $rows = $sentence->fetchAll(PDO::FETCH_ASSOC);
        $sentence->closeCursor();

        return $rows;
    }

    /**
     * Obtiene una fila (registro) desde la base de datos como array
     * @param string $sql
     * @param array $parameters
     *
     * @return array
     */
    public static function findFirst(string $sql, array $parameters = null)
    {
        $rows = self::findAll($sql, $parameters);
        if (isset($rows) && count($rows) > 0) {
            return $rows[0];
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
    public static function getScalar(string $sql)
    {
        $sentence = self::_exec($sql);

        $result = $sentence->fetch(PDO::FETCH_NUM);
        $sentence->closeCursor();

        return !empty($result) ? $result[0] : null;
    }

    /**
     * Ejecuta sentencias SQL para Crear, update o delete
     * Retorna la cantidad de filas afectadas con la consulta
     * @param string $sql
     * @param array $parameters
     *
     * @return int
     */
    private static function execute(string $sql, array $parameters = null)
    {

        $sentence = self::_exec($sql, $parameters);

        $affectedRows = $sentence->rowCount();

        return $affectedRows;
    }

    /**
     * Genera y ejecuta SQL para agregar un registro en la tabla indicada
     * Retorna último ID insertado
     * @param string $table_name
     * @param array $data
     *
     * @return string
     */
    public static function insert(string $table_name, array $data)
    {
        $attributes = '';
        $values = '';
        $parameters = [];

        foreach ($data as $key => $value) {
            $attributes .= $key . ',';
            $values .= ':' . $key . ',';
            $parameters[':' . $key] = $value;
        }

        $attributes = str_replace(',,', '', $attributes . ',');
        $values = str_replace(',,', '', $values . ',');

        $sql = "INSERT INTO " . $table_name . " (" . $attributes . ") ";
        $sql .= " VALUES (" . $values . ")";

        self::execute($sql, $parameters);

        return self::$_connection->lastInsertId();
    }

    /**
     * Genera y ejecuta SQL para actualizar uno o más registros en la tabla indicada
     * Retorna cantidad de filas afectadas
     * @param string $table_name
     * @param array $data
     * @param string $condition
     *
     * @return int
     */
    public static function update(string $table_name, array $data, string $condition = null)
    {
        $values = '';

        $parameters = [];

        foreach ($data as $key => $value) {
            $values .= "$key = :$key,";
            $parameters[':' . $key] = $value;
        }

        $values = str_replace(',,', '', $values . ',');

        $sql = "UPDATE " . $table_name;
        $sql .= " SET " . $values . " ";

        if (!empty($condition)) {
            $sql .= $condition;
        }

        return self::execute($sql, $parameters);
    }

    /**
     * Genera y ejecuta SQL para eliminar uno o más registro en la tabla indicada
     * Retorna cantidad de filas afectadas
     * @param string $table_name
     * @param string $condition
     * @param array $parameters
     *
     * @return int
     */
    public static function delete(string $table_name, string $condition, array $parameters = null)
    {
        $sql = "DELETE FROM " . $table_name . " " . $condition;

        return self::execute($sql, $parameters);
    }


    /**
     * Permite cerrar la conexión a la base de datos
     */
    private static function _closeConnection()
    {
        self::$_connection = null;
    }


    /**
     * Inicia una transacción en caso de requerirla
     */
    public static function beginTransaction()
    {
        self::$_connection->beginTransaction();
    }


    /**
     * Acepta los cambios realizados dentro de la transacción
     */
    public static function commit()
    {
        self::$_connection->commit();
    }


    /**
     * Rechaza los cambios realizados dentro de la transacción
     */
    public static function rollback()
    {
        self::$_connection->rollback();
    }


    /**
     * Cierra la conexión una vez que el objeto sale de memoria
     */
    public function __destruct()
    {
        self::_closeConnection();
    }

}
