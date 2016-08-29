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
 
class Bug42286Test extends Sugar_PHPUnit_Framework_TestCase
{
	var $testUser;
	
    public function setUp() 
    {  
       $this->testUser = SugarTestUserUtilities::createAnonymousUser();
    }    
    
    public function tearDown() 
    {
       SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
	   $this->testUser = null;
    } 	
	
    /**
     * testRemoveUserFromTeam
     * 
     * This test checks the case where a user is removed from a team.  The user in this case is not the user for
     * the private team so we do not expect an exception to be thrown.
     */
    public function testRemoveUserFromTeam()
    {
	   $team = BeanFactory::getBean('Teams', $this->testUser->getPrivateTeamID());
	   
	   $user2 = SugarTestUserUtilities::createAnonymousUser();
	   $team->add_user_to_team($user2->id, $user2);
	   
	   $exceptionThrown = false;
	   try {
	     $team->remove_user_from_team($user2->id, $user2);
	   } catch(Exception $ex) {
	   	 $exceptionThrown = true;
	   }
	   
	   $this->assertFalse($exceptionThrown, 'Assert that an exception was not thrown for attempting to remove user off team');
    }  
}

?>
