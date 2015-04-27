<?php
/**
 * Created by PhpStorm.
 * User: Ace
 * Date: 2015-04-09
 * Time: 01:35
 */

namespace App\LanguageFileHandling;

use App\BaseString;
use App\Log;
use App\String;

class DiffHandler {
	/**
	 * @var array
	 */
	private $input;
	/**
	 * @var int
	 */
	private $projectId;

	/**
	 * @param array $input
	 * @param int $projectId
	 */
	public function __construct( array $input, $projectId ) {
		$this->input     = $input;
		$this->projectId = $projectId;

		$this->process();
	}

	private function process() {
		// Scaffold
		$baseStrings = BaseString::where( 'project_id', '=', $this->projectId )->lists( 'text', 'key' );

		// Remove old strings
		$unusedKeys = array_diff_key( $baseStrings, $this->input );
		foreach ( $unusedKeys as $key => $text ) {
			BaseString::where( [
				'key'        => $key,
				'project_id' => $this->projectId
			] )->firstOrFail()->delete();

			$this->log('String ' . $key . ' was deleted');
		}

		// Add new strings
		$newKeys = array_diff_key( $this->input, $baseStrings );
		foreach ( $newKeys as $key => $text ) {
			BaseString::create( [
				'key'        => $key,
				'text'       => $text,
				'project_id' => $this->projectId
			] );

			$this->log('String ' . $key . ' added');
		}

		// Update current strings
		$baseStrings = BaseString::where( 'project_id', '=', $this->projectId )->lists( 'text', 'key' );
		$updatedKeys = array_diff( $this->input, $baseStrings );
		foreach ( $updatedKeys as $key => $text ) {
			$baseString = BaseString::where( [
				'key'        => $key,
				'project_id' => $this->projectId
			] )->firstOrFail();

			$baseString->update( [
				'text' => $text
			] );

			// Remove related translations
			String::where( [
				'base_string_id' => $baseString->id
			] )->delete();

			$this->log('String ' . $key . ' changed');
		}
	}

	private function log( $text ) {
		Log::create( [
			'project_id' => $this->projectId,
			'user_id'    => 1,
			'text'       => $text
		] );
	}
}