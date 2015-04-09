<?php namespace App\Http\Controllers;

use App\LanguageFileHandling\DiffHandler;
use App\LanguageFileHandling\InputHandlerFactory;
use App\Project;
use Request;

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
		new DiffHandler( $inputHandler->getParsedInput(), $project->id );

		return $this->respond('Success!');
	}

	public function processOutputFiles() {

	}

}
