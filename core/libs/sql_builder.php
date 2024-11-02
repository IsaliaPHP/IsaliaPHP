<?php
/**
 * SqlBuilder
 * @author nelson rojas
 * @abstract
 * Clase encargada de la construcción de consultas SQL
 */
class SqlBuilder {
    use ConditionTrait, JoinTrait, OrderLimitTrait, GroupByTrait, HavingTrait, UnionTrait;

    private $_table;
    private $_columns = ['*'];
    protected $_parameters = [];

    /**
     * Constructor de la clase SqlBuilder
     * @param string $table Nombre de la tabla
     */
    public function __construct($table) {
        $this->_table = $table;
    }

    /**
     * Establece manualmente el nombre de la tabla
     * @param string $table Nombre de la tabla
     * @return $this
     */
    public function setTableName($table) {
        $this->_table = $table;
    }

    /**
     * Selecciona las columnas de la consulta
     * @param array|string $columns Columnas a seleccionar
     * @return $this
     */
    public function select($columns = ['*']) {
        $this->_columns = is_array($columns) ? $columns : func_get_args();
        return $this;
    }

    /**
     * Pagina la consulta
     * @param int $page Número de página
     * @param int $perPage Cantidad de registros por página
     * @return $this
     */
    public function paginate($page, $perPage) {
        $offset = ($page - 1) * $perPage;
        return $this->limit($perPage)->offset($offset);
    }

    public function getParameters() {
        return $this->_parameters;
    }

    public function setParameters($parameters) {
        $this->_parameters = $parameters;
        return $this;
    }

    public function reset()
    {
        // Reset all query builder properties
        $this->_conditions = [];
        $this->_joins = [];
        $this->_orders = [];
        $this->_limit = '';
        $this->_parameters = [];
        return $this;
    }

    /**
     * Convierte la consulta a una cadena SQL
     * @return string
     */
    public function toSql() {
        $columns = implode(', ', $this->_columns);
        $sql = "SELECT {$columns} FROM {$this->_table}";
        $sql .= $this->getJoinClause();
        $sql .= $this->getWhereClause();
        $sql .= $this->getGroupByClause();
        $sql .= $this->getHavingClause();
        $sql .= $this->getOrderByClause();
        $sql .= $this->getLimitClause();
        
        $sql = $this->applyUnions($sql);
        
        return $sql;
    }

    /**
     * Convierte la consulta a una cadena SQL
     * @return string
     */
    public function __toString() {
        return $this->toSql();
    }
}
