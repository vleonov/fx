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
        $this->registerPlugin("modifier", "date", array($this, 'modifierDate'));
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

    public function modifierDate($timestamp, $formatShort = 'H:i', $formatFull = 'd.m.Y H:i')
    {
        static $today, $yesterday;

        if (!$today) {
            $today = strtotime(date('Y-m-d 00:00:00'));
            $yesterday = strtotime(date('Y-m-d 00:00:00') . ' -1day');
        }

        if ($timestamp > $today) {
            $result = 'Сегодня ' . date($formatShort, $timestamp);
        } elseif ($timestamp > $yesterday) {
            $result = 'Вчера ' . date($formatShort, $timestamp);
        } else {
            $result = date($formatFull, $timestamp);
        }

        return $result;
    }
}