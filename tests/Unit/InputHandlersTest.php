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

    public function testSimpleJsonRejectsCorruptedInput()
    {
        $this->assertNull((new SimpleJsonObjectInputHandler('{"Menu.Play": "Play", oops}'))->getParsedInput());
        $this->assertNull((new SimpleJsonObjectInputHandler(''))->getParsedInput());
    }

    public function testIni()
    {
        $raw = "\xEF\xBB\xBFMenu.Play = \"Play\"\r\nMenu.Equation = \"1 + 1 = 2\"\n; comment line\n\nMenu.Quit = \"Quit\"\n"
            ."Menu.Range = \"Set range = \"far\" in options\"\nMenu.Chain = \"a = \"b\" = \"c\"\"\n"
            ."Menu.Aligned        = \"Padded key\"   \r\n";

        $handler = new INIInputHandler($raw);

        $this->assertSame([
            'Menu.Play' => 'Play',
            'Menu.Equation' => '1 + 1 = 2',
            'Menu.Quit' => 'Quit',
            'Menu.Range' => 'Set range = "far" in options',
            'Menu.Chain' => 'a = "b" = "c"',
            'Menu.Aligned' => 'Padded key',
        ], $handler->getParsedInput());
    }

    public function testIniNaturalSelection2File()
    {
        $strings = (new INIInputHandler($this->ns2File()))->getParsedInput();

        // 3681 lines, 6 keys appear twice (the last one wins)
        $this->assertCount(3675, $strings);
        $this->assertSame('Game has not started', $strings['ABM_GAME_NOT_STARTED']);
        $this->assertSame('Female', $strings['CUSTOMIZE_MENU_SEX_MALE']);
        // Escaped quotes and newlines stay escaped, the export writes them back the same way
        $this->assertSame('Some lights emit a subtle \"Godrays\" effect.', $strings['ATMOSPHERICS_TOOLTIP']);
        $this->assertStringContainsString('die.\nYou can switch', $strings['AUTO_TEAM_BALANCE_TOOLTIP']);
    }

    public function testIniNaturalSelection2FileKeys()
    {
        $strings = (new INIInputHandler($this->ns2File()))->getParsedInput();

        $this->assertArrayHasKey('ABM_EVOLVE_FOR', $strings);
        $this->assertSame('Soldier acknowledges', $strings['MARINE_ALERT_ACKNOWLEDGE'] ?? null);
        $this->assertSame([], array_filter(array_keys($strings), fn ($key) => preg_match('/\s|\xEF\xBB\xBF/', $key)));
        // Keys keep their other characters, as the game looks them up
        $this->assertArrayHasKey('BINDINGS_WEAPON_#1', $strings);
        $this->assertArrayHasKey('COMBINDINGS_GRID_SPOT_10_(DEFAULT_G)', $strings);
    }

    public function testCsv()
    {
        $raw = "Menu.Play,Play\nMenu.Quit,\"Quit, really?\"\nMenu.Multiline,First\\nSecond &amp; third\nbroken line\nKey,Text,Extra\n"
            ."Menu.Double,\"Say \"\"hi\"\" now\"\nMenu.Backslash,\"Say \\\"hi\\\" now\"";

        $handler = new CSVInputHandler($raw);

        $this->assertSame([
            'Menu.Play' => 'Play',
            'Menu.Quit' => 'Quit, really?',
            'Menu.Multiline' => "First\nSecond & third",
            'Menu.Double' => 'Say "hi" now',
            // Backslash is the escape character: the quote after it doesn't end the field and both are kept
            'Menu.Backslash' => 'Say \"hi\" now',
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

    private function ns2File(): string
    {
        return file_get_contents(codecept_data_dir('../../_data/NS2_enUS.txt'));
    }
}
