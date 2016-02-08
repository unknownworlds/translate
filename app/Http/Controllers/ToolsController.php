<?php namespace App\Http\Controllers;

use App\BaseString;
use App\Http\Requests;

use App\Http\Requests\ToolsFileImportRequest;
use App\Language;
use App\Project;
use App\User;
use File;
use App\TranslatedString;
use Request;

class ToolsController extends Controller {

	public function __construct() {
		$this->middleware( 'auth' );

		// TODO: hasRole('Root')
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function fileImport() {
		$projects  = Project::orderBy( 'name' )->lists( 'name', 'id' );
		$languages = Language::orderBy( 'name' )->lists( 'name', 'id' );
		$users     = User::orderBy( 'name' )->lists( 'name', 'id' );

		return view( 'tools/fileImport', compact( 'projects', 'languages', 'users' ) );
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param ToolsFileImportRequest $request
	 *
	 * @return Response
	 */
	public function processFileImport( ToolsFileImportRequest $request ) {
		$rawData    = explode( "\r\n", Request::get( 'input' ) );
		$properJson = '';

		foreach ( $rawData as $line ) {
			if ( ! strstr( $line, '//' ) ) {
				$properJson .= trim( $line );
			}
		}

		$translatedStrings = json_decode( $properJson );

		if ( ! $translatedStrings ) {
			return redirect()->back()->withErrors( [ 'Can\'t decode the input' ] );
		}

		foreach ( $translatedStrings as $key => $text ) {
			$baseString = BaseString::where( 'project_id', '=', Request::get( 'project_id' ) )
			                        ->where( 'key', '=', $key )
			                        ->first();

			if ( ! $baseString ) {
				continue;
			}

			TranslatedString::firstOrCreate( [
				'project_id'     => Request::get( 'project_id' ),
				'language_id'    => Request::get( 'language_id' ),
				'base_string_id' => $baseString->id,
				'user_id'        => Request::get( 'user_id' ),
				'text'           => $text
			] );

		}

		return redirect( 'tools/file-import' )->with( 'message', 'Import complete' );
	}

}
