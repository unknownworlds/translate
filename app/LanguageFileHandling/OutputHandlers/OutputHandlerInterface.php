<?php

namespace App\LanguageFileHandling\OutputHandlers;


interface OutputHandlerInterface {
	public function __construct($project, $translations);

	public function getOutputFile();
}