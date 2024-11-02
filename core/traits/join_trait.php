<?php

/**
 * Trait JoinTrait
 * @author nelson rojas
 * @abstract
 * Trait para la construcción de cláusulas JOIN en consultas SQL
 */
trait JoinTrait {
    private $_joins = [];

    /**
     * Agrega una cláusula JOIN a la consulta
     * @param string $join
     * @return self
     */
    public function join($join) {
        $this->_joins[] = $join;
        return $this;
    }

    /**
     * Obtiene las cláusulas JOIN de la consulta
     * @return string
     */
    protected function getJoinClause() {
        return implode(' ', $this->_joins);
    }
}