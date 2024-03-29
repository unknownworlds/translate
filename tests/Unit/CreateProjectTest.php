<?php


namespace Tests\Unit;

use App\Models\Project;

class CreateProjectTest extends \Codeception\Test\Unit
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testCreatingProject()
    {
        $project = Project::create([
            'name' => 'Test project',
            'data_input_handler' => \App\LanguageFileHandling\InputHandlers\InputHandlerFactory::SIMPLE_JSON,
            'data_output_handler' => \App\LanguageFileHandling\OutputHandlers\OutputHandlerFactory::SIMPLE_JSON,
            'api_key' => 'test_api_key',
        ]);

        $this->assertEquals($project->name, 'Test project');
        $this->tester->seeRecord('App\Models\Project', ['name' => 'Test project', 'api_key' => 'test_api_key']);
    }
}
