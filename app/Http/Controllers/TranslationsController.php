<?php

namespace App\Http\Controllers;

use App\BaseString;
use App\Http\Requests\BaseStringRequest;
use App\Http\Requests\BaseStringTrashRequest;
use App\Http\Requests\StringAdminRequest;
use App\Http\Requests\StringRequest;
use App\Http\Requests\StringVoteRequest;
use App\Language;
use App\Log;
use App\Project;
use App\TranslatedString;
use App\User;
use App\Vote;
use Auth;
use Request;

class TranslationsController extends BaseApiController
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $projects = Project::orderBy('name')->get();
        $projectList = $projects->pluck('name', 'id');

        $languages = Language::orderBy('name')->pluck('name', 'id');

        return view('translations/index', compact('projectList', 'languages'));
    }

    public function baseStrings()
    {
        $baseStrings = BaseString::where('project_id', '=', Request::get('project_id'))
            ->get(['id', 'key', 'text']);

        return $this->respond($baseStrings);
    }

    public function strings()
    {
        $baseStrings = TranslatedString::where('project_id', '=', Request::get('project_id'))
            ->where('language_id', '=', Request::get('language_id'))
            ->get(['id', 'base_string_id', 'text', 'up_votes', 'down_votes', 'is_accepted']);

        return $this->respond($baseStrings);
    }

    public function checkPrivileges()
    {
        $language = Language::findOrFail(Request::get('language_id'));

        $result = [
            'is_admin' => Auth::user()->hasRole($language->name . ' admin') || Auth::user()->hasRole('Root'),
            'is_root' => Auth::user()->hasRole('Root')
        ];

        return $this->respond($result);
    }

    public function store(StringRequest $request)
    {
        $input = Request::all();
        $input['user_id'] = Auth::user()->id;

        $duplicates = TranslatedString::where([
            'project_id' => Request::get('project_id'),
            'language_id' => Request::get('language_id'),
            'base_string_id' => Request::get('base_string_id'),
            'text' => Request::get('text'),
        ])->get();

        // TODO: Kinda lame hack. Server was configured by Forge. Broken on both Forge and Homestead. WTF. Caused by MySQL config issues.
        foreach ($duplicates as $duplicate) {
            if ($duplicate->text == Request::get('text')) {
                return $this->respondValidationFailed('Translation already exist!');
            }
        }

        $input['up_votes'] = 0;
        $input['down_votes'] = 0;

        $string = TranslatedString::create($input);

        $baseString = BaseString::findOrFail(Request::get('base_string_id'))->key;
        Log::create([
            'project_id' => Request::get('project_id'),
            'language_id' => Request::get('language_id'),
            'user_id' => Auth::user()->id,
            'text' => Auth::user()->name . ' translated ' . $baseString . ' to ' . Request::get('text')
        ]);

        return $this->respond($string);
    }

    public function storeBaseString(BaseStringRequest $request)
    {
        $input = Request::all();

        if (Request::has('id')) {
            $string = BaseString::findOrFail($input['id']);
            $string->update([
                'text' => $input['text']
            ]);

            TranslatedString::where('base_string_id', '=', $string->id)->delete();
        } else {
            $string = BaseString::create($input);
        }

        Log::create([
            'project_id' => Request::get('project_id'),
            'user_id' => Auth::user()->id,
            'text' => Auth::user()->name . ' added new base string ' . $string['text']
        ]);

        return $this->respond($string);
    }

    public function vote(StringVoteRequest $request)
    {
        $string = TranslatedString::findOrFail(Request::get('string_id'));

        $vote = Vote::firstOrNew([
            'string_id' => Request::get('string_id'),
            'user_id' => Auth::user()->id
        ]);

        if ($vote->id) {
            return $this->respondValidationFailed('Already voted!');
        }

	    if ( $string->user_id == Auth::user()->id ) {
		    return $this->respondValidationFailed( 'Cannot vote on own entries.' );
	    }

        $vote->save();

        $voteType = (Request::get('vote') == 1) ? 'up_votes' : 'down_votes';
        $string->update([$voteType => $string[$voteType] + 1]);

        return $this->respond('Vote saved.');
    }

    public function accept(StringAdminRequest $request)
    {
        $string = TranslatedString::where([
            'id' => Request::get('string_id'),
            'language_id' => Request::get('language_id')
        ])->firstOrFail();

        TranslatedString::where([
            'base_string_id' => Request::get('base_string_id'),
            'language_id' => Request::get('language_id')
        ])->update([
            'is_accepted' => false
        ]);

        $string->update([
            'is_accepted' => true,
            'accepted_by' => Auth::id()
        ]);

        return $this->respond('String accepted.');
    }

    public function update(StringAdminRequest $request)
    {
        $string = TranslatedString::where([
            'id' =>$request->get('string_id'),
        ])->firstOrFail();

        $string->update([
            'text' => $request->get('text')
        ]);

        return $this->respond('String updated.');
    }

    public function trash(StringAdminRequest $request)
    {
        $string = TranslatedString::where([
            'id' => Request::get('string_id'),
            'language_id' => Request::get('language_id')
        ])->firstOrFail();

        $string->update([
            'deleted_by' => Auth::id()
        ]);

        $string->delete();

        return $this->respond('String deleted.');
    }

    /**
     * @param BaseStringTrashRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function trashBaseString(BaseStringTrashRequest $request)
    {
        $string = BaseString::findOrFail(Request::get('id'));

        $string->delete();

        return $this->respond('Base string deleted.');
    }

    public function users()
    {

        $users = TranslatedString::join('users', 'users.id', '=', 'translated_strings.user_id')
            ->selectRaw('count(*) AS count, user_id, name')
            ->where('project_id', '=', Request::get('project_id'))
            ->where('language_id', '=', Request::get('language_id'))
            ->groupBy('translated_strings.user_id')
            ->orderBy('count', 'desc')
            ->limit(25)
            ->get();

        return $this->respond($users);
    }

    public function admins()
    {

        $language = Language::where('id', '=', Request::get('language_id'))->first();

        $admins = User::whereHas('roles', function ($q) use ($language) {
            $q->where('name', '=', $language->name . ' admin');
        })->get(['id', 'name']);

        return $this->respond($admins);
    }

    public function translationHistory()
    {
        $baseStrings = TranslatedString::withTrashed()
            ->where('project_id', '=', Request::get('project_id'))
            ->where('language_id', '=', Request::get('language_id'))
            ->where('base_string_id', '=', Request::get('base_string_id'))
            ->with('User')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return $this->respond($baseStrings);
    }

    public function projectHandlers()
    {
        $projects = Project::orderBy('name')->get();
        $projectHandlers = $projects->pluck('file_handler', 'id');

        return $this->respond($projectHandlers);
    }

}
