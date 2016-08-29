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
 
class TrackerMonitorTest extends Sugar_PHPUnit_Framework_TestCase {

    function setUp() {
    	$trackerManager = TrackerManager::getInstance();
        $trackerManager->unsetMonitors();
        $GLOBALS['app_strings'] = return_application_language($GLOBALS['current_language']);
    }    
    
    function tearDown() {
        unset($GLOBALS['app_strings']);
    }
    
    function testValidMonitors() {
        $trackerManager = TrackerManager::getInstance();
        $exceptionThrown = false;
        try {
	        $monitor = $trackerManager->getMonitor('tracker');
	        $monitor2 = $trackerManager->getMonitor('tracker_queries');
	        $monitor3 = $trackerManager->getMonitor('tracker_perf');
	        $monitor4 = $trackerManager->getMonitor('tracker_sessions');
	        $monitor5 = $trackerManager->getMonitor('tracker_tracker_queries');	
        } catch (Exception $ex) {
        	$exceptionThrown = true;
        }
        $this->assertFalse($exceptionThrown);
    }

    function testInvalidMonitors() {
        $trackerManager = TrackerManager::getInstance();
        $exceptionThrown = false;
	    $monitor = $trackerManager->getMonitor('invalid_tracker');
	    $this->assertTrue(get_class($monitor) == 'BlankMonitor');
    }
            
    function testInvalidValue() {        
        $trackerManager = TrackerManager::getInstance();
        $monitor = $trackerManager->getMonitor('tracker');
        $exceptionThrown = false;
        try {
          $monitor->setValue('invalid_column', 'foo');
        } catch (Exception $exception) {
          $exceptionThrown = true;
        }
        $this->assertTrue($exceptionThrown);
    } 
     
}  
?>
