<?php

use App\Models\User;
use Tests\Support\FunctionalTester;

class UsersAdminCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amLoggedAs($I->grabRootUser());
    }

    public function listUsers(FunctionalTester $I)
    {
        $I->amOnPage('/users');

        $I->see('Test Root user');
        $I->see('root@localhost');
        $I->see('Root');
    }

    public function editUserAndRoles(FunctionalTester $I)
    {
        $user = $I->haveUser('Future admin', ['Test language admin']);
        $languageAdminRole = $user->roles->first();

        $I->amOnPage('/users');
        $I->click(['xpath' => "//a[contains(@href, '/users/{$user->id}/edit')]"]);
        $I->seeCurrentUrlEquals("/users/{$user->id}/edit");

        $I->seeElement("form[action$=\"/users/{$user->id}\"] input[name=_method][value=PATCH]");
        $I->seeInField('name', 'Future admin');
        $I->seeInField('email', 'future.admin@localhost');
        $I->seeCheckboxIsChecked("input[name='userRoles[]'][value='{$languageAdminRole->id}']");
        $I->dontSeeCheckboxIsChecked("input[name='userRoles[]'][value='1']");

        $I->fillField('name', 'Current admin');
        $I->checkOption("input[name='userRoles[]'][value='1']");
        $I->uncheckOption("input[name='userRoles[]'][value='{$languageAdminRole->id}']");
        $I->click('Save user');

        $I->seeCurrentUrlEquals('/users');
        $user = User::findOrFail($user->id);
        $I->assertSame('Current admin', $user->name);
        $I->assertSame(['Root'], $user->roles->pluck('name')->all());
    }

    public function removeAllRoles(FunctionalTester $I)
    {
        $user = $I->haveUser('Former admin', ['Admin']);

        $I->amOnPage("/users/{$user->id}/edit");
        $I->uncheckOption("input[name='userRoles[]'][value='{$user->roles->first()->id}']");
        $I->click('Save user');

        $I->assertCount(0, User::findOrFail($user->id)->roles);
    }

    public function deleteUser(FunctionalTester $I)
    {
        $user = $I->haveUser('Spammer');

        $I->amOnPage('/users');
        $I->click('Delete', "form[action$=\"/users/{$user->id}\"]");

        $I->seeCurrentUrlEquals('/users');
        $I->dontSeeRecord(User::class, ['id' => $user->id]);
    }
}
