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

    public static function rddir($dirname, $parentdir = null)
    {
        $result = array();
        $dd = opendir($dirname);

        while ($file = readdir($dd)) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            if (is_dir($dirname . '/' . $file)) {
                $result = array_merge(
                    $result,
                    self::rddir($dirname . '/' . $file, ($parentdir ? $parentdir . '/' : '') . $file)
                );
            } else {
                $result[] = ($parentdir ? $parentdir . '/' : '') . $file;
            }
        }

        return $result;
    }
}