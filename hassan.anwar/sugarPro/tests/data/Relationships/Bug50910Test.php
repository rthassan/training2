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

class Bug50910Test extends Sugar_PHPUnit_Framework_TestCase
{
    protected $emailAddress;

    public function setUp()
    {
        global $beanFiles, $beanList, $current_user;
        $current_user = SugarTestUserUtilities::createAnonymousUser();
        $GLOBALS['db']->commit();
    }

    public function tearDown()
    {
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        if(!empty($this->emailAddress))
        {
            $GLOBALS['db']->query("DELETE FROM emails WHERE id='{$this->emailAddress->id}'");
            $GLOBALS['db']->query("DELETE FROM emails_beans WHERE email_id='{$this->emailAddress->id}'");
            $GLOBALS['db']->query("DELETE FROM emails_email_addr_rel WHERE email_id='{$this->emailAddress->id}'");
        }
    }

    public function testSugarRelationshipsAddRow()
    {
        global $current_user;
        // create email address instance
        $this->emailAddress = new EmailAddress();
        $this->emailAddress->email_address = 'Bug59010Test@test.com';
        $this->emailAddress->save();

        // create relation between user and email address with empty additional data to test if the addRow function
        // properly handles empty values with not generating incorrect SQL
        $current_user->load_relationship('email_addresses');
        $current_user->email_addresses->add(array($this->emailAddress), array());
        $this->assertNotEmpty($current_user->email_addresses);

    }
}
