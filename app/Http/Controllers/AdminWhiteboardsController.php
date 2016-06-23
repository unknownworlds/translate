<?php

namespace App\Http\Controllers;

use App\AdminWhiteboard;
use Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;

class AdminWhiteboardsController extends BaseApiController
{
    public function __construct()
    {
        parent::__construct(200);
    }

    public function find($project_id, $language_id)
    {
        $result = AdminWhiteboard::where(compact('project_id', 'language_id'))->latest()->with('User')->first();

        return $this->respond($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Requests\AdminWhiteboardRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\AdminWhiteboardRequest $request)
    {
        try {
            $data = Input::all();
            $data['user_id'] = Auth::user()->id;

            AdminWhiteboard::create($data);
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }

        return $this->respondCreated();
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = AdminWhiteboard::findOrFail($id);

        return $this->respond($item);
    }
}
