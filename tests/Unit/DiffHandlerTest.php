<?php

namespace Tests\Unit;

use App\LanguageFileHandling\DataExportHandler;
use App\LanguageFileHandling\DiffHandler;
use App\Models\BaseString;
use App\Models\Log;
use App\Models\TranslatedString;

class DiffHandlerTest extends \Codeception\Test\Unit
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    // "API test project" has no strings in the dump
    private const PROJECT_ID = 2;

    public function testAddsNewStrings()
    {
        // Empty strings and keys with a dot are "alternatives", not counted towards translation progress
        new DiffHandler(['Play' => 'Play', 'Play.alt' => 'Start', 'Empty' => ''], self::PROJECT_ID);

        $this->assertSame(
            ['Empty' => 1, 'Play' => 0, 'Play.alt' => 1],
            BaseString::where('project_id', self::PROJECT_ID)->orderBy('key')->pluck('alternative_or_empty', 'key')
                ->map(fn ($value) => (int) $value)->all()
        );
        $this->assertSame(3, Log::where(['project_id' => self::PROJECT_ID, 'log_type' => 2])->count());
    }

    public function testChangedStringsDropTheirTranslations()
    {
        new DiffHandler(['Play' => 'Play', 'Play.alt' => 'Start', 'Quit' => 'Quit'], self::PROJECT_ID);
        $play = $this->translate('Play', 'Graj');
        $playAlternative = $this->translate('Play.alt', 'Zacznij');
        $quit = $this->translate('Quit', 'Wyjdź');

        new DiffHandler(['Play' => 'Play now', 'Play.alt' => 'Start', 'Quit' => 'Quit'], self::PROJECT_ID);

        $this->assertSame('Play now', BaseString::where(['project_id' => self::PROJECT_ID, 'key' => 'Play'])->value('text'));
        $this->assertTrue($play->fresh()->trashed());
        // Alternatives of a changed string are invalidated as well
        $this->assertTrue($playAlternative->fresh()->trashed());
        $this->assertFalse($quit->fresh()->trashed());
    }

    public function testChangedStringsKeepAlternativesOfOtherProjects()
    {
        $this->markTestSkipped('Known bug: alternatives are looked up across all projects');

        new DiffHandler(['Play' => 'Play', 'Play.alt' => 'Start'], self::PROJECT_ID);
        $otherProjectAlternative = BaseString::create(['project_id' => 3, 'key' => 'Play.alt', 'text' => 'Start']);
        $otherProjectTranslation = TranslatedString::create([
            'project_id' => 3,
            'language_id' => 1,
            'base_string_id' => $otherProjectAlternative->id,
            'user_id' => 101,
            'text' => 'Zacznij',
            'is_accepted' => true,
        ]);

        new DiffHandler(['Play' => 'Play now', 'Play.alt' => 'Start'], self::PROJECT_ID);

        $this->assertFalse($otherProjectTranslation->fresh()->trashed());
    }

    public function testRemovedStringsGetSoftDeleted()
    {
        new DiffHandler(['Play' => 'Play', 'Quit' => 'Quit'], self::PROJECT_ID);
        $this->translate('Quit', 'Wyjdź');

        new DiffHandler(['Play' => 'Play'], self::PROJECT_ID);

        $this->assertSame(['Play'], BaseString::where('project_id', self::PROJECT_ID)->pluck('key')->all());
        $this->assertTrue(BaseString::withTrashed()->where(['project_id' => self::PROJECT_ID, 'key' => 'Quit'])->first()->trashed());
    }

    public function testExportMergesAcceptedTranslationsOverEnglish()
    {
        new DiffHandler(['Play' => 'Play', 'Quit' => 'Quit', 'Options' => 'Options'], self::PROJECT_ID);
        $this->translate('Play', 'Graj');
        $this->translate('Quit', 'Wyjdź', accepted: false);
        $this->translate('Options', 'Opcje');
        BaseString::where(['project_id' => self::PROJECT_ID, 'key' => 'Options'])->first()->delete();

        $export = collect(DataExportHandler::prepareData(self::PROJECT_ID))->keyBy('name');

        $this->assertCount(11, $export);
        $this->assertSame(
            ['name' => 'Test language', 'is_rtl' => false, 'skip_in_output' => false, 'steam_api_name' => 'test'],
            collect($export['Test language'])->except('strings')->all()
        );
        $this->assertSame(['Play' => 'Graj', 'Quit' => 'Quit'], $export['Test language']['strings']);
        $this->assertSame(['Play' => 'Play', 'Quit' => 'Quit'], $export['ti_ET']['strings']);
    }

    private function translate(string $key, string $text, bool $accepted = true): TranslatedString
    {
        return TranslatedString::create([
            'project_id' => self::PROJECT_ID,
            'language_id' => 1,
            'base_string_id' => BaseString::where(['project_id' => self::PROJECT_ID, 'key' => $key])->value('id'),
            'user_id' => 101,
            'text' => $text,
            'is_accepted' => $accepted,
        ]);
    }
}
