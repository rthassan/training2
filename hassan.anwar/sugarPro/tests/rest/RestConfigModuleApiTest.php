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

require_once('tests/rest/RestTestBase.php');

class RestConfigModuleApiTest extends RestTestBase {
    protected $configs = array(
        array('name' => 'AdministrationTest', 'value' => 'Base', 'platform' => 'base', 'category' => 'Forecasts'),
        array('name' => 'AdministrationTest', 'value' => 'Portal', 'platform' => 'portal', 'category' => 'Forecasts'),
        array('name' => 'AdministrationTest', 'value' => '["Portal"]', 'platform' => 'json', 'category' => 'Forecasts'),
    );
    public function setUp()
    {
        parent::setUp();

        $GLOBALS['app_list_strings'] = return_app_list_strings_language('en_us');
        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('moduleList');
        $db = DBManagerFactory::getInstance();
        $db->query("DELETE FROM config where name = 'AdministrationTest'");
        /* @var $admin Administration */
        $admin = BeanFactory::getBean('Administration');
        foreach($this->configs as $config){
            $admin->saveSetting($config['category'], $config['name'], $config['value'], $config['platform']);
        }
    }

    public static function setUpBeforeClass()
    {
        sugar_cache_clear('admin_settings_cache');
    }

    public function tearDown()
    {
        $db = DBManagerFactory::getInstance();
        $db->query("DELETE FROM config where name = 'AdministrationTest' or name = 'AdministrationSaveTest'");
        $db->commit();
        parent::tearDown();
    }

    /**
     * @group rest
     */
    public function testRetrieveConfigSettingsByValidModuleNoSettings()
    {

        $restReply = $this->_restCall('Opportunities/config?platform=base');
        // now returns an empty array not an error
        $this->assertEmpty($restReply['reply']);
    }

    /**
     * @group rest
     */
    public function testRetrieveConfigSettingsByInvalidModule()
    {
        $restReply = $this->_restCall('OneDoesNotSimplyWalkIntoASugarModule/config?platform=base');
        $this->assertEquals('404', $restReply['info']['http_code']);
    }

    /**
     * @group rest
     */
    public function testRetrieveSettingsByValidModuleWithPlatformReturnsSettings()
    {
        $restReply = $this->_restCall('Forecasts/config?platform=base');
        $this->assertEquals('200', $restReply['info']['http_code']);
        $this->assertTrue($restReply['reply'] > 0);
    }

    /**
     * @group rest
     */
    public function testRetrieveSettingsByValidModuleWithPlatformOverRidesBasePlatform()
    {
        $restReply = $this->_restCall('Forecasts/config?platform=portal');
        $this->assertEquals('200', $restReply['info']['http_code']);
        $this->assertEquals('Portal', $restReply['reply']['AdministrationTest']);
    }

    /**
     * @group rest
     */
    public function testJsonValueIsArray()
    {
        $restReply = $this->_restCall('Forecasts/config?platform=json');
        $this->assertEquals('200', $restReply['info']['http_code']);
        $this->assertEquals(array("Portal"), $restReply['reply']['AdministrationTest']);
    }

    /**
     * @group rest
     */
    public function testSaveForecastsConfigValueUnauthorizedUser()
    {
        $GLOBALS['current_user']->is_admin = false;
        $GLOBALS['current_user']->save();
        $restReply = $this->_restCall('Forecasts/config?platform=base',json_encode(array('AdministrationSaveTest' => 'My voice is my passport, verify me')),'POST');
        $this->assertEquals('403', $restReply['info']['http_code']);
        $this->assertEquals("Current User not authorized to change Forecasts configuration settings", $restReply['reply']['error_message']);
    }

    /**
     * @group rest
     */
    public function testSaveForecastsConfigValue()
    {
        $GLOBALS['current_user']->is_admin = true;
        $GLOBALS['current_user']->save();
        $restReply = $this->_restCall('Forecasts/config?platform=base',json_encode(array('AdministrationSaveTest' => 'My voice is my passport, verify me')),'POST');
        $this->assertEquals('200', $restReply['info']['http_code']);
        $this->assertEquals('My voice is my passport, verify me', $restReply['reply']['AdministrationSaveTest']);
    }

}
