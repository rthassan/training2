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


require_once('modules/Calls/CallFormBase.php');


class Bug60114Test extends Sugar_PHPUnit_Framework_TestCase
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
        $_POST['module'] = 'Calls';
        $_POST['action'] = 'Save';
        $_POST['assigned_user_id'] = $current_user->id;

        $formBase = new CallFormBase();
        $call = $formBase->handleSave('', false, false);

        $sql = "SELECT accept_status FROM calls_users WHERE call_id='{$call->id}' AND user_id='{$current_user->id}'";
        $result = $db->query($sql);
        if ($row = $db->fetchByAssoc($result)) {
            $this->assertEquals('accept', $row['accept_status'], 'Should be accepted for the organizer.');
        }
    }
}
