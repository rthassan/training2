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

require_once 'include/api/RestService.php';
require_once 'include/api/ApiHelper.php';

class ApiHelperTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
        SugarTestHelper::setUp('current_user');
        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('moduleList');
        SugarTestHelper::setUp('app_list_strings');
    }

    public function tearDown()
    {
        parent::tearDown();
        SugarTestHelper::tearDown();
    }

    public function testGetHelper_ReturnsBaseHelper()
    {
        $api = new RestService();

        $accountsBean = BeanFactory::newBean('Accounts');

        $helper = ApiHelper::getHelper($api,$accountsBean);

        $this->assertEquals('SugarBeanApiHelper',get_class($helper));
    }

    public function testGetHelper_ReturnsModuleHelper()
    {
        $api = new RestService();

        $bugsBean = BeanFactory::newBean('Users');

        $helper = ApiHelper::getHelper($api,$bugsBean);

        $this->assertEquals('UsersApiHelper',get_class($helper));
    }

    public function testGetHelper_ModulePathSubDirectory_ReturnModuleHelper()
    {
        $moduleName = 'Activities';
        $api = new RestService();

        $bean = BeanFactory::newBean($moduleName);
        $helper = ApiHelper::getHelper($api, $bean);

        $this->assertContains("/", $bean->module_dir);
        $this->assertNotEquals($bean->module_name, $bean->module_dir);
        $this->assertEquals("{$moduleName}ApiHelper", get_class($helper));

    }
}
