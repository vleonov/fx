<?php

class Database {

    const TYPE_INTEGER = 'integer';
    const TYPE_DOUBLE = 'double';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_STRING = 'string';
    const TYPE_JSON = 'json';
    const TYPE_ARRAY = 'array';
    const TYPE_TIMESTAMP = 'timestamp';

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

    public function castValue($v, $type = null)
    {

        if (is_null($v)) {
            $v = 'NULL';
        } else {
            switch ($type ? $type : gettype($v)) {
                case self::TYPE_INTEGER:
                    $v = intval($v);
                    break;
                case self::TYPE_DOUBLE:
                    $v = floatval($v);
                    break;
                case self::TYPE_BOOLEAN:
                    $v = $v ? 'TRUE' : 'FALSE';
                    break;
                case self::TYPE_STRING:
                    $v = $this->_connection->quote($v);
                    break;
                case self::TYPE_TIMESTAMP:
                    $v = $this->_connection->quote(date(DATE_W3C, $v));
                    break;
                case self::TYPE_JSON:
                    $v = $this->_connection->quote(json_encode($v));
                    break;
                case self::TYPE_ARRAY:
                    $v = "'," . implode(',', $v) . ",'";
                    break;
            }
        }

        return $v;
    }

    public function parseValue($v, $type = null)
    {
        switch ($type) {
            case self::TYPE_TIMESTAMP:
                $v = strtotime($v);
                break;
            case self::TYPE_BOOLEAN:
                $v = (bool) $v;
                break;
            case self::TYPE_JSON:
                $v = json_decode($v, true);
                break;
            case self::TYPE_ARRAY:
                $v = array_filter(explode(',', $v));
                break;
        }

        return $v;
    }
}