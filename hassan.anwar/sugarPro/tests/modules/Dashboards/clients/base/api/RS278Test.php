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

require_once 'modules/Dashboards/clients/base/api/DashboardApi.php';
require_once 'modules/Dashboards/clients/base/api/DashboardListApi.php';

/**
 * RS-278: Fix Dashboards regression caused by RussianStandard PR #17355
 * Because of rename `view` field to `view_name` test checks that `view` argument has the same behavior as `view_name`
 */
class RS278Test extends Sugar_PHPUnit_Framework_TestCase
{
    /** @var RestService */
    protected static $service = null;

    /** @var array */
    protected $beans = array();

    public static function setUpBeforeClass()
    {
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('current_user');
        self::$service = SugarTestRestUtilities::getRestServiceMock();
    }

    public function tearDown()
    {
        foreach ($this->beans as $module => $ids) {
            foreach ($ids as $id) {
                $bean = BeanFactory::getBean($module, $id);
                if ($bean instanceof SugarBean) {
                    $bean->mark_deleted($bean->id);
                }
            }
        }
    }

    public static function tearDownAfterClass()
    {
        self::$service = null;
        SugarTestHelper::tearDown();
    }

    /**
     * Creating dashboard through DashboardAPI without 'view'
     *
     * @return array
     */
    public function testCreateDashboardWithoutView()
    {
        $args = array(
            'metadata' => array(
                'components' => array(
                    array(
                        'rows' => array(
                            array(
                                array(
                                    'context' => array(
                                        'module' => 'Forecasts',
                                    ),
                                    'view' => array(
                                        'label' => 'LBL_DASHLET_PIPLINE_NAME',
                                        'type' => 'forecast-pipeline',
                                        'visibility' => 'user',
                                    ),
                                ),
                            ),
                        ),
                        'width' => 12,
                    ),
                ),
            ),
        );

        $api = new DashboardApi();
        $actual = $api->createDashboard(self::$service, $args);
        $this->assertNotEmpty($actual);
        $this->assertEquals('', $actual['view']);
        return $actual;
    }

    /**
     * Creating dashboard through DashboardAPI with 'view' only
     *
     * @return array
     */
    public function testCreateDashboardWithView()
    {
        $args = array(
            'metadata' => array(
                'components' => array(
                    array(
                        'rows' => array(
                            array(
                                array(
                                    'context' => array(
                                        'module' => 'Accounts',
                                    ),
                                    'view' => array(
                                        'display_columns' => array(
                                            'name',
                                            'billing_address_country',
                                            'billing_address_city',
                                        ),
                                        'label' => 'LBL_DASHLET_MY_MODULE',
                                        'type' => 'dashablelist',
                                    ),
                                    'width' => 12,
                                ),
                            ),
                        ),
                        'width' => 12,
                    ),
                ),
            ),
            'module' => 'Leads',
            'name' => 'LBL_DEFAULT_DASHBOARD_TITLE',
            'view' => 'records',
        );

        $api = new DashboardApi();
        $actual = $api->createDashboard(self::$service, $args);
        $this->assertNotEmpty($actual);
        $this->assertEquals($args['view'], $actual['view']);
        return $actual;
    }

    /**
     * Creating dashboard through DashboardAPI with 'view' and 'view_name'
     *
     * @return array
     */
    public function testCreateDashboardWithBoth()
    {
        $args = array(
            'metadata' => array(
                'components' => array(
                    array(
                        'rows' => array(
                            array(
                                array(
                                    'context' => array(
                                        'module' => 'Accounts',
                                    ),
                                    'view' => array(
                                        'display_columns' => array(
                                            'name',
                                            'billing_address_country',
                                            'billing_address_city',
                                        ),
                                        'label' => 'LBL_DASHLET_MY_MODULE',
                                        'type' => 'dashablelist',
                                    ),
                                    'width' => 12,
                                ),
                            ),
                        ),
                        'width' => 12,
                    ),
                ),
            ),
            'module' => 'Contacts',
            'name' => 'LBL_DEFAULT_DASHBOARD_TITLE',
            'view' => '',
            'view_name' => 'records',
        );

        $api = new DashboardApi();
        $actual = $api->createDashboard(self::$service, $args);
        $this->assertNotEmpty($actual);
        $this->assertEquals($args['view_name'], $actual['view']);
        $this->assertEquals($args['view_name'], $actual['view_name']);
        return $actual;
    }

    /**
     * Fetching created dashboard without 'view'
     *
     * @depends testCreateDashboardWithoutView
     * @param $expected
     */
    public function testGetDashboardsWithoutView($expected)
    {
        $args = array(
            'max_num' => '20',
        );

        $api = new DashboardListApi();
        $actual = $api->getDashboards(self::$service, $args);
        $this->assertNotEmpty($actual);
        $this->assertNotEmpty($actual['records']);
        $actual = reset($actual['records']);
        $this->beans['Dashboard'][] = $expected['id'];
        $this->assertEquals($expected['id'], $actual['id']);
        $this->assertEquals($expected['view'], $actual['view']);

    }

    /**
     * Fetching created dashboard with 'view'
     *
     * @depends testCreateDashboardWithView
     * @param $expected
     */
    public function testGetDashboardsWithView($expected)
    {
        $args = array(
            'fields' => '',
            'max_num' => '20',
            'module' => 'Leads',
            'view' => 'records',
        );

        $api = new DashboardListApi();
        $actual = $api->getDashboards(self::$service, $args);
        $this->assertNotEmpty($actual);
        $actual = reset($actual['records']);
        $this->beans['Dashboard'][] = $expected['id'];
        $this->assertEquals($expected['id'], $actual['id']);
        $this->assertEquals($expected['view'], $actual['view']);
    }

    /**
     * Fetching created dashboard with 'view' and 'view_name'
     *
     * @depends testCreateDashboardWithBoth
     * @param $expected
     */
    public function testGetDashboardsWithBoth($expected)
    {
        $args = array(
            'fields' => '',
            'max_num' => '20',
            'module' => 'Contacts',
            'view' => '',
            'view_name' => 'records',
        );

        $api = new DashboardListApi();
        $actual = $api->getDashboards(self::$service, $args);
        $this->assertNotEmpty($actual);
        $actual = reset($actual['records']);
        $this->beans['Dashboard'][] = $expected['id'];
        $this->assertEquals($expected['id'], $actual['id']);
        $this->assertEquals($expected['view_name'], $actual['view']);
        $this->assertEquals($expected['view_name'], $actual['view_name']);
    }
}
