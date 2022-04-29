<?php

class UploadAchievementsLanguageFileCept
{
    public function tryToTest(AcceptanceTester $I)
    {
        $I = new FunctionalTester($scenario);
        $I->wantTo('upload Steam Achievements translation file');

        $I->amOnPage('/');
        $I->click('Login', '.navbar-right');

        $I->fillField('email', 'root@localhost');
        $I->fillField('password', 'SuperSecretPassword');
        $I->click('Login', 'button');
        $I->see('Test Root user');

        $I->click('Projects');
        $I->click('Import');
        $I->attachFile('input#file', 'steam_achievements.vdf');
        $I->click('Import', 'form');

        $I->see('34');
    }
}

