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
 
require_once('modules/Teams/Team.php');

class Bug33745 extends Sugar_PHPUnit_Framework_TestCase
{
    var $set_silent_upgrade = false;
    var $created_anonymous_user = false;
    
    public function setUp() 
    {
       if(!isset($_SESSION['silent_upgrade'])) {
       	  $_SESSION['silent_upgrade'] = true;
       	  $this->set_silent_upgrade = true;
       }
       
       if(!isset($GLOBALS['current_user'])) {
       	  $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
       	  $this->created_anonymous_user = true;
       }
    }    
    
    public function tearDown() 
    {
       if($this->set_silent_upgrade) {
       	  unset($_SESSION['silent_upgrade']);
       }
       
       if($this->created_anonymous_user) {
       	  SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
          unset($GLOBALS['current_user']);
       }
    }     
    
    
    public function test_team_get_display_name_function() {
    	require_once('include/utils.php');
    	$first_name = $GLOBALS['current_user']->first_name;
    	$last_name = $GLOBALS['current_user']->last_name;
	    global $locale;
	    $localeFormat = $locale->getLocaleFormatMacro($GLOBALS['current_user']);
	    $show_last_name_first = strpos($localeFormat,'l') < strpos($localeFormat,'f');
    	
    	$display_name = Team::getDisplayName($GLOBALS['current_user']->first_name, $GLOBALS['current_user']->last_name);
    	
    	if($show_last_name_first) {
    	   $this->assertEquals(trim($last_name . ' ' . $first_name), trim($display_name), "Assert that last name first format is correct");
    	} else {
    	   $this->assertEquals(trim($first_name . ' ' . $last_name), trim($display_name), "Assert that first name first format is correct");
    	}

    }

}
