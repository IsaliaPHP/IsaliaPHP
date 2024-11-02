<?php

/**
 * Trait UnionTrait
 * @author nelson rojas
 * @abstract
 * Trait para la construcción de cláusulas UNION en consultas SQL
 */
trait UnionTrait {
    private $_union = [];

    /**
     * Agrega una consulta a la cláusula UNION
     * @param SqlBuilder $query
     * @return self
     */
    public function union(SqlBuilder $query) {
        $this->_union[] = $query;
        return $this;
    }

    /**
     * Aplica las cláusulas UNION a la consulta
     * @param string $sql
     * @return string
     */
    protected function applyUnions($sql) {
        if (!empty($this->_union)) {
            $unionQueries = array_map(function($query) {
                return $query->toSql();
            }, $this->_union);
            $sql = "({$sql}) UNION " . implode(' UNION ', $unionQueries);
        }
        return $sql;
    }
}