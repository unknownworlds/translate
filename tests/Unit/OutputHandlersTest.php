<?php

namespace Tests\Unit;

use App\LanguageFileHandling\OutputHandlers\INIOutputHandler;
use App\LanguageFileHandling\OutputHandlers\OutputHandlerFactory;
use App\LanguageFileHandling\OutputHandlers\PredefinedTemplateOutputHandler;
use App\LanguageFileHandling\OutputHandlers\SimpleJsonObjectOutputHandler;
use App\LanguageFileHandling\OutputHandlers\SteamAchievementsOutputHandler;
use App\Models\Project;
use Illuminate\Support\Facades\File;
use Tests\Support\Helper\Responses;

class OutputHandlersTest extends \Codeception\Test\Unit
{
    private const PROJECT_ID = 9999;

    private Project $project;

    protected function _before()
    {
        $this->project = new Project(['output_template' => "play={Menu.Play}\nquit={Menu.Quit}\n"]);
        $this->project->id = self::PROJECT_ID;
    }

    protected function _after()
    {
        File::deleteDirectory(public_path('output/'.self::PROJECT_ID));
    }

    public function testFactoryCreatesHandlers()
    {
        $handlers = [
            OutputHandlerFactory::SIMPLE_JSON => SimpleJsonObjectOutputHandler::class,
            OutputHandlerFactory::STEAM_ACHIEVEMENTS => SteamAchievementsOutputHandler::class,
            OutputHandlerFactory::PREDEFINED_TEMPLATE => PredefinedTemplateOutputHandler::class,
            OutputHandlerFactory::INI => INIOutputHandler::class,
        ];

        foreach ($handlers as $type => $class) {
            $this->assertInstanceOf($class, OutputHandlerFactory::getFileHandler($type, $this->project, []));
        }

        $this->expectExceptionMessage('Undefined output handler.');
        OutputHandlerFactory::getFileHandler(999, $this->project, []);
    }

    public function testSimpleJson()
    {
        $files = $this->generate(new SimpleJsonObjectOutputHandler($this->project, $this->translations()));

        $this->assertSame(['Hebrew.json', 'Polish.json'], array_keys($files));
        $this->assertSame(['Menu.Play' => 'Graj', 'Menu.Quit' => "Wyjdź\n\"teraz\""], json_decode($files['Polish.json'], true));
        // RTL languages get their strings reversed for the game's text renderer
        $this->assertSame(['Menu.Play' => 'קחש', 'Menu.Quit' => 'Quit'], json_decode($files['Hebrew.json'], true));
    }

    public function testIni()
    {
        $files = $this->generate(new INIOutputHandler($this->project, $this->translations()));

        $this->assertSame(['Hebrew.txt', 'Polish.txt'], array_keys($files));
        $this->assertSame("Menu.Play = \"Graj\"\nMenu.Quit = \"Wyjdź\n\"teraz\"\"\n", $files['Polish.txt']);
    }

    public function testPredefinedTemplate()
    {
        $files = $this->generate(new PredefinedTemplateOutputHandler($this->project, $this->translations()));

        $this->assertSame(['Hebrew.txt', 'Polish.txt'], array_keys($files));
        $this->assertSame("play=Graj\nquit=Wyjdź\n\"teraz\"\n", $files['Polish.txt']);
        // Unlike the JSON output, templates don't reverse RTL strings
        $this->assertSame("play=שחק\nquit=tiuQ\n", $files['Hebrew.txt']);
    }

    public function testSteamAchievements()
    {
        $files = $this->generate(new SteamAchievementsOutputHandler($this->project, $this->translations()));

        // Languages without a Steam API name are skipped too
        $this->assertSame(['Polish.vdf'], array_keys($files));
        $this->assertStringContainsString('"Language"    "polish"', $files['Polish.vdf']);
        $this->assertStringContainsString('"Menu.Play" "Graj"', $files['Polish.vdf']);
        $this->assertStringContainsString('"Menu.Quit" "Wyjdź'."\n".'\"teraz\""', $files['Polish.vdf']);
    }

    public function testRegeneratingReplacesThePreviousArchive()
    {
        $this->generate(new SimpleJsonObjectOutputHandler($this->project, $this->translations()));

        $translations = $this->translations();
        unset($translations[1]);
        $files = $this->generate(new SimpleJsonObjectOutputHandler($this->project, $translations));

        $this->assertSame(['Polish.json'], array_keys($files));
    }

    /**
     * @return array<string, string>
     */
    private function generate($handler): array
    {
        $outputFile = $handler->getOutputFile();

        $this->assertSame(public_path().'/output/'.self::PROJECT_ID.'/output.zip', $outputFile);
        $this->assertFileExists($outputFile);
        // The working directory with the loose files gets cleaned up
        $this->assertSame([], File::directories(public_path('output/'.self::PROJECT_ID)));

        return Responses::readZip($outputFile);
    }

    private function translations(): array
    {
        return [
            [
                'name' => 'Polish',
                'is_rtl' => false,
                'skip_in_output' => false,
                'steam_api_name' => 'polish',
                'strings' => ['Menu.Play' => 'Graj', 'Menu.Quit' => "Wyjdź\n\"teraz\""],
            ],
            [
                'name' => 'Hebrew',
                'is_rtl' => true,
                'skip_in_output' => false,
                'steam_api_name' => null,
                'strings' => ['Menu.Play' => 'שחק', 'Menu.Quit' => 'tiuQ'],
            ],
            [
                'name' => 'Klingon',
                'is_rtl' => false,
                'skip_in_output' => true,
                'steam_api_name' => 'klingon',
                'strings' => ['Menu.Play' => 'Qapla', 'Menu.Quit' => 'Qapla'],
            ],
        ];
    }
}
