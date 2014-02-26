<?php

/**
 * Shortcut for Session::getInstance()
 * @return Session
 */
function Session()
{
    return Session::getInstance();
}

class Session
{
    static $_instance;

    protected function __construct()
    {
        session_start();
    }

    public static function getInstance()
    {
        if (!is_null(self::$_instance)) {
            return self::$_instance;
        }

        self::$_instance = new self();

        return self::$_instance;
    }

    public function id()
    {
        return session_id();
    }

    public function get($key, $default = null)
    {
        if (isset($_SESSION[$key])) {
            return unserialize($_SESSION[$key]);
        } else {
            return $default;
        }
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = serialize($value);
    }

    public function delete($key)
    {
        unset($_SESSION[$key]);
    }
}
