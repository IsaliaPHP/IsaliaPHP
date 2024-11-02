<?php

/**
 * Trait HavingTrait
 * @author nelson rojas
 * @abstract
 * Trait para la construcción de cláusulas HAVING en consultas SQL
 */
trait HavingTrait {
    private $_having = [];

    /**
     * Agrega una cláusula HAVING a la consulta
     * @param string $condition
     * @return self
     */
    public function having($condition) {
        $this->_having[] = $condition;
        return $this;
    }

    /**
     * Obtiene las cláusulas HAVING de la consulta
     * @return string
     */
    protected function getHavingClause() {
        if (empty($this->_having)) {
            return '';
        }
        return ' HAVING ' . implode(' AND ', $this->_having);
    }
}