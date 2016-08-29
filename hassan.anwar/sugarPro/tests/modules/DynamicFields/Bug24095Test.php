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
require_once("modules/Accounts/Account.php");

/**
 * @ticket 24095
 */
class Bug24095Test extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        SugarTestHelper::setUp('dictionary');
        SugarTestHelper::setUp('custom_field', array(
            'Accounts',
            array(
                'name' => 'foo',
                'type' => 'varchar',
            ),
        ));

        $GLOBALS['db']->query("INSERT INTO accounts_cstm (id_c,foo_c) VALUES ('12345','67890')");
    }
    
    public function tearDown()
    {
        $GLOBALS['db']->query("DELETE FROM accounts_cstm WHERE id_c = '12345'");
        SugarTestHelper::tearDown();
    }
    
    public function testDynamicFieldsRetrieveWorks()
    {
        $bean = BeanFactory::getBean('Accounts');
        $bean->id = '12345';
        $bean->custom_fields->retrieve();
        $this->assertEquals($bean->foo_c, '67890');
    }
}
