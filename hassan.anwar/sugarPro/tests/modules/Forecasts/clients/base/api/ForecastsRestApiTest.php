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

/***
 * Used to test Forecast Module endpoints
 */
class ForecastsRestApiTest extends RestTestBase
{
    private static $currentUser;
    private static $employee1;
    private static $employee2;
    private static $employee3;
    private static $employee4;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        SugarTestForecastUtilities::setUpForecastConfig();

        self::$currentUser = SugarTestUserUtilities::createAnonymousUser();
        self::$currentUser->user_name = 'employee0';
        self::$currentUser->save();

        self::$employee1 = SugarTestUserUtilities::createAnonymousUser();
        self::$employee1->reports_to_id = self::$currentUser->id;
        self::$employee1->user_name = 'employee1';
        self::$employee1->save();

        self::$employee2 = SugarTestUserUtilities::createAnonymousUser();
        self::$employee2->reports_to_id = self::$currentUser->id;
        self::$employee2->user_name = 'employee2';
        self::$employee2->save();

        self::$employee3 = SugarTestUserUtilities::createAnonymousUser();
        self::$employee3->reports_to_id = self::$employee2->id;
        self::$employee3->user_name = 'employee3';
        self::$employee3->save();

        self::$employee4 = SugarTestUserUtilities::createAnonymousUser();
        self::$employee4->reports_to_id = self::$employee3->id;
        self::$employee4->user_name = 'employee4';
        self::$employee4->save();
    }

    public static function tearDownAfterClass()
    {
        SugarTestForecastUtilities::tearDownForecastConfig();
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        parent::tearDown();
    }

    //Override tearDown so we don't lose current user settings
    public function tearDown()
    {

    }

    /**
     * This test is to make sure Forecasts/init endpoint returns an showOpps property
     * @group forecastapi
     * @group forecasts
     */
    public function testCheckForecastSpecificShowOpps()
    {
        $this->markTestIncomplete('Move Rest tests to SOAP UI');

        $restReply = $this->_restCall("Forecasts/init");
        $userData = $restReply['reply']['initData']['userData'];
        $this->assertArrayHasKey('showOpps', $userData, "Forecasts/init did not return showOpps");
    }

    /**
     * This test is to make sure Forecasts/ returns an empty set
     * @group forecastapi
     * @group forecasts
     */
    public function testCheckFavorites()
    {
        $this->markTestIncomplete('Move Rest tests to SOAP UI');

        $restReply = $this->_restCall("Forecasts");
        $this->assertEquals(count($restReply['reply']['records']),0);
    }

    /**
     * This test is to make sure forecast filter requests with tracker return an empty set
     * @group forecastapi
     * @group forecasts
     */
    public function testCheckTrackFilter()
    {
        $this->markTestIncomplete('Move Rest tests to SOAP UI');

        $body = json_encode(array(
            'filter' => array(
                '$tracker' => '-7 DAY'
            )
        ));
        $restReply = $this->_restCall("Forecasts/filter", $body, 'POST');
        $this->assertEquals(count($restReply['reply']['records']),0);
    }

    /**
     * This test is to make sure Forecasts/init endpoint returns an first_name property
     * @group forecastapi
     * @group forecasts
     */
    public function testCheckForecastSpecificFirstName()
    {
        $this->markTestIncomplete('Move Rest tests to SOAP UI');

        $restReply = $this->_restCall("Forecasts/init");
        $userData = $restReply['reply']['initData']['userData'];
        $this->assertArrayHasKey('first_name', $userData, "Forecasts/init did not return first_name");
    }

    /**
     * This test is to make sure Forecasts/init endpoint returns an last_name property
     * @group forecastapi
     * @group forecasts
     */
    public function testCheckForecastSpecificLastName()
    {
        $this->markTestIncomplete('Move Rest tests to SOAP UI');

        $restReply = $this->_restCall("Forecasts/init");
        $userData = $restReply['reply']['initData']['userData'];
        $this->assertArrayHasKey('last_name', $userData, "Forecasts/init did not return last_name");
    }

    /**
     * This test is to see that the data returned for the name field is set correctly when locale name format changes
     *
     * @group testGetLocaleFormattedName
     * @group forecastapi
     * @group forecasts
     */
    public function testGetLocaleFormattedNameUserEndpoint()
    {
        $this->markTestIncomplete('Move Rest tests to SOAP UI');

        global $locale;
        $defaultPreference = $this->_user->getPreference('default_locale_name_format');
        $this->_user->setPreference('default_locale_name_format', 'l, f', 0, 'global');
        $this->_user->savePreferencesToDB();
        $this->_user->reloadPreferences();
        $restReply = $this->_restCall("Forecasts/user/" . self::$currentUser->id);
        $expectedData = $locale->formatName(self::$currentUser);
        $this->assertEquals($expectedData, $restReply['reply']['full_name']);
        $this->_user->setPreference('default_locale_name_format', $defaultPreference, 0, 'global');
        $this->_user->savePreferencesToDB();
        $this->_user->reloadPreferences();
    }

    /**
     * This test is to see that the data returned for the name field is set correctly when locale name format changes
     *
     * @group testGetLocaleFormattedName
     * @group forecastapi
     * @group forecasts
     */
    public function testGetLocaleFormattedNameReporteesEndpoint()
    {
        $this->markTestIncomplete('Move Rest tests to SOAP UI');

        global $locale;
        $db = DBManagerFactory::getInstance();
        $defaultPreference = $this->_user->getPreference('default_locale_name_format');
        $this->_user->setPreference('default_locale_name_format', 'l, f', 0, 'global');
        $this->_user->savePreferencesToDB();
        $this->_user->reloadPreferences();
        $restReply = $this->_restCall("Forecasts/reportees/" . self::$currentUser->id);
        $expectedData = $locale->formatName(self::$currentUser);
        $this->assertEquals($expectedData, $restReply['reply']['data']);
        $this->_user->setPreference('default_locale_name_format', $defaultPreference, 0, 'global');
        $this->_user->savePreferencesToDB();
        $this->_user->reloadPreferences();
    }
    /***
     * Test that we get the reportees assigned to the currentUser id
     *
     * @group forecastapi
     * @group forecasts
     */
    public function testReportees()
    {
        $this->markTestIncomplete('Move Rest tests to SOAP UI');

        $restReply = $this->_restCall("Forecasts/reportees/" . self::$currentUser->id."?level=-1");
        $this->assertEquals($restReply['reply']['metadata']['id'], self::$currentUser->id, "currentUser's id was not found in the Expected place in the rest reply" );

        // get the user ids from first, second, and third levels
        $firstLevel = array();
        $secondLevel = array();
        $thirdLevel = array();
        foreach($restReply['reply']['children'] as $level1Child ) {
            array_push($firstLevel, $level1Child['metadata']['id']);
            foreach($level1Child['children'] as $level2Child) {
                $secondLevel[] = $level2Child['metadata']['id'];
                foreach($level2Child['children'] as $level3Child) {
                    $thirdLevel[] = $level3Child['metadata']['id'];
                }
            }
        }

        // assertContains in case the order is ever jumbled
        $this->assertContains(self::$employee1->id, $firstLevel, "employee1's id was not found in the Expected place in the rest reply" );
        $this->assertContains(self::$employee2->id, $firstLevel, "employee2's id was not found in the Expected place in the rest reply" );
        $this->assertContains(self::$employee3->id, $secondLevel, "employee3's id was not found in the Expected place in the rest reply" );
        $this->assertContains(self::$employee4->id, $thirdLevel, "employee4's id was not found in the Expected place in the rest reply" );
    }

    /**
     * Test that a deleted user does not show up from the filter call
     *
     * @group forecastapi
     * @group forecasts
     */
    public function testDeletedReportees()
    {
        $this->markTestIncomplete('Move Rest tests to SOAP UI');

        $db = DBManagerFactory::getInstance();

        // delete one user for this test
        self::$employee2->deleted = 1;
        self::$employee2->save();
        $GLOBALS['db']->commit();

        $restReply = $this->_restCall("Forecasts/reportees/" . self::$currentUser->id);

        $fullNames = array();

        foreach($restReply['reply']['children'] as $children ) {
            array_push($fullNames, $children['data']);
        }

        $this->assertNotContains(self::$employee2->full_name, $fullNames, "Deleted employee2 was found in the rest reply when it should not have been" );

        // Undelete user if needed for other tests
        self::$employee2->deleted = 0;
        self::$employee2->save();
        $GLOBALS['db']->commit();
    }


    /**
     * Test the timeperiods and that we don't return any fiscal year timeperiods
     *
     * @group forecastapi
     * @group forecasts
     */
    public function testTimeperiods()
    {
        $this->markTestIncomplete('Move Rest tests to SOAP UI');

        $restReply = $this->_restCall("Forecasts/timeperiod/");
        $db = DBManagerFactory::getInstance();
        $fiscal_timeperiods = array();

        $result = $db->query('SELECT id, name FROM timeperiods WHERE is_fiscal_year = 1 AND deleted=0');
        while(($row = $db->fetchByAssoc($result)))
        {
            $fiscal_timeperiods[$row['id']]=$row['name'];
        }

        foreach($fiscal_timeperiods as $id=>$ftp)
        {
            $this->assertArrayNotHasKey($id, $restReply['reply'], "filter contains ". $ftp['name'] . " fiscal timeperiod");
        }
    }

}
