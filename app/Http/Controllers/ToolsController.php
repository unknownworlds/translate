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

			if ( $text == '' ) {
				continue;
			}

			$string = TranslatedString::where( 'project_id', '=', $request->get( 'project_id' ) )
			                          ->where( 'language_id', '=', $request->get( 'language_id' ) )
			                          ->where( 'base_string_id', '=', $baseString->id )
			                          ->where( 'text', '=', $text )
			                          ->first();

			if ( $string ) {
				$string->update( [
					'is_accepted' => true
				] );

				continue;
			} else {
				TranslatedString::where( 'project_id', '=', $request->get( 'project_id' ) )
				                ->where( 'language_id', '=', $request->get( 'language_id' ) )
				                ->where( 'base_string_id', '=', $baseString->id )
				                ->update( [
					                'is_accepted' => false
				                ] );
			}

			TranslatedString::create( [
				'project_id'           => $request->get( 'project_id' ),
				'language_id'          => $request->get( 'language_id' ),
				'base_string_id'       => $baseString->id,
				'user_id'              => $request->get( 'user_id' ),
				'text'                 => $text,
				'is_accepted'          => true,
				'alternative_or_empty' => $baseString->alternative_or_empty
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

	/**
	 * translationsTransfer interface
	 *
	 * @return Response
	 */
	public function translationsTransfer() {
		$projects = Project::orderBy( 'name' )->pluck( 'name', 'id' );

		return view( 'tools/translationsTransfer', compact( 'projects' ) );
	}

	/**
	 * TODO: batch queries, make less database intensive
	 * translationsTransfer procedure
	 * allows for transfer accepted translations from one project to another
	 * if the same translation keys exist in both
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function ProcessTranslationsTransfer( Request $request ) {
		$sourceProject = $request->get( 'source_project' );
		$targetProject = $request->get( 'target_project' );

		$languages     = Language::all();
		$copiedStrings = 0;

		// validate
		if ( $sourceProject == $targetProject ) {
			return back()->withErrors( [ 'Source and target cannot be the same' ] );
		}

		// get base strings from both projects
		$sourceProjectKeys = BaseString::where( 'project_id', '=', $sourceProject )
		                               ->pluck( 'id', 'key' )->toArray();
		$targetProjectKeys = BaseString::where( 'project_id', '=', $targetProject )
		                               ->pluck( 'id', 'key' )->toArray();

		// iterate over target project strings
		foreach ( $targetProjectKeys as $key => $id ) {
			// skip if it's not present in source
			if ( ! array_key_exists( $key, $sourceProjectKeys ) ) {
				continue;
			}

			// iterate over all languages
			$insertDataBatch = [];
			foreach ( $languages as $language ) {

				$existingTargetTranslation = TranslatedString::where( 'project_id', '=', $targetProject )
				                                             ->where( 'is_accepted', '=', true )
				                                             ->where( 'base_string_id', '=', $targetProjectKeys[ $key ] )
				                                             ->where( 'language_id', '=', $language->id )
				                                             ->first();

				// skip if we already have an accepted translation
				if ( $existingTargetTranslation != null ) {
					continue;
				}

				$existingSourceTranslation = TranslatedString::where( 'project_id', '=', $sourceProject )
				                                             ->where( 'is_accepted', '=', true )
				                                             ->where( 'base_string_id', '=', $sourceProjectKeys[ $key ] )
				                                             ->where( 'language_id', '=', $language->id )
				                                             ->first();

				// skip if we don't have that translation ready in the source project
				if ( $existingSourceTranslation == null ) {
					continue;
				}

				$insertDataBatch[] = [
					'project_id'     => $targetProject,
					'language_id'    => $existingSourceTranslation->language_id,
					'base_string_id' => $targetProjectKeys[ $key ],
					'text'           => $existingSourceTranslation->text,
					'is_accepted'    => true,
					'user_id'        => $existingSourceTranslation->user_id,
				];

				$copiedStrings ++;
			}

			TranslatedString::create( $insertDataBatch );

		}

		return back()->withMessage( 'Copied ' . $copiedStrings . ' translations' );
	}

}
