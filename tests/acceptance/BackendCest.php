<?php 

class BackendCest
{
    // tests
    public function given_an_admin_when_I_try_to_modify_reviews_then_I_should_be_able_to_manage_everything(AcceptanceTester $I)
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
        $I->canSee('Muchas gracias!');

        $I->loginAsAdmin();
        $I->amOnPage('/wp-admin/admin.php?page=reviews&orderby=created_at&order=desc');
        $I->click(\Codeception\Util\Locator::firstElement('/html/body/div/div[2]/div[2]/div[1]/div[2]/form/table/tbody/tr[1]/td[1]/a'));
        $I->canSee('Editar Review');
        $I->fillField('author', 'Test2');
        $I->selectOption('status', 'published');
        $I->click('submit');
        $I->amOnPage('/?p=1');
        $I->scrollTo('body', 0, 1080);
        $I->canSee('Test2');
        $I->amOnPage('/wp-admin/admin.php?page=reviews&orderby=created_at&order=desc');
        $I->checkOption('#the-list > tr:nth-child(1) > th > input[type=checkbox]');
        $I->selectOption('#bulk-action-selector-top', 'bulk-delete');
        $I->click('#doaction');
        $I->amOnPage('/?p=1');
        $I->scrollTo('body', 0, 1080);
        $I->dontSee('Test2');
    }
}
