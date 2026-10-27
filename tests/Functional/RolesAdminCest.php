<?php

use App\Models\Role;
use Tests\Support\FunctionalTester;

class RolesAdminCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amLoggedAs($I->grabRootUser());
    }

    public function createEditAndDeleteRole(FunctionalTester $I)
    {
        $I->amOnPage('/roles');
        $I->click('Add new');
        $I->seeElement('form[action$="/roles"][method=POST] input[name=_token]');
        $I->fillField('name', 'Test language admin');
        $I->click('Add role');

        $I->seeCurrentUrlEquals('/roles');
        $I->see('Test language admin');
        $role = $I->grabRecord(Role::class, ['name' => 'Test language admin']);

        $I->amOnPage("/roles/{$role->id}/edit");
        $I->seeElement("form[action$=\"/roles/{$role->id}\"] input[name=_method][value=PATCH]");
        $I->seeInField('name', 'Test language admin');
        $I->fillField('name', 'ti_ET admin');
        $I->click('Save role');

        $I->seeCurrentUrlEquals('/roles');
        $I->seeRecord(Role::class, ['id' => $role->id, 'name' => 'ti_ET admin']);

        $I->click('Delete', "form[action$=\"/roles/{$role->id}\"]");

        $I->seeCurrentUrlEquals('/roles');
        $I->dontSeeRecord(Role::class, ['id' => $role->id]);
    }

    public function rejectDuplicateRole(FunctionalTester $I)
    {
        $I->amOnPage('/roles/create');
        $I->fillField('name', 'Root');
        $I->click('Add role');

        $I->seeCurrentUrlEquals('/roles/create');
        $I->seeElement('.alert-danger');
    }
}
