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

require_once 'modules/Meetings/MeetingFormBase.php';


class Bug58012Test extends Sugar_PHPUnit_Framework_TestCase
{
    public function setup()
    {
        SugarTestHelper::setUp('moduleList');
        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('app_strings');
        SugarTestHelper::setUp('app_list_strings');
        SugarTestHelper::setUp('current_user');
    }

    public function tearDown()
    {
        SugarTestHelper::tearDown();
    }

    public function testOrganizerDefaultAcceptance()
    {
        global $current_user, $db;

        $_POST['user_invitees'] = $current_user->id;
        $_POST['module'] = 'Meetings';
        $_POST['action'] = 'Save';
        $_POST['assigned_user_id'] = $current_user->id;

        $formBase = new MeetingFormBase();
        $meeting = $formBase->handleSave('', false, false);

        $sql = "SELECT accept_status FROM meetings_users WHERE meeting_id='{$meeting->id}' AND user_id='{$current_user->id}'";
        $result = $db->query($sql);
        if ($row = $db->fetchByAssoc($result)) {
            $this->assertEquals('accept', $row['accept_status'], 'Should be accepted for the organizer.');
        }
    }
}
