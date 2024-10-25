<?php

trait HavingTrait {
    private $_having = [];

    public function having($condition) {
        $this->_having[] = $condition;
        return $this;
    }

    protected function getHavingClause() {
        if (empty($this->_having)) {
            return '';
        }
        return ' HAVING ' . implode(' AND ', $this->_having);
    }
}