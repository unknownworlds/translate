<?php

namespace App\LanguageFileHandling;

use PhpSpec\Exception\Exception;

class InputHandlerFactory {
	private $handlerType;

	/**
	 * @param $handlerType
	 */
	public function __construct( $handlerType ) {
		$this->handlerType = $handlerType;
	}

	public function getFileHandler() {
		switch ( $this->handlerType ) {
			case 'SimpleJsonObject':
				return new SimpleJsonObjectInputHandler();
			default:
				throw new Exception( 'Undefined input handler.' );
		}
	}
}