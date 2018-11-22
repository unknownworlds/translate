<?php

namespace App\LanguageFileHandling\InputHandlers;

use \Exception;

class InputHandlerFactory {

	const SIMPLE_JSON = 1;
	const MANUAL = 2;
	const STEAM_ACHIEVEMENTS = 3;

	public static $availableHandlers = [
		self::SIMPLE_JSON        => 'Simple JSON',
		self::MANUAL             => 'Manual',
		self::STEAM_ACHIEVEMENTS => 'Steam achievements',
	];

	public static function getFileHandler( $handlerType ) {
		switch ( $handlerType ) {
			case self::SIMPLE_JSON:
				return new SimpleJsonObjectInputHandler();
			case self::STEAM_ACHIEVEMENTS:
				return new SteamAchievementsInputHandler();
			default:
				throw new Exception( 'Undefined input handler.' );
		}
	}
}