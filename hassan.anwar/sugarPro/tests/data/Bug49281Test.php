<?php

/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/06_Customer_Center/10_Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */

class Bug49281Test extends Sugar_PHPUnit_Framework_TestCase
{
    protected $contact;

    public function setUp()
    {
        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('beanFiles');
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
	}

	public function tearDown()
	{
	    SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
        SugarTestAccountUtilities::removeAllCreatedAccounts();
        SugarTestHelper::tearDown();
	}
    /**
     * @ticket 49281
     */
    public function testInsertUpdateLongData()
    {
        $acc = new Account();
        $acc->name = "PHPUNIT test";
        $acc->assigned_user_id = $GLOBALS['current_user']->id;
        $acc->sic_code = 'mnbvcxzasdfghjklpoiuytrewqmnbvcxzasdfghjklpoiuytre';
        $acc->save();
        $id = $acc->id;
        SugarTestAccountUtilities::setCreatedAccount(array($id));
        $acc = new Account();
        $acc->retrieve($id);
        $this->assertEquals('mnbvcxzasd', $acc->sic_code);

        $acc->sic_code = 'f094f59daaed0983a6a2e5913ddcc5fb';
        $acc->save();
        $acc = new Account();
        $acc->retrieve($id);
        $this->assertEquals('f094f59daa', $acc->sic_code);
    }

}
