<?php

namespace App\Http\Controllers;

use Auth;

class ThemesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($name)
    {

        switch ($name) {
            case 'dark':
                $theme = 'dark';
                break;
            default:
                $theme = 'light';
        }

        Auth::user()->update(['theme' => $theme]);

        return redirect()->back();
    }

}
