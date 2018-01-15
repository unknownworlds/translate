<?php

namespace App\Http\Controllers;

use App\BaseString;
use App\Http\Requests;
use App\Http\Requests\ToolsFileImportRequest;
use App\Language;
use App\Project;
use App\User;
use PDF;
use File;
use App\TranslatedString;
use Symfony\Component\HttpFoundation\Request;

class ToolsController extends Controller {

	public function __construct() {
		$this->middleware( [ 'auth', 'hasRole:Root' ] );
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function fileImport() {
		$projects  = Project::orderBy( 'name' )->pluck( 'name', 'id' );
		$languages = Language::orderBy( 'name' )->pluck( 'name', 'id' );
		$users     = User::orderBy( 'name' )->pluck( 'name', 'id' );

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
		$rawData    = explode( "\r\n", $request->get( 'input' ) );
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
			$baseString = BaseString::where( 'project_id', '=', $request->get( 'project_id' ) )
			                        ->where( 'key', '=', $key )
			                        ->first();

			if ( ! $baseString ) {
				continue;
			}

			TranslatedString::firstOrCreate( [
				'project_id'     => $request->get( 'project_id' ),
				'language_id'    => $request->get( 'language_id' ),
				'base_string_id' => $baseString->id,
				'user_id'        => $request->get( 'user_id' ),
				'text'           => $text,
				'is_accepted'    => true
			] );

		}

		return redirect( 'tools/file-import' )->with( 'message', 'Import complete' );
	}

	public function translationQualityIndex() {
		$projects = Project::orderBy( 'name' )->get();

		return view( 'tools/translationQualityIndex', compact( 'projects' ) );
	}

	public function translationQualityStrings( Request $request ) {
		$project = Project::findOrFail( $request->get( 'project_id' ) );
		$strings = BaseString::where( 'project_id', '=', $project->id )
		                     ->orderBy( 'key' )->get();

		return view( 'tools/translationQualityStrings', compact( 'strings', 'project' ) );
	}

	public function translationQualityDownload( Request $request ) {
		$project       = Project::findOrFail( $request->get( 'project_id' ) );
		$baseStrings   = BaseString::where( 'project_id', '=', $project->id )
		                           ->where( 'quality_controlled', '=', true )
		                           ->orderBy( 'key' )->get();
		$languages     = Language::all();
		$baseStringIds = $baseStrings->pluck( 'id' );

		$baseDir = public_path() . '/output/' . $project->id;
		$tempDir = $baseDir . '/qapdfs_' . time();
		mkdir( $tempDir, 0777, true );

		foreach ( $languages as $language ) {
			$translatedStrings    = [];
			$translatedStringsRaw = TranslatedString::where( 'language_id', '=', $language->id )
			                                        ->where( 'is_accepted', '=', 1 )
			                                        ->whereIn( 'base_string_id', $baseStringIds )
			                                        ->with( 'User' )
			                                        ->get();
			foreach ( $translatedStringsRaw as $string ) {
				$translatedStrings[ $string->base_string_id ] = $string;
			}

			$pdf = PDF::loadView( 'pdfs.qualityControl', compact( 'project', 'baseStrings', 'translatedStrings', 'language' ) );

			$pdf->save( $tempDir . '/' . $language->name . '.pdf' );
//			return view( 'pdfs.qualityControl', compact( 'project', 'baseStrings', 'translatedStrings', 'language' ) );
//			return $pdf->save( $tempDir . '/' . $language->name . '.pdf' )->stream();
		}

		// zip & clean
		$outputFile = $baseDir . '/QA_PDFs.zip';
		if ( is_file( $outputFile ) ) {
			unlink( $outputFile );
		}

		exec( 'cd ' . $baseDir . '/ && zip -j QA_PDFs.zip ' . $tempDir . '/*' );
		File::deleteDirectory( $tempDir );

		// return file
		return \Response::download( $outputFile );
	}

}
