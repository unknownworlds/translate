<?php

namespace App\LanguageFileHandling\OutputHandlers;

use File;
use VdfParser\Parser;

class SteamAchievementsOutputHandler implements OutputHandlerInterface
{
    private $project, $translations;

    public function __construct($project, $translations)
    {
        $this->project = $project;
        $this->translations = $translations;
    }

    public function getOutputFile()
    {
        $dir = public_path() . '/output/' . $this->project->id . '/' . time();
        mkdir($dir, 0777, true);

        foreach ($this->translations as $language) {

            if ($language['skip_in_output'] || $language['steam_api_name'] == null) {
                continue;
            }

            $strings = $language['strings'];

            $output = view('languageFileTemplates/steamAchievements', compact('language', 'strings'));

            $file = fopen($dir . '/' . $language['name'] . '.vdf', 'w+');
            fputs($file, $output);
            fclose($file);
        }

        $outputFile = public_path() . '/output/' . $this->project->id . '/output.zip';
        if (is_file($outputFile)) {
            unlink($outputFile);
        }

        exec('cd ' . public_path() . '/output/' . $this->project->id . ' && zip -j output.zip ' . $dir . '/*');

        File::deleteDirectory($dir);

        return $outputFile;
    }
}