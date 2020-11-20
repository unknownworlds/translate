<?php

namespace App\LanguageFileHandling\InputHandlers;

class INIInputHandler implements InputHandlerInterface
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
            $string = explode(' = "', $line);

            if (count($string) != 2) {
                continue;
            }

            $output[$string[0]] = substr(trim($string[1]), 0, -1);
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