<?php

use App\Models\User;

$I = new ApiTester($scenario);
$I->wantTo('add a translation with incorrect base_string_id and project_id pair');

$user = User::create(['name' => 'Testerro!', 'email' => 'testerro@localhost', 'password' => Hash::make('password')]);

$I->amLoggedAs($user);

$I->seeRecord('App\Models\User', ['name' => 'Testerro!']);

// Correct data
$I->sendPOST('strings/store', [
    'project_id' => 1,
    'language_id' => 1,
    'base_string_id' => 1500,
    'text' => 'Lorem ipsum',
]);

$I->seeResponseIsJson();
$I->canSeeResponseContains('"project_id":1,"language_id":"1","base_string_id":1500');

// Malicious data - wrong project_id
$I->sendPOST('strings/store', [
    'project_id' => 2,
    'language_id' => 1,
    'base_string_id' => 1500,
    'text' => 'Lorem ipsum dolor sit amet',
]);

$I->seeResponseIsJson();
$I->canSeeResponseContains('"project_id":1,"language_id":"1","base_string_id":1500');

