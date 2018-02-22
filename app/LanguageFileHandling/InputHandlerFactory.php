<?php

namespace App\LanguageFileHandling;

use \Exception;

class InputHandlerFactory {

	public static function getFileHandler($handlerType) {
		switch ( $handlerType ) {
			case 'SimpleJsonObject':
				return new SimpleJsonObjectInputHandler();
			case 'SteamAchievements':
				return new SteamAchievementsInputHandler();
			default:
				throw new Exception( 'Undefined input handler.' );
		}
	}
}