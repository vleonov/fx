<?php

abstract class Model {

    protected $_idIsInt = true;
    protected $_id;
    protected $_data = array();

    protected $_tblName;

    /**
     * @var Database
     */
    protected $_oDb;

    public function __construct($id = null)
    {
        $this->_oDb = Database::get();

        if (!is_null($id)) {
            $this->_getById($id);
        }
    }

    public function __set($property, $value)
    {
        if ($property != 'id') {
            $this->_data[$property] = $value;
        }
    }

    public function __get($property)
    {
        if ($property == 'id') {
            return $this->_id;
        } else {
            return isset($this->_data[$property]) ? $this->_data[$property] : null;
        }
    }

    public function __isset($property)
    {
        if ($property == 'id') {
            return true;
        } else {
            return isset($this->_data[$property]);
        }
    }

    public function save()
    {
        if ($this->_id) {
            $values = array();
            foreach ($this->_data as $k=>$v) {
                $values[] = $k . '=' . $this->_oDb->castValue($v);
            }

            $sql = 'UPDATE %s SET %s WHERE id=%s';
            $sql = sprintf(
                $sql,
                $this->_tblName,
                implode(', ', $values),
                $this->_idIsInt ? intval($this->_id) : $this->_oDb->castValue(strval($this->_id))
            );
        } else {
            $columns = $values = array();
            foreach ($this->_data as $k=>$v) {
                $columns[] = $k;
                $values[] = $this->_oDb->castValue($v);
            }

            $sql = 'INSERT INTO %s (%s) VALUES (%s)';
            $sql = sprintf(
                $sql,
                $this->_tblName,
                implode(', ', $columns),
                implode(', ', $values)
            );
        }

        $this->_oDb->query($sql);

        $this->_id = $this->_id ? $this->_id : $this->_oDb->getLastId();

        return $this->_id;
    }

    public function fromArray(array $data)
    {
        if (isset($data['id'])) {
            $this->_id = $data['id'];
            unset($data['id']);
        }
        $this->_data = $data;

        return $this;
    }

    public function toArray()
    {
        return $this->_data;
    }

    protected function _getById($id)
    {
        $sql = 'SELECT * FROM %s WHERE id=%s';
        $sql = sprintf(
            $sql,
            $this->_tblName,
            $this->_idIsInt ? intval($id) : $this->_oDb->castValue(strval($id))
        );

        $res = $this->_oDb->query($sql);
        if (!$res->rowCount()) {
            return false;
        }

        $this->fromArray($res->fetch());
        return true;
    }
}