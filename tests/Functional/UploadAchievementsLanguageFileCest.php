<?php

use App\Models\BaseString;
use Tests\Support\FunctionalTester;
use Tests\Support\Helper\Fixtures;

class UploadAchievementsLanguageFileCest
{
    public function tryToTest(FunctionalTester $I)
    {
        $I->wantTo('upload Steam Achievements translation file');

        $I->amOnPage('/');
        $I->click('Login', '.navbar-right');

        $I->fillField('email', Fixtures::ROOT_EMAIL);
        $I->fillField('password', Fixtures::ROOT_PASSWORD);
        $I->click('Login', 'button');
        $I->see('Test Root user');

        $I->click('Projects');
        $I->click('Import');
        $I->seeElement('form[enctype="multipart/form-data"] input[name=_token]');
        $I->attachFile('input#file', '../../_data/steam_achievements.vdf');
        $I->click('Import', 'form');

        $I->seeResponseCodeIs(200);
        $I->seeCurrentUrlEquals('/projects');
        $I->assertSame(34, BaseString::where('project_id', 4)->count());
        $I->seeRecord(BaseString::class, [
            'project_id' => 4,
            'key' => 'NEW_ACHIEVEMENT_5_1_NAME',
            'text' => 'Getting Your Feet Wet',
        ]);
    }
}
