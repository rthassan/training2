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

require_once ('modules/SNIP/iCalParser.php');

/**
 * Tests SNIP's iCal Parser
 */
class iCalParserTest extends Sugar_PHPUnit_Framework_TestCase
{
    static protected $e;

    static public function setUpBeforeClass() {
        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('current_user', array(true, true));
        $meeting = SugarTestMeetingUtilities::createMeeting();

        // email with description that contains meeting id
        self::$e = SugarTestEmailUtilities::createEmail();
        self::$e->description = 'record=' . $meeting->id . "&gt";
        self::$e->save();
    }

    static public function tearDownAfterClass() {
        SugarTestMeetingUtilities::removeAllCreatedMeetings();
        SugarTestEmailUtilities::removeAllCreatedEmails();
        // delete it in case it's created, outlook_id is from Bug53942Test.ics
        $GLOBALS['db']->query('delete from meetings where outlook_id='."'".'73fc8eef-bacc-4d7b-94eb-af2080437132'."'");
        parent::tearDownAfterClass();
    }

    protected function getEmailCount() {
        return $GLOBALS['db']->getOne("select count(*) from meetings where deleted = 0");
    }

    /**
     * @ticket 66027
     */
    public function testForwardedEmailWithMeetingId()
    {
        $beforeCount = $this->getEmailCount();

        // to test createSugarEvents
        $ic = new iCalendar();
        $ic->parse(file_get_contents(dirname(__FILE__).'/Bug53942Test.ics'));
        // this should not create new meeting since meeting id is in email description
        $ic->createSugarEvents(self::$e);

        $afterCount = $this->getEmailCount();

        $this->assertEquals($beforeCount, $afterCount);
    }
}
