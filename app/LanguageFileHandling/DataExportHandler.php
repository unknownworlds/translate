<?php
/**
 * Created by PhpStorm.
 * User: Ace
 * Date: 22.02.2018
 * Time: 15:47
 */

namespace App\LanguageFileHandling;


use App\Models\BaseString;
use App\Models\Language;
use App\Models\TranslatedString;

class DataExportHandler
{
    public static function prepareData($projectId)
    {
        $languages = Language::all();
        $baseStrings = BaseString::where('project_id', '=', $projectId)->pluck('text', 'key')->toArray();
        $translations = [];

        foreach ($languages as $language) {
            $translatedStrings = TranslatedString::join('base_strings', 'translated_strings.base_string_id', '=',
                'base_strings.id')
                ->where('translated_strings.project_id', '=', $projectId)
                ->where('language_id', '=', $language->id)
                ->where('is_accepted', '=', true)
                ->get(['key', 'translated_strings.text'])
                ->pluck('text', 'key')
                ->toArray();

            $translations[] = [
                'name' => $language->name,
                'is_rtl' => $language->is_rtl,
                'skip_in_output' => $language->skip_in_output,
                'steam_api_name' => $language->steam_api_name,
                'strings' => array_merge($baseStrings, $translatedStrings)
            ];
        }

        return $translations;
    }
}
