<?php

require_once(ROOT_DIR . '/lib/External/Smarty/Smarty.class.php');

class View extends Smarty
{

    public function __construct()
    {
        parent::__construct();

        $this->setTemplateDir(Config()->dir['view'])
            ->setCompileDir(Config()->dir['var_tmp']);
    }
}