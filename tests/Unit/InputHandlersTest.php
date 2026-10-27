<?php

namespace Tests\Unit;

use App\LanguageFileHandling\InputHandlers\CSVInputHandler;
use App\LanguageFileHandling\InputHandlers\InputHandlerFactory;
use App\LanguageFileHandling\InputHandlers\INIInputHandler;
use App\LanguageFileHandling\InputHandlers\SimpleJsonObjectInputHandler;
use App\LanguageFileHandling\InputHandlers\SteamAchievementsInputHandler;

class InputHandlersTest extends \Codeception\Test\Unit
{
    public function testFactoryCreatesHandlers()
    {
        $this->assertInstanceOf(SimpleJsonObjectInputHandler::class, InputHandlerFactory::getFileHandler(InputHandlerFactory::SIMPLE_JSON, '{}'));
        $this->assertInstanceOf(SteamAchievementsInputHandler::class, InputHandlerFactory::getFileHandler(InputHandlerFactory::STEAM_ACHIEVEMENTS, $this->achievementsFile()));
        $this->assertInstanceOf(INIInputHandler::class, InputHandlerFactory::getFileHandler(InputHandlerFactory::INI, ''));
        $this->assertInstanceOf(CSVInputHandler::class, InputHandlerFactory::getFileHandler(InputHandlerFactory::CSV, ''));
    }

    public function testFactoryRejectsManualAndUnknownHandlers()
    {
        foreach ([InputHandlerFactory::MANUAL, 999] as $type) {
            try {
                InputHandlerFactory::getFileHandler($type, '');
                $this->fail("Handler $type should not exist");
            } catch (\Exception $e) {
                $this->assertSame('Undefined input handler.', $e->getMessage());
            }
        }
    }

    public function testSimpleJson()
    {
        $handler = new SimpleJsonObjectInputHandler('{"Menu.Play": "Play", "Menu.Quit": "Quit\ngame", "Empty": ""}');

        $this->assertSame(['Menu.Play' => 'Play', 'Menu.Quit' => "Quit\ngame", 'Empty' => ''], $handler->getParsedInput());
    }

    public function testSimpleJsonWithRawNewlinesAndTabs()
    {
        // Not valid JSON - the game files contain raw newlines and tabs inside the strings
        $raw = "{\n\t\"Intro\": \"First line\nSecond\tline\",\n\t\"Outro\": \"Bye\"\n}\n";

        $handler = new SimpleJsonObjectInputHandler($raw);

        $this->assertSame(['Intro' => "First line\nSecond line", 'Outro' => 'Bye'], $handler->getParsedInput());
    }

    public function testIni()
    {
        $raw = "Menu.Play = \"Play\"\r\nMenu.Equation = \"1 + 1 = 2\"\n; comment line\n\nMenu.Quit = \"Quit\"";

        $handler = new INIInputHandler($raw);

        $this->assertSame([
            'Menu.Play' => 'Play',
            'Menu.Equation' => '1 + 1 = 2',
            'Menu.Quit' => 'Quit',
        ], $handler->getParsedInput());
    }

    public function testCsv()
    {
        $raw = "Menu.Play,Play\nMenu.Quit,\"Quit, really?\"\nMenu.Multiline,First\\nSecond &amp; third\nbroken line\nKey,Text,Extra";

        $handler = new CSVInputHandler($raw);

        $this->assertSame([
            'Menu.Play' => 'Play',
            'Menu.Quit' => 'Quit, really?',
            'Menu.Multiline' => "First\nSecond & third",
        ], $handler->getParsedInput());
    }

    public function testSteamAchievements()
    {
        $strings = (new SteamAchievementsInputHandler($this->achievementsFile()))->getParsedInput();

        $this->assertCount(34, $strings);
        $this->assertSame('Getting Your Feet Wet', $strings['NEW_ACHIEVEMENT_5_1_NAME']);
        $this->assertSame('Dive for the very first time', $strings['NEW_ACHIEVEMENT_5_1_DESC']);
    }

    public function testSteamAchievementsIgnoresNonEnglishFiles()
    {
        $raw = str_replace('"english"', '"polish"', $this->achievementsFile());

        $this->assertNull((new SteamAchievementsInputHandler($raw))->getParsedInput());
    }

    private function achievementsFile(): string
    {
        return file_get_contents(codecept_data_dir('../../_data/steam_achievements.vdf'));
    }
}
