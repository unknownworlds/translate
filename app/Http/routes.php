<?php
// Frontend
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');
Route::get('translations', 'TranslationsController@index');
Route::resource('users', 'UsersController');
Route::resource('languages', 'LanguagesController');
Route::resource('roles', 'RolesController');
Route::resource('projects', 'ProjectsController');

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
