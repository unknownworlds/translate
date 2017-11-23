<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Auth::routes();
Route::get('social-login/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('auth/social-callback/{provider}', 'Auth\LoginController@handleProviderCallback');

// Frontend - public
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');
Route::get('translations', 'TranslationsController@index');
Route::get('pages/{name}', 'PagesController@index');
Route::get('theme/{name}', 'ThemesController@index');
Route::get('rss', 'RssController@index');
Route::get('rss/base-strings/{id}', 'RssController@baseStrings');
Route::get('rss/translations/{project}/{language}', 'RssController@translations');

// Backend
Route::resource('users', 'UsersController');
Route::resource('languages', 'LanguagesController');
Route::resource('roles', 'RolesController');
Route::resource('projects', 'ProjectsController');
Route::get('admin-tool/language-status', 'AdminToolController@languageStatus');
Route::get('admin-tool/potential-admins', 'AdminToolController@potentialAdmins');
Route::get('admin-tool/audit', 'AdminToolController@audit');
Route::get('stats/translations-per-day', 'StatsController@translationsPerDay');
Route::get('stats/users-per-day', 'StatsController@usersPerDay');

Route::get('tools/translation-quality', 'ToolsController@translationQualityIndex');
Route::get('tools/translation-quality/strings', 'ToolsController@translationQualityStrings');
Route::get('tools/translation-quality/download', 'ToolsController@translationQualityDownload');
//Route::get('tools/file-import', 'ToolsController@fileImport');
//Route::post('tools/file-import', 'ToolsController@processFileImport');

// Private API
Route::group(['prefix' => 'api'], function () {
    Route::get('/project-handlers', 'TranslationsController@projectHandlers'); //TODO: perfect location, huh? wtf
    Route::get('/base-strings', 'TranslationsController@baseStrings');
    Route::post('/base-strings', 'TranslationsController@storeBaseString');
    Route::post('/base-strings/trash', 'TranslationsController@trashBaseString');
    Route::get('/strings', 'TranslationsController@strings');
    Route::get('/check-privileges', 'TranslationsController@checkPrivileges');
    Route::post('/strings/store', 'TranslationsController@store');
    Route::post('/strings/update', 'TranslationsController@update');
    Route::post('/strings/trash', 'TranslationsController@trash');
    Route::post('/strings/accept', 'TranslationsController@accept');
    Route::post('/strings/vote', 'TranslationsController@vote');
    Route::get('/strings/history', 'TranslationsController@translationHistory');
    Route::get('/strings/users', 'TranslationsController@users');
    Route::get('/strings/admins', 'TranslationsController@admins');
    Route::get('/admin-whiteboard/{project_id}/{language_id}', 'AdminWhiteboardsController@find');
	Route::post('/admin-whiteboard', 'AdminWhiteboardsController@store');
	Route::post('/tools/mark-quality-controlled-string', 'ToolsApiController@markQualityControlledString');
});