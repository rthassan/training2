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
require_once('include/SugarForecasting/Progress/Individual.php');
class SugarForecasting_Progress_IndividualTest extends Sugar_PHPUnit_Framework_TestCase
{

    /**
     * @var array args to be passed onto methods
     */
    protected static $args = array();

    /**
     * @var array array of users used throughout class
     */
    protected static $users = array();

    /**
     * @var Currency
     */
    protected static $currency;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        SugarTestHelper::setUp('app_strings');
        SugarTestHelper::setUp('app_list_strings');
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setup('mod_strings', array('manager', 'Forecasts'));
        SugarTestHelper::setup('current_user');
        SugarTestForecastUtilities::setUpForecastConfig();

        $timeperiod = SugarTestTimePeriodUtilities::createTimePeriod('2009-01-01', '2009-03-31');

        self::$args['timeperiod_id'] = $timeperiod->id;

        self::$currency = SugarTestCurrencyUtilities::createCurrency('Yen','¥','YEN',78.87);

        SugarTestForecastUtilities::setTimePeriod($timeperiod);

        global $current_user;

        $config = array(
            'timeperiod_id' => $timeperiod->id,
            'currency_id' => self::$currency->id,
            'quota' => array('amount' => 27000)
        );
        self::$users['reportee'] = SugarTestForecastUtilities::createForecastUser($config);

        $current_user = self::$users['reportee']['user'];
        $current_user->setPreference('currency', self::$currency->id);
        self::$args['user_id'] = self::$users['reportee']['user']->id;

    }

    /**
     * reset after each test back to manager id, some tests may have changed to use top manager
     */
    public function setup() {
        global $current_user;

        self::$args['user_id'] = self::$users['reportee']['user']->id;
        $current_user = self::$users['reportee']['user'];
    }

    public static function tearDownAfterClass()
    {
        SugarTestForecastUtilities::tearDownForecastConfig();
        SugarTestHelper::tearDown();
        SugarTestTimePeriodUtilities::removeAllCreatedTimePeriods();
        SugarTestForecastUtilities::cleanUpCreatedForecastUsers();
        SugarTestCurrencyUtilities::removeAllCreatedCurrencies();
        SugarTestQuotaUtilities::removeAllCreatedQuotas();
        parent::tearDown();
    }

    /**
     * check process method to make sure what is returned to the endpoint is correct
     *
     * @group forecasts
     * @group forecastsprogress
     */
    public function testProcess()
    {
        $obj = new SugarForecasting_Progress_Individual(self::$args);
        $data = $obj->process();

        //find expected quota object for the created quotas
        foreach(SugarTestQuotaUtilities::getCreatedQuotaIds() as $quotaID) {
            $quota = BeanFactory::getBean('Quotas', $quotaID);
            if($quota->timeperiod_id == self::$args['timeperiod_id'] && $quota->user_id == self::$args['user_id'] && $quota->quota_type == "Direct"){
                break;
            }
        }
        //test parts of the process return
        $this->assertEquals($quota->amount, $data['quota_amount'], "Quota not matching expected amount.  Expected: ".$quota->amount." Actual: ".$data['quota_amount']);
    }

    /**
     * check process method to make sure what is returned to the endpoint is correct
     *
     * @group forecasts
     * @group forecastsprogress
     */
    public function testProcessNewUser()
    {
        $newUser = SugarTestUserUtilities::createAnonymousUser();
        $newUser->save();
        self::$args['user_id'] = $newUser->id;
        $obj = new SugarForecasting_Progress_Individual(self::$args);
        $data = $obj->process();
        //test parts of the process return
        $this->assertEquals(0, $data['quota_amount'], "Quota not matching expected amount.  Expected: 0 Actual: ".$data['quota_amount']);
    }


}
