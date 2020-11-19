<?php

namespace App\LanguageFileHandling\OutputHandlers;

use \Exception;

class OutputHandlerFactory
{

    const SIMPLE_JSON = 1;
    const STEAM_ACHIEVEMENTS = 2;
    const PREDEFINED_TEMPLATE = 3;
    const INI = 4;

    public static $availableHandlers = [
        self::SIMPLE_JSON => 'Simple JSON',
        self::STEAM_ACHIEVEMENTS => 'Steam achievements',
        self::PREDEFINED_TEMPLATE => 'Predefined template',
        self::INI => 'INI file',
    ];

    public static function getFileHandler($handlerType, $project, $translations)
    {
        switch ($handlerType) {
            case self::SIMPLE_JSON:
                return new SimpleJsonObjectOutputHandler($project, $translations);
            case self::STEAM_ACHIEVEMENTS:
                return new SteamAchievementsOutputHandler($project, $translations);
            case self::PREDEFINED_TEMPLATE:
                return new PredefinedTemplateOutputHandler($project, $translations);
            case self::INI:
                return new INIOutputHandler($project, $translations);
            default:
                throw new Exception('Undefined output handler.');
        }
    }
}