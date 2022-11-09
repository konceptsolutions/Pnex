<?php

namespace App\Services;

class Settings
{
    public static $theme = 'dark-theme';
    public static function setTheme($theme)
    {
        self::$theme = $theme;
    }
}
