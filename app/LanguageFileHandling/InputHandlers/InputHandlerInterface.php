<?php

namespace App\LanguageFileHandling\InputHandlers;

interface InputHandlerInterface
{

    /**
     * @return array
     */
    public function getParsedInput();

}