<?php

namespace App\Http\Controllers;

class PagesController extends BaseApiController
{

    public function __construct()
    {
    }

    public function index($name)
    {
        if (!file_exists(resource_path('views/pages/' . $name . '.blade.php')))
            abort(404);

        return view('pages/' . $name);
    }

}
