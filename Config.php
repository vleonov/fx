<?php

/**
 * Shortcut for Config::getInstance()
 * @return Config
 */
function Config()
{
    return Config::getInstance();
}

class Config
{
    static $_instances;

    private $_data = array();

    private function __construct($filename)
    {
        $this->_data = include $filename;
    }

    public static function getInstance($filename = null)
    {
        if (is_null($filename)) {
            return reset(self::$_instances);
        } elseif (!is_null(self::$_instances[$filename])) {
            return self::$_instances[$filename];
        }

        if (!file_exists($filename)) {
            throw new Exception("Config file doesn't exist: " . $filename);
        }

        self::$_instances[$filename] = new self($filename);

        return self::$_instances[$filename];
    }

    public function __get($key)
    {
        if (isset($this->_data[$key])) {
            return $this->_data[$key];
        } else {
            return array();
        }
    }

}