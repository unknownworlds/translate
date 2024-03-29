<?php

use Tests\Support\ApiTester;

class UploadAlternativeLanguageFileCest
{
    public function tryToTest(ApiTester $I)
    {
        $I->wantTo('test upload a translation file with modifications');

        $I->seeRecord('App\Models\Project', ['name' => 'API test project']);

        $I->sendPOST('strings/translation-file', [
            'api_key' => 'test_api_key_wow',
            'data' => file_get_contents('tests/_data/English.json')
        ]);
        $I->sendPOST('strings/translation-file', [
            'api_key' => 'test_api_key_wow',
            'data' => file_get_contents('tests/_data/English_alternative.json')
        ]);

        $I->see('"Success!"');
    }
}
