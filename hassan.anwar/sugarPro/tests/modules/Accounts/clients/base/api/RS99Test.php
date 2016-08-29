<?php
require_once 'modules/Accounts/clients/base/api/AccountsApi.php';

/**
 * RS-99 Prepare Accounts Api
 */
class RS99Test extends Sugar_PHPUnit_Framework_TestCase
{
    /**
     * @var Account
     */
    protected $account = null;

    /**
     * @var Opportunity
     */
    protected $opportunity = null;

    /** @var Revenuelineitem */
    protected $revenuelineitem = null;

    public function setUp()
    {
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('current_user', array(true, true));

        $this->account = SugarTestAccountUtilities::createAccount();
        $this->opportunity = SugarTestOpportunityUtilities::createOpportunity('', $this->account);

        Opportunity::$settings = array(
            'opps_view_by' => 'RevenueLineItems'
        );

        $this->revenuelineitem = new RevenueLineItem();
        $this->revenuelineitem->name = 'Revenue Line Item ' . __CLASS__;
        $this->revenuelineitem->opportunity_id = $this->opportunity->id;
        $this->revenuelineitem->sales_stage = 'Closed Lost';
        $this->revenuelineitem->save();

        $this->opportunity->retrieve($this->opportunity->id);
        $this->opportunity->sales_status = 'Closed Lost';
        $this->opportunity->save();
    }

    public function tearDown()
    {
        Opportunity::$settings = array();

        if ($this->revenuelineitem instanceof SugarBean) {
            $this->revenuelineitem->mark_deleted($this->revenuelineitem->id);
        }
        SugarTestOpportunityUtilities::removeAllCreatedOpportunities();
        SugarTestAccountUtilities::removeAllCreatedAccounts();
        SugarTestHelper::tearDown();
    }

    /**
     * Test asserts count of records (success result of query)
     */
    public function testOpportunityStats()
    {
        $api = new AccountsApi();
        $actual = $api->opportunityStats(SugarTestRestUtilities::getRestServiceMock(), array(
                'module' => 'Accounts',
                'record' => $this->account->id
            ));
        $this->assertArrayHasKey('lost', $actual);
        $this->assertEquals(1, $actual['lost']['count']);
    }
}
