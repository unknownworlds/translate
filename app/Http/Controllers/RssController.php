<?php

namespace App\Http\Controllers;

use App\Log;
use App\Project;
use Illuminate\Http\Request;
use App\Http\Requests;

class RssController extends Controller
{
    public function index()
    {
        $projects = Project::get(['name', 'id']);

        return view('rss/page', compact('projects'));
    }

    public function baseStrings($id)
    {
        $project = Project::findOrFail($id);
        $log = Log::where('log_type', '=', 2)->where('project_id', '=', $id)->latest()->limit(500)->get();

        return response()
            ->view('rss/baseStringUpdates', compact('project', 'log'))
            ->header('Content-Type', 'application/rss+xml');
    }
}
