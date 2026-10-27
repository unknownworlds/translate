<?php

use App\Models\BaseString;
use App\Models\Log;
use App\Models\TranslatedString;
use Tests\Support\ApiTester;

/**
 * Private API used by the translation UI (public/js/translate.js), authenticated by the web session.
 */
class TranslationApiCest
{
    public function guestIsUnauthenticated(ApiTester $I)
    {
        $I->haveHttpHeader('Accept', 'application/json');

        $I->sendGet('strings', ['project_id' => 1, 'language_id' => 1]);

        $I->seeResponseCodeIs(401);
    }

    public function readStrings(ApiTester $I)
    {
        $I->amLoggedAs($I->haveUser('Plain translator'));

        $I->sendGet('base-strings', ['project_id' => 1]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson(['id' => 1, 'key' => 'Enim aut dolores ea.']);
        $I->assertCount(1500, $I->grabDataFromResponseByJsonPath('$[*].id'));
        $I->assertCount(1500, $I->grabDataFromResponseByJsonPath('$[*].locked'));

        $I->sendGet('strings', ['project_id' => 1, 'language_id' => 1]);
        $I->seeResponseCodeIs(200);
        $I->assertCount(2560, $I->grabDataFromResponseByJsonPath('$[*].base_string_id'));

        $I->sendGet('project-handlers');
        $I->seeResponseContainsJson(['1' => 1, '4' => 3]);
    }

    public function checkPrivileges(ApiTester $I)
    {
        $I->amLoggedAs($I->haveUser('Plain translator'));
        $I->sendGet('check-privileges', ['language_id' => 1]);
        $I->seeResponseContainsJson(['is_admin' => false, 'is_root' => false]);

        $I->switchUserTo($I->haveUser('Language admin', ['Test language admin']));
        $I->sendGet('check-privileges', ['language_id' => 1]);
        $I->seeResponseContainsJson(['is_admin' => true, 'is_root' => false]);
        $I->sendGet('check-privileges', ['language_id' => 2]);
        $I->seeResponseContainsJson(['is_admin' => false]);

        $I->switchUserTo($I->grabRootUser());
        $I->sendGet('check-privileges', ['language_id' => 1]);
        $I->seeResponseContainsJson(['is_admin' => true, 'is_root' => true]);
    }

    public function storeTranslation(ApiTester $I)
    {
        $translator = $I->haveUser('Plain translator');
        $I->amLoggedAs($translator);

        $I->sendPost('strings/store', [
            'project_id' => 1,
            'language_id' => 2,
            'base_string_id' => 3,
            'text' => "\nNowe tłumaczenie\n",
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson(['base_string_id' => 3, 'text' => 'Nowe tłumaczenie']);
        $I->seeRecord(TranslatedString::class, [
            'project_id' => 1,
            'language_id' => 2,
            'base_string_id' => 3,
            'user_id' => $translator->id,
            'text' => 'Nowe tłumaczenie',
            'is_accepted' => false,
        ]);
        $I->seeRecord(Log::class, ['user_id' => $translator->id, 'language_id' => 2, 'log_type' => 1]);

        $I->sendPost('strings/store', [
            'project_id' => 1,
            'language_id' => 2,
            'base_string_id' => 3,
            'text' => 'Nowe tłumaczenie',
        ]);

        $I->seeResponseCodeIs(422);
        $I->seeResponseContainsJson(['error' => ['message' => 'Translation already exist!']]);
    }

    public function rejectIncompleteTranslation(ApiTester $I)
    {
        $I->amLoggedAs($I->haveUser('Plain translator'));
        $I->haveHttpHeader('Accept', 'application/json');

        $I->sendPost('strings/store', ['project_id' => 1, 'language_id' => 2, 'base_string_id' => 3]);

        $I->seeResponseCodeIs(422);
        $I->seeResponseJsonMatchesJsonPath('$.errors.text');
    }

    public function vote(ApiTester $I)
    {
        $author = $I->haveUser('Author');
        $string = TranslatedString::create([
            'project_id' => 1, 'language_id' => 2, 'base_string_id' => 3, 'user_id' => $author->id,
            'text' => 'Do oceny', 'up_votes' => 0, 'down_votes' => 0,
        ]);

        $I->amLoggedAs($author);
        $I->sendPost('strings/vote', ['string_id' => $string->id, 'vote' => 1]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseContainsJson(['error' => ['message' => 'Cannot vote on own entries.']]);

        $I->switchUserTo($I->haveUser('Voter one'));
        $I->sendPost('strings/vote', ['string_id' => $string->id, 'vote' => 1]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('Vote saved.');
        $I->sendPost('strings/vote', ['string_id' => $string->id, 'vote' => 1]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseContainsJson(['error' => ['message' => 'Already voted!']]);

        $I->switchUserTo($I->haveUser('Voter two'));
        $I->sendPost('strings/vote', ['string_id' => $string->id, 'vote' => 0]);

        $string->refresh();
        $I->assertEquals(1, $string->up_votes);
        $I->assertEquals(1, $string->down_votes);
    }

    public function translatorCannotModerate(ApiTester $I)
    {
        $I->amLoggedAs($I->haveUser('Plain translator'));

        foreach (['strings/accept', 'strings/update', 'strings/trash'] as $endpoint) {
            $I->sendPost($endpoint, ['string_id' => 1, 'language_id' => 1, 'base_string_id' => 1, 'text' => 'Hacked']);
            $I->seeResponseCodeIs(403);
        }

        $I->dontSeeRecord(TranslatedString::class, ['text' => 'Hacked']);
    }

    public function languageAdminCanOnlyModerateTheirLanguage(ApiTester $I)
    {
        $string = TranslatedString::create([
            'project_id' => 1, 'language_id' => 2, 'base_string_id' => 3, 'user_id' => 5, 'text' => 'Inny język',
        ]);
        $I->amLoggedAs($I->haveUser('Language admin', ['Test language admin']));

        $I->sendPost('strings/accept', ['string_id' => $string->id, 'language_id' => 2, 'base_string_id' => 3]);

        $I->seeResponseCodeIs(403);
    }

    public function acceptUpdateAndTrash(ApiTester $I)
    {
        $previouslyAccepted = TranslatedString::create([
            'project_id' => 1, 'language_id' => 1, 'base_string_id' => 3, 'user_id' => 5,
            'text' => 'Stara wersja', 'is_accepted' => true,
        ]);
        $string = TranslatedString::create([
            'project_id' => 1, 'language_id' => 1, 'base_string_id' => 3, 'user_id' => 6, 'text' => 'Nowa wersja',
        ]);
        $admin = $I->haveUser('Language admin', ['Test language admin']);
        $I->amLoggedAs($admin);

        $I->sendPost('strings/accept', ['string_id' => $string->id, 'language_id' => 1, 'base_string_id' => 3]);
        $I->seeResponseContains('String accepted.');
        $I->seeRecord(TranslatedString::class, ['id' => $string->id, 'is_accepted' => true, 'accepted_by' => $admin->id]);
        $I->seeRecord(TranslatedString::class, ['id' => $previouslyAccepted->id, 'is_accepted' => false]);

        $I->sendPost('strings/update', ['string_id' => $string->id, 'text' => 'Poprawiona wersja']);
        $I->seeResponseContains('String updated.');
        $I->seeRecord(TranslatedString::class, ['id' => $string->id, 'text' => 'Poprawiona wersja']);

        $I->sendPost('strings/trash', ['string_id' => $string->id, 'language_id' => 1]);
        $I->seeResponseContains('String deleted.');
        $I->dontSeeRecord(TranslatedString::class, ['id' => $string->id]);
        $I->assertEquals($admin->id, TranslatedString::withTrashed()->findOrFail($string->id)->deleted_by);

        // History includes trashed entries, newest first, with their authors
        $I->sendGet('strings/history', ['project_id' => 1, 'language_id' => 1, 'base_string_id' => 3]);
        $I->seeResponseContainsJson(['id' => $string->id, 'text' => 'Poprawiona wersja']);
        $I->seeResponseJsonMatchesJsonPath('$[0].user.name');
    }

    public function lockedStringCanOnlyBeAcceptedByRoot(ApiTester $I)
    {
        BaseString::whereKey(3)->update(['locked' => true]);
        $string = TranslatedString::create([
            'project_id' => 1, 'language_id' => 1, 'base_string_id' => 3, 'user_id' => 6, 'text' => 'Zablokowane',
        ]);
        $params = ['string_id' => $string->id, 'language_id' => 1, 'base_string_id' => 3];

        $I->amLoggedAs($I->haveUser('Language admin', ['Test language admin']));
        $I->sendPost('strings/accept', $params);
        $I->seeResponseContains('String locked.');
        $I->seeRecord(TranslatedString::class, ['id' => $string->id, 'is_accepted' => false]);

        $I->switchUserTo($I->grabRootUser());
        $I->sendPost('strings/accept', $params);
        $I->seeResponseContains('String accepted.');
        $I->seeRecord(TranslatedString::class, ['id' => $string->id, 'is_accepted' => true]);
    }

    public function listTranslatorsAndAdmins(ApiTester $I)
    {
        $admin = $I->haveUser('Language admin', ['Test language admin']);
        $I->amLoggedAs($admin);

        $I->sendGet('strings/users', ['project_id' => 1, 'language_id' => 1]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseJsonMatchesJsonPath('$[0].count');
        $I->seeResponseJsonMatchesJsonPath('$[0].name');

        $I->sendGet('strings/admins', ['language_id' => 1]);
        $I->seeResponseContainsJson([['id' => $admin->id, 'name' => 'Language admin']]);
    }

    public function adminWhiteboard(ApiTester $I)
    {
        $I->amLoggedAs($I->haveUser('Plain translator'));
        $I->sendPost('admin-whiteboard', ['project_id' => 1, 'language_id' => 1, 'text' => 'Hijacked']);
        $I->seeResponseCodeIs(403);

        $I->switchUserTo($I->haveUser('Language admin', ['Test language admin']));
        $I->sendPost('admin-whiteboard', ['project_id' => 1, 'language_id' => 1, 'text' => 'Notes for admins']);
        $I->seeResponseCodeIs(201);

        $I->sendGet('admin-whiteboard/1/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson(['text' => 'Notes for admins', 'user' => ['name' => 'Language admin']]);
    }
}
