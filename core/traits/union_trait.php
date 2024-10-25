<?php

trait UnionTrait {
    private $_union = [];

    public function union(SqlBuilder $query) {
        $this->_union[] = $query;
        return $this;
    }

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