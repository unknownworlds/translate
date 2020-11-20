<?php

namespace App\LanguageFileHandling\InputHandlers;

use Request;
use VdfParser\Parser;

class SteamAchievementsInputHandler implements InputHandlerInterface
{
    private $rawData;
    private $translations;

    public function __construct($rawData)
    {
        $this->rawData = $rawData;
        $this->processInput();
    }

    /**
     * @return bool
     */
    private function processInput()
    {
        $parser = new Parser;
        $result = $parser->parse($this->rawData);

        if ($result['lang']['Language'] != 'english') {
            return false;
        }

        $this->translations = $result['lang']['Tokens'];

        return true;
    }

    /**
     * @return array
     */
    public function getParsedInput()
    {
        $this->processInput();

        return $this->translations;
    }

    public function setRawInput($rawData)
    {
        $this->rawData = $rawData;
    }
}