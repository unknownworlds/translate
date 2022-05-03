<?php

use Tests\Support\AcceptanceTester;

class HomeCest
{
    public function tryToTest(AcceptanceTester $I)
    {
        $I->wantTo('ensure that frontpage works');
        $I->amOnPage('/');
        $I->see('Home');
    }
}
