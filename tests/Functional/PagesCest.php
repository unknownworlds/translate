<?php

use App\Models\Page;
use Tests\Support\FunctionalTester;

class PagesCest
{
    public function showPageBySlug(FunctionalTester $I)
    {
        $I->amOnPage('/pages/guidelines');

        $I->seeResponseCodeIs(200);
        $I->see('test rawr');
    }

    public function showMissingPage(FunctionalTester $I)
    {
        $I->amOnPage('/pages/does-not-exist');

        $I->seeResponseCodeIs(404);
    }

    public function homePageShowsNews(FunctionalTester $I)
    {
        $I->amLoggedAs($I->grabRootUser());
        Page::where('title', 'Home')->update(['content' => '<p>Fresh news from the team</p>']);

        $I->amOnPage('/');

        $I->see('Fresh news from the team');
    }

    public function createEditAndDeletePage(FunctionalTester $I)
    {
        $I->amLoggedAs($I->grabRootUser());

        $I->amOnPage('/pages');
        $I->click('Add new');
        $I->seeElement('form[action$="/pages"][method=POST] input[name=_token]');
        $I->fillField('title', 'Frequently asked questions');
        $I->fillField('content', '<p>Answers</p>');
        $I->click('Add page');

        $I->seeCurrentUrlEquals('/pages');
        $page = $I->grabRecord(Page::class, ['slug' => 'frequently-asked-questions']);
        $I->amOnPage('/pages/frequently-asked-questions');
        $I->see('Answers');

        $I->amOnPage("/pages/{$page->id}/edit");
        $I->seeElement("form[action$=\"/pages/{$page->id}\"] input[name=_method][value=PATCH]");
        $I->seeInField('title', 'Frequently asked questions');
        $I->seeInField('content', '<p>Answers</p>');
        $I->fillField('title', 'Instructions');
        $I->click('Save page');

        $I->seeCurrentUrlEquals('/pages');
        $I->seeRecord(Page::class, ['id' => $page->id, 'title' => 'Instructions', 'slug' => 'instructions']);

        $I->click('Delete', "form[action$=\"/pages/{$page->id}\"]");

        $I->dontSeeRecord(Page::class, ['id' => $page->id]);
    }
}
