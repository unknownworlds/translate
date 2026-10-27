<?php

use Codeception\Attribute\DataProvider;
use Codeception\Example;
use Tests\Support\FunctionalTester;

class AccessControlCest
{
    #[DataProvider('rootPages')]
    #[DataProvider('adminPages')]
    public function guestIsSentToLogin(FunctionalTester $I, Example $page)
    {
        $I->amOnPage($page[0]);

        $I->seeCurrentUrlEquals('/login');
    }

    #[DataProvider('rootPages')]
    #[DataProvider('adminPages')]
    public function translatorIsForbidden(FunctionalTester $I, Example $page)
    {
        $I->amLoggedAs($I->haveUser('Plain translator'));

        $I->amOnPage($page[0]);

        $I->seeResponseCodeIs(403);
    }

    #[DataProvider('rootPages')]
    public function adminIsForbiddenFromRootPages(FunctionalTester $I, Example $page)
    {
        $I->amLoggedAs($I->haveUser('Global admin', ['Admin']));

        $I->amOnPage($page[0]);

        $I->seeResponseCodeIs(403);
    }

    #[DataProvider('adminPages')]
    public function adminCanUseTools(FunctionalTester $I, Example $page)
    {
        $I->amLoggedAs($I->haveUser('Global admin', ['Admin']));

        $I->amOnPage($page[0]);

        $I->seeResponseCodeIs(200);
    }

    #[DataProvider('rootPages')]
    #[DataProvider('adminPages')]
    public function rootCanAccessEverything(FunctionalTester $I, Example $page)
    {
        $I->amLoggedAs($I->grabRootUser());

        $I->amOnPage($page[0]);

        $I->seeResponseCodeIs(200);
        $I->see($page[1]);
    }

    public function guestCannotCreatePages(FunctionalTester $I)
    {
        // PagesController has no middleware; PageRequest::authorize() is the only guard
        $I->amOnPage('/pages/create');

        $I->seeResponseCodeIs(403);
    }

    protected function rootPages(): array
    {
        return [
            ['/users', 'Users'],
            ['/users/101/edit', 'Edit user'],
            ['/roles', 'Roles'],
            ['/roles/create', 'New role'],
            ['/languages', 'Languages'],
            ['/languages/create', 'New language'],
            ['/projects', 'Projects'],
            ['/projects/create', 'New project'],
            ['/projects/4/import', 'Import data to Achievements test project'],
            ['/admin-tool/language-status', 'Test language'],
            ['/admin-tool/potential-admins', 'Admin tool: potential admins'],
            ['/admin-tool/audit', 'Admin tool: audit'],
            ['/stats/translations-per-day', 'Translations'],
            ['/stats/users-per-day', 'Users'],
        ];
    }

    protected function adminPages(): array
    {
        return [
            ['/tools/translation-quality', 'Download PDFs'],
            ['/tools/translation-quality/strings?project_id=1', 'Subnautica'],
            ['/tools/file-import', 'Data input'],
            ['/tools/translations-transfer', 'Translations transfer'],
            ['/tools/translation-spreadsheet', 'Translation spreadsheet export'],
        ];
    }
}
