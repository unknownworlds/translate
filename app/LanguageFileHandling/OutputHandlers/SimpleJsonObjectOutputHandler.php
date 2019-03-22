<?php

namespace App\LanguageFileHandling\OutputHandlers;

use File;

class SimpleJsonObjectOutputHandler implements OutputHandlerInterface
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
            $output = $language['strings'];

            if ($language['skip_in_output']) {
                continue;
            }

            // Total hack :(
            if ($language['is_rtl']) {
                foreach ($output as $key => $value) {
                    $output[$key] = $this->utf8_strrev($value);
                }
            }

            $output = json_encode($output, JSON_PRETTY_PRINT);

            $file = fopen($dir . '/' . $language['name'] . '.json', 'w+');
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

    private function utf8_strrev($str)
    {
        preg_match_all('/./us', $str, $ar);
        return join('', array_reverse($ar[0]));
    }
}