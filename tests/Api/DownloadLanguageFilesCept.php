<?php

class DownloadLanguageFilesCept
{
    public function tryToTest(AcceptanceTester $I)
    {
        $I = new ApiTester($scenario);
        $I->wantTo('test download of translation files *.zip');

        $I->seeRecord('App\Models\Project', ['name' => 'Another test project']);

        $I->sendGET('strings/translation-file', [
            'api_key' => 'another_test_project'
        ]);

        $I->seeHttpHeader('Content-Disposition', 'attachment; filename=output.zip');
        $I->seeHttpHeader('Content-Type', 'application/zip');
        $I->assertGreaterThan(50000, $I->grabHttpHeader('Content-Length'));
    }
}

