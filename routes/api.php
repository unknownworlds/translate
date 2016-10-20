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

// Public API
Route::post('/strings/translation-file', 'TranslationFilesController@storeInputFile');
Route::get('/strings/translation-file', 'TranslationFilesController@processOutputFiles');