<?php

use App\Models\BaseString;
use App\Models\TranslatedString;
use Tests\Support\FunctionalTester;

class ToolsCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amLoggedAs($I->haveUser('Global admin', ['Admin']));
    }

    public function importTranslationFile(FunctionalTester $I)
    {
        $I->amOnPage('/tools/file-import');
        $I->seeElement('form[action$="/tools/file-import"][method=POST] input[name=_token]');

        $I->selectOption('project_id', 'Subnautica');
        $I->selectOption('language_id', 'Test language');
        $I->selectOption('user_id', 'Test Root user');
        $I->selectOption('input_type', 'Simple JSON');
        $I->checkOption('accept_translations');
        $I->fillField('data', json_encode([
            'Enim aut dolores ea.' => 'Zaimportowane tłumaczenie',
            'In ut minima iste.' => '',
            'Unknown key' => 'Whatever',
        ]));
        $I->click('Process');

        $I->seeCurrentUrlEquals('/tools/file-import');
        $I->see('Import complete');
        $I->seeRecord(TranslatedString::class, [
            'project_id' => 1,
            'language_id' => 1,
            'base_string_id' => 1,
            'user_id' => 101,
            'text' => 'Zaimportowane tłumaczenie',
            'is_accepted' => true,
        ]);
        // The imported translation replaces previously accepted ones
        $I->assertSame(1, TranslatedString::where(['base_string_id' => 1, 'language_id' => 1, 'is_accepted' => true])->count());
    }

    public function importSkipsLockedStrings(FunctionalTester $I)
    {
        BaseString::whereKey(1)->update(['locked' => true]);

        $I->amOnPage('/tools/file-import');
        $I->selectOption('project_id', 'Subnautica');
        $I->selectOption('language_id', 'Test language');
        $I->fillField('data', json_encode(['Enim aut dolores ea.' => 'Should not be imported']));
        $I->click('Process');

        $I->see('Import complete');
        $I->dontSeeRecord(TranslatedString::class, ['text' => 'Should not be imported']);
    }

    public function rejectUndecodableImport(FunctionalTester $I)
    {
        $I->amOnPage('/tools/file-import');
        $I->selectOption('input_type', 'CSV file');
        $I->fillField('data', 'no commas in here');
        $I->click('Process');

        $I->seeCurrentUrlEquals('/tools/file-import');
        $I->see("Can't decode the input");
    }

    public function transferTranslationsBetweenProjects(FunctionalTester $I)
    {
        $source = BaseString::create(['project_id' => 1, 'key' => 'Shared.key', 'text' => 'Shared string']);
        $target = BaseString::create(['project_id' => 3, 'key' => 'Shared.key', 'text' => 'Shared string']);
        $accepted = TranslatedString::create([
            'project_id' => 1, 'language_id' => 2, 'base_string_id' => $source->id, 'user_id' => 5,
            'text' => 'Wspólny tekst', 'is_accepted' => true,
        ]);

        $I->amOnPage('/tools/translations-transfer');
        $I->selectOption('source_project', 'Subnautica');
        $I->selectOption('target_project', 'Another test project');
        $I->click('Process');

        // Faker-generated keys of both fixture projects overlap, so the total count varies
        $I->see('Copied', '.alert-info');
        $I->seeRecord(TranslatedString::class, [
            'project_id' => 3,
            'base_string_id' => $target->id,
            'language_id' => 2,
            'user_id' => 5,
            'text' => 'Wspólny tekst',
            'is_accepted' => true,
        ]);
    }

    public function rejectTransferWithinTheSameProject(FunctionalTester $I)
    {
        $I->amOnPage('/tools/translations-transfer');
        $I->selectOption('source_project', 'Subnautica');
        $I->selectOption('target_project', 'Subnautica');
        $I->click('Process');

        $I->see('Source and target cannot be the same');
    }

    public function downloadTranslationSpreadsheet(FunctionalTester $I)
    {
        $accepted = TranslatedString::where(['project_id' => 1, 'is_accepted' => true])->firstOrFail();

        $I->amOnPage('/tools/translation-spreadsheet');
        $I->seeLink('Download summary', '/tools/translation-spreadsheet/download-summary?project_id=1');
        $I->amOnPage('/tools/translation-spreadsheet/download-summary?project_id=1');

        $I->seeResponseCodeIs(200);
        $I->seeResponseHeaderContains('Content-Disposition', 'translation-spreadsheet.csv');
        $I->seeInSource('"Key","ID","Original English text","Word count","Test language",');
        $I->seeInSource('"Enim aut dolores ea.","1",');
        $I->seeInSource($accepted->text);
    }

    public function downloadWordCounts(FunctionalTester $I)
    {
        $I->amOnPage('/tools/translation-spreadsheet/download-word-counts?project_id=1');

        $I->seeResponseCodeIs(200);
        $I->seeResponseHeaderContains('Content-Disposition', 'translated-word-count.csv');
        $I->seeInSource('"Language","Translated word count","Remaining word count"');
        $I->seeInSource('"ti_ET","0","');
    }

    public function downloadQualityControlPdfs(FunctionalTester $I)
    {
        BaseString::whereKey([1, 2])->update(['quality_controlled' => true]);
        TranslatedString::where(['base_string_id' => 1, 'language_id' => 1])->firstOrFail()
            ->update(['is_accepted' => true, 'text' => 'Zażółć gęślą jaźń']);

        $I->amOnPage('/tools/translation-quality');
        $I->click('Download PDFs');

        $I->seeResponseCodeIs(200);
        $I->seeResponseHeaderContains('Content-Disposition', 'QA_PDFs.zip');
        $files = $I->grabZipEntriesFromResponse();
        $I->assertCount(11, $files);
        $I->assertArrayHasKey('Test language.pdf', $files);
        $I->assertStringStartsWith('%PDF', $files['Test language.pdf']);
        // Non-ANSI text needs an embedded Unicode font, the core PDF fonts would render it as "?"
        $I->assertStringContainsString('DejaVuSans', $files['Test language.pdf']);
    }
}
