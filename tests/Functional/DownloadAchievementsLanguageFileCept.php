<?php

class DownloadAchievementsLanguageFileCept
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
        $I->click('Export');

        $I->haveHttpHeader('Content-Type', 'application/octet-stream');
    }
}

