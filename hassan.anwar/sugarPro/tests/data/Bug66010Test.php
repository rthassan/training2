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

/**
 * @ticket 66010
 */
class Bug66010Test extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('beanFiles');
    }

    public function tearDown()
    {
        SugarTestHelper::tearDown();
    }

    public function testCreateNewListQuery()
    {
        $bean = BeanFactory::getBean('Accounts');
        $query = $bean->create_new_list_query("","",array('create_by_name','modified_by_name'),array(),0,"",true);
        $this->assertEquals(1, substr_count($query['select'], 'accounts.created_by'));
        $this->assertEquals(1, substr_count($query['select'], 'accounts.modified_user_id'));
        $query = $bean->create_new_list_query("","",array(),array(),0,"",true);
        $this->assertEquals(0, substr_count($query['select'], 'accounts.modified_user_id'));
        $query = $bean->create_new_list_query("","",array('modified_by_name','modified_user_id'),array(),0,"",true);
        $this->assertEquals(1, substr_count($query['select'], 'accounts.modified_user_id'));
    }
}
