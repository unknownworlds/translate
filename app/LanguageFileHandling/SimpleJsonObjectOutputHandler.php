<?php
/**
 * Copyright (c) Unknown Worlds Entertainment, 2016. 
 * Created by Lukas Nowaczek <lukas@unknownworlds.com> <@lnowaczek>
 * Visit http://unknownworlds.com/
 * This file is a part of proprietary software. 
 */

namespace App\LanguageFileHandling;

use File;

class SimpleJsonObjectOutputHandler implements OutputHandlerInterface {
	private $project, $translations;

	public function __construct( $project, $translations ) {
		$this->project      = $project;
		$this->translations = $translations;
	}

	public function getOutputFile() {
		$dir = public_path() . '/output/' . $this->project->id . '/' . time();
		mkdir( $dir, 0777, true );

		foreach ( $this->translations as $language ) {

			$output = $language['strings'];
			$output = json_encode( $output, JSON_PRETTY_PRINT );
			//$output = str_replace( '\n', "\n", $output );

			$file = fopen( $dir . '/' . $language['name'] . '.json', 'w+' );
			fputs( $file, $output );
			fclose( $file );
		}

		$outputFile = public_path() . '/output/' . $this->project->id . '/output.zip';
		if ( is_file( $outputFile ) ) {
			unlink( $outputFile );
		}

		exec( 'cd ' . public_path() . '/output/' . $this->project->id . ' && zip -j output.zip ' . $dir . '/*' );

		File::deleteDirectory( $dir );

		return $outputFile;
	}
}