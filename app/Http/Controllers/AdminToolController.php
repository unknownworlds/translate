<?php

namespace App\Http\Controllers;

use App\Models\BaseString;
use App\Models\Language;
use App\Models\TranslatedString;
use App\Models\User;
use Request;

class AdminToolController extends Controller
{
    public function __construct()
    {
        // TODO: make pretty. Cause it's ugly. It really is.

        $this->middleware(['auth', 'hasRole:Root']);
    }

    public function languageStatus()
    {
        $languages = Language::sortedByName()->get();
        $languageAdmins = $languages->pluck(0, 'name')->toArray();

        $users = User::has('roles')->with('roles')->get();
        foreach ($users as $user) {
            foreach ($user->roles as $role) {

                if (!strstr($role->name, ' admin')) {
                    continue;
                }

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

        return view('adminTool/languageStatus',
            compact('languages', 'languageAdmins', 'unacceptedStrings', 'acceptedStrings', 'baseStrings'));
    }

    public function potentialAdmins()
    {
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

    public function audit()
    {
        $language = Request::get('language_id', 1);
        $languages = Language::orderBy('name')->pluck('name', 'id');

        // TODO: refactor
        // It looks like old, Laravel 4 way of doing things
        $admins = [];
        $adminIds = [];
        $users = User::has('roles')->with('roles')->get();
        foreach ($users as $user) {
            foreach ($user->roles as $role) {

                if ($role->name != $languages[$language] . ' admin') {
                    continue;
                }

                $admins[] = $user;
                $adminIds[] = $user->id;
            }
        }

        $counts = [];
        $acceptedCountsRaw = TranslatedString::selectRaw('user_id, is_accepted, count(*) as count')
            ->whereIn('user_id', $adminIds)
            ->groupBy('user_id', 'is_accepted')
            ->get()->toArray();

        foreach ($acceptedCountsRaw as $item) {
            $counts[$item['user_id']][($item['is_accepted'] ? 'accepted_count' : 'unaccepted_count')] = $item['count'];
        }

        $voteCounts = TranslatedString::selectRaw('user_id, SUM(up_votes) AS up_votes_sum, SUM(down_votes) AS down_votes_sum')
            ->whereIn('user_id', $adminIds)
            ->groupBy('user_id')
            ->get()->toArray();

        foreach ($voteCounts as $item) {
            $counts[$item['user_id']]['up_votes_sum'] = $item['up_votes_sum'];
            $counts[$item['user_id']]['down_votes_sum'] = $item['down_votes_sum'];
        }

        $acceptedStrings = TranslatedString::selectRaw('accepted_by AS admin_id, MAX(updated_at) AS last_date, COUNT(*) AS count')
            ->groupBy('accepted_by')
            ->whereIn('accepted_by', $adminIds)
            ->get()->toArray();

        foreach ($acceptedStrings as $data) {
            $counts[$data['admin_id']]['last_accepted'] = $data['last_date'];
            $counts[$data['admin_id']]['accepted_by_count'] = $data['count'];
        }

        $deletedStrings = TranslatedString::selectRaw('deleted_by AS admin_id, MAX(deleted_at) AS last_date, COUNT(*) AS count')
            ->groupBy('deleted_by')
            ->whereIn('deleted_by', $adminIds)
            ->withTrashed()
            ->get()->toArray();

        foreach ($deletedStrings as $data) {
            $counts[$data['admin_id']]['last_deleted'] = $data['last_date'];
            $counts[$data['admin_id']]['deleted_by_count'] = $data['count'];
        }

        return view('adminTool/audit', compact('admins', 'counts', 'languages', 'language'));
    }
}
