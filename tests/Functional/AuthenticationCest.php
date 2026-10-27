<?php

use Tests\Support\FunctionalTester;
use Tests\Support\Helper\Fixtures;

class AuthenticationCest
{
    public function guestIsRedirectedToLogin(FunctionalTester $I)
    {
        $I->amOnPage('/');

        $I->seeCurrentUrlEquals('/login');
        $I->dontSeeAuthentication();
    }

    public function logInAndOut(FunctionalTester $I)
    {
        $I->amOnPage('/login');
        $I->fillField('email', Fixtures::ROOT_EMAIL);
        $I->fillField('password', Fixtures::ROOT_PASSWORD);
        $I->click('Login', 'button');

        $I->seeAuthentication();
        $I->seeCurrentUrlEquals('/home');
        $I->see('Test Root user');

        $I->submitForm('#logout-form', []);

        $I->dontSeeAuthentication();
        $I->seeCurrentUrlEquals('/login');
    }

    public function rejectWrongPassword(FunctionalTester $I)
    {
        $I->amOnPage('/login');
        $I->fillField('email', Fixtures::ROOT_EMAIL);
        $I->fillField('password', 'wrong password');
        $I->click('Login', 'button');

        $I->dontSeeAuthentication();
        $I->see('These credentials do not match our records.');
    }

    public function rejectRegistrationWithTakenEmail(FunctionalTester $I)
    {
        $I->amOnPage('/register');
        $I->fillField('name', 'Someone new');
        $I->fillField('email', Fixtures::ROOT_EMAIL);
        $I->fillField('password', 'kopytko5566');
        $I->fillField('password_confirmation', 'kopytko5566');
        $I->click('Register', 'form');

        $I->dontSeeAuthentication();
        $I->see('The email has already been taken.');
        $I->dontSeeRecord('App\Models\User', ['name' => 'Someone new']);
    }

    public function showPasswordResetForm(FunctionalTester $I)
    {
        $I->amOnPage('/password/reset');

        $I->see('Reset Password');
        $I->seeElement('form[action$="/password/email"] input[name=email]');
    }

    public function switchTheme(FunctionalTester $I)
    {
        $root = $I->grabRootUser();
        $I->amLoggedAs($root);

        $I->amOnPage('/theme/dark');
        $I->seeRecord('App\Models\User', ['id' => $root->id, 'theme' => 'dark']);

        $I->amOnPage('/theme/anything-else');
        $I->seeRecord('App\Models\User', ['id' => $root->id, 'theme' => 'light']);
    }
}
