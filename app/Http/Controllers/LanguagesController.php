<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\CreateLanguageRequest;
use App\Http\Requests\DeleteLanguageRequest;
use App\Http\Requests\UpdateLanguageRequest;
use App\Language;
use Request;

class LanguagesController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$languages = Language::sortedByName()->get();
		return view('languages/index', compact('languages'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('languages.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param CreateLanguageRequest $request
	 *
	 * @return Response
	 */
	public function store( CreateLanguageRequest $request )
	{
		Language::create(Request::all());

		return redirect('languages');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$language = Language::findOrFail($id);

		return $language;
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$language = Language::findOrFail($id);

		return view('languages.edit', compact('language'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param UpdateLanguageRequest $request
	 *
	 * @param $id
	 *
	 * @return Response
	 * @internal param int $id
	 */
	public function update( UpdateLanguageRequest $request, $id )
	{
		Language::findOrFail($id)->update($request->all());

		return redirect('languages');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param DeleteLanguageRequest $request
	 *
	 * @return Response
	 * @internal param int $id
	 */
	public function destroy( DeleteLanguageRequest $request )
	{
		Language::findOrFail($request->id)->delete();

		return redirect('languages');
	}

}
