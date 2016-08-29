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

require_once("modules/Forecasts/clients/base/api/ForecastsProgressApi.php");

class ForecastsProgressApiTest extends Sugar_PHPUnit_Framework_TestCase
{
    /**
     * @var TimePeriod
     */
    protected static $timeperiod;

    /**
     * @var ForecastsProgressApi
     */
    protected $api;

    /**
     * @var RestService
     */
    protected $service;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        SugarTestHelper::setUp('app_strings');
        SugarTestHelper::setUp('app_list_strings');
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('current_user');

        self::$timeperiod = SugarTestForecastUtilities::getCreatedTimePeriod();
    }

    public static function tearDownAfterClass()
    {
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        SugarTestHelper::tearDown();
        parent::tearDownAfterClass();
    }

    public function setUp()
    {
        $this->service = SugarTestRestUtilities::getRestServiceMock();
        $this->api = new ForecastsProgressApi();
    }

    public function tearDown()
    {
        unset($this->service);
        unset($this->api);
    }

    /**
     * Test method progressRep
     */
    public function testProgressRep()
    {
        $user = SugarTestUserUtilities::createAnonymousUser();

        $result = $this->api->progressRep($this->service, array(
            'user_id' => $user->id,
            'timeperiod_id' => self::$timeperiod->id,
        ));
        $this->assertNotEmpty($result);

        $this->assertArrayHasKey('quota_amount', $result);
        $this->assertArrayHasKey('amount', $result);
        $this->assertArrayHasKey('best_case', $result);
        $this->assertArrayHasKey('worst_case', $result);
        $this->assertArrayHasKey('overall_amount', $result);
        $this->assertArrayHasKey('overall_best', $result);
        $this->assertArrayHasKey('overall_worst', $result);
        $this->assertArrayHasKey('overall_worst', $result);
        $this->assertArrayHasKey('timeperiod_id', $result);
        $this->assertArrayHasKey('lost_count', $result);
        $this->assertArrayHasKey('lost_amount', $result);
        $this->assertArrayHasKey('lost_best', $result);
        $this->assertArrayHasKey('lost_worst', $result);
        $this->assertArrayHasKey('won_count', $result);
        $this->assertArrayHasKey('won_amount', $result);
        $this->assertArrayHasKey('won_best', $result);
        $this->assertArrayHasKey('won_worst', $result);
        $this->assertArrayHasKey('included_opp_count', $result);
        $this->assertArrayHasKey('total_opp_count', $result);
        $this->assertArrayHasKey('includedClosedCount', $result);
        $this->assertArrayHasKey('includedClosedAmount', $result);
        $this->assertArrayHasKey('includedClosedBest', $result);
        $this->assertArrayHasKey('includedClosedWorst', $result);
        $this->assertArrayHasKey('includedIdsInLikelyTotal', $result);
        $this->assertArrayHasKey('user_id', $result);

        $this->assertEquals($user->id, $result['user_id']);
        $this->assertEquals(self::$timeperiod->id, $result['timeperiod_id']);
    }

    /**
     * Test method progressManager
     */
    public function testProgressManager()
    {
        $user = SugarTestUserUtilities::createAnonymousUser();

        $result = $this->api->progressManager($this->service, array(
            'user_id' => $user->id,
            'timeperiod_id' => self::$timeperiod->id,
        ));
        $this->assertNotEmpty($result);

        $this->assertArrayHasKey('best_case', $result);
        $this->assertArrayHasKey('best_adjusted', $result);
        $this->assertArrayHasKey('likely_case', $result);
        $this->assertArrayHasKey('likely_adjusted', $result);
        $this->assertArrayHasKey('timeperiod_id', $result);
        $this->assertArrayHasKey('user_id', $result);
        $this->assertArrayHasKey('worst_case', $result);
        $this->assertArrayHasKey('worst_adjusted', $result);
        $this->assertArrayHasKey('closed_amount', $result);
        $this->assertArrayHasKey('quota_amount', $result);

        $this->assertEquals($user->id, $result['user_id']);
        $this->assertEquals(self::$timeperiod->id, $result['timeperiod_id']);
    }
}
