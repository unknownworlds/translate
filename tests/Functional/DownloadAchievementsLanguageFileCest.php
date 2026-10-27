<?php

use App\Models\BaseString;
use App\Models\TranslatedString;
use Tests\Support\FunctionalTester;
use Tests\Support\Helper\Fixtures;

class DownloadAchievementsLanguageFileCest
{
    public function tryToTest(FunctionalTester $I)
    {
        $I->wantTo('download Steam Achievements translation files');

        $I->amOnPage('/');
        $I->fillField('email', Fixtures::ROOT_EMAIL);
        $I->fillField('password', Fixtures::ROOT_PASSWORD);
        $I->click('Login', 'button');

        $I->amOnPage('/projects/4/import');
        $I->attachFile('input#file', '../../_data/steam_achievements.vdf');
        $I->click('Import', 'form');

        $baseString = BaseString::where(['project_id' => 4, 'key' => 'NEW_ACHIEVEMENT_5_1_NAME'])->firstOrFail();
        TranslatedString::create([
            'project_id' => 4,
            'language_id' => 1,
            'base_string_id' => $baseString->id,
            'user_id' => 101,
            'text' => 'Zamoczone "stopy"',
            'is_accepted' => true,
        ]);

        $I->amOnPage('/projects');
        $I->click('Export');

        $I->seeResponseCodeIs(200);
        $I->seeResponseHeaderContains('Content-Disposition', 'output.zip');
        $files = $I->grabZipEntriesFromResponse();

        // One file per language, all of them have steam_api_name set in the dump
        $I->assertCount(11, $files);
        $I->assertArrayHasKey('Test language.vdf', $files);
        $I->assertStringContainsString('"Language"    "test"', $files['Test language.vdf']);
        $I->assertStringContainsString('"NEW_ACHIEVEMENT_5_1_NAME" "Zamoczone \"stopy\""', $files['Test language.vdf']);
        // Untranslated strings fall back to English
        $I->assertStringContainsString('"NEW_ACHIEVEMENT_5_1_DESC" "Dive for the very first time"', $files['Test language.vdf']);
        $I->assertStringContainsString('"NEW_ACHIEVEMENT_5_1_NAME" "Getting Your Feet Wet"', $files['ti_ET.vdf']);
    }
}
