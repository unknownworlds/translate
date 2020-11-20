<?php

namespace App\LanguageFileHandling\InputHandlers;

use \Exception;

class InputHandlerFactory
{
    const SIMPLE_JSON = 1;
    const MANUAL = 2;
    const STEAM_ACHIEVEMENTS = 3;
    const INI = 4;
    const CSV = 5;

    public static $availableHandlers = [
        self::SIMPLE_JSON        => 'Simple JSON',
        self::MANUAL             => 'Manual',
        self::STEAM_ACHIEVEMENTS => 'Steam achievements',
        self::INI                => 'INI file',
        self::CSV                => 'CSV file',
    ];

    public static function getFileHandler($handlerType, $rawData = null)
    {
        switch ($handlerType) {
            case self::SIMPLE_JSON:
                return new SimpleJsonObjectInputHandler($rawData);
            case self::STEAM_ACHIEVEMENTS:
                return new SteamAchievementsInputHandler($rawData);
            case self::INI:
                return new INIInputHandler($rawData);
            case self::CSV:
                return new CSVInputHandler($rawData);
            default:
                throw new Exception('Undefined input handler.');
        }
    }
}