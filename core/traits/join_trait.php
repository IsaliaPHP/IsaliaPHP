<?php
trait JoinTrait {
    private $_joins = [];

    public function join($join) {
        $this->_joins[] = $join;
        return $this;
    }

    protected function getJoinClause() {
        return implode(' ', $this->_joins);
    }
}