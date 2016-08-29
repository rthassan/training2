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

require_once('modules/Trackers/store/TrackerSessionsDatabaseStore.php');
require_once('modules/Trackers/TrackerManager.php');

class Bug41051Test extends Sugar_PHPUnit_Framework_TestCase {

    private $revert = array();

    public function setup()
    {
        $trackerManager = TrackerManager::getInstance();
        $this->revert['disabledMonitors'] = $trackerManager->getDisabledMonitors();
        $this->revert['isPaused'] = $trackerManager->isPaused();

        $trackerManager->isPaused = false;
        $trackerManager->setDisabledMonitors(array());
    }

    public function tearDown()
    {
        $trackerManager = TrackerManager::getInstance();
        $trackerManager->setDisabledMonitors($this->revert['disabledMonitors']);
        $trackerManager->isPaused = $this->revert['isPaused'];

        $GLOBALS['db']->query("DELETE FROM tracker_sessions WHERE session_id = 'Bug41051Test'");
    }

    public function testTrackerSessionDatabaseStore()
    {
        $trackerManager = TrackerManager::getInstance();
        if ($monitor = $trackerManager->getMonitor('tracker_sessions')) {
            $monitor->setValue('session_id', 'Bug41051Test');
            // Must set date_end for the Tracker to save
            $monitor->setValue('date_end', TimeDate::getInstance()->nowDb());
            $monitor->setValue('seconds', 10);
            $trackerManager->saveMonitor($monitor, true, true);

            $seconds = $GLOBALS['db']->getOne("SELECT seconds FROM tracker_sessions WHERE session_id = 'Bug41051Test'");

            $this->assertEquals('10', $seconds, 'Assert that new database entry is created');

            $GLOBALS['db']->query("UPDATE tracker_sessions SET seconds='10' WHERE session_id = 'Bug41051Test'");
            if ($monitor = $trackerManager->getMonitor('tracker_sessions')) {
                $monitor->new = false;
                $monitor->setValue('session_id', 'Bug41051Test');
                // Must set date_end for the Tracker to save
                $monitor->setValue('date_end', TimeDate::getInstance()->nowDb());
                $monitor->setValue('seconds', 0);
                $trackerManager->saveMonitor($monitor, true, true);

                $seconds = $GLOBALS['db']->getOne("SELECT seconds FROM tracker_sessions WHERE session_id = 'Bug41051Test'");
                $this->assertEquals('0', $seconds, 'Assert that new database entry is modified as expected');
            }
        } else {
            $this->markTestSkipped = true;
        }
    }
}
