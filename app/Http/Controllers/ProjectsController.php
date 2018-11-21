<?php

namespace App\Http\Controllers;

use App\BaseString;
use App\Http\Requests\ProjectDataImportRequest;
use App\Http\Requests\ProjectRequest;
use App\Language;
use App\LanguageFileHandling\DataExportHandler;
use App\LanguageFileHandling\DiffHandler;
use App\LanguageFileHandling\InputHandlerFactory;
use App\LanguageFileHandling\OutputHandlerFactory;
use App\Project;
use App\TranslatedString;
use Request;
use Response;

class ProjectsController extends Controller {

	public function __construct() {
		$this->middleware( [ 'auth', 'hasRole:Root' ] );
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @param ProjectRequest $request
	 *
	 * @return Response
	 */
	public function index( ProjectRequest $request ) {
		$projects = Project::orderBy( 'name' )->get();

		$baseStringCounts = BaseString::where( 'alternative_or_empty', '=', false )
		                              ->selectRaw( 'project_id, COUNT(*) AS count' )
		                              ->groupBy( 'project_id' )
		                              ->get()
		                              ->pluck( 'count', 'project_id' );

		$baseStringAlternativeCounts = BaseString::where( 'alternative_or_empty', '=', true )
		                                         ->selectRaw( 'project_id, COUNT(*) AS count' )
		                                         ->groupBy( 'project_id' )
		                                         ->get()
		                                         ->pluck( 'count', 'project_id' );

		return view( 'projects/index', compact( 'projects', 'baseStringCounts', 'baseStringAlternativeCounts' ) );
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @param ProjectRequest $request
	 *
	 * @return Response
	 */
	public function create( ProjectRequest $request ) {
		return view( 'projects.create' );
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param ProjectRequest $request
	 *
	 * @return Response
	 */
	public function store( ProjectRequest $request ) {
		Project::create( Request::all() );

		return redirect( 'projects' );
	}

	/**
	 * Display the specified resource.
	 *
	 * @param ProjectRequest $request
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function show( ProjectRequest $request, $id ) {
		$project = Project::findOrFail( $id );

		return $project;
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param ProjectRequest $request
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function edit( ProjectRequest $request, $id ) {
		$project = Project::findOrFail( $id );

		return view( 'projects.edit', compact( 'project' ) );
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param ProjectRequest $request
	 *
	 * @param $id
	 *
	 * @return Response
	 * @internal param int $id
	 */
	public function update( $id, ProjectRequest $request ) {
		Project::findOrFail( $id )->update( $request->all() );

		return redirect( 'projects' );
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param $id
	 * @param ProjectRequest $request
	 *
	 * @return Response
	 * @internal param int $id
	 * @throws \Exception
	 */
	public function destroy( $id, ProjectRequest $request ) {
		Project::findOrFail( $id )->delete();

		return redirect( 'projects' );
	}

	/**
	 * Show language data import screen
	 *
	 * @param $id
	 * @param ProjectRequest $request
	 *
	 * @return Response
	 * @internal param int $id
	 * @throws \Exception
	 */
	public function importData( $id, ProjectRequest $request ) {
		$project = Project::findOrFail( $id );

		return view( 'projects.importData', compact( 'project' ) );
	}

	/**
	 * Import language file
	 *
	 * @param $id
	 * @param ProjectDataImportRequest $request
	 *
	 * @return void
	 * @internal param int $id
	 * @throws \Exception
	 */
	public function processDataImport( $id, ProjectDataImportRequest $request ) {
		$project = Project::where( [ 'id' => $id ] )->firstOrFail();

		$file = $request->file( 'file' );
		$data = file_get_contents( $file->getRealPath() );

		$inputHandler = InputHandlerFactory::getFileHandler( $project->data_input_handler );
		$inputHandler->setRawInput( $data );
		$input = $inputHandler->getParsedInput();

		new DiffHandler( $input, $project->id );

		return redirect( 'projects' );
	}

	/**
	 * Export language file
	 *
	 * @param $id
	 * @param ProjectRequest $request
	 *
	 * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
	 * @throws \Exception
	 * @internal param int $id
	 */
	public function exportData( $id, ProjectRequest $request ) {
		// get project by api key
		$project = Project::where( [ 'id' => $id ] )->firstOrFail();

		// get the data
		$translations = DataExportHandler::prepareData( $project->id );

		// init output handler
		$outputHandler = OutputHandlerFactory::getFileHandler( $project->data_output_handler, $project, $translations );
		$output        = $outputHandler->getOutputFile();

		return Response::download( $output );
	}

}
