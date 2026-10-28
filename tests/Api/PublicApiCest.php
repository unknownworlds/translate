<?php

use App\Models\BaseString;
use Tests\Support\ApiTester;

/**
 * Public, API key authenticated endpoints called from game build pipelines.
 */
class PublicApiCest
{
    public function rejectUnknownApiKey(ApiTester $I)
    {
        $I->sendGet('strings/translation-file', ['api_key' => 'nope']);
        $I->seeResponseCodeIs(404);

        $I->sendPost('strings/translation-file', ['api_key' => 'nope', 'data' => '{}']);
        $I->seeResponseCodeIs(404);
    }

    public function uploadAddsUpdatesAndRemovesStrings(ApiTester $I)
    {
        $I->sendPost('strings/translation-file', [
            'api_key' => 'test_api_key_wow',
            'data' => json_encode(['Menu.Play' => 'Play', 'Menu.Quit' => 'Quit']),
        ]);
        $I->seeResponseContains('"Success!"');

        $I->sendPost('strings/translation-file', [
            'api_key' => 'test_api_key_wow',
            'data' => json_encode(['Menu.Play' => 'Play game', 'Menu.Options' => 'Options']),
        ]);
        $I->seeResponseContains('"Success!"');

        $I->assertSame(
            ['Menu.Options' => 'Options', 'Menu.Play' => 'Play game'],
            BaseString::where('project_id', 2)->orderBy('key')->pluck('text', 'key')->all()
        );
        $I->assertTrue(BaseString::withTrashed()->where(['project_id' => 2, 'key' => 'Menu.Quit'])->firstOrFail()->trashed());
    }

    public function uploadJsonWithByteOrderMark(ApiTester $I)
    {
        // The TrimStrings middleware strips the BOM, the input handlers don't handle it themselves
        $I->sendPost('strings/translation-file', [
            'api_key' => 'test_api_key_wow',
            'data' => "\xEF\xBB\xBF".json_encode(['Menu.Play' => 'Play']),
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('"Success!"');
        $I->assertSame(['Menu.Play' => 'Play'], BaseString::where('project_id', 2)->pluck('text', 'key')->all());
    }

    public function rejectCorruptedJsonUpload(ApiTester $I)
    {
        $I->sendPost('strings/translation-file', [
            'api_key' => 'another_test_project',
            'data' => '{"Menu.Play": "Play", oops}',
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('"Fail, probably the input file is corrupted"');
        // Nothing gets removed from the project
        $I->assertSame(500, BaseString::where('project_id', 3)->count());
    }

    public function corsHeadersForBrowserClients(ApiTester $I)
    {
        $I->haveHttpHeader('Origin', 'https://example.com');
        $I->haveHttpHeader('Access-Control-Request-Method', 'POST');

        $I->sendOptions('strings/translation-file');

        $I->seeResponseCodeIsSuccessful();
        $I->seeHttpHeader('Access-Control-Allow-Origin', '*');
        $I->seeHttpHeader('Access-Control-Allow-Methods', 'POST');

        $I->deleteHeader('Access-Control-Request-Method');
        $I->sendGet('strings/translation-file', ['api_key' => 'another_test_project']);
        $I->seeHttpHeader('Access-Control-Allow-Origin', '*');
    }
}
