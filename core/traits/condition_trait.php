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
     * Agrega una condición AND a la consulta
     * @param string $condition
     * @return self
     */
    public function where($condition) {
        if (!empty($this->_conditions)) {
            $this->_conditions[] = ['AND', $condition];
        } else {
            $this->_conditions[] = ['', $condition];
        }
        return $this;
    }

    /**
     * Agrega una condición OR a la consulta
     * @param string $condition
     * @return self
     */
    public function orWhere($condition) {
        $this->_conditions[] = ['OR', $condition];
        return $this;
    }

    /**
     * Obtiene las cláusulas WHERE de la consulta
     * @return string
     */
    protected function getWhereClause() {
        if (empty($this->_conditions)) {
            return '';
        }

        $sql = ' WHERE ';
        foreach ($this->_conditions as $i => $condition) {
            if ($i > 0) {
                $sql .= " {$condition[0]} ";
            }
            $sql .= $condition[1];
        }
        return $sql;
    }
}