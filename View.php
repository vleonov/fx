<?php

class View extends Smarty
{

    public function __construct()
    {
        parent::__construct();

        $this->setTemplateDir(Config()->dir['view'])
            ->setCompileDir(Config()->dir['var_tmp']);
        $this->force_compile = Config()->view['forceCompile'];
        $this->compile_check = Config()->view['compileCheck'];

        $this->registerPlugin("modifier", "size", array($this, 'modifierSize'));
    }

    public function modifierSize($size)
    {
        $kbyte = 1024;
        $mbyte = $kbyte * 1024;
        $gbyte = $mbyte * 1024;

        if ($size < $kbyte) {
            $measure = 'b';
        } elseif ($size < $mbyte) {
            $measure = 'Kb';
            $size = round($size / $kbyte, 2);
        } elseif ($size < $gbyte) {
            $measure = 'Mb';
            $size = round($size / $mbyte, 2);
        } else {
            $measure = 'Gb';
            $size = round($size / $gbyte, 2);
        }

        return $size . $measure;
    }
}