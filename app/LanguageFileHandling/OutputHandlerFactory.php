<?php

namespace App\LanguageFileHandling;

use \Exception;

class OutputHandlerFactory {

	public static function getFileHandler($handlerType, $project, $translations) {
		switch ( $handlerType ) {
			case 'SimpleJsonObject':
				return new SimpleJsonObjectOutputHandler( $project, $translations );
			default:
				throw new Exception( 'Undefined input handler.' );
		}
	}
}