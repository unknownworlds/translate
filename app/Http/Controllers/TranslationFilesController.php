<?php

namespace App\Http\Controllers;

use App\LanguageFileHandling\DataExportHandler;
use App\LanguageFileHandling\DiffHandler;
use App\LanguageFileHandling\InputHandlerFactory;
use App\LanguageFileHandling\OutputHandlerFactory;
use App\Project;
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
		$inputHandler = InputHandlerFactory::getFileHandler( $project->file_handler );

		// pass reader results to diff handler
		$input = $inputHandler->getParsedInput();

		if ( $input == null ) {
			return $this->respond( 'Fail, probably the input file is corrupted' );
		}

		new DiffHandler( $input, $project->id );

		return $this->respond( 'Success!' );
	}

	public function processOutputFiles() {
		// get project by api key
		$project = Project::where( [ 'api_key' => Request::get( 'api_key' ) ] )->firstOrFail();

		// get the data
		$translations = DataExportHandler::prepareData( $project->id );

		// init output handler
		$outputHandler = OutputHandlerFactory::getFileHandler( $project->file_handler, $project, $translations );
		$output        = $outputHandler->getOutputFile();

		return Response::download( $output );
	}

}
