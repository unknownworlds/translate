<?php
/**
 * Copyright (c) Unknown Worlds Entertainment, 2016.
 * Created by Lukas Nowaczek <lukas@unknownworlds.com> <@lnowaczek>
 * Visit http://unknownworlds.com/
 * This file is a part of proprietary software.
 */

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
