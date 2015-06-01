<?php
// Frontend - public
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');
Route::get('translations', 'TranslationsController@index');
Route::get('pages/{name}', 'PagesController@index');

// Backend
Route::resource('users', 'UsersController');
Route::resource('languages', 'LanguagesController');
Route::resource('roles', 'RolesController');
Route::resource('projects', 'ProjectsController');
Route::get('tools/file-import', 'ToolsController@fileImport');
Route::post('tools/file-import', 'ToolsController@processFileImport');

// Auth
Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

// API
Route::group(array('prefix' => 'api'), function () {
	// Frontend
	Route::get('/base-strings', 'TranslationsController@baseStrings');
	Route::get('/strings', 'TranslationsController@strings');
	Route::get('/check-privileges', 'TranslationsController@checkPrivileges');
	Route::post('/strings/store', 'TranslationsController@store');
	Route::post('/strings/trash', 'TranslationsController@trash');
	Route::post('/strings/accept', 'TranslationsController@accept');
	Route::post('/strings/vote', 'TranslationsController@vote');
	Route::get('/strings/users', 'TranslationsController@users');
	Route::get('/strings/admins', 'TranslationsController@admins');

	// Backend
	Route::post('/strings/translation-file', 'TranslationFilesController@storeInputFile');
	Route::get('/strings/translation-file', 'TranslationFilesController@processOutputFiles');
});
