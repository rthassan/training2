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


/**
 * Bug #PAT-543
 * Email invitation language does not adjust
 *
 * @author bsitnikovski@sugarcrm.com
 * @ticket PAT-543
 */
class BugPAT543Test extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        global $current_user;
        $current_user = SugarTestUserUtilities::createAnonymousUser();
    }

    public function tearDown()
    {
        SugarTestMeetingUtilities::removeAllCreatedMeetings();
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
    }

    public function meetingLanguagesProvider()
    {
        return array(
            array('en_us'),
            array('es_ES')
        );
    }

    /**
     * Test the functionality based on data provider
     *
     * @dataProvider meetingLanguagesProvider
     */
    public function testMeetingLanguage($lang)
    {
        global $current_user;

        $current_user->preferred_language = $lang;
        $bean = SugarTestMeetingUtilities::createMeeting();
        $tpl = $bean->getNotificationEmailTemplate();

        $htmltpl = file_get_contents(get_notify_template_file($lang));

        $this->assertEquals($tpl->filecontents, $htmltpl);
    }
}
