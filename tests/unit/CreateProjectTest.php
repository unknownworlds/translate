<?php


use App\Project;

class CreateProjectTest extends \Codeception\Test\Unit
{
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
            'file_handler' => 'SimpleJsonObject',
            'api_key' => 'test_api_key',
        ]);

        $this->assertEquals($project->name, 'Test project');
        $this->tester->seeRecord('App\Project', ['name' => 'Test project', 'api_key' => 'test_api_key']);
    }
}