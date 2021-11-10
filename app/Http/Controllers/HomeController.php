<?php

namespace App\Http\Controllers;

use App\Models\BaseString;
use App\Models\Language;
use App\Models\Log;
use App\Models\Page;
use App\Models\Project;
use App\Models\TranslatedString;
use DB;

class HomeController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Home Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders your application's "dashboard" for users that
    | are authenticated. Of course, you are free to change or remove the
    | controller as you wish. It is just here to get your app started!
    |
    */

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {
        $news = Page::where('title', '=', 'Home')->first();

        $log = Log::where('log_type', '=', 1)->with('project', 'language')->latest()->limit(250)->get();
        $languages = Language::all()->pluck('locale', 'id');
        $baseStringsLog = Log::where('log_type', '=', 2)->latest()->limit(500)->get();

        $baseStringCounts = BaseString::where('alternative_or_empty', '=', false)
            ->selectRaw('project_id, COUNT(*) AS count')
            ->groupBy('project_id')
            ->get()
            ->pluck('count', 'project_id');

        $translationProgress = TranslatedString::where('is_accepted', '=', true)
            ->where('alternative_or_empty', '=', false)
            ->selectRaw('project_id, language_id, COUNT(*) AS count')
            ->with('project', 'language')
            ->groupBy(['project_id', 'language_id'])
            ->get();

        foreach ($translationProgress as &$entry) {
            $entry->completion = round($entry->count / $baseStringCounts[$entry->project_id] * 100, 3);
            $entry->progress_bar_class = '';

            if ($entry->completion >= 80) {
                $entry->progress_bar_class = 'progress-bar-info';
            }
            if ($entry->completion >= 100) {
                $entry->progress_bar_class = 'progress-bar-success';
            }
        }

        return view('home',
            compact('log', 'baseStringsLog', 'translationProgress', 'baseStringCounts', 'languages', 'news'));
    }

}
