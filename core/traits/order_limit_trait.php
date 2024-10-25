<?php

trait OrderLimitTrait {
    private $_orders = [];
    private $_limit;
    private $_offset;

    public function orderBy($column, $direction = 'ASC') {
        $this->_orders[] = "$column $direction";
        return $this;
    }

    public function limit($limit) {
        $this->_limit = $limit;
        return $this;
    }

    public function offset($offset) {
        $this->_offset = $offset;
        return $this;
    }

    protected function getOrderByClause() {
        return empty($this->_orders) ? '' : ' ORDER BY ' . implode(', ', $this->_orders);
    }

    protected function getLimitClause() {
        $clause = '';
        if ($this->_limit !== null) {
            $clause .= " LIMIT {$this->_limit}";
            if ($this->_offset !== null) {
                $clause .= " OFFSET {$this->_offset}";
            }
        }
        return $clause;
    }
}
