<?php namespace App\Http\Controllers;

use App\BaseString;
use App\Log;
use App\Project;
use App\TranslatedString;
use DB;

class HomeController extends Controller {

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
		$log = Log::where('log_type', '=', 1)->with('project')->latest()->limit(250)->get();
		$baseStringsLog = Log::where('log_type', '=', 2)->latest()->limit(500)->get();

		$baseStringCounts = BaseString::selectRaw('project_id, COUNT(*) AS count')->groupBy('project_id')->get()->lists('count', 'project_id');
		$translationProgress = TranslatedString::where('is_accepted', '=', true)->selectRaw('project_id, language_id, COUNT(*) AS count')
		                                       ->with('project', 'language')
		                                       ->groupBy(['project_id', 'language_id'])->get();

		return view('home', compact('log', 'baseStringsLog', 'translationProgress', 'baseStringCounts'));
	}

}
