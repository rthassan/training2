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

 
require_once('modules/Emails/Email.php');

class Bug45960 extends Sugar_PHPUnit_Framework_TestCase
{
    protected $email_id = null;

    public function setUp()
    {
        $this->_user = SugarTestUserUtilities::createAnonymousUser();
        $GLOBALS['current_user'] = $this->_user;
        $this->_account = SugarTestAccountUtilities::createAccount();
    }
    
    public function tearDown()
    {
        if ($this->email_id) {
            $GLOBALS['db']->query("delete from emails where id='{$this->email_id}'");
        }
        SugarTestAccountUtilities::removeAllCreatedAccounts();
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
    }

    public function testSaveNewEmailWithParent()
    {
        $email = new Email();
        $email->type = 'out';
        $email->status = 'sent';
        $email->from_addr_name = $email->cleanEmails("sender@domain.eu");
        $email->to_addrs_names = $email->cleanEmails("to@domain.eu");
        $email->cc_addrs_names = $email->cleanEmails("cc@domain.eu");

        // set a few parent info to test the scenario
        $email->parent_type = 'Accounts';
        $email->parent_id = $this->_account->id;
        $email->fetched_row['parent_type'] = 'Accounts';
        $email->fetched_row['parent_id'] = $this->_account->id;

        $email->save();

        $this->assertNotNull($email->id, 'Null email id');
        $this->email_id = $email->id;

        // ensure record is inserted into emails_beans table
        $query = "select count(*) as cnt from emails_beans eb WHERE eb.bean_id = '{$this->_account->id}' AND eb.bean_module = 'Accounts' AND eb.email_id = '{$email->id}' AND eb.deleted=0";
        $result = $GLOBALS['db']->query($query);
        $count = $GLOBALS['db']->fetchByAssoc($result);
        $this->assertEquals(1, $count['cnt'], 'Incorrect emails_beans count');
    }
    
}


