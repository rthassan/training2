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

require_once 'modules/Schedulers/Scheduler.php';

/**
 * Bug44819Test
 * Test Scheduler static function initUser() which returns a valid admin user
 * 
 * @author avucinic@sugarcrm.com
 */
class Bug44819Test extends Sugar_PHPUnit_Framework_TestCase
{

	public function setUp()
    {
        parent::setUp();
        SugarTestHelper::setUp("current_user", array(true, 1));
    	// Create admin user
    	$GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser(true, 1);
    }

    public function tearDown()
    {
		// Clear the admin user created
        SugarTestHelper::tearDown();
    }

	public function testInitUser() {
		// Check if the initUser() function returns an Admin user
		$user = Scheduler::initUser();
		$this->assertNotEquals(false, $user, "No admnin users found in the system.");
		$this->assertEquals(1, $user->is_admin, "User returned is not admin.");
		$this->assertEquals("Active", $user->status, "User returned is not active.");
	}

}
