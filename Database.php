<?php

class Database {

    /**
     * @var PDO
     */
    protected $_connection;

    /**
     * @var Database
     */
    static protected $_instance;

    protected function __construct($dsn, $user, $password)
    {
        $this->_connection = new PDO($dsn, $user, $password);
        $this->_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->_connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->_connection->exec('SET NAMES "UTF8"');
    }

    public static function get()
    {
        $dbConfig = Config()->db;
        if (!self::$_instance) {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s',
                $dbConfig['host'],
                $dbConfig['name']
            );
            self::$_instance = new self($dsn, $dbConfig['user'], $dbConfig['password']);
        }

        return self::$_instance;
    }

    public function escape($value)
    {
        return $this->_connection->quote($value);
    }

    public function exec($sql)
    {
        return $this->_connection->exec($sql);
    }

    public function query($sql)
    {
        return $this->_connection->query($sql);
    }

    public function begin()
    {
        return $this->_connection->beginTransaction();
    }

    public function commit()
    {
        return $this->_connection->commit();
    }

    public function rollback()
    {
        return $this->_connection->rollBack();
    }

    public function getLastError()
    {
        return $this->_connection->errorInfo();
    }

    public function getLastId()
    {
        return $this->_connection->lastInsertId();
    }

    public function castValue($v)
    {

        if (is_null($v)) {
            $v = 'NULL';
        } elseif (is_array($v)) {
            $v = $this->arrayEncode($v);
        } else {
            switch (gettype($v)) {
                case 'integer':
                case 'double':
                    break;
                case 'boolean':
                    $v = $v ? 'TRUE' : 'FALSE';
                    break;
                case 'string':
                    $v = $this->_connection->quote($v);
                    break;
            }
        }

        return $v;
    }

    public function listDecode($value)
    {
        return array_filter(explode(',', $value));
    }

    public function arrayEncode($arr)
    {
        return "'," . implode(',', $arr) . ",'";
    }
}