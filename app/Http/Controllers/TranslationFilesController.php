<?php namespace App\Http\Controllers;

use App\BaseString;
use App\Language;
use App\LanguageFileHandling\DiffHandler;
use App\LanguageFileHandling\InputHandlerFactory;
use App\Project;
use App\String;
use File;
use Request;
use Response;

class TranslationFilesController extends BaseApiController {

	public function __construct() {
		parent::__construct( 200 );
	}

	public function storeInputFile() {
		// get project by api key
		$project = Project::where( [ 'api_key' => Request::get( 'api_key' ) ] )->firstOrFail();

		// init input handler
		$inputHandler = new InputHandlerFactory( $project->file_handler );
		$inputHandler = $inputHandler->getFileHandler();

		// pass reader results to diff handler
		$input = $inputHandler->getParsedInput();

		if ( $input == null ) {
			return $this->respond( 'Fail, probably the input file is corrupted' );
		}
		new DiffHandler( $inputHandler->getParsedInput(), $project->id );

		return $this->respond( 'Success!' );
	}

	/*
	 * TODO: Extract! Refactor!
	 */
	public function processOutputFiles() {
		// get project by api key
		$project = Project::where( [ 'api_key' => Request::get( 'api_key' ) ] )->firstOrFail();

		$dir = public_path() . '/output/' . $project->id . '/' . time();
		mkdir( $dir, 0777, true );

		$baseStrings = BaseString::where( 'project_id', '=', $project->id )->lists( 'text', 'key' )->toArray();
		$languages   = Language::all();

		foreach ( $languages as $language ) {
			$translatedStrings = String::join( 'base_strings', 'strings.base_string_id', '=', 'base_strings.id' )
			                           ->where( 'strings.project_id', '=', $project->id )
			                           ->where( 'language_id', '=', $language->id )
			                           ->where( 'is_accepted', '=', true )
			                           ->get( [ 'key', 'strings.text' ] )
			                           ->lists( 'text', 'key' )
			                           ->toArray();

			$output = array_merge( $baseStrings, $translatedStrings );

			$file = fopen( $dir . '/' . $language->name . '.json', 'w+' );
			fputs( $file, json_encode( $output, JSON_PRETTY_PRINT ) );
			fclose( $file );
		}

		$outputFile = public_path() . '/output/' . $project->id . '/output.zip';
		if ( is_file( $outputFile ) ) {
			unlink( $outputFile );
		}

//		exec( 'rm ' . $outputFile );
		exec( 'cd ' . public_path() . '/output/' . $project->id . ' && zip -j output.zip ' . $dir . '/*' );

		File::deleteDirectory( $dir );

		return Response::download( $outputFile );
	}

}
