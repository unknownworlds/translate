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
                $roleData = explode(' ', $role->name);

                if (array_key_exists(1, $roleData) && $roleData[1] == 'admin') {
                    $languageAdmins[$roleData[0]]++;
                }
            }
        }

        $acceptedStrings = DB::table('translated_strings')
            ->select(DB::raw('count(*) as count, language_id'))
            ->where('is_accepted', '=', true)
            ->groupBy('language_id')
            ->get()->pluck('count', 'language_id');

        $unacceptedStrings = DB::table('translated_strings')
            ->select(DB::raw('count(*) as count, language_id'))
            ->where('is_accepted', '=', false)
            ->groupBy('language_id')
            ->get()->pluck('count', 'language_id');

        $baseStrings  = BaseString::count();

        return view('adminTool/languageStatus', compact('languages', 'languageAdmins', 'unacceptedStrings', 'acceptedStrings', 'baseStrings'));
    }
}
