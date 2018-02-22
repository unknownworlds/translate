<?php

namespace App\LanguageFileHandling;

use \Exception;

class OutputHandlerFactory {

	public static function getFileHandler($handlerType, $project, $translations) {
		switch ( $handlerType ) {
            case 'SimpleJsonObject':
                return new SimpleJsonObjectOutputHandler( $project, $translations );
			case 'Manual':
				return new ManualOutputHandler( $project, $translations );
			case 'SteamAchievements':
				return new SteamAchievementsOutputHandler( $project, $translations );
			default:
				throw new Exception( 'Undefined output handler.' );
		}
	}
}