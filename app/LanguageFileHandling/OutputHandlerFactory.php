<?php
/**
 * Copyright (c) Unknown Worlds Entertainment, 2016. 
 * Created by Lukas Nowaczek <lukas@unknownworlds.com> <@lnowaczek>
 * Visit http://unknownworlds.com/
 * This file is a part of proprietary software. 
 */

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