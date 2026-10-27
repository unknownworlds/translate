<?php

use App\Models\BaseString;
use App\Models\TranslatedString;
use Tests\Support\ApiTester;

/**
 * Root-only base string management of the private API.
 */
class BaseStringApiCest
{
    public function nonRootIsForbidden(ApiTester $I)
    {
        $I->amLoggedAs($I->haveUser('Global admin', ['Admin']));

        $I->sendPost('base-strings', ['project_id' => 1, 'key' => 'New.key', 'text' => 'New']);
        $I->seeResponseCodeIs(403);
        $I->sendPost('base-strings/trash', ['id' => 1]);
        $I->seeResponseCodeIs(403);
        $I->sendPost('base-strings/lock', ['id' => 1]);
        $I->seeResponseCodeIs(403);
        $I->sendPost('base-strings/restore-translations', ['id' => 1]);
        $I->seeResponseCodeIs(403);
        $I->sendPost('tools/mark-quality-controlled-string', ['base_string_id' => 1]);
        $I->seeResponseCodeIs(403);

        $I->dontSeeRecord(BaseString::class, ['key' => 'New.key']);
    }

    public function addBaseString(ApiTester $I)
    {
        $I->amLoggedAs($I->grabRootUser());

        $I->sendPost('base-strings', ['project_id' => 1, 'key' => 'New.key', 'text' => 'Brand new string']);

        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson(['key' => 'New.key', 'text' => 'Brand new string']);
        $I->seeRecord(BaseString::class, ['project_id' => 1, 'key' => 'New.key', 'text' => 'Brand new string']);
    }

    public function changingBaseStringDropsItsTranslations(ApiTester $I)
    {
        $I->amLoggedAs($I->grabRootUser());
        $I->assertGreaterThan(0, TranslatedString::where('base_string_id', 1)->count());

        $I->sendPost('base-strings', ['id' => 1, 'project_id' => 1, 'key' => 'Enim aut dolores ea.', 'text' => 'Fixed typo']);

        $I->seeResponseCodeIs(200);
        $I->seeRecord(BaseString::class, ['id' => 1, 'text' => 'Fixed typo']);
        $I->assertSame(0, TranslatedString::where('base_string_id', 1)->count());
    }

    public function trashBaseString(ApiTester $I)
    {
        $I->amLoggedAs($I->grabRootUser());

        $I->sendPost('base-strings/trash', ['id' => 1]);

        $I->seeResponseContains('Base string deleted.');
        $I->dontSeeRecord(BaseString::class, ['id' => 1]);
        $I->assertTrue(BaseString::withTrashed()->findOrFail(1)->trashed());
    }

    public function toggleLock(ApiTester $I)
    {
        $I->amLoggedAs($I->grabRootUser());

        $I->sendPost('base-strings/lock', ['id' => 1]);
        $I->seeRecord(BaseString::class, ['id' => 1, 'locked' => true]);

        $I->sendPost('base-strings/lock', ['id' => 1]);
        $I->seeRecord(BaseString::class, ['id' => 1, 'locked' => false]);
    }

    public function toggleQualityControl(ApiTester $I)
    {
        $I->amLoggedAs($I->grabRootUser());

        $I->sendPost('tools/mark-quality-controlled-string', ['base_string_id' => 1]);
        $I->seeRecord(BaseString::class, ['id' => 1, 'quality_controlled' => true]);

        $I->sendPost('tools/mark-quality-controlled-string', ['base_string_id' => 1]);
        $I->seeRecord(BaseString::class, ['id' => 1, 'quality_controlled' => false]);
    }

    public function restoreTranslations(ApiTester $I)
    {
        $I->amLoggedAs($I->grabRootUser());
        $deleted = TranslatedString::create([
            'project_id' => 1, 'language_id' => 2, 'base_string_id' => 3, 'user_id' => 5,
            'text' => 'Przywrócone', 'is_accepted' => true,
        ]);
        $deleted->delete();
        // Languages that still have an accepted translation are left alone
        $current = TranslatedString::where(['base_string_id' => 3, 'language_id' => 1])->firstOrFail();
        $current->update(['is_accepted' => true]);

        $I->sendPost('base-strings/restore-translations', ['id' => 3]);

        $I->seeResponseContains('1 deleted strings restored');
        $I->seeRecord(TranslatedString::class, ['id' => $deleted->id, 'deleted_at' => null]);
    }
}
