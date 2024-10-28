<?php

/**
 * Trait ConditionTrait
 * @author nelson rojas
 * @abstract
 * Trait para la construcción de condiciones en consultas SQL
 */
trait ConditionTrait {
    private $_conditions = [];

    /**
     * Agrega una condición a la consulta
     * @param string $condition
     * @return self
     */
    public function where($condition) {
        $this->_conditions[] = $condition;
        return $this;
    }

    /**
     * Obtiene las cláusulas WHERE de la consulta
     * @return string
     */
    protected function getWhereClause() {
        return empty($this->_conditions) ? '' : ' WHERE ' . implode(' AND ', $this->_conditions);
    }
}