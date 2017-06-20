<?php

namespace App\LanguageFileHandling;

use Request;

class SimpleJsonObjectInputHandler implements InputHandlerInterface {
	private $rawData;
	private $baseStrings;

	public function __construct() {
		$this->rawData = Request::get( 'data' );
		$this->processInput();
	}

	/**
	 * @return bool
	 */
	private function processInput() {
		$output = $this->rawData;
		// Remove whitespace from string
		$output = trim( $output );
		// Remove whitespace from the start of the JSON object
		// No need to remove // (comments) - they're removed by python script
		$output = preg_replace( '/{(\s+")/im', '{ "', $output );
		// Remove whitespace from the end of the doc
		$output = preg_replace( '/"(\s)+}/im', '" }', $output );
		// Remove whitespace between entries
        $output = preg_replace( '/"\s*,\s*"/im', '", "', $output );
		// Replace remaining newlines with <br>
		$output = str_replace( array( "\r\n", "\r", "\n" ), "<br>", $output );
		// Remove tab character
		$output = preg_replace( '/\t/im', ' ', $output );
		// Decode JSON
		$output = json_decode( $output, true );
		// Bring back the newline char
		foreach ( $output as $key => $value ) {
			$output[ $key ] = str_replace( '<br>', "\n", $value );
		}

		$this->baseStrings = $output;
	}

	/**
	 * @return array
	 */
	public function getParsedInput() {
		return $this->baseStrings;
	}
}