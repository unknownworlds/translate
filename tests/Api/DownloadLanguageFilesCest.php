<?php
use Tests\Support\ApiTester;

class DownloadLanguageFilesCest
{
    public function tryToTest(ApiTester $I)
    {
        $I->wantTo('test download of translation files *.zip');

        $I->seeRecord('App\Models\Project', ['name' => 'Another test project']);

        $I->sendGET('strings/translation-file', [
            'api_key' => 'another_test_project'
        ]);

        $I->seeHttpHeader('Content-Disposition', 'attachment; filename=output.zip');
        $I->seeHttpHeader('Content-Type', 'application/zip');
        $I->assertGreaterThan(50000, $I->grabHttpHeader('Content-Length'));

        // A JSON file per language; without any translations every one falls back to the English base strings
        $files = $I->grabZipEntriesFromResponse();
        $I->assertCount(11, $files);
        $I->assertArrayHasKey('Test language.json', $files);
        $strings = json_decode($files['Test language.json'], true);
        // The faker-generated dump has a few duplicate keys
        $I->assertCount(\App\Models\BaseString::where('project_id', 3)->distinct()->count('key'), $strings);
        $I->assertGreaterThan(450, count($strings));
        $I->assertSame(json_decode($files['ti_ET.json'], true), $strings);
    }
}

