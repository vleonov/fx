<?php

class U_Misc
{

    public static function is(&$v, $default = null)
    {
        return isset($v) ? $v : $default;
    }

    public static function mkdir($dirname)
    {
        if (file_exists($dirname)) {
            return true;
        }

        mkdir($dirname, 0777, true);

        return true;
    }
}