<?php

namespace App\LanguageFileHandling;

use Request;

class SimpleJsonObjectInputHandler implements InputHandlerInterface {
	private $rawData;
	private $baseStrings;

	public function __construct() {
		$this->rawData = Request::get('data');
		$this->processInput();
	}

	/**
	 * @return bool
	 */
	private function processInput() {
		$this->baseStrings = json_decode($this->rawData, true);
	}

	/**
	 * @return array
	 */
	public function getParsedInput() {
		return $this->baseStrings;
	}
}