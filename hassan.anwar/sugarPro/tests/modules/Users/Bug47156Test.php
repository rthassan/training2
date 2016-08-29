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

require_once('modules/Users/User.php');
/**
 * Bug #47156
 * Reassigning Users With Instance That Has Numeric Ids
 * @ticket 47156
 */
class Bug47156Test extends Sugar_PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        parent::tearDown();
    }

    /**
     * @group 47156
     */
    public function testCorrectUserListOutput()
    {
        $activeUser = SugarTestUserUtilities::createAnonymousUser(true, 0, array('status' => 'Active'));
        $inactiveUser = SugarTestUserUtilities::createAnonymousUser(true, 0, array('status' => 'Inactive'));

        $allUsers = User::getAllUsers(); 

        $this->assertArrayHasKey($activeUser->id, $allUsers);
        $this->assertArrayHasKey($inactiveUser->id, $allUsers);
    }
}
