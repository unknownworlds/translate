<?php

namespace App\LanguageFileHandling\OutputHandlers;

use App\LanguageFileHandling\Zip;
use File;

class PredefinedTemplateOutputHandler implements OutputHandlerInterface
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

            if ($language['skip_in_output']) {
                continue;
            }

            // Update the keys to be contained within brackets
            $strings = [];
            foreach ($language['strings'] as $key => $value) {
                $strings['{' . $key . '}'] = $value;
            }

            $output = str_replace(array_keys($strings), array_values($strings), $this->project->output_template);

            $file = fopen($dir . '/' . $language['name'] . '.txt', 'w+');
            fputs($file, $output);
            fclose($file);
        }

        $outputFile = public_path() . '/output/' . $this->project->id . '/output.zip';
        Zip::directory($dir, $outputFile);

        File::deleteDirectory($dir);

        return $outputFile;
    }
}