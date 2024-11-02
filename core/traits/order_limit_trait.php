<?php

/**
 * Trait OrderLimitTrait
 * @author nelson rojas
 * @abstract
 * Trait para la construcción de cláusulas ORDER BY y LIMIT en consultas SQL
 */
trait OrderLimitTrait {
    private $_orders = [];
    private $_limit;
    private $_offset;

    /**
     * Agrega una cláusula ORDER BY a la consulta
     * @param string $column
     * @param string $direction
     * @return self
     */
    public function orderBy($column, $direction = 'ASC') {
        $this->_orders[] = "$column $direction";
        return $this;
    }

    /**
     * Agrega una cláusula LIMIT a la consulta
     * @param int $limit
     * @return self
     */
    public function limit($limit) {
        $this->_limit = $limit;
        return $this;
    }

    /**
     * Agrega una cláusula OFFSET a la consulta
     * @param int $offset
     * @return self
     */ 
    public function offset($offset) {
        $this->_offset = $offset;
        return $this;
    }

    /**
     * Obtiene las cláusulas ORDER BY de la consulta
     * @return string
     */
    protected function getOrderByClause() {
        return empty($this->_orders) ? '' : ' ORDER BY ' . implode(', ', $this->_orders);
    }

    /**
     * Obtiene las cláusulas LIMIT y OFFSET de la consulta
     * @return string
     */
    protected function getLimitClause() {
        $clause = '';
        if ($this->_limit !== null && !empty($this->_limit)) {
            $clause .= " LIMIT {$this->_limit}";
            if ($this->_offset !== null) {
                $clause .= " OFFSET {$this->_offset}";
            }
        }
        return $clause;
    }
}
