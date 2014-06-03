<?php

class U_Image
{
    public static $availableTypes = array(
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/gif',
    );

    public static function is($filename)
    {
        if (!file_exists($filename)) {
            return false;
        }

        $info = getimagesize($filename);
        if (empty($info['mime']) || !in_array($info['mime'], self::$availableTypes)) {
            return false;
        }

        return true;
    }

    public static function resize($from, $to, $width, $height)
    {
        if (!U_Image::is($from)) {
            return false;
        }

        $info = getimagesize($from);

        $fromWidth = $info[0];
        $fromHeight = $info[1];

        $toWidth = min($width, $fromWidth);
        $toHeight = min($height, $fromHeight);

        $diffWidth = abs($fromWidth - $toWidth);
        $diffHeight = abs($fromHeight - $toHeight);

        if ($diffWidth > $diffHeight) {
            $ratio = $fromWidth / $toWidth;
            $width = $toWidth;
            $height = round($fromHeight / $ratio);
        } else {
            $ratio = $fromHeight / $toHeight;
            $width = round($fromWidth / $ratio);
            $height = $toHeight;
        }

        switch ($info['mime']) {
            case 'image/jpeg':
            case 'image/jpg':
                $imageFrom = imagecreatefromjpeg($from);
                break;
            case 'image/png':
                $imageFrom = imagecreatefrompng($from);
                break;
            case 'image/gif':
                $imageFrom = imagecreatefromgif($from);
                break;
        }

        $imageTo = imagecreatetruecolor($width, $height);
        imagecopyresampled(
            $imageTo,
            $imageFrom,
            0,
            0,
            0,
            0,
            $width,
            $height,
            $fromWidth,
            $fromHeight
        );

        switch ($info['mime']) {
            case 'image/jpeg':
            case 'image/jpg':
                $res = imagejpeg($imageTo, $to, 100);
                break;
            case 'image/png':
                $res = imagepng($imageTo, $to, 100);
                break;
            case 'image/gif':
                $res = imagegif($imageTo, $to);
                break;
        }

        return $res;
    }
}