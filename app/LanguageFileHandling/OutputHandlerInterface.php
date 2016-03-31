<?php

namespace App\LanguageFileHandling;


interface OutputHandlerInterface {
	public function __construct($project, $translations);

	public function getOutputFile();
}