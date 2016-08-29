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

class Bug33036Test extends Sugar_PHPUnit_Framework_TestCase
{
    private $obj;

    public static function setUpBeforeClass()
    {
        SugarTestHelper::setUp('current_user');
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('beanList');
	}


	public static function tearDownAfterClass()
	{
        SugarTestHelper::tearDown();
	}

	public function setUp()
	{
	    $this->obj = new Contact();
	}

	public function tearDown()
	{
        if (! empty($this->obj->id)) {
            $this->obj->db->query("DELETE FROM contacts WHERE id = '" . $this->obj->id . "'");
        }
        unset($this->obj);
	}

    public function testAuditForRelatedFields()
    {
        $test_account_name = 'test account name after';

        $account = SugarTestAccountUtilities::createAccount();

        $this->obj->field_defs['account_name']['audited'] = 1;
        $this->obj->name = 'test';
        $this->obj->account_id = $account->id;
        $this->obj->save();

        $this->obj->retrieve($this->obj->id);
        $this->obj->account_name = $test_account_name;
        $changes = $this->obj->db->getAuditDataChanges($this->obj);

        $this->assertTrue(isset($changes['account_name']),"The account name was not in the list of changes");
        $this->assertEquals($changes['account_name']['after'], $test_account_name);

        SugarTestAccountUtilities::removeAllCreatedAccounts();
    }
}
