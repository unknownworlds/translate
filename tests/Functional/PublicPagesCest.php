<?php

use App\Models\Log;
use Tests\Support\FunctionalTester;

class PublicPagesCest
{
    public function rssIndex(FunctionalTester $I)
    {
        $I->amOnPage('/rss');

        $I->seeResponseCodeIs(200);
        $I->see('Subnautica');
        $I->seeLink('Base string updates', '/rss/base-strings/1');
        $I->seeLink('Test language translations feed', '/rss/translations/1/1');
    }

    public function baseStringUpdatesFeed(FunctionalTester $I)
    {
        Log::create(['project_id' => 1, 'user_id' => 1, 'log_type' => 2, 'text' => 'String Some.key added']);

        $I->amOnPage('/rss/base-strings/1');

        $I->seeResponseCodeIs(200);
        $I->seeResponseHeaderContains('Content-Type', 'application/rss+xml');
        $I->seeInSource('String Some.key added');
    }

    public function translationsFeed(FunctionalTester $I)
    {
        Log::create([
            'project_id' => 1, 'language_id' => 1, 'user_id' => 1, 'log_type' => 1,
            'text' => 'Someone translated Some.key to Coś',
        ]);

        $I->amOnPage('/rss/translations/1/1');

        $I->seeResponseCodeIs(200);
        $I->seeResponseHeaderContains('Content-Type', 'application/rss+xml');
        $I->seeInSource('Someone translated Some.key to Coś');
    }

    public function translationsPage(FunctionalTester $I)
    {
        $I->amLoggedAs($I->haveUser('Plain translator'));

        $I->amOnPage('/translations');

        $I->seeResponseCodeIs(200);
        $I->seeOptionIsSelected('project', 'Achievements test project');
        $I->seeElement('select[name=language] option[value="1"]');
        // Vue binds to the button rendered by the form builder
        $I->seeElement(['xpath' => "//button[@type='button' and normalize-space()='Load data']"]);
        $I->seeInSource('@click="loadData()"');
        $I->seeElement('meta[name=csrf-token]');
    }
}
