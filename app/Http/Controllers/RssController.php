<?php

namespace App\Http\Controllers;

use App\Language;
use App\Log;
use App\Project;
use Illuminate\Http\Request;
use App\Http\Requests;

class RssController extends Controller
{
    public function index()
    {
        $projects = Project::get(['name', 'id']);
        $languages = Language::orderBy('name')->get();

        return view('rss/page', compact('projects', 'languages'));
    }

    public function baseStrings($id)
    {
        $project = Project::findOrFail($id);
        $log = Log::where('log_type', '=', 2)
            ->where('project_id', '=', $id)
            ->latest()->limit(500)->get();

        return response()
            ->view('rss/baseStringUpdates', compact('project', 'log'))
            ->header('Content-Type', 'application/rss+xml');
    }

    public function translations($projectId, $languageId)
    {
        $project = Project::findOrFail($projectId);
        $language = Language::findOrFail($languageId);

        $log = Log::where('log_type', '=', 1)
            ->where('language_id', '=', $languageId)
            ->where('project_id', '=', $projectId)
            ->latest()->limit(250)->get();

        return response()
            ->view('rss/translations', compact('project', 'language', 'log'))
            ->header('Content-Type', 'application/rss+xml');
    }
}
