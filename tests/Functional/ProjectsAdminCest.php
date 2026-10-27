<?php

use App\Models\Project;
use Tests\Support\FunctionalTester;

class ProjectsAdminCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amLoggedAs($I->grabRootUser());
    }

    public function listProjects(FunctionalTester $I)
    {
        $I->amOnPage('/projects');

        $I->see('Subnautica');
        $I->see('1500');
        $I->see('secret_api_key');
        $I->see('Achievements test project');
        $I->seeLink('Import', '/projects/4/import');
        $I->seeLink('Export', '/projects/4/export');
    }

    public function createProject(FunctionalTester $I)
    {
        $I->amOnPage('/projects');
        $I->click('Add new');
        $I->seeCurrentUrlEquals('/projects/create');
        $I->seeElement('form[action$="/projects"][method=POST] input[name=_token]');

        $I->fillField('name', 'Brand new project');
        $I->selectOption('data_input_handler', 'INI file');
        $I->selectOption('data_output_handler', 'Predefined template');
        $I->fillField('api_key', 'brand_new_project_key');
        $I->fillField('output_template', 'Hello {greeting}');
        $I->click('Add project');

        $I->seeCurrentUrlEquals('/projects');
        $I->see('Brand new project');
        $I->seeRecord(Project::class, [
            'name' => 'Brand new project',
            'data_input_handler' => 4,
            'data_output_handler' => 3,
            'api_key' => 'brand_new_project_key',
            'output_template' => 'Hello {greeting}',
        ]);
    }

    public function rejectInvalidProject(FunctionalTester $I)
    {
        $I->amOnPage('/projects/create');
        $I->fillField('name', 'Subnautica');
        $I->fillField('api_key', 'too_short');
        $I->click('Add project');

        $I->seeCurrentUrlEquals('/projects/create');
        $I->seeElement('.alert-danger');
        $I->seeNumberOfElements('.alert-danger li', 2);
        $I->dontSeeRecord(Project::class, ['api_key' => 'too_short']);
    }

    public function editProject(FunctionalTester $I)
    {
        $I->amOnPage('/projects');
        $I->click('Edit', 'tr:nth-child(1)');
        $I->seeCurrentUrlEquals('/projects/4/edit');

        $I->seeElement('form[action$="/projects/4"] input[name=_method][value=PATCH]');
        $I->seeInField('name', 'Achievements test project');
        $I->seeOptionIsSelected('data_input_handler', 'Steam achievements');
        $I->seeOptionIsSelected('data_output_handler', 'Steam achievements');
        $I->seeInField('api_key', 'very_creative_key');

        $I->fillField('name', 'Renamed project');
        $I->selectOption('data_output_handler', 'INI file');
        $I->click('Save project');

        $I->seeCurrentUrlEquals('/projects');
        $I->seeRecord(Project::class, ['id' => 4, 'name' => 'Renamed project', 'data_output_handler' => 4]);
    }

    public function deleteProject(FunctionalTester $I)
    {
        $I->amOnPage('/projects');
        $I->seeElement('form[action$="/projects/2"] input[name=_method][value=DELETE]');
        $I->click('Delete', 'form[action$="/projects/2"]');

        $I->seeCurrentUrlEquals('/projects');
        $I->dontSee('API test project');
        $I->assertTrue(Project::withTrashed()->findOrFail(2)->trashed());
    }

    public function listBaseStrings(FunctionalTester $I)
    {
        $I->amOnPage('/projects/1/base-strings');

        $I->seeResponseCodeIs(200);
        $I->see('Enim aut dolores ea.');
    }
}
