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

require_once('modules/TimePeriods/TimePeriod.php');
require_once('include/SugarForecasting/Filter/TimePeriodFilter.php');

class ForecastsTimePeriodTest extends Sugar_PHPUnit_Framework_TestCase
{
    private $preTestIds = array();
    private static $configDateFormat;
    private static $currentDate;
    private static $currentYear;

// Marking as skipped as it fails in strict mode


//    //These are the default forecast configuration settings we will use to test
//    private static $forecastConfigSettings = array (
//        array('name' => 'timeperiod_type', 'value' => 'chronological', 'platform' => 'base', 'category' => 'Forecasts'),
//        array('name' => 'timeperiod_interval', 'value' => TimePeriod::ANNUAL_TYPE, 'platform' => 'base', 'category' => 'Forecasts'),
//        array('name' => 'timeperiod_leaf_interval', 'value' => TimePeriod::QUARTER_TYPE, 'platform' => 'base', 'category' => 'Forecasts'),
//        array('name' => 'timeperiod_start_date', 'value' => '2013-01-01', 'platform' => 'base', 'category' => 'Forecasts'),
//        array('name' => 'timeperiod_shown_forward', 'value' => '2', 'platform' => 'base', 'category' => 'Forecasts'),
//        array('name' => 'timeperiod_shown_backward', 'value' => '2', 'platform' => 'base', 'category' => 'Forecasts')
//    );
//
//    /**
//     * Setup global variables
//     */
//    public static function setUpBeforeClass()
//    {
//        self::$configDateFormat = $GLOBALS['sugar_config']['datef'];
//        $db = DBManagerFactory::getInstance();
//        $db->query('UPDATE timeperiods set deleted = 1');
//    }
//
//    /**
//     * Call SugarTestHelper to teardown initialization in setUpBeforeClass
//     */
//    public static function tearDownAfterClass()
//    {
//        SugarTestHelper::tearDown();
//        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
//        $GLOBALS['sugar_config']['datef'] = self::$configDateFormat;
//    }

    public function setUp()
    {
        $this->markTestIncomplete("Skipping as it causes mass failures.  SFA team.");
//        parent::setUp();
//        SugarTestHelper::setUp('app_strings');
//        SugarTestHelper::setUp('beanFiles');
//        SugarTestHelper::setUp('beanList');
//        SugarTestHelper::setUp('current_user');
//        self::$currentYear = date('Y');
//        //get current timedate
//        $timedate = TimeDate::getInstance();
//        self::$currentDate = $timedate->getNow();
//        $timedate->clearCache();
//        $this->preTestIds = TimePeriod::get_timeperiods_dom();
//
//        $db = DBManagerFactory::getInstance();
//
//        $db->query('UPDATE timeperiods set deleted = 1');
//
//        $admin = BeanFactory::getBean('Administration');
//
//        self::$forecastConfigSettings[3]['timeperiod_start_date']['value'] = TimeDate::getInstance()->getNow()->setDate(date('Y'), 1, 1)->asDbDate(false);
//        foreach(self::$forecastConfigSettings as $config)
//        {
//            $admin->saveSetting($config['category'], $config['name'], $config['value'], $config['platform']);
//        }
//
//        //Run rebuildForecastingTimePeriods which takes care of creating the TimePeriods based on the configuration data
//        $timePeriod = TimePeriod::getByType(TimePeriod::ANNUAL_TYPE);
//
//        $currentForecastSettings = $admin->getConfigForModule('Forecasts', 'base');
//        $timePeriod->rebuildForecastingTimePeriods(array(), $currentForecastSettings);
//
//        //add all of the newly created timePeriods to the test utils
//        $result = $db->query('SELECT id, start_date, end_date, type FROM timeperiods WHERE deleted = 0');
//        $createdTimePeriods = array();
//
//        while($row = $db->fetchByAssoc($result))
//        {
//            $createdTimePeriods[] = TimePeriod::getBean($row['id']);
//        }
//
//        SugarTestTimePeriodUtilities::setCreatedTimePeriods($createdTimePeriods);
    }

//    public function tearDown()
//    {
//        $db = DBManagerFactory::getInstance();
//
//        $db->query("UPDATE timeperiods set deleted = 1");
//
//        //Clean up anything else left in timeperiods table that was not deleted
//        $db->query("UPDATE timeperiods SET deleted = 0 WHERE id IN ('" . implode("', '", array_keys($this->preTestIds))  . "')");
//
//        $db->query("DELETE FROM timeperiods WHERE deleted = 1");
//
//        //reset timedate
//        $timedate = TimeDate::getInstance();
//        $timedate->setNow(self::$currentDate);
//        $timedate->clearCache();
//        $timedate->allow_cache = true;
//        parent::tearDown();
//    }

    /**
     * testTimePeriodDeleteTimePeriodsWithSamePreviousSettings
     *
     * This test will check
     * 1) That the count of the the timeperiods in the database will be the same before and after the deleteTimePeriods call
     * 2) That the count of the deleted timeperiods will remain the same before and after the deleteTimePeriods calls
     * @group timeperiods
     * @group forecasts
     *
     */
    public function testTimePeriodDeleteTimePeriodsWithSamePreviousSettings()
    {
        $admin = BeanFactory::newBean('Administration');
        $prior_forecasts_settings = $admin->getConfigForModule('Forecasts', 'base');

        $timePeriod = BeanFactory::newBean('TimePeriods');
        $this->assertTrue($timePeriod->isSettingIdentical($prior_forecasts_settings, $prior_forecasts_settings));
    }


    /**
     * getShownDifferenceProvider
     *
     * This is the data provider function for getShownDifferenceProvider
     */
    public function getShownDifferenceProvider()
    {
        return array(
           array(1, 2, 'timeperiod_shown_forward', 1),
           array(2, 2, 'timeperiod_shown_forward', 0),
           array(2, 1, 'timeperiod_shown_forward', -1),
           array(1, 2, 'timeperiod_shown_backward', 1),
           array(2, 2, 'timeperiod_shown_backward', 0),
           array(2, 1, 'timeperiod_shown_backward', -1)
        );
    }


    /**
     * This function tests the getShownDifference method in TimePeriod
     *
     * @group timeperiods
     * @group forecasts
     * @dataProvider getShownDifferenceProvider
     */
    public function testGetShownDifference($previous, $current, $key, $expected)
    {
        $timePeriod = BeanFactory::getBean('TimePeriods');

        $admin = BeanFactory::newBean('Administration');
        $priorForecastSettings = $admin->getConfigForModule('Forecasts', 'base');
        $priorForecastSettings[$key] = $previous;

        $newConfigSettings = $priorForecastSettings;
        $newConfigSettings[$key] = $current;

        $this->assertEquals($expected, $timePeriod->getShownDifference($priorForecastSettings, $newConfigSettings, $key), sprintf("Failed asserting that %s difference was not %d", $key, $expected));
    }

    /**
     * testIsTargetDateDifferentFromPrevious
     *
     * This test will check the accuracy of the timedate->isTargetDateDifferentFromPrevious method
     *
     * @group timeperiods
     * @group forecasts
     */
    public function testIsTargetDateDifferentFromPrevious()
    {
        $timedate = TimeDate::getInstance();
        $timeperiod = BeanFactory::getBean('TimePeriods');

        //First let's check what happens when we pass the same start month and day
        $targetStartDate = $timedate->getNow();
        $targetStartDate->setDate($targetStartDate->format('Y'), 1, 1);

        $admin = BeanFactory::newBean('Administration');
        $priorForecastSettings = $admin->getConfigForModule('Forecasts', 'base');

        $this->assertFalse($timeperiod->isTargetDateDifferentFromPrevious($targetStartDate, $priorForecastSettings), sprintf("Failed asserting that %s is not different target start date", $timedate->asDbDate($targetStartDate)));

        //Check if the start_date is different
        $priorForecastSettings['timeperiod_start_date'] = '2012-02-02';
        $this->assertTrue($timeperiod->isTargetDateDifferentFromPrevious($targetStartDate, $priorForecastSettings), sprintf("Failed asserting that %s is different target start date", $timedate->asDbDate($targetStartDate)));

        //Check if the targetStartDate is one year back
        $targetStartDate->modify('-1 year');
        $priorForecastSettings['timeperiod_start_date'] = '2012-01-01';
        $this->assertFalse($timeperiod->isTargetDateDifferentFromPrevious($targetStartDate, $priorForecastSettings), sprintf("Failed asserting that %s is different target start date", $timedate->asDbDate($targetStartDate)));

        //Check if the targetStartDate is one year back
        $targetStartDate->modify('+2 year');
        $this->assertFalse($timeperiod->isTargetDateDifferentFromPrevious($targetStartDate, $priorForecastSettings), sprintf("Failed asserting that %s is different target start date", $timedate->asDbDate($targetStartDate)));

        //Check if there were no previous settings
        $this->assertTrue($timeperiod->isTargetDateDifferentFromPrevious($targetStartDate, array()), sprintf("Failed asserting that %s is different target start date", $timedate->asDbDate($targetStartDate)));
    }


    /**
     * testIsTargetIntervalDifferent
     *
     * @group timeperiods
     * @group forecasts
     */
    public function testIsTargetIntervalDifferent()
    {
        $timeperiod = BeanFactory::getBean('TimePeriods');
        $admin = BeanFactory::newBean('Administration');
        $priorForecastSettings = $admin->getConfigForModule('Forecasts', 'base');
        $currentForecastSettings = $priorForecastSettings;

        //Check if they're the same
        $this->assertFalse($timeperiod->isTargetIntervalDifferent($priorForecastSettings, $currentForecastSettings));

        //Check if prior settings are empty
        $this->assertTrue($timeperiod->isTargetIntervalDifferent(array(), $currentForecastSettings));

        //Check if timeperiod_interval chagnes
        $currentForecastSettings['timeperiod_interval'] = TimePeriod::QUARTER_TYPE;
        $this->assertTrue($timeperiod->isTargetIntervalDifferent($priorForecastSettings, $currentForecastSettings));

        //Check if timeperiod_leaf_interval chagnes
        $currentForecastSettings['timeperiod_interval'] = TimePeriod::QUARTER_TYPE;
        $currentForecastSettings['timeperiod_leaf_interval'] = TimePeriod::MONTH_TYPE;
        $this->assertTrue($timeperiod->isTargetIntervalDifferent($priorForecastSettings, $currentForecastSettings));
    }


    /**
     * getByTypeDataProvider
     *
     * This is the data provider function for the testGetByType function
     * @group timeperiods
     * @group forecasts
     */
    public function getByTypeDataProvider()
    {
        return array(
            array(TimePeriod::ANNUAL_TYPE),
            array(TimePeriod::QUARTER_TYPE),
            array(TimePeriod::MONTH_TYPE)
        );
    }

    /**
     * testGetByType
     *
     * @group timeperiod
     * @group forecasts
     *
     * This is a test to check that the TimePeriod::getByType function returns the appropriate TimePeriod bean instance
     * @dataProvider getByTypeDataProvider
     */
    public function testGetByType($type)
    {
        $bean = TimePeriod::getByType($type);
        $this->assertEquals($type, $bean->type);
    }


    /**
     * getTimePeriodNameProvider
     *
     * This is the data provider function for the testTimePeriodName function
     */
    public function getTimePeriodNameProvider()
    {
        return array(
            array('m/d/Y', TimePeriod::ANNUAL_TYPE, '2012-07-01', 1, 'Year 2012'),
            array('m/d/Y', TimePeriod::ANNUAL_TYPE, '2012-12-31', 2, 'Year 2012'),
            array('m/d/Y', TimePeriod::ANNUAL_TYPE, '2013-01-01', 1, 'Year 2013'),
            array('m/d/Y', TimePeriod::QUARTER_TYPE, '2012-07-01', 1, 'Q1 (07/01/2012 - 09/30/2012)'),
            array('m/d/Y', TimePeriod::QUARTER_TYPE, '2012-10-01', 2, 'Q2 (10/01/2012 - 12/31/2012)'),
            array('m/d/Y', TimePeriod::QUARTER_TYPE, '2013-01-01', 3, 'Q3 (01/01/2013 - 03/31/2013)'),
            array('m/d/Y', TimePeriod::QUARTER_TYPE, '2013-04-01', 4, 'Q4 (04/01/2013 - 06/30/2013)'),
            array('m/d/Y', TimePeriod::MONTH_TYPE, '2012-07-01', 1, '07/01/2012 - 07/31/2012'),
            array('m/d/Y', TimePeriod::MONTH_TYPE, '2012-08-01', 2, '08/01/2012 - 08/31/2012'),
            array('m/d/Y', TimePeriod::MONTH_TYPE, '2012-09-01', 3, '09/01/2012 - 09/30/2012'),
            array('m/d/Y', TimePeriod::MONTH_TYPE, '2012-10-01', 4, '10/01/2012 - 10/31/2012'),
            array('m/d/Y', TimePeriod::MONTH_TYPE, '2012-11-01', 5, '11/01/2012 - 11/30/2012'),
            array('m/d/Y', TimePeriod::MONTH_TYPE, '2012-12-01', 6, '12/01/2012 - 12/31/2012'),
            array('m/d/Y', TimePeriod::MONTH_TYPE, '2012-01-01', 7, '01/01/2012 - 01/31/2012'),
            array('m/d/Y', TimePeriod::MONTH_TYPE, '2012-02-01', 8, '02/01/2012 - 02/29/2012'),
            array('m/d/Y', TimePeriod::MONTH_TYPE, '2012-03-01', 9, '03/01/2012 - 03/31/2012'),
            array('m/d/Y', TimePeriod::MONTH_TYPE, '2012-04-01', 10, '04/01/2012 - 04/30/2012'),
            array('m/d/Y', TimePeriod::MONTH_TYPE, '2012-05-01', 11, '05/01/2012 - 05/31/2012'),
            array('m/d/Y', TimePeriod::MONTH_TYPE, '2012-06-01', 12, '06/01/2012 - 06/30/2012'),

            //Test with a different date format
            array('m.d.Y', TimePeriod::ANNUAL_TYPE, '2012-07-01', 1, 'Year 2012'),
            array('m.d.Y', TimePeriod::ANNUAL_TYPE, '2012-12-31', 2, 'Year 2012'),
            array('m.d.Y', TimePeriod::ANNUAL_TYPE, '2013-01-01', 1, 'Year 2013'),
            array('m.d.Y', TimePeriod::QUARTER_TYPE, '2012-07-01', 1, 'Q1 (07.01.2012 - 09.30.2012)'),
            array('m.d.Y', TimePeriod::QUARTER_TYPE, '2012-10-01', 2, 'Q2 (10.01.2012 - 12.31.2012)'),
            array('m.d.Y', TimePeriod::QUARTER_TYPE, '2013-01-01', 3, 'Q3 (01.01.2013 - 03.31.2013)'),
            array('m.d.Y', TimePeriod::QUARTER_TYPE, '2013-04-01', 4, 'Q4 (04.01.2013 - 06.30.2013)'),
            array('m.d.Y', TimePeriod::MONTH_TYPE, '2012-07-01', 1, '07.01.2012 - 07.31.2012'),
            array('m.d.Y', TimePeriod::MONTH_TYPE, '2012-08-01', 2, '08.01.2012 - 08.31.2012'),
            array('m.d.Y', TimePeriod::MONTH_TYPE, '2012-09-01', 3, '09.01.2012 - 09.30.2012'),
            array('m.d.Y', TimePeriod::MONTH_TYPE, '2012-10-01', 4, '10.01.2012 - 10.31.2012'),
            array('m.d.Y', TimePeriod::MONTH_TYPE, '2012-11-01', 5, '11.01.2012 - 11.30.2012'),
            array('m.d.Y', TimePeriod::MONTH_TYPE, '2012-12-01', 6, '12.01.2012 - 12.31.2012'),
            array('m.d.Y', TimePeriod::MONTH_TYPE, '2012-01-01', 7, '01.01.2012 - 01.31.2012'),
            array('m.d.Y', TimePeriod::MONTH_TYPE, '2012-02-01', 8, '02.01.2012 - 02.29.2012'),
            array('m.d.Y', TimePeriod::MONTH_TYPE, '2012-03-01', 9, '03.01.2012 - 03.31.2012'),
            array('m.d.Y', TimePeriod::MONTH_TYPE, '2012-04-01', 10, '04.01.2012 - 04.30.2012'),
            array('m.d.Y', TimePeriod::MONTH_TYPE, '2012-05-01', 11, '05.01.2012 - 05.31.2012'),
            array('m.d.Y', TimePeriod::MONTH_TYPE, '2012-06-01', 12, '06.01.2012 - 06.30.2012')
        );
    }

    /**
     * testGetTimePeriodName
     *
     * This is a test to check that the getTimePeriodName function returns the appropriate names based on the TimePeriod bean instance
     *
     * @group forecasts
     * @group timeperiods
     * @dataProvider getTimePeriodNameProvider
     */
    public function testGetTimePeriodName($datef, $type, $startDate, $count, $expectedName)
    {
        $GLOBALS['sugar_config']['datef'] = $datef;
        $timePeriod = TimePeriod::getByType($type);
        $timePeriod->setStartDate($startDate);
        $this->assertEquals($expectedName, $timePeriod->getTimePeriodName($count));
    }


    /**
     * testGetLatest
     * This is a test for TimePeriod::getLatest function
     *
     * @group forecasts
     * @group timeperiods
     */
    public function testGetLatest()
    {
        $db = DBManagerFactory::getInstance();
        //Mark all created test timeperiods as deleted so that they do not interfere with the test
        $db->query('UPDATE timeperiods SET deleted = 1');

        //Create 3 timeperiods.  The latest should be the last one
        $tp1 = SugarTestTimePeriodUtilities::createTimePeriod('2000-01-01', '2000-03-31');
        $tp1->type = TimePeriod::ANNUAL_TYPE;
        $tp1->save();

        $tp2 = SugarTestTimePeriodUtilities::createTimePeriod('2001-01-01', '2001-03-31');
        $tp2->type = TimePeriod::ANNUAL_TYPE;
        $tp2->save();

        $tp3 = SugarTestTimePeriodUtilities::createTimePeriod('2002-01-01', '2002-03-31');
        $tp3->type = TimePeriod::ANNUAL_TYPE;
        $tp3->save();
        $timePeriod = TimePeriod::getLatest(TimePeriod::ANNUAL_TYPE);

        $this->assertEquals($tp3->id, $timePeriod->id);
    }


    /**
     * testGetEarliest
     * This is a test for the TimePeriod::getEarliest function
     *
     * @group forecasts
     * @group timeperiods
     */
    public function testGetEarliest()
    {
        $db = DBManagerFactory::getInstance();
        //Mark all created test timeperiods as deleted so that they do not interfere with the test
        $db->query('UPDATE timeperiods SET deleted = 1');

        //Create three timeperiods.  The earliest should be $tp1
        $tp1 = SugarTestTimePeriodUtilities::createTimePeriod('1980-01-01', '1980-03-31');
        $tp1->type = TimePeriod::ANNUAL_TYPE;
        $tp1->save();

        $tp2 = SugarTestTimePeriodUtilities::createTimePeriod('1981-01-01', '1981-03-31');
        $tp2->type = TimePeriod::ANNUAL_TYPE;
        $tp2->save();

        $tp3 = SugarTestTimePeriodUtilities::createTimePeriod('1982-01-01', '1982-03-31');
        $tp3->type = TimePeriod::ANNUAL_TYPE;
        $tp3->save();

        $timePeriod = TimePeriod::getEarliest(TimePeriod::ANNUAL_TYPE);
        $this->assertEquals($tp1->id, $timePeriod->id);
    }


    /**
     * rebuildForecastingTimePeriodsProvider
     *
     *
     * The arguments are as follows
     * 1) The is_upgrade setting to use in simulating the call to rebuildForecastingTimePeriods
     * 2) The prior timeperiod_shown_backward argument
     * 3) The current timeperiod_shown_backward argument
     * 4) The parent TimePeriod type to create
     * 5) The leaf TimePeriod type to create
     * 6) The timeperiod_start_month argument
     * 7) The timeperiod_start_day argument
     * 8) The expected number of parent TimePeriod instances to create
     * 9) The expected number of leaf TimePeriod instances to create
     * 10) Direction
     * 11) The expected month of the parent TimePeriod based on direction
     * 12) The expected day of the parent TimePeriod based on direction
     * 13) The expected month of the leaf TimePeriod based on direction
     * 14) The expected day of the leaf TimePeriod based on direction
     */
    public function rebuildForecastingTimePeriodsProvider()
    {
        return array
        (
            //Going from 2 to 4 creates 2 additional annual timeperiods backwards (2 annual, 8 quarters)

            array(0, 2, 4, TimePeriod::ANNUAL_TYPE, TimePeriod::QUARTER_TYPE, 1, 1, '-2 year', 2, 8, 'backward'),

            array(0, 2, 4, TimePeriod::ANNUAL_TYPE, TimePeriod::QUARTER_TYPE, 7, 1, '-2 year', 2, 8, 'backward'),

            //Going from 4 to 6 creates 2 annual timeperiods backwards (2 annual, 8 quarters)
            array(0, 4, 6, TimePeriod::ANNUAL_TYPE, TimePeriod::QUARTER_TYPE, 1, 1, '-2 year', 2, 8, 'backward'),

            //Going from 6 to 2 should not create anything
            array(0, 6, 2, TimePeriod::ANNUAL_TYPE, TimePeriod::QUARTER_TYPE, 1, 1, '0 year', 0, 0, 'backward'),

            //Going from 2 to 4 creates 2 annual timeperiods forward (2 annual, 8 quarters)
            array(0, 2, 4, TimePeriod::ANNUAL_TYPE, TimePeriod::QUARTER_TYPE, 1, 1, '1 year', 2, 8, 'forward', 1, 1, 10, 1),

            //Going from 2 to 4 creates 2 annual timeperiods forward (2 annual, 8 quarters)
            array(0, 2, 4, TimePeriod::ANNUAL_TYPE, TimePeriod::QUARTER_TYPE, 7, 1, '1 year', 2, 8, 'forward', 1, 1, 10, 1),

            //Going from 4 to 6 creates 2 annual timeperiods forward (2 annual, 8 quarters)
            array(0, 4, 6, TimePeriod::ANNUAL_TYPE, TimePeriod::QUARTER_TYPE, 1, 1, '1 year', 2, 8, 'forward', 1, 1, 10, 1),

            //Going from 6 to 2 should not create anything
            array(0, 6, 2, TimePeriod::ANNUAL_TYPE, TimePeriod::QUARTER_TYPE, 1, 1, '0 year', 0, 0, 'forward', 1, 1, 10, 1),

            //Create 4 quarters going backward.  Earliest quarter and month should be -1 year from timeperiod
            array(0, 0, 4, TimePeriod::QUARTER_TYPE, TimePeriod::MONTH_TYPE, 1, 1, '-1 year', 4, 12, 'backward'),

            //Create 8 quarters going backward.  Earliest quarter and month should be -2 years from timeperiod
            array(0, 4, 12, TimePeriod::QUARTER_TYPE, TimePeriod::MONTH_TYPE, 1, 1, '-2 year', 8, 24, 'backward'),

            //Going from 12 to 6 should not create anything
            array(0, 12, 6, TimePeriod::QUARTER_TYPE, TimePeriod::MONTH_TYPE, 1, 1, '0 year', 0, 0, 'backward'),

            array(0, 0, 4, TimePeriod::QUARTER_TYPE, TimePeriod::MONTH_TYPE, 1, 1, '0 year', 4, 12, 'forward', 10, 1, 12, 1),
            array(0, 4, 12, TimePeriod::QUARTER_TYPE, TimePeriod::MONTH_TYPE, 1, 1, '1 year', 8, 24, 'forward', 10, 1, 12, 1),
            array(0, 12, 6, TimePeriod::QUARTER_TYPE, TimePeriod::MONTH_TYPE, 1, 1, '0 year', 0, 0, 'forward', 10, 1, 12, 1),

            //Forward TimePeriods will be created
            array(1, 2, 2, TimePeriod::ANNUAL_TYPE, TimePeriod::QUARTER_TYPE, 1, 1, '2 year', 2, 8, 'forward'),
            array(1, 2, 4, TimePeriod::QUARTER_TYPE, TimePeriod::MONTH_TYPE, 1, 1, '1 year', 4, 12, 'forward'),

        );
    }

    /**
     * This is a test for checking the creation of time periods based on various scenarios
     *
     * @group forecasts
     * @group timeperiods
     * @dataProvider rebuildForecastingTimePeriodsProvider
     */
    public function testRebuildForecastingTimePeriods (
            $isUpgrade,
            $previous,
            $current,
            $parentType,
            $leafType,
            $startMonth,
            $startDay,
            $dateModifier,
            $expectedParents,
            $expectedLeaves,
            $direction,
            $expectedMonth = 1,
            $expectedDay = 1,
            $expectedLeafMonth = 1,
            $expectedLeafDay = 1
    ) {
        $this->markTestIncomplete('SFA Team - This test breaks when run with the test suite, on data sets #12 and #13');
        $timedate = TimeDate::getInstance();

        $admin = BeanFactory::newBean('Administration');

        $priorForecastSettings = $admin->getConfigForModule('Forecasts', 'base');
        $priorForecastSettings["timeperiod_shown_{$direction}"] = $previous;
        $priorForecastSettings['timeperiod_interval'] = $parentType;
        $priorForecastSettings['timeperiod_leaf_interval'] = $leafType;
        $priorForecastSettings['is_upgrade'] = $isUpgrade;

        $currentForecastSettings = $priorForecastSettings;
        $currentForecastSettings["timeperiod_shown_{$direction}"] = $current;
        $currentForecastSettings['timeperiod_interval'] = $parentType;
        $currentForecastSettings['timeperiod_leaf_interval'] = $leafType;
        $currentForecastSettings['is_upgrade'] = $isUpgrade;

        $db = DBManagerFactory::getInstance();

        //If it's not annual type we need to re-seed with Quarter/Monthly intervals
        if($parentType != TimePeriod::ANNUAL_TYPE)
        {
           $admin->saveSetting('Forecasts', 'timeperiod_interval', $parentType, 'base');
           $admin->saveSetting('Forecasts', 'timeperiod_leaf_interval', $leafType, 'base');
           $db->query("UPDATE timeperiods SET deleted = 0");
           $priorForecastSettings["timeperiod_shown_backward"] = 8;
           $priorForecastSettings["timeperiod_shown_forward"] = 8;
           $timePeriod = TimePeriod::getByType($parentType);
           $timePeriod->rebuildForecastingTimePeriods(array(), $priorForecastSettings);
           $priorForecastSettings["timeperiod_shown_{$direction}"] = $previous;
        }

        $expectedSeed = ($direction == 'backward') ? TimePeriod::getEarliest($parentType) :  TimePeriod::getLatest($parentType);

        $timePeriod = TimePeriod::getByType($parentType);
        $timePeriod->rebuildForecastingTimePeriods($priorForecastSettings, $currentForecastSettings);

        $expectedDate = $timedate->getNow()->setDate($timedate->fromDbDate($expectedSeed->start_date)->modify($dateModifier)->format('Y'), $expectedMonth, $expectedDay);

        if($isUpgrade && $direction == 'forward') {
            $start_date = $db->getOne("SELECT max(start_date) FROM timeperiods WHERE type = '{$parentType}' AND deleted = 0");
            $expectedDate = $timedate->fromDbDate(substr($start_date, 0, 10));
        }

        $tp = $direction == 'backward' ? TimePeriod::getEarliest($parentType) : TimePeriod::getLatest($parentType);

        $this->assertEquals($expectedDate->asDbDate(false), $tp->start_date, "Failed creating {$expectedParents} new {$direction} timeperiods");

        //If this is an upgrade the expectedDate should be forward from what the current time period is
        if($isUpgrade && $direction == 'forward') {
            $tp = TimePeriod::getLatest($leafType);
            $start_date = $db->getOne("SELECT max(start_date) FROM timeperiods WHERE type = '{$leafType}' AND deleted = 0");
            $expectedDate = $timedate->fromDbDate(substr($start_date, 0, 10));
            $this->assertEquals($expectedDate->asDbDate(false), $tp->start_date, "Failed creating {$expectedLeaves} leaf timeperiods");
        }

    }


    /**
     * This is the data provider to simulate arguments we pass to the testCreateTimePeriodsForUpgrade test
     *
     */
    public function testCreateTimePeriodsForUpgradeProvider() {

        return array(

            //This data set simulates case where the start date specified is the same as current date

            array(1, TimePeriod::ANNUAL_TYPE, TimePeriod::QUARTER_TYPE, TimeDate::getInstance()->getNow()->setDate(date('Y'), 1, 1), 2, TimeDate::getInstance()->getNow()->setDate(date('Y'), 1, 1), 15, TimeDate::getInstance()->getNow()->setDate(date('Y'), 10, 1), TimeDate::getInstance()->getNow()->setDate(date('Y'), 12, 31)),
            array(1, TimePeriod::ANNUAL_TYPE, TimePeriod::QUARTER_TYPE, TimeDate::getInstance()->getNow()->setDate(date('Y'), 1, 1), 4, TimeDate::getInstance()->getNow()->setDate(date('Y'), 1, 1), 25, TimeDate::getInstance()->getNow()->setDate(date('Y'), 10, 1), TimeDate::getInstance()->getNow()->setDate(date('Y'), 12, 31)),
            array(1, TimePeriod::QUARTER_TYPE, TimePeriod::MONTH_TYPE, TimeDate::getInstance()->getNow()->setDate(date('Y'), 1, 1), 2, TimeDate::getInstance()->getNow()->setDate(date('Y'), 1, 1), 10, TimeDate::getInstance()->getNow()->setDate(date('Y'), 1, 1), TimeDate::getInstance()->getNow()->setDate(date('Y'), 3, 31)),
            array(1, TimePeriod::QUARTER_TYPE, TimePeriod::MONTH_TYPE, TimeDate::getInstance()->getNow()->setDate(date('Y'), 1, 1), 4, TimeDate::getInstance()->getNow()->setDate(date('Y'), 1, 1), 18, TimeDate::getInstance()->getNow()->setDate(date('Y'), 1, 1), TimeDate::getInstance()->getNow()->setDate(date('Y'), 3, 31)),
            array(9, TimePeriod::QUARTER_TYPE, TimePeriod::MONTH_TYPE, TimeDate::getInstance()->getNow()->setDate(date('Y'), 1, 1), 2, TimeDate::getInstance()->getNow()->setDate(date('Y'), 1, 1), 10, TimeDate::getInstance()->getNow()->setDate(date('Y'), 9, 1), TimeDate::getInstance()->getNow()->setDate(date('Y'), 9, 30)),

            array(17, TimePeriod::QUARTER_TYPE, TimePeriod::MONTH_TYPE, TimeDate::getInstance()->getNow()->setDate(date('Y'), 1, 1), 4, TimeDate::getInstance()->getNow()->setDate(date('Y'), 1, 1), 18, TimeDate::getInstance()->getNow()->setDate(date('Y')+1, 3, 1), TimeDate::getInstance()->getNow()->setDate(date('Y')+1, 3, 31)),

            //This data set simulates case where the start date specified is before the current date
            array(1, TimePeriod::ANNUAL_TYPE, TimePeriod::QUARTER_TYPE, TimeDate::getInstance()->getNow()->setDate(date('Y'), 2, 1), 2, TimeDate::getInstance()->getNow()->setDate(date('Y'), 3, 1), 15, TimeDate::getInstance()->getNow()->setDate(date('Y'), 11, 1), TimeDate::getInstance()->getNow()->setDate(date('Y')+1, 1, 31)),

            array(1, TimePeriod::ANNUAL_TYPE, TimePeriod::QUARTER_TYPE, TimeDate::getInstance()->getNow()->setDate(date('Y'), 2, 1), 4, TimeDate::getInstance()->getNow()->setDate(date('Y'), 3, 1), 25, TimeDate::getInstance()->getNow()->setDate(date('Y'), 11, 1), TimeDate::getInstance()->getNow()->setDate(date('Y')+1, 1, 31)),
            array(1, TimePeriod::QUARTER_TYPE, TimePeriod::MONTH_TYPE, TimeDate::getInstance()->getNow()->setDate(date('Y'), 2, 1), 2, TimeDate::getInstance()->getNow()->setDate(date('Y'), 3, 1), 11, TimeDate::getInstance()->getNow()->setDate(date('Y'), 4, 1), TimeDate::getInstance()->getNow()->setDate(date('Y'), 4, 30)),
            array(1, TimePeriod::QUARTER_TYPE, TimePeriod::MONTH_TYPE, TimeDate::getInstance()->getNow()->setDate(date('Y'), 2, 1), 4, TimeDate::getInstance()->getNow()->setDate(date('Y'), 3, 1), 19, TimeDate::getInstance()->getNow()->setDate(date('Y'), 4, 1), TimeDate::getInstance()->getNow()->setDate(date('Y'), 4, 30)),
            array(14, TimePeriod::ANNUAL_TYPE, TimePeriod::QUARTER_TYPE, TimeDate::getInstance()->getNow()->setDate(date('Y'), 2, 1), 2, TimeDate::getInstance()->getNow()->setDate(date('Y'), 3, 1), 15, TimeDate::getInstance()->getNow()->setDate(date('Y')+2, 11, 1), TimeDate::getInstance()->getNow()->setDate(date('Y')+3, 1, 31)),
            array(24, TimePeriod::ANNUAL_TYPE, TimePeriod::QUARTER_TYPE, TimeDate::getInstance()->getNow()->setDate(date('Y'), 2, 1), 4, TimeDate::getInstance()->getNow()->setDate(date('Y'), 3, 1), 25, TimeDate::getInstance()->getNow()->setDate(date('Y')+4, 11, 1), TimeDate::getInstance()->getNow()->setDate(date('Y')+5, 1, 31)),

            //This data set simulates case where the start date specified is after the current date
            array(1, TimePeriod::ANNUAL_TYPE, TimePeriod::QUARTER_TYPE, TimeDate::getInstance()->getNow()->setDate(date('Y'), 3, 1), 2, TimeDate::getInstance()->getNow()->setDate(date('Y'), 2, 1), 15, TimeDate::getInstance()->getNow()->setDate(date('Y'), 12, 1), TimeDate::getInstance()->getNow()->setDate(date('Y')+1, 2, 28)),
            array(1, TimePeriod::ANNUAL_TYPE, TimePeriod::QUARTER_TYPE, TimeDate::getInstance()->getNow()->setDate(date('Y'), 3, 1), 4, TimeDate::getInstance()->getNow()->setDate(date('Y'), 2, 1), 25, TimeDate::getInstance()->getNow()->setDate(date('Y'), 12, 1), TimeDate::getInstance()->getNow()->setDate(date('Y')+1, 2, 28)),
            array(1, TimePeriod::QUARTER_TYPE, TimePeriod::MONTH_TYPE, TimeDate::getInstance()->getNow()->setDate(date('Y'), 3, 1), 2, TimeDate::getInstance()->getNow()->setDate(date('Y'), 2, 1), 12, TimeDate::getInstance()->getNow()->setDate(date('Y'), 5, 1), TimeDate::getInstance()->getNow()->setDate(date('Y'), 5, 31)),
            array(1, TimePeriod::QUARTER_TYPE, TimePeriod::MONTH_TYPE, TimeDate::getInstance()->getNow()->setDate(date('Y'), 3, 1), 4, TimeDate::getInstance()->getNow()->setDate(date('Y'), 2, 1), 20, TimeDate::getInstance()->getNow()->setDate(date('Y'), 5, 1), TimeDate::getInstance()->getNow()->setDate(date('Y'), 5, 31)),
            array(11, TimePeriod::QUARTER_TYPE, TimePeriod::MONTH_TYPE, TimeDate::getInstance()->getNow()->setDate(date('Y'), 3, 1), 2, TimeDate::getInstance()->getNow()->setDate(date('Y'), 2, 1), 12, TimeDate::getInstance()->getNow()->setDate(date('Y'), 11, 1), TimeDate::getInstance()->getNow()->setDate(date('Y'), 11, 30)),
            array(19, TimePeriod::QUARTER_TYPE, TimePeriod::MONTH_TYPE, TimeDate::getInstance()->getNow()->setDate(date('Y'), 3, 1), 4, TimeDate::getInstance()->getNow()->setDate(date('Y'), 2, 1), 20, TimeDate::getInstance()->getNow()->setDate(date('Y')+1, 5, 1), TimeDate::getInstance()->getNow()->setDate(date('Y')+1, 5, 31)),

            //This data set simulates case where the start date specified is before current date and there are no existing current TimePeriods for the current date
            array(1, TimePeriod::ANNUAL_TYPE, TimePeriod::QUARTER_TYPE, TimeDate::getInstance()->getNow()->setDate(date('Y')+2, 1, 1), 2, TimeDate::getInstance()->getNow()->setDate(date('Y')+2, 1, 1)->modify('+1 day'), 15, TimeDate::getInstance()->getNow()->setDate(date('Y')+2, 10, 1), TimeDate::getInstance()->getNow()->setDate(date('Y')+2, 12, 31)),

            //This data set simulates upgrades using variable TimePeriods so that we are not bound to the TimePeriods created in the setUp method
            array(1, TimePeriod::ANNUAL_TYPE, TimePeriod::QUARTER_TYPE, TimeDate::getInstance()->fromDbDate('2013-01-01'), 2, TimeDate::getInstance()->fromDbDate('2013-01-02'), 15, TimeDate::getInstance()->fromDbDate('2013-10-01'), TimeDate::getInstance()->fromDbDate('2013-12-31'),
                array("INSERT into timeperiods (id, name, start_date, end_date, parent_id, deleted) values ('abc1', 'Q4 2013', '2013-10-01', '2013-12-31', 'abc5', 0)",
                      "INSERT into timeperiods (id, name, start_date, end_date, parent_id, deleted) values ('abc2', 'Q3 2013', '2013-07-01', '2013-09-30', 'abc5', 0)",
                      "INSERT into timeperiods (id, name, start_date, end_date, parent_id, deleted) values ('abc3', 'Q2 2013', '2013-04-01', '2013-06-30', 'abc5', 0)",
                      "INSERT into timeperiods (id, name, start_date, end_date, deleted) values ('abc5', 'Year 2013', '2013-10-01', '2013-12-31', 0)",
                      "INSERT into timeperiods (id, name, start_date, end_date, parent_id, deleted) values ('abc4', 'Q1 2013', '2013-01-01', '2013-03-31', 'abc5', 0)"
                )
            ),

            array(1, TimePeriod::QUARTER_TYPE, TimePeriod::MONTH_TYPE, TimeDate::getInstance()->fromDbDate('2013-01-01'), 2, TimeDate::getInstance()->fromDbDate('2013-01-02'), 10, TimeDate::getInstance()->fromDbDate('2013-01-01'), TimeDate::getInstance()->fromDbDate('2013-03-31'),
                array("INSERT into timeperiods (id, name, start_date, end_date, parent_id, deleted) values ('abc1', 'Q4 2013', '2013-10-01', '2013-12-31', 'abc5', 0)",
                      "INSERT into timeperiods (id, name, start_date, end_date, parent_id, deleted) values ('abc2', 'Q3 2013', '2013-07-01', '2013-09-30', 'abc5', 0)",
                      "INSERT into timeperiods (id, name, start_date, end_date, parent_id, deleted) values ('abc3', 'Q2 2013', '2013-04-01', '2013-06-30', 'abc5', 0)",
                      "INSERT into timeperiods (id, name, start_date, end_date, deleted) values ('abc5', 'Year 2013', '2013-10-01', '2013-12-31', 0)",
                      "INSERT into timeperiods (id, name, start_date, end_date, parent_id, deleted) values ('abc4', 'Q1 2013', '2013-01-01', '2013-03-31', 'abc5', 0)"
                )
            ),

            array(1, TimePeriod::ANNUAL_TYPE, TimePeriod::QUARTER_TYPE, TimeDate::getInstance()->fromDbDate('2013-01-01'), 2, TimeDate::getInstance()->fromDbDate('2013-01-27'), 12, TimeDate::getInstance()->fromDbDate('2012-10-01'), TimeDate::getInstance()->fromDbDate('2012-12-31'),
                array(
                    "INSERT INTO timeperiods (id, name, parent_id, start_date, end_date, deleted) VALUES ('103b91cc-f7a3-d597-0b73-501a8ce616ff','13 Weeks (From Oct. 1st)','40b1de01-d7cf-3031-89e1-4ffeecd30fa8','2012-10-01','2012-12-30',0)",
                    "INSERT INTO timeperiods (id, name, parent_id, start_date, end_date, deleted) VALUES ('2d3c752b-c36b-bd0a-2f23-501a8c5e754e','13 Weeks (From Sep. 24th)','40b1de01-d7cf-3031-89e1-4ffeecd30fa8','2012-09-24','2012-12-23',0)",
                    "INSERT INTO timeperiods (id, name, parent_id, start_date, end_date, deleted) VALUES ('32a04171-977c-fd45-3fbf-4ffeedcca0a9','Q4-2012','40b1de01-d7cf-3031-89e1-4ffeecd30fa8','2012-10-01','2012-12-31',1)",
                    "INSERT INTO timeperiods (id, name, parent_id, start_date, end_date, deleted) VALUES ('40b1de01-d7cf-3031-89e1-4ffeecd30fa8','Year 2012',NULL,'2012-01-01','2012-12-31',0)",
                    "INSERT INTO timeperiods (id, name, parent_id, start_date, end_date, deleted) VALUES ('410be0db-1aba-76bf-eb01-50196f23cf51','13 Weeks (From Aug. 6th)','40b1de01-d7cf-3031-89e1-4ffeecd30fa8','2012-08-06','2012-11-04',1)",
                    "INSERT INTO timeperiods (id, name, parent_id, start_date, end_date, deleted) VALUES ('6d4ec3fa-9a02-0880-0f86-4ffeed752a7c','Second Half 2012','40b1de01-d7cf-3031-89e1-4ffeecd30fa8','2012-07-01','2012-12-31',0)",
                    "INSERT INTO timeperiods (id, name, parent_id, start_date, end_date, deleted) VALUES ('70c1717b-72b6-726b-e15f-501a8bfb16a7','13 Weeks (From Sep. 3rd)','40b1de01-d7cf-3031-89e1-4ffeecd30fa8','2012-09-03','2012-12-02',0)",
                    "INSERT INTO timeperiods (id, name, parent_id, start_date, end_date, deleted) VALUES ('748c6ad5-4243-c031-0597-501a8b277b04','13 Weeks (From Aug. 27th)','40b1de01-d7cf-3031-89e1-4ffeecd30fa8','2012-08-27','2012-11-25',0)",
                    "INSERT INTO timeperiods (id, name, parent_id, start_date, end_date, deleted) VALUES ('7cc177c2-7763-f88c-46e3-501a8c06b9c4','13 Weeks (From Sep. 17th)','40b1de01-d7cf-3031-89e1-4ffeecd30fa8','2012-09-17','2012-12-16',0)",
                    "INSERT INTO timeperiods (id, name, parent_id, start_date, end_date, deleted) VALUES ('857764f1-9c0f-947c-572e-4ff5b7776f82','2012 Q3','','2012-07-01','2012-09-30',1)",
                    "INSERT INTO timeperiods (id, name, parent_id, start_date, end_date, deleted) VALUES ('8d0c0dcc-4bed-7a69-625c-4ff5adcaddc2','2012 Quater 3','','2012-07-01','2012-09-30',1)",
                    "INSERT INTO timeperiods (id, name, parent_id, start_date, end_date, deleted) VALUES ('b1a67ae0-ee38-d98d-7a97-501a8aba16fd','13 Weeks (From Aug. 13th)','40b1de01-d7cf-3031-89e1-4ffeecd30fa8','2012-08-13','2012-11-11',0)",
                    "INSERT INTO timeperiods (id, name, parent_id, start_date, end_date, deleted) VALUES ('ba4cc512-85a7-dba6-99ae-501a8b64a1b2','13 Weeks (From Aug. 20th)','40b1de01-d7cf-3031-89e1-4ffeecd30fa8','2012-08-20','2012-11-18',0)",
                    "INSERT INTO timeperiods (id, name, parent_id, start_date, end_date, deleted) VALUES ('ca574889-868f-909b-247f-501a8b63d48e','13 Weeks (From Sep. 10th)','40b1de01-d7cf-3031-89e1-4ffeecd30fa8','2012-09-10','2012-12-09',0)",
                    "INSERT INTO timeperiods (id, name, parent_id, start_date, end_date, deleted) VALUES ('e4623e91-6b0e-a514-b68c-4ff5b1a5c100','2012',NULL,'2012-01-01','2012-12-31',1)",
                    "INSERT INTO timeperiods (id, name, parent_id, start_date, end_date, deleted) VALUES ('e5446401-af4d-abf2-7969-4ffeed400cdf','Q3-2012','40b1de01-d7cf-3031-89e1-4ffeecd30fa8','2012-07-01','2012-09-30',1)"
                )
            ),

            //The fifth created TimePeriod is where things could potentially fall out of sync since 2013-01-31 ->modify('+3 months') = 2013-05-01, but we are adjusting this to be 2013-04-29 so that the next TimePeriod
            //starts on the last day of the month 2013-04-30 and likewise ends the day before the last day of the month for that TimePeriod 2013-07-30
            array(4, TimePeriod::ANNUAL_TYPE, TimePeriod::QUARTER_TYPE, TimeDate::getInstance()->fromDbDate('2013-01-31'), 2, TimeDate::getInstance()->fromDbDate('2013-01-27'), 12, TimeDate::getInstance()->fromDbDate('2013-04-30'), TimeDate::getInstance()->fromDbDate('2013-07-30'),
                array(
                    "INSERT INTO timeperiods (id, name, parent_id, start_date, end_date, deleted) VALUES ('103b91cc-f7a3-d597-0b73-501a8ce616ff','13 Weeks (From Oct. 1st)','40b1de01-d7cf-3031-89e1-4ffeecd30fa8','2012-10-01','2012-12-30',0)",
                    "INSERT INTO timeperiods (id, name, parent_id, start_date, end_date, deleted) VALUES ('2d3c752b-c36b-bd0a-2f23-501a8c5e754e','13 Weeks (From Sep. 24th)','40b1de01-d7cf-3031-89e1-4ffeecd30fa8','2012-09-24','2012-12-23',0)",
                    "INSERT INTO timeperiods (id, name, parent_id, start_date, end_date, deleted) VALUES ('32a04171-977c-fd45-3fbf-4ffeedcca0a9','Q4-2012','40b1de01-d7cf-3031-89e1-4ffeecd30fa8','2012-10-01','2012-12-31',1)",
                    "INSERT INTO timeperiods (id, name, parent_id, start_date, end_date, deleted) VALUES ('40b1de01-d7cf-3031-89e1-4ffeecd30fa8','Year 2012',NULL,'2012-01-01','2012-12-31',0)",
                    "INSERT INTO timeperiods (id, name, parent_id, start_date, end_date, deleted) VALUES ('410be0db-1aba-76bf-eb01-50196f23cf51','13 Weeks (From Aug. 6th)','40b1de01-d7cf-3031-89e1-4ffeecd30fa8','2012-08-06','2012-11-04',1)",
                    "INSERT INTO timeperiods (id, name, parent_id, start_date, end_date, deleted) VALUES ('6d4ec3fa-9a02-0880-0f86-4ffeed752a7c','Second Half 2012','40b1de01-d7cf-3031-89e1-4ffeecd30fa8','2012-07-01','2012-12-31',0)",
                    "INSERT INTO timeperiods (id, name, parent_id, start_date, end_date, deleted) VALUES ('70c1717b-72b6-726b-e15f-501a8bfb16a7','13 Weeks (From Sep. 3rd)','40b1de01-d7cf-3031-89e1-4ffeecd30fa8','2012-09-03','2012-12-02',0)",
                    "INSERT INTO timeperiods (id, name, parent_id, start_date, end_date, deleted) VALUES ('748c6ad5-4243-c031-0597-501a8b277b04','13 Weeks (From Aug. 27th)','40b1de01-d7cf-3031-89e1-4ffeecd30fa8','2012-08-27','2012-11-25',0)",
                    "INSERT INTO timeperiods (id, name, parent_id, start_date, end_date, deleted) VALUES ('7cc177c2-7763-f88c-46e3-501a8c06b9c4','13 Weeks (From Sep. 17th)','40b1de01-d7cf-3031-89e1-4ffeecd30fa8','2012-09-17','2012-12-16',0)",
                    "INSERT INTO timeperiods (id, name, parent_id, start_date, end_date, deleted) VALUES ('857764f1-9c0f-947c-572e-4ff5b7776f82','2012 Q3','','2012-07-01','2012-09-30',1)",
                    "INSERT INTO timeperiods (id, name, parent_id, start_date, end_date, deleted) VALUES ('8d0c0dcc-4bed-7a69-625c-4ff5adcaddc2','2012 Quater 3','','2012-07-01','2012-09-30',1)",
                    "INSERT INTO timeperiods (id, name, parent_id, start_date, end_date, deleted) VALUES ('b1a67ae0-ee38-d98d-7a97-501a8aba16fd','13 Weeks (From Aug. 13th)','40b1de01-d7cf-3031-89e1-4ffeecd30fa8','2012-08-13','2012-11-11',0)",
                    "INSERT INTO timeperiods (id, name, parent_id, start_date, end_date, deleted) VALUES ('ba4cc512-85a7-dba6-99ae-501a8b64a1b2','13 Weeks (From Aug. 20th)','40b1de01-d7cf-3031-89e1-4ffeecd30fa8','2012-08-20','2012-11-18',0)",
                    "INSERT INTO timeperiods (id, name, parent_id, start_date, end_date, deleted) VALUES ('ca574889-868f-909b-247f-501a8b63d48e','13 Weeks (From Sep. 10th)','40b1de01-d7cf-3031-89e1-4ffeecd30fa8','2012-09-10','2012-12-09',0)",
                    "INSERT INTO timeperiods (id, name, parent_id, start_date, end_date, deleted) VALUES ('e4623e91-6b0e-a514-b68c-4ff5b1a5c100','2012',NULL,'2012-01-01','2012-12-31',1)",
                    "INSERT INTO timeperiods (id, name, parent_id, start_date, end_date, deleted) VALUES ('e5446401-af4d-abf2-7969-4ffeed400cdf','Q3-2012','40b1de01-d7cf-3031-89e1-4ffeecd30fa8','2012-07-01','2012-09-30',1)"
                )
            ),
        );

    }

    /**
     * This is a test for the createTimePeriodsForUpgrade method
     *
     * @group forecasts
     * @group timeperiods
     * @dataProvider testCreateTimePeriodsForUpgradeProvider
     *
     * @param $createdTimePeriodToCheck int value of the created TimePeriod index to check
     * @param $interval The TimePeriod interval type
     * @param $leafInterval The TimePeriod leaf interval type
     * @param $startDate TimeDate instance of chosen start date for the TimePeriod interval
     * @param $shownForward The number of forward TimePeriod intervals to create
     * @param $currentDate TimeDate instance of the current date
     * @param $expectedTimePeriods int value of the expected TimePeriods created
     * @param $startDateFirstCreated TimeDate instance of the start date of created TimePeriod interval to test
     * @param $endDateFirstCreated TimeDate instance of the end date of created TimePeriod interval to test
     * @param $overrideEntries
     *
     * @outputBuffering disabled
     */
    public function testCreateTimePeriodsForUpgrade(
        $createdTimePeriodToCheck,
        $interval,
        $leafInterval,
        $startDate,
        $shownForward,
        $currentDate,
        $expectedTimePeriods,
        $startDateFirstCreated,
        $endDateFirstCreated,
        $overrideEntries = array())
    {
        $this->markTestSkipped(
                      'This test skipped due to upgrade issues found around bugs 61489 and 60606'
                    );

        if(!empty($overrideEntries)) {
            /* @var $db DbManager */
            $db = DBManagerFactory::getInstance();
            //Get rid of all non-deleted timeperiods
            $db->query("DELETE FROM timeperiods WHERE deleted = 0");
            foreach($overrideEntries as $entry) {
                $db->query($entry, false, '', true);
            }
        }

        $currentSettings = array();
        $currentSettings['timeperiod_interval'] = $interval;
        $currentSettings['timeperiod_leaf_interval'] = $leafInterval;
        $currentSettings['timeperiod_start_date'] = $startDate->asDbDate(false);
        $currentSettings['timeperiod_shown_forward'] = $shownForward;

        //Save the altered admin settings
        $admin = BeanFactory::getBean('Administration');
        foreach($currentSettings as $key=>$value) {
            $admin->saveSetting('Forecasts', $key, $value, 'base');
        }

        $timePeriod = TimePeriod::getByType($interval);
        $created = $timePeriod->createTimePeriodsForUpgrade($currentSettings, $currentDate);

        /*
        foreach($created as $c) {
            echo "{$c->name}, {$c->start_date} - {$c->end_date}  (leaf count: {$c->leaf_cycle})\n";
        }
        */

        $this->assertEquals($expectedTimePeriods, count($created));
        $firstTimePeriod = $created[$createdTimePeriodToCheck];
        $this->assertEquals($startDateFirstCreated->asDbDate(false), $firstTimePeriod->start_date, 'Failed asserting that the start date of first backward timeperiod is ' . $startDateFirstCreated);
        $this->assertEquals($endDateFirstCreated->asDbDate(false), $firstTimePeriod->end_date, 'Failed asserting that the end date of first backward timeperiod is ' . $firstTimePeriod->end_date);

        $klass = new SugarForecasting_Filter_TimePeriodFilter(array());
        $timePeriods = $klass->process();
        $this->assertNotEmpty($timePeriods);
    }

    /**
     * This is a test for TimePeriod::getCurrentTimePeriod
     *
     * @group forecasts
     * @group timeperiods
     */
    public function testGetCurrentTimePeriod() {
        global $app_strings;
        global $sugar_config;
        $timedate = TimeDate::getInstance();
        $queryDate = $timedate->getNow()->format('Y');
        $currentAnnualTimePeriod = TimePeriod::getCurrentTimePeriod(TimePeriod::ANNUAL_TYPE);
        $expectedAnnualTimePeriodName = string_format($app_strings['LBL_ANNUAL_TIMEPERIOD_FORMAT'], array($queryDate));
        $this->assertEquals($expectedAnnualTimePeriodName, $currentAnnualTimePeriod->name);

        $month = $timedate->getNow()->format('m');
        $year = $timedate->getNow()->format('Y');
        $currentId = 1;
        $startMonth = '01-01';

        switch($month) {
            case 4:
            case 5:
            case 6:
                $currentId = 2;
                $startMonth = '04-01';
                break;
            case 7:
            case 8:
            case 9:
                $currentId = 3;
                $startMonth = '07-01';
                break;
            case 10:
            case 11:
            case 12:
                $currentId = 4;
                $startMonth = '10-01';
                break;
        }

        $startMonth = $year . '-' . $startMonth;
        $currentQuarterTimePeriod = TimePeriod::getCurrentTimePeriod(TimePeriod::QUARTER_TYPE);
        $start = $timedate->fromDbDate($startMonth)->format($sugar_config['datef']);
        $end = $timedate->fromDbDate($startMonth)->modify($currentQuarterTimePeriod->next_date_modifier)->modify('-1 day')->format($sugar_config['datef']);
        $expectedQuarterTimePeriodName = string_format($app_strings['LBL_QUARTER_TIMEPERIOD_FORMAT'], array($currentId, $start, $end));
        $this->assertEquals($expectedQuarterTimePeriodName, $currentQuarterTimePeriod->name);

        //Test without passing any arguments
        $admin = BeanFactory::getBean('Administration');
        $config = $admin->getConfigForModule('Forecasts', 'base');
        $type = $config['timeperiod_leaf_interval'];
        $currentTimePeriod = TimePeriod::getCurrentTimePeriod($type);
        $this->assertNotNull($currentTimePeriod);
    }



    /*
     * This is a test to see how the TimePeriod code handles specifying corner cases like a leap year as the starting forecasting date
     *
     * @group timeperiods
     * @group forecasts
    public function testCreateLeapYearTimePeriods() {
        $db = DBManagerFactory::getInstance();
        $db->query("UPDATE timeperiods SET deleted = 1");

        $settings['timeperiod_start_date'] = "2012-02-29";
        $settings['timeperiod_interval'] = TimePeriod::ANNUAL_TYPE;
        $settings['timeperiod_leaf_interval'] = TimePeriod::QUARTER_TYPE;
        $settings['timeperiod_shown_backward'] = 4;
        $settings['timeperiod_shown_forward'] = 4;

        $timePeriod = TimePeriod::getByType(TimePeriod::ANNUAL_TYPE);
        $timePeriod->setStartDate('2012-02-29');
        $timePeriod->rebuildForecastingTimePeriods(array(), $settings);

        $timePeriods = TimePeriod::get_not_fiscal_timeperiods_dom();

        //We are basically asserting that for 8 years of timeperiods created, we should have two leaf timeperiods
        $leapYearFoundCount = 0;
        foreach($timePeriods as $id=>$name) {
            $timePeriod = TimePeriod::getByType(TimePeriod::QUARTER_TYPE, $id);
            if(preg_match('/\d{4}\-02-29/', $timePeriod->start_date)) {
                $leapYearFoundCount++;
            }
        }
        $this->assertTrue($leapYearFoundCount >= 2, "Failed to find at least 2 leap year leaf timeperiods for 8 years of timeperiods");
    }
    */


    /**
     * This is the provider function for the testGetChartLabels method.  We return a multi-dimensional Array where each
     * entry of the top level Array contains the arguments in the following order:
     * 1) TimePeriod type as String
     * 2) TimePeriod start date as String
     * 3) Array of expected chart labels for TimePeriod
     *
     */
    public function getChartLabelsProvider()
    {
        return array(

            array(TimePeriod::QUARTER_TYPE, '2013-01-01', array('January 2013', 'February 2013', 'March 2013')),
            array(TimePeriod::QUARTER_TYPE, '2013-10-01', array('October 2013', 'November 2013', 'December 2013')),
            array(TimePeriod::QUARTER_TYPE, '2013-11-01', array('November 2013', 'December 2013', 'January 2014')),

            array(TimePeriod::QUARTER_TYPE, '2012-01-15', array('1/15-2/14', '2/15-3/14', '3/15-4/14')),
            array(TimePeriod::QUARTER_TYPE, '2013-01-15', array('1/15-2/14', '2/15-3/14', '3/15-4/14')),

            array(TimePeriod::QUARTER_TYPE, '2011-12-31', array('12/31-1/30', '1/31-2/28', '2/29-3/30')),
            array(TimePeriod::QUARTER_TYPE, '2011-12-30', array('12/30-1/29', '1/30-2/28', '2/29-3/29')),
            array(TimePeriod::QUARTER_TYPE, '2011-12-29', array('12/29-1/28', '1/29-2/28', '2/29-3/28')),
            array(TimePeriod::QUARTER_TYPE, '2011-12-28', array('12/28-1/27', '1/28-2/27', '2/28-3/27')),

            array(TimePeriod::QUARTER_TYPE, '2012-12-31', array('12/31-1/30', '1/31-2/27', '2/28-3/30')),
            array(TimePeriod::QUARTER_TYPE, '2012-12-30', array('12/30-1/29', '1/30-2/27', '2/28-3/29')),
            array(TimePeriod::QUARTER_TYPE, '2012-12-29', array('12/29-1/28', '1/29-2/27', '2/28-3/28')),
            array(TimePeriod::QUARTER_TYPE, '2012-12-28', array('12/28-1/27', '1/28-2/27', '2/28-3/27')),

            array(TimePeriod::MONTH_TYPE, '2013-01-01', array('1/1-1/7', '1/8-1/14', '1/15-1/21', '1/22-1/28', '1/29-1/31')),
            array(TimePeriod::MONTH_TYPE, '2013-02-01', array('2/1-2/7', '2/8-2/14', '2/15-2/21', '2/22-2/28')),
            array(TimePeriod::MONTH_TYPE, '2012-02-01', array('2/1-2/7', '2/8-2/14', '2/15-2/21', '2/22-2/28', '2/29-2/29')),
            array(TimePeriod::MONTH_TYPE, '2013-04-10', array('4/10-4/16', '4/17-4/23', '4/24-4/30', '5/1-5/7', '5/8-5/9')),
            array(TimePeriod::MONTH_TYPE, '2013-12-10', array('12/10-12/16', '12/17-12/23', '12/24-12/30', '12/31-1/6', '1/7-1/9')),
        );
    }


    /**
     * This is a test for the TimePeriod's instance getChartLabels function.  We are asserting that the correct label
     * interval is shown for the given TimePeriod's start date.
     *
     * @dataProvider getChartLabelsProvider
     * @group timeperiods
     * @group forecasts
     */
    public function testGetChartLabels($tpType, $tpStartDate, $expectedLabels)
    {
        $timePeriod = TimePeriod::getByType($tpType);
        $timePeriod->setStartDate($tpStartDate);
        $timePeriod->save();
        SugarTestTimePeriodUtilities::$_createdTimePeriods[] = $timePeriod;
        $chartLabels = $timePeriod->getChartLabels(array());
        $this->assertEquals(count($expectedLabels), count($chartLabels));
        foreach($expectedLabels as $key=>$expectedLabel) {
            $this->assertEquals($expectedLabel, $chartLabels[$key]['label']);
        }
        sugar_cache_clear($timePeriod->id . ':labels');
    }


    /**
     * This is the dataProvider function for the testChartLabelsKey function.  We return a multi-dimensional Array where each
     * entry of the top level Array contains the arguments in the following order:
     * 1) TimePeriod type as String
     * 2) TimePeriod start date as String
     * 3) Date closed as String
     * 4) Expected return value from the getChartLabelsKey function as an integer
     *
     * @return array
     */
    public function getChartLabelsKeyProvider()
    {
        return array(
            array(TimePeriod::QUARTER_TYPE, '2013-01-01', '2013-01-01', 0),
            array(TimePeriod::QUARTER_TYPE, '2013-01-01', '2013-01-31', 0),
            array(TimePeriod::QUARTER_TYPE, '2013-01-01', '2013-02-01', 1),
            array(TimePeriod::QUARTER_TYPE, '2013-01-01', '2013-02-28', 1),
            array(TimePeriod::QUARTER_TYPE, '2013-01-01', '2013-03-01', 2),
            array(TimePeriod::QUARTER_TYPE, '2013-01-01', '2013-03-31', 2),

            array(TimePeriod::QUARTER_TYPE, '2013-01-15', '2013-01-15', 0),
            array(TimePeriod::QUARTER_TYPE, '2013-01-15', '2013-01-31', 0),
            array(TimePeriod::QUARTER_TYPE, '2013-01-15', '2013-02-14', 0),
            array(TimePeriod::QUARTER_TYPE, '2013-01-15', '2013-02-15', 1),
            array(TimePeriod::QUARTER_TYPE, '2013-01-15', '2013-02-28', 1),
            array(TimePeriod::QUARTER_TYPE, '2013-01-15', '2013-03-15', 2),
            array(TimePeriod::QUARTER_TYPE, '2013-01-15', '2013-03-15', 2),
            array(TimePeriod::QUARTER_TYPE, '2013-01-15', '2013-04-14', 2),

            array(TimePeriod::QUARTER_TYPE, '2012-12-27', '2013-01-26', 0),
            array(TimePeriod::QUARTER_TYPE, '2012-12-27', '2013-01-27', 1),
            array(TimePeriod::QUARTER_TYPE, '2012-12-27', '2013-02-26', 1),
            array(TimePeriod::QUARTER_TYPE, '2012-12-27', '2013-02-27', 2),

            array(TimePeriod::QUARTER_TYPE, '2012-12-28', '2013-02-27', 1),
            array(TimePeriod::QUARTER_TYPE, '2012-12-29', '2013-02-27', 1),
            array(TimePeriod::QUARTER_TYPE, '2012-12-30', '2013-02-27', 1),
            array(TimePeriod::QUARTER_TYPE, '2012-12-31', '2013-02-27', 1),
            array(TimePeriod::QUARTER_TYPE, '2012-12-28', '2013-02-28', 2),
            array(TimePeriod::QUARTER_TYPE, '2012-12-29', '2013-02-28', 2),
            array(TimePeriod::QUARTER_TYPE, '2012-12-30', '2013-02-28', 2),
            array(TimePeriod::QUARTER_TYPE, '2012-12-31', '2013-02-28', 2),

            array(TimePeriod::QUARTER_TYPE, '2011-12-28', '2012-02-27', 1),
            array(TimePeriod::QUARTER_TYPE, '2011-12-29', '2012-02-27', 1),
            array(TimePeriod::QUARTER_TYPE, '2011-12-30', '2012-02-27', 1),
            array(TimePeriod::QUARTER_TYPE, '2011-12-31', '2012-02-27', 1),
            array(TimePeriod::QUARTER_TYPE, '2011-12-28', '2012-02-29', 2),
            array(TimePeriod::QUARTER_TYPE, '2011-12-29', '2012-02-29', 2),
            array(TimePeriod::QUARTER_TYPE, '2011-12-30', '2012-02-29', 2),
            array(TimePeriod::QUARTER_TYPE, '2011-12-31', '2012-02-29', 2),
            array(TimePeriod::QUARTER_TYPE, '2012-01-01', '2012-02-29', 1),
            array(TimePeriod::QUARTER_TYPE, '2011-12-01', '2012-02-29', 2),
            array(TimePeriod::QUARTER_TYPE, '2012-02-01', '2012-02-29', 0),
            array(TimePeriod::QUARTER_TYPE, '2012-02-29', '2012-02-29', 0),

            array(TimePeriod::MONTH_TYPE, '2013-01-01', '2013-01-01', 0),
            array(TimePeriod::MONTH_TYPE, '2013-01-01', '2013-01-07', 0),
            array(TimePeriod::MONTH_TYPE, '2013-01-01', '2013-01-08', 1),
            array(TimePeriod::MONTH_TYPE, '2013-01-01', '2013-01-14', 1),
            array(TimePeriod::MONTH_TYPE, '2013-01-01', '2013-01-15', 2),
            array(TimePeriod::MONTH_TYPE, '2013-01-01', '2013-01-21', 2),
            array(TimePeriod::MONTH_TYPE, '2013-01-01', '2013-01-22', 3),
            array(TimePeriod::MONTH_TYPE, '2013-01-01', '2013-01-23', 3),
            array(TimePeriod::MONTH_TYPE, '2013-01-01', '2013-01-29', 4),
            array(TimePeriod::MONTH_TYPE, '2013-01-01', '2013-01-31', 4),

            array(TimePeriod::MONTH_TYPE, '2013-04-10', '2013-04-10', 0),
            array(TimePeriod::MONTH_TYPE, '2013-04-10', '2013-04-16', 0),
            array(TimePeriod::MONTH_TYPE, '2013-04-10', '2013-04-17', 1),
            array(TimePeriod::MONTH_TYPE, '2013-04-10', '2013-04-23', 1),
            array(TimePeriod::MONTH_TYPE, '2013-04-10', '2013-04-24', 2),
            array(TimePeriod::MONTH_TYPE, '2013-04-10', '2013-04-30', 2),
            array(TimePeriod::MONTH_TYPE, '2013-04-10', '2013-05-01', 3),
            array(TimePeriod::MONTH_TYPE, '2013-04-10', '2013-05-07', 3),
            array(TimePeriod::MONTH_TYPE, '2013-04-10', '2013-05-08', 4),
            array(TimePeriod::MONTH_TYPE, '2013-04-10', '2013-05-10', 4),

            array(TimePeriod::MONTH_TYPE, '2012-12-31', '2012-12-31', 0),
            array(TimePeriod::MONTH_TYPE, '2012-12-31', '2013-01-06', 0),
            array(TimePeriod::MONTH_TYPE, '2012-12-31', '2013-01-07', 1),
            array(TimePeriod::MONTH_TYPE, '2012-12-31', '2013-01-13', 1),
            array(TimePeriod::MONTH_TYPE, '2012-12-31', '2013-01-14', 2),
            array(TimePeriod::MONTH_TYPE, '2012-12-31', '2013-01-20', 2),
            array(TimePeriod::MONTH_TYPE, '2012-12-31', '2013-01-21', 3),
            array(TimePeriod::MONTH_TYPE, '2012-12-31', '2013-01-22', 3),
            array(TimePeriod::MONTH_TYPE, '2012-12-31', '2013-01-28', 4),
            array(TimePeriod::MONTH_TYPE, '2012-12-31', '2013-01-30', 4),

            array(TimePeriod::MONTH_TYPE, '2012-02-01', '2012-02-01', 0),
            array(TimePeriod::MONTH_TYPE, '2012-02-01', '2012-02-29', 4),
            array(TimePeriod::MONTH_TYPE, '2012-02-29', '2012-02-29', 0),
            array(TimePeriod::MONTH_TYPE, '2012-02-29', '2012-03-31', 4),
        );
    }

    /**
     * This is a test for the getChartLabelsKey function.  We are asserting that the call to the TimePeriod's instance
     * getChartLabelsKey corresponds to the correct interval so that the chart data for the dateClosed value may be
     * rendered in the correct group.
     *
     * @dataProvider getChartLabelsKeyProvider
     * @group timeperiods
     * @group forecasts
     */
    public function testGetChartLabelsKey($tpType, $tpStartDate, $dateClosed, $expectedKey)
    {
        $timePeriod = TimePeriod::getByType($tpType);
        $timePeriod->setStartDate($tpStartDate);
        $timePeriod->save();
        SugarTestTimePeriodUtilities::$_createdTimePeriods[] = $timePeriod;
        $chartLabelKey = $timePeriod->getChartLabelsKey($dateClosed);
        $this->assertEquals($expectedKey, $chartLabelKey);
        sugar_cache_clear($timePeriod->id . ':keys');
    }

    /**
     * This is a test for checking the edge time periods and crossed timeperiods
     *
     * @group forecasts
     * @group timeperiods
     */
    public function testCurrentTimePeriodAcrossTimeZones () {
        $this->markTestIncomplete("This is failing intermittently and needs to be addressed as part of SFA-1068");
        //store the current global user
        $user = $GLOBALS['current_user'];
        $GLOBALS['disable_date_format'] = 0;
        //create my anonymous users
        $userA = SugarTestUserUtilities::createAnonymousUser(true);
        $userB = SugarTestUserUtilities::createAnonymousUser(true);
        //get timeDate instance and disable timedate chaching
        $timedate = TimeDate::getInstance();
        $timedate->allow_cache = false;

        //get timezones to find
        $timeZones = DateTimeZone::listIdentifiers();
        //need to find two timezones that cross dates of each other
        $timeZoneA = new DateTimeZone($timeZones[0]);
        $timeZoneANow = new SugarDateTime("now", $timeZoneA);
        $timeZoneADay = $timeZoneANow->format("j");
        $timeZoneAMonth = $timeZoneANow->format("n");
        foreach($timeZones as $tz) {
            $timeZoneB = new DateTimeZone($tz);
            $timeZoneBNow = new SugarDateTime("now", $timeZoneB);
            $timeZoneBDay = $timeZoneBNow->format("j");
            $timeZoneBMonth = $timeZoneBNow->format("n");

            if($timeZoneBDay != $timeZoneADay)
            {
                //check if they are in reverse order, we want A to come before B
                if ( strcmp($timeZoneBNow->format('Y-m-d H:i:s'), $timeZoneANow->format('Y-m-d H:i:s')) < 0 )
                {
                    $timeZoneB = new DateTimeZone($timeZones[0]);
                    $timeZoneA = new DateTimeZone($tz);
                    $timeZoneANow = new SugarDateTime("now", $timeZoneA);
                    $timeZoneBNow = new SugarDateTime("now", $timeZoneB);
                }
                break;
            }
        }

        //set users to be in different timezones
        $userA->setPreference('timezone', $timeZoneA->getName());
        $userA->savePreferencesToDB();
        $userB->setPreference('timezone', $timeZoneB->getName());
        $userB->savePreferencesToDB();

        //destroy existing time periods created by setup
        $db = DBManagerFactory::getInstance();

        $db->query("UPDATE timeperiods set deleted = 1");

        $admin = BeanFactory::newBean('Administration');

        //change settings as needed to reset dates
        $currentForecastSettings = $admin->getConfigForModule('Forecasts', 'base');
        $currentForecastSettings['is_upgrade'] = 0;

        //set start date to be today by the later time zone standards, which may be today or tomorrow
        $currentForecastSettings['timeperiod_start_date'] = $timeZoneBNow->asDbDate(false);

        //rebuild time periods
        $timePeriod = TimePeriod::getByType(TimePeriod::ANNUAL_TYPE);
        $timePeriod->rebuildForecastingTimePeriods(array(), $currentForecastSettings);

        //add all of the newly created timePeriods to the test utils
        $result = $db->query('SELECT id, start_date, end_date, type FROM timeperiods WHERE deleted = 0');
        $createdTimePeriods = array();

        while($row = $db->fetchByAssoc($result))
        {
            $createdTimePeriods[] = TimePeriod::getBean($row['id']);
        }

        SugarTestTimePeriodUtilities::setCreatedTimePeriods($createdTimePeriods);

        //reset current user to use the later time zone
        $GLOBALS['current_user'] = $userB;
        //update timedate to pertain to userb
        $timedate->setUser($userB);
        $timedate->setNow($timeZoneBNow);

        $timeZoneBCurrentTimePeriod = TimePeriod::getCurrentTimePeriod();

        //now get timeperiods for UserA
        $GLOBALS['current_user'] = $userA;
        $timedate->setUser($userA);
        $timedate->setNow($timeZoneANow);

        //get timeperiod per userA's timezone
        $timeZoneACurrentTimePeriod = TimePeriod::getCurrentTimePeriod();
        //make assertions, Users should have timeperiods based on timezones
        $this->assertNotEquals($timeZoneACurrentTimePeriod->id, $timeZoneBCurrentTimePeriod->id, "time periods were equal, users were in same time zone");

        //check that today for user a matches the timeperiod end date
        $this->assertEquals($timeZoneANow->asDbDate(false), $timeZoneACurrentTimePeriod->end_date, "User in Time Zone A current date should have matched the timeperiod end date, but it didn't");

        //check that today for user b matches the timeperiod start date
        $this->assertEquals($timeZoneBNow->asDbDate(false), $timeZoneBCurrentTimePeriod->start_date, "User in Time Zone B current date should have matched the timeperiod start date, but it didn't");

        //reset current user back to the original user
        $GLOBALS['current_user'] = $user;
    }

    /**
     * This is a test for checking the end of the month scenario to make sure the end date of the leaf doesn't overlap the next time period
     *
     * @group forecasts
     * @group timeperiods
     */
    public function testCurrentTimePeriodNoOverlap () {
        //store the current global user
        $user = $GLOBALS['current_user'];
        $GLOBALS['disable_date_format'] = 0;
        //get timeDate instance
        $timedate = TimeDate::getInstance();

        //destroy existing time periods created by setup
        $db = DBManagerFactory::getInstance();

        $db->query("UPDATE timeperiods set deleted = 1");

        $admin = BeanFactory::newBean('Administration');

        //change settings as needed to reset dates
        $currentForecastSettings = $admin->getConfigForModule('Forecasts', 'base');
        $currentForecastSettings['is_upgrade'] = 0;

        //set start date to be today by the later time zone standards, which may be today or tomorrow
        $currentForecastSettings['timeperiod_start_date'] = '2013-11-30';

        //rebuild time periods
        $timePeriod = TimePeriod::getByType(TimePeriod::ANNUAL_TYPE);
        $timePeriod->rebuildForecastingTimePeriods(array(), $currentForecastSettings);

        //add all of the newly created timePeriods to the test utils
        $result = $db->query('SELECT id, name, start_date, end_date, type FROM timeperiods WHERE deleted = 0 and parent_id  is not null order by start_date asc');
        $createdTimePeriods = array();

        while($row = $db->fetchByAssoc($result))
        {
            $createdTimePeriods[] = TimePeriod::getBean($row['id']);
        }

        SugarTestTimePeriodUtilities::setCreatedTimePeriods($createdTimePeriods);

        $currentTimePeriod = TimePeriod::getCurrentTimePeriod();

        $overlappingPeriodId = $db->getOne("SELECT id FROM timeperiods WHERE type = '{$currentTimePeriod->type}' AND deleted = 0 and end_date = '{$currentTimePeriod->start_date}'");

        $this->assertFalse($overlappingPeriodId, "Overlapping timeperiod found.  This means a timeperiod has the same end date as the current time period's start date.  TimePeriods should not overlap");
    }

    /**
      * This is the dataProvider function for the testOddEdgeCases function.  We return a multi-dimensional Array where each
      * entry of the top level Array contains the arguments in the following order:
      * 1) TimePeriod type as String
      * 2) TimePeriod start date as String
      * 3) TimePeriod End Date as a String
      * 4) an associative array containing the expected start and end dates of the leaf periods
     *  5) current date to use so that the timeperiods created are current as of this date (makes the test safe against time itself)
      *
      * @return array
      */
    public function getOddEdgeCasesProvider()
    {
        return array(
            array(TimePeriod::ANNUAL_TYPE,
                  TimePeriod::QUARTER_TYPE,
                  '2012-11-30',
                  '2013-11-29',
                  array(
                      array('expectedStartDate' => '2012-11-30', 'expectedEndDate' => '2013-02-27'),
                      array('expectedStartDate' => '2013-02-28', 'expectedEndDate' => '2013-05-30'),
                      array('expectedStartDate' => '2013-05-31', 'expectedEndDate' => '2013-08-30'),
                      array('expectedStartDate' => '2013-08-31', 'expectedEndDate' => '2013-11-29'),
                  ),
                  '2013-01-29'
            ),
            array(TimePeriod::ANNUAL_TYPE,
                  TimePeriod::QUARTER_TYPE,
                  '2011-11-30',
                  '2012-11-29',
                  array(
                      array('expectedStartDate' => '2011-11-30', 'expectedEndDate' => '2012-02-28'),
                      array('expectedStartDate' => '2012-02-29', 'expectedEndDate' => '2012-05-30'),
                      array('expectedStartDate' => '2012-05-31', 'expectedEndDate' => '2012-08-30'),
                      array('expectedStartDate' => '2012-08-31', 'expectedEndDate' => '2012-11-29'),
                  ),
                  '2012-01-29'
            ),
            array(TimePeriod::ANNUAL_TYPE,
                  TimePeriod::QUARTER_TYPE,
                  '2013-01-31',
                  '2014-01-30',
                  array(
                      array('expectedStartDate' => '2013-01-31', 'expectedEndDate' => '2013-04-29'),
                      array('expectedStartDate' => '2013-04-30', 'expectedEndDate' => '2013-07-30'),
                      array('expectedStartDate' => '2013-07-31', 'expectedEndDate' => '2013-10-30'),
                      array('expectedStartDate' => '2013-10-31', 'expectedEndDate' => '2014-01-30'),
                  ),
                  '2013-02-27'
            ),
            array(TimePeriod::ANNUAL_TYPE,
                TimePeriod::QUARTER_TYPE,
                '2013-02-28',
                '2014-02-27',
                array(
                    array('expectedStartDate' => '2013-02-28', 'expectedEndDate' => '2013-05-30'),
                    array('expectedStartDate' => '2013-05-31', 'expectedEndDate' => '2013-08-30'),
                    array('expectedStartDate' => '2013-08-31', 'expectedEndDate' => '2013-11-29'),
                    array('expectedStartDate' => '2013-11-30', 'expectedEndDate' => '2014-02-27'),
                ),
                '2013-03-05'
            ),
            array(TimePeriod::ANNUAL_TYPE,
                  TimePeriod::QUARTER_TYPE,
                  '2012-03-31',
                  '2013-03-30',
                  array(
                      array('expectedStartDate' => '2012-03-31', 'expectedEndDate' => '2012-06-29'),
                      array('expectedStartDate' => '2012-06-30', 'expectedEndDate' => '2012-09-29'),
                      array('expectedStartDate' => '2012-09-30', 'expectedEndDate' => '2012-12-30'),
                      array('expectedStartDate' => '2012-12-31', 'expectedEndDate' => '2013-03-30'),
                  ),
                  '2013-01-29'
            ),
            array(TimePeriod::ANNUAL_TYPE,
                  TimePeriod::QUARTER_TYPE,
                  '2012-04-30',
                  '2013-04-29',
                  array(
                      array('expectedStartDate' => '2012-04-30', 'expectedEndDate' => '2012-07-30'),
                      array('expectedStartDate' => '2012-07-31', 'expectedEndDate' => '2012-10-30'),
                      array('expectedStartDate' => '2012-10-31', 'expectedEndDate' => '2013-01-30'),
                      array('expectedStartDate' => '2013-01-31', 'expectedEndDate' => '2013-04-29'),
                  ),
                  '2013-01-29'
            ),
            array(TimePeriod::ANNUAL_TYPE,
                  TimePeriod::QUARTER_TYPE,
                  '2012-05-31',
                  '2013-05-30',
                  array(
                      array('expectedStartDate' => '2012-05-31', 'expectedEndDate' => '2012-08-30'),
                      array('expectedStartDate' => '2012-08-31', 'expectedEndDate' => '2012-11-29'),
                      array('expectedStartDate' => '2012-11-30', 'expectedEndDate' => '2013-02-27'),
                      array('expectedStartDate' => '2013-02-28', 'expectedEndDate' => '2013-05-30'),
                  ),
                  '2013-01-29'
            ),
            array(TimePeriod::ANNUAL_TYPE,
                  TimePeriod::QUARTER_TYPE,
                  '2012-09-30',
                  '2013-09-29',
                  array(
                      array('expectedStartDate' => '2012-09-30', 'expectedEndDate' => '2012-12-30'),
                      array('expectedStartDate' => '2012-12-31', 'expectedEndDate' => '2013-03-30'),
                      array('expectedStartDate' => '2013-03-31', 'expectedEndDate' => '2013-06-29'),
                      array('expectedStartDate' => '2013-06-30', 'expectedEndDate' => '2013-09-29'),
                  ),
                  '2013-01-29'
            ),
            array(TimePeriod::ANNUAL_TYPE,
                  TimePeriod::QUARTER_TYPE,
                  '2012-11-28',
                  '2013-11-27',
                  array(
                      array('expectedStartDate' => '2012-11-28', 'expectedEndDate' => '2013-02-27'),
                      array('expectedStartDate' => '2013-02-28', 'expectedEndDate' => '2013-05-27'),
                      array('expectedStartDate' => '2013-05-28', 'expectedEndDate' => '2013-08-27'),
                      array('expectedStartDate' => '2013-08-28', 'expectedEndDate' => '2013-11-27'),
                  ),
                  '2013-01-29'
            ),
            array(TimePeriod::QUARTER_TYPE,
                  TimePeriod::MONTH_TYPE,
                  '2013-01-01',
                  '2013-03-31',
                  array(
                      array('expectedStartDate' => '2013-01-01', 'expectedEndDate' => '2013-01-31'),
                      array('expectedStartDate' => '2013-02-01', 'expectedEndDate' => '2013-02-28'),
                      array('expectedStartDate' => '2013-03-01', 'expectedEndDate' => '2013-03-31'),
                  ),
                  '2013-01-29'
            ),
            array(TimePeriod::QUARTER_TYPE,
                  TimePeriod::MONTH_TYPE,
                  '2013-01-31',
                  '2013-04-29',
                  array(
                      array('expectedStartDate' => '2013-01-31', 'expectedEndDate' => '2013-02-27'),
                      array('expectedStartDate' => '2013-02-28', 'expectedEndDate' => '2013-03-30'),
                      array('expectedStartDate' => '2013-03-31', 'expectedEndDate' => '2013-04-29'),
                  ),
                  '2013-02-23'
            ),
        );
    }

     /**
      * This is a test to check odd conditions around edge cases for end of month start date scenarios
      *
      * @dataProvider getOddEdgeCasesProvider
      * @group timeperiods
      * @group forecasts
      */
     public function testOddEdgeCases($tpType, $tpLeafType, $tpStartDate, $tpExpectedCloseDate, $tpExpectedLeafDatesArray, $currentDate)
     {
        $this->markTestIncomplete('SFA Team -- two of the test cases (#4 and #9) are failing. Are those cases date-dependent?');
        //get timeDate instance
        $timedate = TimeDate::getInstance();

        //destroy existing time periods created by setup
        $db = DBManagerFactory::getInstance();

        $db->query("UPDATE timeperiods set deleted = 1");

        $admin = BeanFactory::newBean('Administration');

        //change settings as needed to reset dates
        $currentForecastSettings = $admin->getConfigForModule('Forecasts', 'base');
        $currentForecastSettings['is_upgrade'] = 0;

        //set start date to be today by the later time zone standards, which may be today or tomorrow
        $currentForecastSettings['timeperiod_start_date'] = $tpStartDate;
        $currentForecastSettings['timeperiod_interval'] = $tpType;
        $currentForecastSettings['timeperiod_leaf_interval'] = $tpLeafType;

        //rebuild time periods
        $timePeriod = TimePeriod::getByType($tpType);
        $timedate->allow_cache = true;
        $timedate->clearCache();
        $timedate->setNow($timedate->fromDbDate($currentDate));
        $timePeriod->rebuildForecastingTimePeriods(array(), $currentForecastSettings);

        //add all of the newly created timePeriods to the test utils
        $result = $db->query('SELECT id, name, start_date, end_date, type FROM timeperiods WHERE deleted = 0 and parent_id  is not null order by start_date asc');
        $createdTimePeriods = array();

        while($row = $db->fetchByAssoc($result))
        {
            $createdTimePeriods[] = TimePeriod::getBean($row['id']);
        }

        SugarTestTimePeriodUtilities::setCreatedTimePeriods($createdTimePeriods);

        $currentTimePeriod = TimePeriod::getCurrentTimePeriod($tpType);

        $this->assertEquals($tpStartDate, $currentTimePeriod->start_date, "current time period's start date doesn't not match what was set by dataprovider");

        $this->assertEquals($tpExpectedCloseDate, $currentTimePeriod->end_date, "current time period's end date doesn't not match expected end date.");

        $leavesArray = $currentTimePeriod->getLeaves();

        for($i = 0; $i < sizeof($leavesArray); $i++) {
            $this->assertEquals($tpExpectedLeafDatesArray[$i]['expectedStartDate'], $leavesArray[$i]->start_date, "Quarter " . $i+1 . " start date does not match expected start date from data provider.");
            $this->assertEquals($tpExpectedLeafDatesArray[$i]['expectedEndDate'], $leavesArray[$i]->end_date, "Quarter " . $i+1 . " end date does not match expected end date from data provider.");
        }
     }


    /**
     * buildTimePeriodsProvider
     *
     */
    public function buildTimePeriodsProvider() {
        return array(

            array(TimePeriod::QUARTER_TYPE, '2013-01-01', 4, 'forward', '2013-12-01', '2013-12-31'),
            array(TimePeriod::ANNUAL_TYPE, '2013-01-01', 2, 'forward', '2014-10-01', '2014-12-31'),
            array(TimePeriod::QUARTER_TYPE, '2013-01-01', 4, 'backward', '2012-06-01', '2012-06-30'),
            array(TimePeriod::ANNUAL_TYPE, '2013-01-01', 2, 'backward', '2012-10-01', '2012-12-31'),

            array(TimePeriod::QUARTER_TYPE, '2013-01-31', 4, 'forward', '2013-12-31', '2014-01-30'),
            array(TimePeriod::ANNUAL_TYPE, '2013-01-31', 2, 'forward', '2014-10-31', '2015-01-30'),
            array(TimePeriod::QUARTER_TYPE, '2013-01-31', 4, 'backward', '2012-06-30', '2012-07-30'),
            array(TimePeriod::ANNUAL_TYPE, '2013-01-31', 2, 'backward', '2012-10-31', '2013-01-30'),

        );
    }

    /**
     * @dataProvider buildTimePeriodsProvider
     * @outputBuffering disabled
     *
     */
    public function testBuildTimePeriods($type, $startDate, $timePeriods, $direction, $lastStartDate, $lastEndDate) {
        $timedate = TimeDate::getInstance();
        $tp = TimePeriod::getByType($type);
        $tp->setStartDate($startDate);
        $tp->save();
        $created = $tp->buildTimePeriods($timePeriods, $direction);
        /*
        foreach($created as $t) {
            echo $t->name . "\n";
        }
        */
        $el = array_pop($created);
        $this->assertEquals($lastStartDate, $timedate->fromDbDate($el->start_date)->asDbDate());
        $this->assertEquals($lastEndDate, $timedate->fromDbDate($el->end_date)->asDbDate());
    }


    /**
     * createTimePeriodsProvider
     *
     */
    public function createTimePeriodsProvider() {
        return array(
            //Standard Quarter/Month test with January 1st start date
            array(
                TimePeriod::QUARTER_TYPE,
                array(),
                array(
                    'timeperiod_start_date'=>'2013-01-01',
                    'timeperiod_interval'=>TimePeriod::QUARTER_TYPE,
                    'timeperiod_leaf_interval'=>TimePeriod::MONTH_TYPE,
                    'timeperiod_shown_backward'=>2,
                    'timeperiod_shown_forward'=>2
                ),
                '2013-01-01',
                15,
                '2012-07-01',
                '2013-09-01',
            ),
            //Test Quarter/Month with future date
            array(
                TimePeriod::QUARTER_TYPE,
                array(),
                array(
                    'timeperiod_start_date'=>'2013-09-05',
                    'timeperiod_interval'=>TimePeriod::QUARTER_TYPE,
                    'timeperiod_leaf_interval'=>TimePeriod::MONTH_TYPE,
                    'timeperiod_shown_backward'=>2,
                    'timeperiod_shown_forward'=>2
                ),
                '2013-02-22',
                15,
                '2012-06-05',
                '2013-08-05',
            ),
            //Test Quarter/Month with past date
            array(
                TimePeriod::QUARTER_TYPE,
                array(),
                array(
                    'timeperiod_start_date'=>'2013-02-22',
                    'timeperiod_interval'=>TimePeriod::QUARTER_TYPE,
                    'timeperiod_leaf_interval'=>TimePeriod::MONTH_TYPE,
                    'timeperiod_shown_backward'=>2,
                    'timeperiod_shown_forward'=>2
                ),
                '2013-09-02',
                15,
                '2013-02-22',
                '2014-04-22',
            ),
            //Standard Annual/Quarter test with January 1st start date
            array(
                TimePeriod::ANNUAL_TYPE,
                array(),
                array(
                    'timeperiod_start_date'=>'2013-01-01',
                    'timeperiod_interval'=>TimePeriod::ANNUAL_TYPE,
                    'timeperiod_leaf_interval'=>TimePeriod::QUARTER_TYPE,
                    'timeperiod_shown_backward'=>2,
                    'timeperiod_shown_forward'=>2
                ),
                '2013-01-01',
                20,
                '2011-01-01',
                '2015-10-01',
            ),
        );
    }


    /**
     * @dataProvider createTimePeriodsProvider
     * @outputBuffering disabled
     */
    public function testCreateTimePeriods($timePeriodType, $priorSettings, $currentSettings, $currentDate, $expectedLeafTimePeriods, $expectedStartDate, $expectedEndDate) {
        $db = DBManagerFactory::getInstance();
        $db->query("UPDATE timeperiods SET deleted = 1");
        $tp = TimePeriod::getByType($timePeriodType);
        $currentDate = TimeDate::getInstance()->fromDbDate($currentDate);
        $created = $tp->createTimePeriods($priorSettings, $currentSettings, $currentDate);
        $leafTimePeriods = array();
        foreach($created as $t) {
            if($t->type != $timePeriodType) {
                $leafTimePeriods[] = $t;
            }
        }

        usort($leafTimePeriods, array("ForecastsTimePeriodTest", "sortTimePeriods"));
        $total = count($leafTimePeriods);
        /*
        foreach($leafTimePeriods as $t) {
            echo $t->name . "\n";
        }
        */
        $this->assertEquals($expectedLeafTimePeriods, $total);
        $this->assertEquals($expectedStartDate, $leafTimePeriods[0]->start_date);
        $this->assertEquals($expectedEndDate, $leafTimePeriods[$total-1]->start_date);
    }


    /**
     * This is an internal function used to sort timeperiods
     */
    static function sortTimePeriods($a, $b) {
        if($a->start_date_timestamp == $b->start_date_timestamp) {
            return 0;
        }

        return $a->start_date_timestamp > $b->start_date_timestamp ? 1 : -1;
    }
}
