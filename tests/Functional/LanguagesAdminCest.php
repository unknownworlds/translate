<?php

use App\Models\Language;
use Tests\Support\FunctionalTester;

class LanguagesAdminCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amLoggedAs($I->grabRootUser());
    }

    public function createLanguage(FunctionalTester $I)
    {
        $I->amOnPage('/languages');
        $I->see('Test language');
        $I->click('Add new');
        $I->seeCurrentUrlEquals('/languages/create');
        $I->seeElement('form[action$="/languages"][method=POST] input[name=_token]');

        $I->fillField('name', 'Hebrew');
        $I->fillField('locale', 'he_IL');
        $I->fillField('steam_api_name', 'hebrew');
        $I->checkOption('is_rtl');
        $I->click('Add language');

        $I->seeCurrentUrlEquals('/languages');
        $I->see('Hebrew');
        $I->seeRecord(Language::class, [
            'name' => 'Hebrew',
            'locale' => 'he_IL',
            'steam_api_name' => 'hebrew',
            'is_rtl' => true,
            'skip_in_output' => false,
        ]);
    }

    public function rejectDuplicateLanguage(FunctionalTester $I)
    {
        $I->amOnPage('/languages/create');
        $I->fillField('name', 'Test language');
        $I->fillField('locale', 'te_ST');
        $I->click('Add language');

        $I->seeCurrentUrlEquals('/languages/create');
        $I->seeNumberOfElements('.alert-danger li', 2);
    }

    public function editLanguage(FunctionalTester $I)
    {
        Language::findOrFail(2)->update(['is_rtl' => true]);

        $I->amOnPage('/languages/2/edit');
        $I->seeElement('form[action$="/languages/2"] input[name=_method][value=PATCH]');
        $I->seeInField('name', 'ti_ET');
        $I->seeInField('locale', 'ti_ET');
        $I->seeCheckboxIsChecked('is_rtl');
        $I->dontSeeCheckboxIsChecked('skip_in_output');

        $I->fillField('name', 'Tigrinya');
        $I->uncheckOption('is_rtl');
        $I->checkOption('skip_in_output');
        $I->click('Save language');

        $I->seeCurrentUrlEquals('/languages');
        $I->seeRecord(Language::class, ['id' => 2, 'name' => 'Tigrinya', 'is_rtl' => false, 'skip_in_output' => true]);
    }

    public function deleteLanguage(FunctionalTester $I)
    {
        $I->amOnPage('/languages');
        $I->click('Delete', 'form[action$="/languages/11"]');

        $I->seeCurrentUrlEquals('/languages');
        $I->dontSeeRecord(Language::class, ['id' => 11]);
    }
}
