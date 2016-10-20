<?php
/**
 * Copyright (c) Unknown Worlds Entertainment, 2016.
 * Created by Lukas Nowaczek <lukas@unknownworlds.com> <@lnowaczek>
 * Visit http://unknownworlds.com/
 * This file is a part of proprietary software.
 */

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

// API
Route::group(array('prefix' => 'api'), function () {
    // Frontend
    Route::get('/base-strings', 'TranslationsController@baseStrings');
    Route::post('/base-strings', 'TranslationsController@storeBaseString');
    Route::post('/base-strings/trash', 'TranslationsController@trashBaseString');
    Route::get('/strings', 'TranslationsController@strings');
    Route::get('/check-privileges', 'TranslationsController@checkPrivileges');
    Route::post('/strings/store', 'TranslationsController@store');
    Route::post('/strings/trash', 'TranslationsController@trash');
    Route::post('/strings/accept', 'TranslationsController@accept');
    Route::post('/strings/vote', 'TranslationsController@vote');
    Route::get('/strings/history', 'TranslationsController@translationHistory');
    Route::get('/strings/users', 'TranslationsController@users');
    Route::get('/strings/admins', 'TranslationsController@admins');
    Route::get('/admin-whiteboard/{project_id}/{language_id}', 'AdminWhiteboardsController@find');
    Route::post('/admin-whiteboard', 'AdminWhiteboardsController@store');

    // Backend
    Route::post('/strings/translation-file', 'TranslationFilesController@storeInputFile');
    Route::get('/strings/translation-file', 'TranslationFilesController@processOutputFiles');
});