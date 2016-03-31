<?php namespace App\Http\Controllers;

use App\BaseString;
use App\Language;
use App\LanguageFileHandling\DiffHandler;
use App\LanguageFileHandling\InputHandlerFactory;
use App\LanguageFileHandling\OutputHandlerFactory;
use App\Project;
use App\TranslatedString;
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
		$inputHandler = InputHandlerFactory::getFileHandler($project->file_handler);

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

		// get the data
		$languages    = Language::all();
		$baseStrings  = BaseString::where( 'project_id', '=', $project->id )->lists( 'text', 'key' )->toArray();
		$translations = [ ];

		foreach ( $languages as $language ) {
			$translatedStrings = TranslatedString::join( 'base_strings', 'translated_strings.base_string_id', '=', 'base_strings.id' )
			                                     ->where( 'translated_strings.project_id', '=', $project->id )
			                                     ->where( 'language_id', '=', $language->id )
			                                     ->where( 'is_accepted', '=', true )
			                                     ->get( [ 'key', 'translated_strings.text' ] )
			                                     ->lists( 'text', 'key' )
			                                     ->toArray();

			$translations[] = [
				'name'    => $language->name,
				'strings' => array_merge( $baseStrings, $translatedStrings )
			];
		}

		// init output handler
		$outputHandler = OutputHandlerFactory::getFileHandler( $project->file_handler, $project, $translations );
		$output        = $outputHandler->getOutputFile();

		return Response::download( $output );
	}

}
