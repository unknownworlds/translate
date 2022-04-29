<?php

class UploadLanguageFileCept
{
    public function tryToTest(AcceptanceTester $I)
    {
        $I = new ApiTester($scenario);
        $I->wantTo('test upload of an translation file');

        $I->seeRecord('App\Models\Project', ['name' => 'API test project']);

        $I->sendPOST('strings/translation-file', [
            'api_key' => 'test_api_key_wow',
            'data' => file_get_contents('tests/_data/English.json')
        ]);

        $I->see('"Success!"');
    }
}
