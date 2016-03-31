<?php
/**
 * Copyright (c) Unknown Worlds Entertainment, 2016. 
 * Created by Lukas Nowaczek <lukas@unknownworlds.com> <@lnowaczek>
 * Visit http://unknownworlds.com/
 * This file is a part of proprietary software. 
 */

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