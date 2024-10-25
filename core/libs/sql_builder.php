<?php
/**
 * SqlBuilder
 * Clase encargada de la construcción de consultas SQL
 * @author nelson rojas
 */
class SqlBuilder {
    use ConditionTrait, JoinTrait, OrderLimitTrait, GroupByTrait, HavingTrait, UnionTrait;

    private $_table;
    private $_columns = ['*'];

    /**
     * Constructor de la clase SqlBuilder
     * @param string $table Nombre de la tabla
     */
    public function __construct($table) {
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
