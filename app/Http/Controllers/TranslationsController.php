<?php

namespace App\Http\Controllers;

use App\Models\BaseString;
use App\Http\Requests\BaseStringRequest;
use App\Http\Requests\BaseStringTrashRequest;
use App\Http\Requests\StringAdminRequest;
use App\Http\Requests\StringRequest;
use App\Http\Requests\StringVoteRequest;
use App\Models\Language;
use App\Models\Log;
use App\Models\Project;
use App\Models\TranslatedString;
use App\Models\User;
use App\Models\Vote;
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
            ->get(['id', 'key', 'text', 'locked']);

        return $this->respond($baseStrings);
    }

    public function strings()
    {
        $baseStrings = TranslatedString::where('project_id', '=', Request::get('project_id'))
            ->where('language_id', '=', Request::get('language_id'))
            ->get([
                'id',
                'base_string_id',
                'text',
                'up_votes',
                'down_votes',
                'is_accepted'
            ]);

        return $this->respond($baseStrings);
    }

    public function checkPrivileges()
    {
        $language = Language::findOrFail(Request::get('language_id'));

        $result = [
            'is_admin' => Auth::user()->hasRole($language->name.' admin') || Auth::user()->hasRole('Root'),
            'is_root' => Auth::user()->hasRole('Root')
        ];

        return $this->respond($result);
    }

    public function store(StringRequest $request)
    {
        $input = $request->all();
        $input['user_id'] = Auth::user()->id;

        $baseString = BaseString::findOrFail($input['base_string_id']);

        // Trim newlines
        $input['text'] = trim($input['text'], "\n");

        $duplicates = TranslatedString::where([
            'project_id' => $baseString->project_id,
            'language_id' => $input['language_id'],
            'base_string_id' => $baseString->id,
            'text' => $input['text'],
        ])->get();

        // TODO: Kinda lame hack. Server was configured by Forge. Broken on both Forge and Homestead. WTF. Caused by MySQL config issues.
        foreach ($duplicates as $duplicate) {
            if ($duplicate->text == $input['text']) {
                return $this->respondValidationFailed('Translation already exist!');
            }
        }

        $newString = [
            'project_id' => $baseString->project_id,
            'language_id' => $input['language_id'],
            'base_string_id' => $baseString->id,
            'user_id' => Auth::user()->id,
            'text' => $input['text'],
            'up_votes' => 0,
            'down_votes' => 0,
            'alternative_or_empty' => $baseString->alternative_or_empty,
        ];

        $string = TranslatedString::create($newString);

        Log::create([
            'project_id' => $baseString->project_id,
            'language_id' => $input['language_id'],
            'user_id' => Auth::user()->id,
            'text' => Auth::user()->name.' translated '.$baseString->key.' to '.mb_substr($input['text'], 0, 200)
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
            'text' => Auth::user()->name.' added new base string '.$string['text']
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

        if ($string->user_id == Auth::user()->id) {
            return $this->respondValidationFailed('Cannot vote on own entries.');
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

        $baseString = BaseString::findOrFail(Request::get('base_string_id'));

        if ($baseString->locked && !Auth::user()->hasRole('Root')) {
            return $this->respond('String locked.');
        }

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
            'id' => $request->get('string_id'),
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
     * @param  BaseStringTrashRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function trashBaseString(BaseStringTrashRequest $request)
    {
        $string = BaseString::findOrFail(Request::get('id'));

        $string->delete();

        return $this->respond('Base string deleted.');
    }

    /**
     * @param  BaseStringTrashRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function lockBaseString(BaseStringTrashRequest $request)
    {
        $string = BaseString::findOrFail(Request::get('id'));

        $string->locked = !(bool) $string->locked;

        $string->save();

        return $this->respond($string);
    }

    public function restoreTranslations(BaseStringTrashRequest $request)
    {
        $baseString = BaseString::findOrFail($request->get('id'));
        $languages = Language::all();
        $restoredStringsCount = 0;

        foreach ($languages as $language) {
            $currentlyAcceptedString = TranslatedString::where('base_string_id', '=', $baseString->id)
                ->where('language_id', '=', $language->id)
                ->where('is_accepted', '=', true)
                ->first();

            if ($currentlyAcceptedString !== null) {
                continue;
            }

            $previouslyAcceptedString = TranslatedString::withTrashed()
                ->where('base_string_id', '=', $baseString->id)
                ->where('language_id', '=', $language->id)
                ->where('is_accepted', '=', true)
                ->whereNotNull('deleted_at')
                ->latest()
                ->first();

            if ($previouslyAcceptedString !== null) {
                $restoredStringsCount++;
                $previouslyAcceptedString->restore();
            }
        }

        return $this->respond($restoredStringsCount.' deleted strings restored');
    }

    public function users()
    {
        $users = TranslatedString::join('users', 'users.id', '=', 'translated_strings.user_id')
            ->selectRaw('count(*) AS count, user_id, name')
            ->where('project_id', '=', Request::get('project_id'))
            ->where('language_id', '=', Request::get('language_id'))
            ->groupBy('translated_strings.user_id', 'name')
            ->orderBy('count', 'desc')
            ->limit(25)
            ->get();

        return $this->respond($users);
    }

    public function admins()
    {
        $language = Language::where('id', '=', Request::get('language_id'))->first();

        $admins = User::whereHas('roles', function ($q) use ($language) {
            $q->where('name', '=', $language->name.' admin');
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
        $projectHandlers = $projects->pluck('data_input_handler', 'id');

        return $this->respond($projectHandlers);
    }

}
