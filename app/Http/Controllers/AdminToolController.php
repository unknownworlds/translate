<?php

namespace App\Http\Controllers;

use App\BaseString;
use App\Language;
use App\Role;
use App\TranslatedString;
use App\User;
use DB;
use Illuminate\Http\Request;

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
}
