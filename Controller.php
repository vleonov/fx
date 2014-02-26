<?php

class Controller
{
    public function __construct()
    {
        $host = U_Url::host();
        $base = Config()->base;
        $baseHref = '//' . $host . U_Misc::is($base[$host], '') . '/';

        $r = array(
            'BaseHref' => $baseHref,
        );

        Response()->assign($r);
    }
}