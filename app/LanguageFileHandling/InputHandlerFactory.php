<?php

namespace App\LanguageFileHandling;

use \Exception;

class InputHandlerFactory {

	public static function getFileHandler($handlerType) {
		switch ( $handlerType ) {
			case 'SimpleJsonObject':
				return new SimpleJsonObjectInputHandler();
			default:
				throw new Exception( 'Undefined input handler.' );
		}
	}
}