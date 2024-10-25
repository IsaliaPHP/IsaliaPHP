<?php

trait GroupByTrait {
    private $_groupBy = [];

    public function groupBy($columns) {
        $this->_groupBy = is_array($columns) ? $columns : func_get_args();
        return $this;
    }

    protected function getGroupByClause() {
        if (empty($this->_groupBy)) {
            return '';
        }
        return ' GROUP BY ' . implode(', ', $this->_groupBy);
    }
}