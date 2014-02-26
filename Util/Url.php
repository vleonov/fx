<?php

class U_Url
{
    public static function host($url = null)
    {
        return U_Misc::is($_SERVER['HTTP_HOST']);
    }

    public static function base()
    {
       $host = self::host();
       $base = '//' . $host . U_Misc::is(Config()->base[$host], '');

       return $base;
    }

    public static function query($url = null)
    {
        return self::_part($url, PHP_URL_QUERY);
    }

    public static function path($url = null)
    {
        return self::_part($url, PHP_URL_PATH);
    }

    protected static function _part($url, $part)
    {
        $url = $url ? $url : U_Misc::is($_SERVER['REQUEST_URI']);
        return parse_url($url, $part);
    }
}