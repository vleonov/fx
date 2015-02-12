<?php

class Bootstrap
{
    /**
     * @var Config
     */
    private static $config;

    public static function run($configFilename)
    {

        // автозагрузчик классов
        spl_autoload_register('Bootstrap::autoload');

        require_once dirname(__FILE__) . '/Request.php';
        require_once dirname(__FILE__) . '/Response.php';
        require_once dirname(__FILE__) . '/Session.php';
        require_once dirname(__FILE__) . '/Config.php';

        self::$config = Config::getInstance($configFilename);

        if (PHP_SAPI != 'cli') {
            $oRouter = new Router();

            $callback = $oRouter->proceed();
            $oResponse = $callback ? call_user_func($callback) : Response()->error404();

            echo $oResponse;
        }
    }

    public static function autoload($className)
    {
        $replaces = array(
            'C_' => 'Controller_',
            'M_' => 'Model_',
            'L_' => 'ModelList_',
            'U_' => 'Util_',
        );
        foreach ($replaces as $from=>$to) {
            $className = preg_replace('/^' . $from . '/', $to, $className);
        }

        $fileName = self::$config->dir['root'] . '/lib/' . str_replace('_', '/', $className) . '.php';
        $fxFileName = dirname(__FILE__) . '/' . str_replace('_', '/', $className) . '.php';

        if (file_exists($fileName)) {
            require_once $fileName;
        } elseif (file_exists($fxFileName)) {
            require_once $fxFileName;
        }
    }
}