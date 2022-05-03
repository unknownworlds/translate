<?php

use Tests\Support\FunctionalTester;

class RegisterCest
{
    public function tryToTest(FunctionalTester $I)
    {
        $I->wantTo('register a new account');

        $I->amOnPage('/');
        $I->click('Register');
        $I->fillField('name', 'Tester');
        $I->fillField('email', 'tester@unknownworlds.com');
        $I->fillField('password', 'kopytko5566');
        $I->fillField('password_confirmation', 'kopytko5566');
        $I->click('Register', 'form');

        $I->see('Tester');
        $I->seeRecord('App\Models\User', ['name' => 'Tester']);
    }
}
