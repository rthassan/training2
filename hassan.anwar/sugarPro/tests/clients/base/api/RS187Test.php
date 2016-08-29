<?php

require_once 'modules/Dashboards/clients/base/api/DashboardApi.php';

/**
 * RS-187: Prepare Dashboard Api
 */
class RS187Test extends Sugar_PHPUnit_Framework_TestCase
{
    /**
     * @var DashboardApi
     */
    protected $dashboardApi;

    /**
     * @var RestService
     */
    protected $serviceMock;

    protected $dashboard;

    public function setUp()
    {
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('current_user', array(true, true));

        $this->dashboardApi = new DashboardApi();
        $this->serviceMock = SugarTestRestUtilities::getRestServiceMock();
    }

    public function tearDown()
    {
        $GLOBALS['db']->query("DELETE FROM dashboards WHERE id = '{$this->dashboard}'");
        SugarTestHelper::tearDown();
    }


    /**
     * Test asserts behavior of create dashboard for module
     */
    public function testCreateDashboardForModule()
    {
        $result = $this->dashboardApi->createDashboard($this->serviceMock, array(
            'module' => 'Accounts',
            'name' => 'Test Dashboard'
        ));

        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('dashboard_module', $result);
        $this->assertEquals('Accounts', $result['dashboard_module']);

        $this->dashboard = $result['id'];

        $dashboard = BeanFactory::newBean('Dashboards');
        $dashboard->retrieve($result['id']);

        $this->assertAttributeNotEmpty('id', $dashboard);
        $this->assertEquals('Test Dashboard', $dashboard->name);
    }

    /**
     * Test asserts behavior of create dashboard for Home
     */
    public function testCreateDashboardForHome()
    {
        $result = $this->dashboardApi->createDashboard($this->serviceMock, array(
            'name' => 'Test Dashboard'
        ));

        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('dashboard_module', $result);
        $this->assertEquals('Home', $result['dashboard_module']);

        $this->dashboard = $result['id'];

        $dashboard = BeanFactory::newBean('Dashboards');
        $dashboard->retrieve($result['id']);

        $this->assertAttributeNotEmpty('id', $dashboard);
        $this->assertEquals('Test Dashboard', $dashboard->name);
    }
}
