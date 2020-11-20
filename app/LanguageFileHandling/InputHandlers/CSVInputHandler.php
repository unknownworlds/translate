<?php

namespace App\LanguageFileHandling\InputHandlers;

use Request;

class CSVInputHandler implements InputHandlerInterface
{
    private $rawData;
    private $baseStrings;

    public function __construct()
    {
        $this->rawData = Request::get('data');
        $this->processInput();
    }

    /**
     * @return array
     */
    private function processInput()
    {
        $input  = explode("\n", $this->rawData);
        $output = [];

        foreach ($input as $line) {
            $string = str_getcsv($line);

            if (count($string) != 2) {
                continue;
            }

            $output[$string[0]] = str_replace('\n', "\n", $string[1]);
        }

        return $this->baseStrings = $output;
    }

    /**
     * @return array
     */
    public function getParsedInput()
    {
        return $this->baseStrings;
    }
}