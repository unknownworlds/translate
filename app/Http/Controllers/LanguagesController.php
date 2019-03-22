<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\LanguageRequest;
use App\Language;
use Request;

class LanguagesController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'hasRole:Root']);
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
     * @param LanguageRequest $request
     *
     * @return Response
     */
    public function store(LanguageRequest $request)
    {
        Language::create(Request::all());

        return redirect('languages');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
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
     * @param  int $id
     *
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
     * @param LanguageRequest $request
     *
     * @param $id
     *
     * @return Response
     * @internal param int $id
     */
    public function update($id, LanguageRequest $request)
    {
        Language::findOrFail($id)->update($request->only([
            'name',
            'locale',
            'is_rtl',
            'skip_in_output',
            'steam_api_name'
        ]));

        return redirect('languages');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @param LanguageRequest $request
     *
     * @return Response
     * @internal param int $id
     * @throws \Exception
     */
    public function destroy($id, LanguageRequest $request)
    {
        Language::findOrFail($id)->delete();

        return redirect('languages');
    }

}
