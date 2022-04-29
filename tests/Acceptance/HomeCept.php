<?php
class HomeCept
{
    public function tryToTest(AcceptanceTester $I)
    {
        $I = new AcceptanceTester();
        $I->wantTo('ensure that frontpage works');
        $I->amOnPage('/');
        $I->see('Home');
    }
}
