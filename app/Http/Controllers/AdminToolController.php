<?php

namespace App\Http\Controllers;

use App\BaseString;
use App\Language;
use App\Role;
use App\TranslatedString;
use App\User;
use Request;

use App\Http\Requests;

class AdminToolController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'hasRole:Root']);
    }

    public function languageStatus()
    {
        $languages = Language::sortedByName()->get();
        $languageAdmins = $languages->pluck(0, 'name')->toArray();

        $users = User::has('roles')->with('roles')->get();
        foreach ($users as $user) {
            foreach ($user->roles as $role) {

                if (!strstr($role->name, ' admin'))
                    continue;

                $languageName = substr($role->name, 0, strpos($role->name, ' admin'));
                $languageAdmins[$languageName]++;
            }
        }

        $acceptedStrings = TranslatedString::selectRaw('language_id, count(*) as count')
            ->where('is_accepted', '=', true)
            ->groupBy('language_id')
            ->get()->pluck('count', 'language_id');

        $unacceptedStrings = TranslatedString::selectRaw('count(*) as count, language_id')
            ->where('is_accepted', '=', false)
            ->groupBy('language_id')
            ->get()->pluck('count', 'language_id');

        $baseStrings = BaseString::count();

        return view('adminTool/languageStatus', compact('languages', 'languageAdmins', 'unacceptedStrings', 'acceptedStrings', 'baseStrings'));
    }

    public function potentialAdmins()
    {
        // TODO: make pretty. Cause it's ugly. It really is.

        $language = Request::get('language_id', 1);
        $languages = Language::orderBy('name')->pluck('name', 'id');

        $users = TranslatedString::join('users', 'users.id', '=', 'translated_strings.user_id')
            ->selectRaw('count(*) AS count, user_id, name')
            ->where('language_id', '=', $language)
            ->groupBy('translated_strings.user_id')
            ->orderBy('count', 'desc')
            ->limit(25)
            ->get();

        $counts = [];
        $acceptedCountsRaw = TranslatedString::selectRaw('user_id, is_accepted, count(*) as count')
            ->groupBy('user_id', 'is_accepted')
            ->get()->toArray();

        foreach ($acceptedCountsRaw as $item) {
            $counts[$item['user_id']][($item['is_accepted'] ? 'accepted_count' : 'unaccepted_count')] = $item['count'];
        }

        $voteCounts = TranslatedString::selectRaw('user_id, SUM(up_votes) AS up_votes_sum, SUM(down_votes) AS down_votes_sum')
            ->groupBy('user_id')
            ->get()->toArray();

        foreach ($voteCounts as $item) {
            $counts[$item['user_id']]['up_votes_sum'] = $item['up_votes_sum'];
            $counts[$item['user_id']]['down_votes_sum'] = $item['down_votes_sum'];
        }

        return view('adminTool/potentialAdmins', compact('users', 'counts', 'languages', 'language'));
    }
}
