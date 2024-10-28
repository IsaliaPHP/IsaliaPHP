<?php

/**
 * Trait GroupByTrait
 * @author nelson rojas
 * @abstract
 * Trait para la construcción de cláusulas GROUP BY en consultas SQL
 */
trait GroupByTrait {
    private $_groupBy = [];

    /**
     * Agrega una cláusula GROUP BY a la consulta
     * @param mixed $columns
     * @return self
     */
    public function groupBy($columns) {
        $this->_groupBy = is_array($columns) ? $columns : func_get_args();
        return $this;
    }

    /**
     * Obtiene las cláusulas GROUP BY de la consulta
     * @return string
     */
    protected function getGroupByClause() {
        if (empty($this->_groupBy)) {
            return '';
        }
        return ' GROUP BY ' . implode(', ', $this->_groupBy);
    }
}