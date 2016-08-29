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

require_once 'service/v4/SugarWebServiceImplv4.php';
require_once 'modules/Employees/Employee.php';

class Bug48889Test extends Sugar_PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        SugarTestHelper::setUp("beanList");
        SugarTestHelper::setUp("beanFiles");
        SugarTestHelper::setUp("current_user");
        $GLOBALS['current_user']->status = 'Active';
        $GLOBALS['current_user']->is_admin = 1;
        $GLOBALS['current_user']->save();
    }

    public function tearDown()
    {
        SugarTestHelper::tearDown();
    }

    public function testGetRelationshipsWithCustomFields()
    {
        $employee = new Employee();
        $web_service_util = new SugarWebServiceUtilv4();
        $result = $web_service_util->get_data_list($employee);

        //$total = $GLOBALS['db']->getOne("SELECT count(id) AS total FROM users WHERE portal_only=0 AND deleted=0");
        $this->assertArrayHasKey('list', $result, 'Assert that we have a list of results and that the get_data_list query on Employees does not cause an error');
    }
}

