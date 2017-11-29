<?php

namespace App\Http\Controllers;

use App\BaseString;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\ProjectRequest;
use App\Project;
use Request;

class ProjectsController extends Controller {

	public function __construct() {
        $this->middleware(['auth', 'hasRole:Root']);
	}

    /**
     * Display a listing of the resource.
     *
     * @param ProjectRequest $request
     * @return Response
     */
	public function index(ProjectRequest $request) {
		$projects = Project::orderBy('name')->get();

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
     * @return Response
     */
	public function create(ProjectRequest $request) {
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
	 */
	public function destroy( $id, ProjectRequest $request ) {
		Project::findOrFail( $id )->delete();

		return redirect( 'projects' );
	}

}
