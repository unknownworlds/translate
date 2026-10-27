<?php

namespace App\LanguageFileHandling\OutputHandlers;

use App\LanguageFileHandling\Zip;
use File;

class INIOutputHandler implements OutputHandlerInterface
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

            $strings = $language['strings'];
            $output = '';

            foreach ($strings as $key => $value) {
                $output .= $key . ' = "' . $value . "\"\n";
            }

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