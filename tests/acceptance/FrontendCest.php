<?php 

class FrontendCest
{

    // tests
    public function given_an_user_when_I_try_to_navigate_to_some_post_i_should_able_to_add_reviews(AcceptanceTester $I)
    {

        $I->amOnPage('/?p=1');
        $I->canSee('Valorar producto');
        $I->fillField('author', 'Test');
        $I->click('label[for="rating-5"]');
        $I->fillField('email', 'test@test.com');
        $I->fillField('title', 'This is a test');
        $I->fillField('content', 'This is a test content');
        $I->checkOption('validate');
        $I->click('submit-opinion');
	    $I->scrollTo('body', 0, 500);
        $I->canSee('Gracias');
    }
}
