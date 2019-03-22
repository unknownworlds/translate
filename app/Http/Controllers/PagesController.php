<?php

namespace App\Http\Controllers;

use App\Http\Requests\PageRequest;
use App\Page;
use Illuminate\Support\Str;

class PagesController extends BaseController
{

    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $pages = Page::orderBy('title')->get();

        return view('pages/index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param PageRequest $request
     * @return Response
     */
    public function create(PageRequest $request)
    {
        return view('pages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PageRequest $request
     *
     * @return Response
     */
    public function store(PageRequest $request)
    {
        Page::create([
            'title' => $request->get('title'),
            'slug' => Str::slug($request->get('title')),
            'content' => $request->get('content'),
        ]);

        return redirect('pages');
    }

    /**
     * Display the specified resource.
     *
     * @param  string $slug
     *
     * @return Response
     */
    public function show($slug)
    {
        $page = Page::where('slug', '=', $slug)->first();

        if ($page == null) {
            abort(404);
        }

        return view('pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @param PageRequest $request
     * @return Response
     */
    public function edit($id, PageRequest $request)
    {
        $page = Page::findOrFail($id);

        return view('pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PageRequest $request
     *
     * @param $id
     *
     * @return Response
     * @internal param int $id
     */
    public function update($id, PageRequest $request)
    {
        Page::findOrFail($id)->update([
            'title' => $request->get('title'),
            'slug' => Str::slug($request->get('title')),
            'content' => $request->get('content'),
        ]);

        return redirect('pages');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @param PageRequest $request
     *
     * @return Response
     * @internal param int $id
     */
    public function destroy($id, PageRequest $request)
    {
        Page::findOrFail($id)->delete();

        return redirect('pages');
    }

}
