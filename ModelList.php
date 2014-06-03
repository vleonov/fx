<?php

abstract class ModelList implements Iterator {

    public $length = 0;

    protected $_tblName;
    protected $_modelClass;

    protected $_data = array();
    protected $_cursor = 0;

    public function __construct(array $criterias = array(), array $orders = array('id ASC'), $limit = null)
    {
//        if (empty($criterias)) {
//            return null;
//        }
        $oDb = Database::get();

        $conditions = array();
        foreach ($criterias as $k=>$v) {
            if (is_int($k)) {
                $k = '';
            } elseif (is_null($v)) {
                $v = ' IS NULL';
            } elseif (is_array($v)) {
                $vs = array();
                foreach ($v as $vi) {
                    $vs[] = $oDb->castValue($vi);
                }
                $v = ' IN (' . implode(',', $vs) . ')';
            } else {
                $v = '=' . $oDb->castValue($v);
            }
            $conditions[] = $k . $v;
        }

        $sql = 'SELECT * FROM %s WHERE %s ORDER BY %s LIMIT %d';
        $sql = sprintf(
            $sql,
            $this->_tblName,
            implode(' AND ', $conditions),
            implode(', ', $orders),
            $limit ? $limit : 1e3
        );

        $res = $oDb->query($sql);
        $this->length = $res->rowCount();
        if (!$this->length) {
            return true;
        }

        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
            $modelClass = 'M_' . $this->_modelClass;
            $model = new $modelClass;
            /** @var $model Model */
            $model->fromArray($row);
            $this->_data[] = $model;
        }
    }

    final public function current()
    {
        return $this->_data[$this->_cursor];
    }

    final public function key()
    {
        return $this->_cursor;
    }

    final public function next()
    {
        $this->_cursor++;
    }

    final public function rewind()
    {
        $this->_cursor = 0;
    }

    final public function valid()
    {
        return isset($this->_data[$this->_cursor]);
    }

    final public function sizeof()
    {
        return sizeof($this->_data);
    }
}