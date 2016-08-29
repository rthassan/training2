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

require_once("modules/Calendar/Calendar.php");

class Bug50567Test extends Sugar_PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        global $current_user;
        $current_user = SugarTestUserUtilities::createAnonymousUser();
    }

    public static function tearDownAfterClass()
    {
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
    }

    /**
     * providerCorrectNextMonth
     *
     */
    public function providerCorrectNextMonth()
    {
        return array(
            array('2012-01-31', 'next', '&year=2012&month=2&day=1'),
            array('2012-02-29', 'next', '&year=2012&month=3&day=1'), //Check leap year
            array('2011-02-28', 'next', '&year=2011&month=3&day=1'), //Check non-leap year
            array('2012-12-31', 'next', '&year=2013&month=1&day=1'), //Check new year

            array('2012-01-31', 'previous', '&year=2011&month=12&day=1'),
            array('2012-12-31', 'previous', '&year=2012&month=11&day=1'),
            array('2012-02-29', 'previous', '&year=2012&month=1&day=1'), //Check leap year
            array('2011-02-28', 'previous', '&year=2011&month=1&day=1'), //Check non-leap year
        );
    }


    /**
     * @dataProvider providerCorrectNextMonth
     *
     */
    public function testCorrectNextMonth($testDate, $direction, $expectedString)
    {
        global $timedate;
        $timedate = TimeDate::getInstance();
        $this->calendar = new Calendar('month');
        $this->calendar->date_time = $timedate->fromString($testDate);
        $uri = $this->calendar->get_neighbor_date_str($direction);
        $this->assertContains($expectedString, $uri, "Failed to get {$direction} expected URL: {$expectedString} from date: {$testDate}");

    }
}