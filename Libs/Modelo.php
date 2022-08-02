<?php

class Modelo {

    protected $_tabla;
    private $_bd;

    function __construct($nombreTabla) {
        $this->_tabla = $nombreTabla;
        $this->_bd = new Bd();
    }

    function __destruct() {
        
    }

    function todos(string $condicion = '', $parametros = null) {
        $sql = "SELECT * FROM " . $this->_tabla;
        if (!empty($condicion)) {
            $sql .= " " . $condicion;
        }

        return $this->_bd->buscar($sql, $parametros);
    }

    function porId(int $id) {
        $sql = "SELECT * FROM " . $this->_tabla;
        $sql .= " WHERE id = :id";
        $parametros = [':id' => $id];

        return $this->_bd->buscarPrimero($sql, $parametros);
    }

    function primero(string $condicion = '', $parametros = null) {
        $sql = "SELECT * FROM " . $this->_tabla;
        if (!empty($condicion)) {
            $sql .= " " . $condicion;
        }

        return $this->_bd->buscarPrimero($sql, $parametros);
    }

    public function insertar($data) {
        $atributos = '';
        $valores = '';
        $parametros = [];

        foreach ($data as $key => $value) {
            $atributos .= $key . ',';
            $valores .= ':' . $key . ',';
            $parametros[':' . $key] = $value;
        }

        $atributosFinal = str_replace(',,', '', $atributos . ',');
        $valoresFinal = str_replace(',,', '', $valores . ',');

        $sql = "INSERT INTO " . $this->_tabla . " (" . $atributosFinal . ") ";
        $sql .= " VALUES (" . $valoresFinal . ")";


        return $this->_bd->ejecutar($sql, $parametros);
    }

    public function actualizar($data, $condicion = null, $parametros = null) {
        $valores = '';

        $parametrosFinal = [];

        foreach ($data as $key => $value) {
            $valores .= "$key = :$key,";
            $parametrosFinal[':' . $key] = $value;
        }
        if (isset($parametros) && is_array($parametros)) {
            foreach ($parametros as $key => $value) {
                $parametrosFinal[':' . $key] = $value;
            }
        }

        $valoresFinal = str_replace(',,', '', $valores . ',');
        
        $sql = "UPDATE " . $this->_tabla;
        $sql .= " SET " . $valoresFinal . " ";

        if (!empty($condicion)) {
            $sql .= $condicion;
        }
        
        return $this->_bd->ejecutar($sql, $parametrosFinal);
    }

    public function eliminar(string $condicion, $parametros = null) {
        $sql = "DELETE FROM " . $this->_tabla . " " . $condicion;

        return $this->_bd->ejecutar($sql, $parametros);
    }

}
