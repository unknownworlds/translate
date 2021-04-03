<?php

namespace App\LanguageFileHandling;

use App\Models\BaseString;
use App\Models\Log;
use App\Models\TranslatedString;

class DiffHandler
{
    /**
     * @var array
     */
    private $input;
    /**
     * @var int
     */
    private $projectId;

    /**
     * @param array $input
     * @param int $projectId
     */
    public function __construct(array $input, $projectId)
    {
        $this->input = $input;
        $this->projectId = $projectId;

        $this->process();
    }

    private function process()
    {
        // Scaffold
        $baseStrings = BaseString::where('project_id', '=', $this->projectId)->pluck('text', 'key')->toArray();

        // Remove old strings
        $unusedKeys = array_diff_key($baseStrings, $this->input);
        foreach ($unusedKeys as $key => $text) {
            BaseString::where([
                'key' => $key,
                'project_id' => $this->projectId
            ])->firstOrFail()->delete();

            $this->log('String ' . $key . ' was deleted');
        }

        // Add new strings
        $newKeys = array_diff_key($this->input, $baseStrings);
        foreach ($newKeys as $key => $text) {
            BaseString::create([
                'key' => $key,
                'text' => $text,
                'project_id' => $this->projectId,
                'alternative_or_empty' => empty($text) || strstr($key, '.'),
            ]);

            $this->log('String ' . $key . ' added');
        }

        // Update current strings
        $baseStrings = BaseString::where('project_id', '=', $this->projectId)->pluck('text', 'key')->toArray();
        $updatedKeys = array_diff_assoc($this->input, $baseStrings);
        foreach ($updatedKeys as $key => $text) {
            $baseString = BaseString::where([
                'key' => $key,
                'project_id' => $this->projectId
            ])->firstOrFail();

            $baseString->update([
                'text' => $text,
                'alternative_or_empty' => empty($text) || strstr($key, '.'),
            ]);

            // Remove related translations
            $alternativeBaseStrings = BaseString::where('key', 'like', $key . '.%')->get()->pluck('id')->toArray();

            TranslatedString::whereIn('base_string_id', array_merge($alternativeBaseStrings, [$baseString->id]))
                ->delete();

            $this->log('String ' . $key . ' changed');
        }
    }

    private function log($text)
    {
        Log::create([
            'project_id' => $this->projectId,
            'user_id' => 1,
            'log_type' => 2,
            'text' => $text
        ]);
    }
}
