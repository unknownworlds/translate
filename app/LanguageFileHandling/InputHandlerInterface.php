<?php

namespace App\LanguageFileHandling;

interface InputHandlerInterface {

	/**
	 *
	 */
	public function __construct();

	/**
	 * @return array
	 */
	public function getParsedInput();

}