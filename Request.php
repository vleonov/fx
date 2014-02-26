<?php

/**
 * Shortcut for Request::getInstance()
 * @return Request
 */
function Request()
{
    return Request::getInstance();
}

class Request
{
    static $_instance;

    protected $_args = array();

    protected function __construct()
    {

    }

    public static function getInstance()
    {
        if (!is_null(self::$_instance)) {
            return self::$_instance;
        }

        self::$_instance = new self();

        return self::$_instance;
    }

    public function setArgs(array $args)
    {
        $this->_args = $args;

        return $this;
    }

    public function args($index = null, $default = null)
    {
        if (is_null($index)) {
            return $this->_args;
        } else {
            return U_Misc::is($this->_args[$index], $default);
        }
    }

    public function get($key, $default = null)
    {
        return U_Misc::is($_GET[$key], $default);
    }

    public function isPost()
    {
        return U_Misc::is($_SERVER['REQUEST_METHOD']) == 'POST';
    }

    public function post($key, $default = null)
    {
        return U_Misc::is($_POST[$key], $default);
    }

    public function file($key, $field = '')
    {
        if (!empty($_FILES[$key])
            && empty($_FILES[$key]['error'])
            && !empty($_FILES[$key]['name'])
            && file_exists($_FILES[$key]['tmp_name'])
        ) {
            return $_FILES[$key][$field];
        } else {
            return null;
        }
    }

    public function rawFile()
    {
        return file_get_contents(STDIN);
    }

    public function cookie($key, $default = null)
    {
        return U_Misc::is($_COOKIE[$key], $default);
    }

    public function isAJAX()
    {
        return U_Misc::is($_SERVER['HTTP_X_REQUESTED_WITH']) == 'XMLHttpRequest';
    }

    public function __get($key)
    {
        return U_Misc::is($_REQUEST[$key]);
    }

    public function backUrl()
    {
        if ($this->backUrl) {
            return $this->backUrl;
        } elseif (!empty($_SERVER['HTTP_REFERER'])) {
            return $_SERVER['HTTP_REFERER'];
        } else {
            return PROJECT_HOST;
        }
    }
}