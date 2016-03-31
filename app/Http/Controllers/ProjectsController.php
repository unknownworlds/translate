<?php
/**
 * Copyright (c) Unknown Worlds Entertainment, 2016. 
 * Created by Lukas Nowaczek <lukas@unknownworlds.com> <@lnowaczek>
 * Visit http://unknownworlds.com/
 * This file is a part of proprietary software. 
 */

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\ProjectRequest;
use App\Project;
use Request;

class ProjectsController extends Controller {

	public function __construct() {
		$this->middleware( 'auth' );

		// TODO: hasRole('Root')
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {
		$projects = Project::orderBy('name')->get();

		return view( 'projects/index', compact( 'projects' ) );
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create() {
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
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function show( $id ) {
		$project = Project::findOrFail( $id );

		return $project;
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function edit( $id ) {
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
