<?php

namespace App\LanguageFileHandling\InputHandlers;

class CSVInputHandler implements InputHandlerInterface
{
    private $rawData;
    private $translations;

    public function __construct($rawData)
    {
        $this->rawData = $rawData;
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

            $key  = $string[0];

            $text = $string[1];
            $text = str_replace('\n', "\n", $text);
            $text = html_entity_decode($text);

            $output[$key] = $text;
        }

        return $this->translations = $output;
    }

    /**
     * @return array
     */
    public function getParsedInput()
    {
        return $this->translations;
    }
}