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
        // Files saved by Windows tools start with a UTF-8 byte order mark
        $input  = explode("\n", preg_replace('/^\xEF\xBB\xBF/', '', (string) $this->rawData));
        $output = [];

        foreach ($input as $line) {
            $string = explode(' = "', $line, 2);

            if (count($string) != 2) {
                continue;
            }

            // Keys may be padded with spaces to align the values
            $output[trim($string[0])] = substr(trim($string[1]), 0, -1);
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