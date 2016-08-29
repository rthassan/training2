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
 * Bug #67170
 * @ticket 67170
 */
class Bug67170Test extends Sugar_PHPUnit_Framework_TestCase
{


    public function setUp()
    {
        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('app_strings');
        SugarTestHelper::setUp('app_list_strings');
        SugarTestHelper::setUp('mod_strings', array('Contacts'));
        SugarTestHelper::setUp('current_user', array(true, 1));

    }


    public function tearDown()
    {
        SugarTestHelper::tearDown();
    }

    /**
     * Tests that create_export_query, which uses 'create_new_list_query' does not perform extra
     * table joins that are not needed for exporting
     */
    public function testListQuery()
    {
        $bean = BeanFactory::getBean('Contacts');
        //simulate call from export_utils to retrieve export query
        $query = $bean->create_export_query('', 'contacts.deleted id = 0');

        $this->assertNotContains('calls_contacts',$query, ' calls_contacts was found in string, extra table joins have been introduced into export query');
        $this->assertNotContains('opportunities',$query, ' opportunities was found in string, extra table joins have been introduced into export query');
    } 

}
