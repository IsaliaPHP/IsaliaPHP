<?php

trait ConditionTrait {
    private $_conditions = [];

    public function where($condition) {
        $this->_conditions[] = $condition;
        return $this;
    }

    protected function getWhereClause() {
        return empty($this->_conditions) ? '' : ' WHERE ' . implode(' AND ', $this->_conditions);
    }
}