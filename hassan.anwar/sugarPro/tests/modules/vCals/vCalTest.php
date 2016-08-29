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

require_once 'modules/vCals/vCal.php';

/**
 * Test class for vCal
 */
class vCalTest extends Sugar_PHPUnit_Framework_TestCase
{
    /** @var vCal */
    protected $bean = null;

    public function setup()
    {
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('current_user');
        SugarTestHelper::setUp('timedate');
        $this->bean = new vCal();
    }

    public function tearDown()
    {
        SugarTestHelper::tearDown();
    }

    public function testGetFreeBusyLinesCache()
    {
        $actual = $this->bean->get_freebusy_lines_cache($GLOBALS['current_user']);
        $this->assertEmpty($actual);
    }

    public function testCreateSugarFreeBusy()
    {
        $actual = $this->bean->create_sugar_freebusy($GLOBALS['current_user'], new SugarDateTime(), new SugarDateTime());
        $this->assertEmpty($actual);
    }

    public function testGetVcalFreeBusy()
    {
        $actual = $this->bean->get_vcal_freebusy($GLOBALS['current_user']);
        $this->assertNotEmpty($actual);
    }

    public function testCacheSugarVcal()
    {
        $actual = vCal::cache_sugar_vcal($GLOBALS['current_user']);
        $this->assertEmpty($actual);
    }

    public function testCacheSugarVcalFreeBusy()
    {
        $actual = vCal::cache_sugar_vcal_freebusy($GLOBALS['current_user']);
        $this->assertEmpty($actual);
    }

    public function testGetIcalEvent()
    {
        $meeting = new Meeting();
        $meeting->date_start = '2013-01-01 00:00:00';
        $meeting->date_end = '2013-01-01 02:00:00';
        $actual = vCal::get_ical_event($meeting, $GLOBALS['current_user']);
        $this->assertNotEmpty($actual);
    }

    /**
     * Test an empty string and see if it gets added
     *
     */
    public function testLineBreaks()
    {
        // this field should not be added by fold_ical_lines
        // and it is already checked because $icalstring does not contain it
        $icalarray = array();
        $icalarray[] = array("TESTLINEBREAKS", "------------------------75characters------------------------0");
        $res = vCal::create_ical_string_from_array($icalarray);

        $icalstring = "TESTLINEBREAKS:------------------------75characters------------------------\r\n\t0\r\n";
        $this->assertEquals($icalstring, $res);
    }

    /**
     * Test the function create_ical_string_from_array()
     *
     * @dataProvider iCalProvider
     */
    public function testiCalStringFromArray($icalarray, $icalstring)
    {
        $res = vCal::create_ical_string_from_array($icalarray);
        $this->assertEquals($icalstring, $res);
    }

    /**
     * Test the function create_ical_array_from_string()
     *
     * @dataProvider iCalProvider
     */
    public function testiCalArrayFromString($icalarray, $icalstring)
    {
        $res = vCal::create_ical_array_from_string($icalstring);
        $this->assertEquals($icalarray, $res);
    }

    public function iCalProvider()
    {
        $ical_array = array();
        $ical_array[] = array("BEGIN", "VCALENDAR");
        $ical_array[] = array("VERSION", "2.0");
        $ical_array[] = array("PRODID", "-//SugarCRM//SugarCRM Calendar//EN");
        $ical_array[] = array("BEGIN", "VEVENT");
        $ical_array[] = array("UID", "123");
        $ical_array[] = array("ORGANIZED;CN=Boro Sitnikovski", "bsitnikovski@sugarcrm.com");
        $ical_array[] = array("SUMMARY", "Dummy Bean");
        $ical_array[] = array("LOCATION", "Sugar, Cupertino; Sugar, EMEA");
        $ical_array[] = array("DESCRIPTION", "Hello, this is a dummy description.\nIt contains newlines, " .
            "backslash \ semicolon ; and commas. This line should also contain more than 75 characters.");
        $ical_array[] = array("END", "VEVENT");
        $ical_array[] = array("END", "VCALENDAR");

        $ical_string = "BEGIN:VCALENDAR\r\n" .
            "VERSION:2.0\r\n" .
            "PRODID:-//SugarCRM//SugarCRM Calendar//EN\r\n" .
            "BEGIN:VEVENT\r\n" .
            "UID:123\r\n" .
            "ORGANIZED;CN=Boro Sitnikovski:bsitnikovski@sugarcrm.com\r\n" .
            "SUMMARY:Dummy Bean\r\n" .
            "LOCATION:Sugar\\, Cupertino\\; Sugar\\, EMEA\r\n" .
            "DESCRIPTION:Hello\\, this is a dummy description.\\nIt contains newlines\\, ba\r\n" .
            "\tckslash \\\\ semicolon \\; and commas. This line should also contain more tha\r\n" .
            "\tn 75 characters.\r\n" .
            "END:VEVENT\r\n" .
            "END:VCALENDAR\r\n";

        return array(array($ical_array, $ical_string));
    }
}
